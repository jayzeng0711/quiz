<!DOCTYPE html>
<html lang="zh-Hant">
<head>
<meta charset="UTF-8">
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: 'stheiti', 'STHeiti', sans-serif; color: #334155; font-size: 13px; line-height: 1.7; }
  .page { padding: 40px 48px; }

  /* Header */
  .header { background: #4f6ef7; color: #fff; padding: 28px 48px; margin: -40px -48px 32px; }
  .header .quiz-name { font-size: 11px; opacity: .8; margin-bottom: 4px; text-transform: uppercase; letter-spacing: .05em; }
  .header h1 { font-size: 22px; font-weight: 700; }
  .header .subtitle { font-size: 13px; opacity: .85; margin-top: 4px; }

  /* Type badge */
  .type-badge { display: inline-block; background: #e0e7ff; color: #3b55e6; font-size: 11px;
                font-weight: 700; padding: 3px 10px; border-radius: 20px; margin-bottom: 10px; }

  /* Sections */
  .section { margin-bottom: 24px; }
  .section-title { font-size: 13px; font-weight: 700; color: #1e293b; border-bottom: 2px solid #e2e8f0;
                   padding-bottom: 6px; margin-bottom: 12px; text-transform: uppercase; letter-spacing: .03em; }

  p { margin-bottom: 8px; color: #475569; }
  h2 { font-size: 16px; font-weight: 700; color: #1e293b; margin-bottom: 8px; }
  h3 { font-size: 13px; font-weight: 700; color: #334155; margin: 12px 0 6px; }
  ul { padding-left: 18px; }
  li { margin-bottom: 4px; color: #475569; }

  /* Score bars */
  .score-row { margin-bottom: 6px; }
  .score-label { font-size: 11px; color: #64748b; margin-bottom: 2px; }
  .score-bar-bg { background: #f1f5f9; border-radius: 4px; height: 8px; }
  .score-bar-fill { background: #4f6ef7; height: 8px; border-radius: 4px; }
  .score-bar-fill.secondary { background: #cbd5e1; }

  /* AI section */
  .ai-box { background: #f0f4ff; border-left: 4px solid #4f6ef7; padding: 14px 18px;
             border-radius: 0 8px 8px 0; margin-bottom: 20px; }
  .ai-box .ai-label { font-size: 10px; font-weight: 700; color: #4f6ef7; text-transform: uppercase;
                       letter-spacing: .06em; margin-bottom: 6px; }
  .ai-box p { color: #1e293b; margin: 0; }

  /* Footer */
  .footer { position: fixed; bottom: 20px; left: 0; right: 0; text-align: center;
             font-size: 10px; color: #94a3b8; }
  .footer .token { font-family: monospace; font-size: 9px; }
</style>
</head>
<body>
<div class="header">
    <div class="quiz-name">{{ $attempt->quiz->title }}</div>
    <h1>{{ $resultType->title }}</h1>
    <div class="subtitle">個人化職場溝通風格完整分析報告</div>
</div>

<div class="page">
    {{-- Type description --}}
    <div class="section">
        <div class="type-badge">{{ $resultType->code }}</div>
        <p>{{ $resultType->description }}</p>
    </div>

    {{-- AI analysis --}}
    @php $aiAnalysis = $report->rendered_content['ai_analysis'] ?? ''; @endphp
    @if ($aiAnalysis)
    <div class="section">
        <div class="section-title">AI 個人化洞察</div>
        <div class="ai-box">
            <div class="ai-label">✦ 專屬分析</div>
            <p>{{ $aiAnalysis }}</p>
        </div>
    </div>
    @endif

    {{-- Score breakdown --}}
    @if ($attempt->score_breakdown)
    @php $scores = collect($attempt->score_breakdown)->sortDesc(); $max = $scores->max() ?: 1; @endphp
    <div class="section">
        <div class="section-title">各維度得分</div>
        @foreach ($scores as $code => $score)
        <div class="score-row">
            <div class="score-label">{{ $code }}{{ $code === $resultType->code ? ' ✦' : '' }}</div>
            <div class="score-bar-bg">
                <div class="score-bar-fill {{ $code === $resultType->code ? '' : 'secondary' }}"
                     style="width: {{ round(($score / $max) * 100) }}%"></div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Report content --}}
    @if ($resultType->report_content)
    <div class="section">
        <div class="section-title">深度分析</div>
        {!! $resultType->report_content !!}
    </div>
    @endif

    {{-- Order meta --}}
    <div class="section" style="margin-top: 32px; border-top: 1px solid #e2e8f0; padding-top: 16px;">
        <p style="font-size: 11px; color: #94a3b8;">
            訂單編號：{{ $order?->order_number ?? '—' }} ｜
            生成時間：{{ now()->format('Y/m/d H:i') }}
        </p>
    </div>
</div>

<div class="footer">
    職場溝通風格測驗 &nbsp;·&nbsp; 報告代碼：<span class="token">{{ $report->access_token }}</span>
</div>
</body>
</html>
