<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrderRequest;
use App\Models\Order;
use App\Models\QuizAttempt;
use App\Models\Report;
use App\Services\AiAnalysisService;
use App\Services\OrderService;
use App\Services\Payment\PaymentGatewayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderService $orderService,
        private readonly PaymentGatewayService $gateway,
        private readonly AiAnalysisService $ai,
    ) {}

    /**
     * GET /quiz/{token}/unlock
     * Show the upsell / unlock page.
     */
    public function unlock(string $token): View|RedirectResponse
    {
        $attempt = $this->resolveAttempt($token);

        if (! $attempt->isCompleted()) {
            return redirect()->route('quiz.show');
        }

        // Already paid — go straight to the full report
        if ($attempt->hasPaidOrder()) {
            return redirect()->route('quiz.attempt.report', ['token' => $token]);
        }

        $attempt->loadMissing(['resultType', 'quiz']);

        return view('quiz.unlock', compact('attempt'));
    }

    /**
     * POST /quiz/{token}/order
     * Create a pending order and redirect to the mock pay form.
     */
    public function store(CreateOrderRequest $request, string $token): RedirectResponse|Response
    {
        $attempt = $this->resolveAttempt($token);

        if (! $attempt->isCompleted()) {
            return redirect()->route('quiz.show');
        }

        if ($attempt->hasPaidOrder()) {
            return redirect()->route('quiz.attempt.report', ['token' => $token]);
        }

        // Persist email/name onto the attempt for report delivery
        $attempt->update([
            'email' => $request->email,
            'name'  => $request->name,
        ]);

        $order = $this->orderService->createOrder($attempt);

        // In mock mode this JS-redirects to the in-app confirm page.
        // In ecpay mode it renders a self-submitting form to ECPay's checkout.
        $checkoutHtml = $this->gateway->buildCheckoutForm($order);

        return response($checkoutHtml, 200)
            ->header('Content-Type', 'text/html');
    }

    /**
     * GET /quiz/{token}/pay/{order}
     * Show the mock payment confirmation page.
     */
    public function showMockPay(string $token, string $orderNumber): View|RedirectResponse
    {
        $attempt = $this->resolveAttempt($token);
        $order   = Order::where('order_number', $orderNumber)
            ->where('quiz_attempt_id', $attempt->id)
            ->firstOrFail();

        if ($order->isPaid()) {
            return redirect()->route('quiz.attempt.report', ['token' => $token]);
        }

        $attempt->loadMissing(['resultType', 'quiz']);

        return view('quiz.mock-pay', compact('attempt', 'order'));
    }

    /**
     * POST /quiz/{token}/pay/{order}
     * Simulate payment success.
     */
    public function mockPay(string $token, string $orderNumber): RedirectResponse
    {
        $attempt = $this->resolveAttempt($token);
        $order   = Order::where('order_number', $orderNumber)
            ->where('quiz_attempt_id', $attempt->id)
            ->firstOrFail();

        if (! $order->isPaid()) {
            $this->orderService->processPayment($order, provider: 'mock');
        }

        return redirect()->route('quiz.attempt.result', ['token' => $token])
            ->with('payment_success', true);
    }

    /**
     * GET /quiz/{token}/report
     * Redirect to the unified result page (which shows paid content when unlocked).
     */
    public function fullReport(string $token): RedirectResponse
    {
        return redirect()->route('quiz.attempt.result', ['token' => $token]);
    }

    /**
     * GET /share/{shareToken}
     * Public shareable result page — free content only.
     */
    public function share(string $shareToken): View
    {
        $report = Report::where('access_token', $shareToken)
            ->with(['resultType', 'attempt.quiz'])
            ->firstOrFail();

        return view('quiz.share', [
            'report'     => $report,
            'resultType' => $report->resultType,
            'attempt'    => $report->attempt,
        ]);
    }


    /**
     * POST /quiz/{token}/generate-ai
     * Called by the result page when AI analysis is missing.
     * Generates AI synchronously and returns JSON so the page can update.
     */
    public function generateAi(string $token): JsonResponse
    {
        $attempt = $this->resolveAttempt($token);

        if (! $attempt->hasPaidOrder()) {
            return response()->json(['status' => 'not_paid'], 403);
        }

        $attempt->loadMissing(['resultType', 'quiz', 'answers.question', 'report']);
        $report = $attempt->report;

        if (! $report) {
            return response()->json(['status' => 'no_report'], 404);
        }

        // Already done (concurrent request race)
        if (! blank($report->rendered_content['ai_analysis'] ?? '')) {
            return response()->json([
                'status'      => 'done',
                'ai_analysis' => $report->rendered_content['ai_analysis'],
            ]);
        }

        try {
            $text = $this->ai->generatePersonalizedInsight($attempt);

            if (blank($text)) {
                return response()->json(['status' => 'empty'], 202);
            }

            $content                = $report->rendered_content ?? [];
            $content['ai_analysis'] = $text;
            $report->update(['rendered_content' => $content]);

            return response()->json([
                'status'      => 'done',
                'ai_analysis' => $text,
            ]);

        } catch (\Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    // -------------------------------------------------------------------------

    private function resolveAttempt(string $token): QuizAttempt
    {
        return QuizAttempt::with('quiz.questions')
            ->where('session_token', $token)
            ->firstOrFail();
    }
}
