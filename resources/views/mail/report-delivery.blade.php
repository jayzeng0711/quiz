<!DOCTYPE html>
<html lang="zh-Hant">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>你的完整分析報告已送達</title>
<style>
  body { margin:0; padding:0; background:#f1f5f9; font-family:'Helvetica Neue',Arial,sans-serif; color:#334155; }
  .wrap { max-width:560px; margin:32px auto; background:#fff; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,.08); }
  .header { background:linear-gradient(135deg,#4f6ef7,#818cf8); padding:40px 40px 32px; text-align:center; }
  .header h1 { margin:0; color:#fff; font-size:22px; font-weight:700; line-height:1.4; }
  .header p { margin:8px 0 0; color:rgba(255,255,255,.85); font-size:14px; }
  .body { padding:36px 40px; }
  .result-badge { display:inline-block; background:#f0f4ff; color:#4f6ef7; font-size:12px; font-weight:600; padding:4px 12px; border-radius:20px; margin-bottom:16px; }
  .result-title { font-size:24px; font-weight:700; color:#1e293b; margin:0 0 12px; }
  .desc { font-size:15px; line-height:1.7; color:#475569; margin:0 0 28px; }
  .cta { display:block; background:#4f6ef7; color:#fff !important; text-align:center; padding:14px 24px; border-radius:10px; font-size:15px; font-weight:600; text-decoration:none; margin-bottom:28px; }
  .divider { border:none; border-top:1px solid #e2e8f0; margin:24px 0; }
  .meta { font-size:13px; color:#94a3b8; line-height:1.6; }
  .footer { background:#f8fafc; padding:20px 40px; text-align:center; font-size:12px; color:#94a3b8; }
</style>
</head>
<body>
<div class="wrap">
  <div class="header">
    <h1>你的完整分析報告已送達 🎉</h1>
    <p>感謝你完成職場溝通風格測驗</p>
  </div>
  <div class="body">
    <div class="result-badge">你的溝通風格</div>
    <h2 class="result-title">{{ $report->resultType->title }}</h2>
    <p class="desc">{{ $report->resultType->description }}</p>

    <a href="{{ url('/quiz/' . $report->attempt->session_token . '/report') }}" class="cta">
      立即查看完整分析報告 →
    </a>

    <hr class="divider">
    <p class="meta">
      訂單編號：{{ $report->order?->order_number ?? '—' }}<br>
      報告連結永久有效，可隨時回來查看。<br>
      若有任何問題，請回覆此郵件聯繫我們。
    </p>
  </div>
  <div class="footer">
    © {{ date('Y') }} 職場溝通風格測驗 &nbsp;·&nbsp; 此為系統自動發送，請勿直接回覆
  </div>
</div>
</body>
</html>
