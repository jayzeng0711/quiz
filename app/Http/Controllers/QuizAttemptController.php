<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveAnswerRequest;
use App\Http\Requests\StartQuizRequest;
use App\Models\Quiz;
use App\Models\QuizAnswer;
use App\Models\QuizAttempt;
use App\Models\QuizQuestion;
use App\Services\AiAnalysisService;
use App\Services\ReportGenerationService;
use App\Services\ResultCalculationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class QuizAttemptController extends Controller
{
    public function __construct(
        private readonly ResultCalculationService $calculator,
        private readonly ReportGenerationService $reportGenerator,
        private readonly AiAnalysisService $ai,
    ) {}

    /**
     * POST /quiz/start
     * Create a new attempt, randomly selecting 10 questions from the full pool.
     */
    public function start(StartQuizRequest $request): RedirectResponse
    {
        $quiz = Quiz::findOrFail($request->quiz_id);

        // Randomly select 10 questions from the full pool using a plain query
        // (avoids the sort_order scope on the relationship interfering with RAND())
        $selectedIds = \App\Models\QuizQuestion::where('quiz_id', $quiz->id)
            ->inRandomOrder()
            ->limit(10)
            ->pluck('id')
            ->toArray();

        $attempt = QuizAttempt::create([
            'quiz_id'               => $quiz->id,
            'session_token'         => Str::uuid()->toString(),
            'status'                => 'in_progress',
            'selected_question_ids' => $selectedIds,
        ]);

        return redirect()->route('quiz.attempt.question', [
            'token'    => $attempt->session_token,
            'question' => 1,
        ]);
    }

    /**
     * GET /quiz/{token}/q/{n}
     * Show question number {n} (1-based) from this attempt's selected questions.
     */
    public function showQuestion(string $token, int $question): View|RedirectResponse
    {
        $attempt = $this->resolveAttempt($token);

        if ($attempt->isCompleted()) {
            return redirect()->route('quiz.attempt.result', ['token' => $token]);
        }

        $questions = $attempt->selected_questions;
        $total     = $questions->count();

        if ($question < 1 || $question > $total) {
            abort(404);
        }

        // Enforce sequential answering — cannot skip ahead
        $answeredCount = $attempt->answers()->count();
        if ($question > $answeredCount + 1) {
            return redirect()->route('quiz.attempt.question', [
                'token'    => $token,
                'question' => $answeredCount + 1,
            ]);
        }

        /** @var QuizQuestion $currentQuestion */
        $currentQuestion = $questions->get($question - 1);

        // Pre-fill if already answered
        $existingAnswer = $attempt->answers()
            ->where('quiz_question_id', $currentQuestion->id)
            ->first();

        return view('quiz.question', [
            'attempt'        => $attempt,
            'question'       => $currentQuestion,
            'questionNumber' => $question,
            'total'          => $total,
            'existingAnswer' => $existingAnswer,
        ]);
    }

    /**
     * POST /quiz/{token}/q/{n}
     * Persist the selected answer, then navigate based on direction.
     */
    public function saveAnswer(SaveAnswerRequest $request, string $token, int $question): RedirectResponse
    {
        $attempt = $this->resolveAttempt($token);

        if ($attempt->isCompleted()) {
            return redirect()->route('quiz.attempt.result', ['token' => $token]);
        }

        $questions = $attempt->selected_questions;
        $total     = $questions->count();

        if ($question < 1 || $question > $total) {
            abort(404);
        }

        $currentQuestion = $questions->get($question - 1);

        QuizAnswer::updateOrCreate(
            [
                'quiz_attempt_id'  => $attempt->id,
                'quiz_question_id' => $currentQuestion->id,
            ],
            [
                'selected_options' => $request->selected_options,
            ]
        );

        $direction = $request->input('direction', 'next');

        if ($direction === 'prev' && $question > 1) {
            return redirect()->route('quiz.attempt.question', [
                'token'    => $token,
                'question' => $question - 1,
            ]);
        }

        if ($question === $total) {
            return $this->finishAttempt($attempt, $token);
        }

        return redirect()->route('quiz.attempt.question', [
            'token'    => $token,
            'question' => $question + 1,
        ]);
    }

    /**
     * GET /quiz/{token}/result
     * Display the result page (free + paid if unlocked).
     */
    public function result(string $token): View|RedirectResponse
    {
        $attempt = $this->resolveAttempt($token);

        if (! $attempt->isCompleted()) {
            $answeredCount = $attempt->answers()->count();
            return redirect()->route('quiz.attempt.question', [
                'token'    => $token,
                'question' => $answeredCount + 1,
            ]);
        }

        $attempt->loadMissing(['resultType', 'quiz', 'report', 'order']);

        $report = $attempt->report
            ?? $this->reportGenerator->generate($attempt);

        // If paid but AI is missing, generate inline (blocks ~10s, runs only once)
        if ($attempt->hasPaidOrder() && blank($report->rendered_content['ai_analysis'] ?? '')) {
            try {
                $text = $this->ai->generatePersonalizedInsight($attempt);
                if (! blank($text)) {
                    $content                = $report->rendered_content ?? [];
                    $content['ai_analysis'] = $text;
                    $report->update(['rendered_content' => $content]);

                    if ($report->pdf_path) {
                        Storage::disk('local')->delete($report->pdf_path);
                        $report->update(['pdf_path' => null]);
                    }
                    $path = $this->reportGenerator->generatePdf($report);
                    $this->reportGenerator->markGenerated($report, $path);
                    $report->refresh();
                }
            } catch (\Throwable $e) {
                Log::warning('Inline AI generation failed on result page', [
                    'report_id' => $report->id,
                    'error'     => $e->getMessage(),
                ]);
            }
        }

        return view('quiz.result', [
            'attempt'    => $attempt,
            'resultType' => $attempt->resultType,
            'report'     => $report,
        ]);
    }

    // -------------------------------------------------------------------------

    private function resolveAttempt(string $token): QuizAttempt
    {
        return QuizAttempt::with('quiz.questions')
            ->where('session_token', $token)
            ->firstOrFail();
    }

    private function finishAttempt(QuizAttempt $attempt, string $token): RedirectResponse
    {
        $attempt->refresh()->load('answers.question');

        $this->calculator->calculate($attempt);

        $attempt->refresh()->load('resultType');

        $this->reportGenerator->generate($attempt);

        return redirect()->route('quiz.attempt.result', ['token' => $token]);
    }
}
