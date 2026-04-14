<!DOCTYPE html>
<html lang="zh-Hant">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>訂單付款確認</title>
<style>
  body { margin:0; padding:0; background:#f1f5f9; font-family:'Helvetica Neue',Arial,sans-serif; color:#334155; }
  .wrap { max-width:560px; margin:32px auto; background:#fff; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,.08); }
  .header { background:#1e293b; padding:36px 40px; text-align:center; }
  .header h1 { margin:0; color:#fff; font-size:20px; font-weight:700; }
  .header p { margin:6px 0 0; color:#94a3b8; font-size:14px; }
  .body { padding:36px 40px; }
  .status-pill { display:inline-flex; align-items:center; gap:6px; background:#f0fdf4; color:#16a34a; font-size:13px; font-weight:600; padding:6px 14px; border-radius:20px; margin-bottom:24px; }
  table.info { width:100%; border-collapse:collapse; margin-bottom:28px; }
  table.info td { padding:10px 0; border-bottom:1px solid #f1f5f9; font-size:14px; }
  table.info td:first-child { color:#64748b; width:40%; }
  table.info td:last-child { font-weight:600; color:#1e293b; text-align:right; }
  .cta { display:block; background:#4f6ef7; color:#fff !important; text-align:center; padding:14px 24px; border-radius:10px; font-size:15px; font-weight:600; text-decoration:none; margin-bottom:0; }
  .footer { background:#f8fafc; padding:20px 40px; text-align:center; font-size:12px; color:#94a3b8; margin-top:32px; }
</style>
</head>
<body>
<div class="wrap">
  <div class="header">
    <h1>訂單付款確認</h1>
    <p>職場溝通風格測驗</p>
  </div>
  <div class="body">
    <div class="status-pill">
      ✓ &nbsp;付款成功
    </div>

    <table class="info">
      <tr>
        <td>訂單編號</td>
        <td>{{ $order->order_number }}</td>
      </tr>
      <tr>
        <td>付款金額</td>
        <td>NT$ {{ number_format($order->amount / 100) }}</td>
      </tr>
      <tr>
        <td>付款時間</td>
        <td>{{ $order->paid_at?->format('Y/m/d H:i') }}</td>
      </tr>
      <tr>
        <td>收件信箱</td>
        <td>{{ $order->email }}</td>
      </tr>
    </table>

    <a href="{{ url('/quiz/' . $order->attempt->session_token . '/report') }}" class="cta">
      查看完整分析報告 →
    </a>
  </div>
  <div class="footer">
    © {{ date('Y') }} 職場溝通風格測驗 &nbsp;·&nbsp; 此為系統自動發送，請勿直接回覆
  </div>
</div>
</body>
</html>
