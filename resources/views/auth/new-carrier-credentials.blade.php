<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <title>NextLoad Credentials</title>
    <style>
      body {
        background: #f8f9fa;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        color: #1f2937;
        margin: 0;
        padding: 24px;
      }

      .card {
        max-width: 480px;
        margin: 0 auto;
        background: #ffffff;
        border-radius: 12px;
        padding: 32px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
      }

      .title {
        font-size: 20px;
        font-weight: 600;
        margin: 0 0 8px;
        color: #1f2937;
        letter-spacing: -0.3px;
      }

      .muted {
        color: #6b7280;
        margin: 0 0 24px;
        font-size: 14px;
        line-height: 1.6;
      }

      .box {
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 16px;
        margin: 24px 0;
        font-family: ui-monospace, 'Courier New', Courier, monospace;
        font-size: 13px;
      }

      .box div {
        margin: 8px 0;
        line-height: 1.6;
      }

      .box strong {
        color: #374151;
      }

      .btn {
        display: inline-block;
        background: #374151;
        color: #ffffff;
        text-decoration: none;
        padding: 11px 24px;
        border-radius: 8px;
        margin: 24px 0;
        font-weight: 500;
        font-size: 14px;
        transition: background 0.2s ease;
        border: none;
        cursor: pointer;
      }

      .btn:hover {
        background: #1f2937;
      }

      .small {
        font-size: 13px;
        color: #6b7280;
        margin-top: 24px;
        line-height: 1.6;
      }

      .divider {
        height: 1px;
        background: #e5e7eb;
        margin: 24px 0;
      }

      em {
        font-style: normal;
        color: #374151;
      }
    </style>
  </head>
  <body>
    <div class="card">
      <p class="title">Welcome to NextLoad!</p>
      <p class="muted">Your account has been created by our team. Use the credentials below to access the platform.</p>

      <div class="box">
        <div><strong>Email:</strong> {{ $user->email }}</div>
        <div><strong>Temporary password:</strong> {{ $plainPassword }}</div>
      </div>

      <a class="btn" href="{{ $loginUrl }}">Access NextLoad</a>

      <div class="divider"></div>

      <p class="small">
        For your security, please change your password after your first login in <em>Profile &gt; Security</em> (or use "Forgot your password?"). If you did not request this account, you can ignore this email.
      </p>

      <p class="small">© {{ date('Y') }} NextLoad</p>
    </div>
  </body>
</html>
