<?php
session_start();
include "db.php";

if(isset($_POST['login'])){
    $user = $_POST['username'];
    $pass = $_POST['password'];

    $sql = "SELECT * FROM admin WHERE username='$user' AND password='$pass'";
    $result = $conn->query($sql);

    if($result->num_rows > 0){
        $_SESSION['admin'] = $user;
        header("Location: dashboard.php");
    } else {
        $error = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>EduPanel — Admin Login</title>
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --bg:          #f0f2f7;
      --surface:     #ffffff;
      --surface-2:   #f7f8fc;
      --border:      #e3e7f0;
      --accent:      #3d5aff;
      --accent-soft: #eef0ff;
      --text-1:      #111827;
      --text-2:      #4b5563;
      --text-3:      #9ca3af;
      --danger:      #ef4444;
      --danger-soft: #fef2f2;
      --radius-sm:   8px;
      --radius:      14px;
      --radius-lg:   20px;
      --shadow-sm:   0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
      --shadow:      0 4px 24px rgba(0,0,0,.08), 0 1px 4px rgba(0,0,0,.05);
    }

    html, body {
      height: 100%;
      font-family: 'Sora', sans-serif;
      background: var(--bg);
      color: var(--text-1);
    }

    body {
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    /* ── TOP NAV (matches dashboard exactly) ── */
    .nav {
      background: var(--surface);
      border-bottom: 1px solid var(--border);
      padding: 0 32px;
      height: 64px;
      display: flex;
      align-items: center;
      box-shadow: var(--shadow-sm);
    }

    .nav-brand {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .nav-logo {
      width: 36px; height: 36px;
      background: var(--accent);
      border-radius: 10px;
      display: grid;
      place-items: center;
      flex-shrink: 0;
    }

    .nav-logo svg { color: #fff; }

    .nav-title {
      font-size: 15px;
      font-weight: 600;
      color: var(--text-1);
      letter-spacing: -.02em;
    }

    .nav-sub {
      font-size: 11.5px;
      color: var(--text-3);
      font-weight: 400;
      margin-top: 1px;
    }

    /* ── CENTER LAYOUT ── */
    .center {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 40px 20px;
    }

    /* ── LOGIN CARD ── */
    .card {
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: var(--radius-lg);
      box-shadow: var(--shadow);
      width: 100%;
      max-width: 420px;
      overflow: hidden;
      animation: fadeUp .4s ease both;
    }

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(12px); }
      to   { opacity: 1; transform: translateY(0);    }
    }

    .card-head {
      padding: 26px 30px 22px;
      border-bottom: 1px solid var(--border);
      background: var(--surface);
    }

    .card-head-top {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 6px;
    }

    .head-icon {
      width: 40px; height: 40px;
      background: var(--accent-soft);
      border-radius: 11px;
      display: grid;
      place-items: center;
      color: var(--accent);
      flex-shrink: 0;
    }

    .card-title {
      font-size: 16px;
      font-weight: 600;
      color: var(--text-1);
      letter-spacing: -.02em;
    }

    .card-meta {
      font-size: 12.5px;
      color: var(--text-3);
      margin-top: 2px;
    }

    /* ── FORM BODY ── */
    .card-body {
      padding: 26px 30px 30px;
    }

    .error-msg {
      display: flex;
      align-items: center;
      gap: 9px;
      background: var(--danger-soft);
      border: 1px solid #fecaca;
      border-radius: var(--radius-sm);
      padding: 11px 14px;
      font-size: 13px;
      color: var(--danger);
      margin-bottom: 20px;
      animation: shake .35s ease;
    }

    @keyframes shake {
      0%,100% { transform: translateX(0);    }
      20%,60%  { transform: translateX(-4px); }
      40%,80%  { transform: translateX( 4px); }
    }

    .form { display: flex; flex-direction: column; gap: 18px; }

    .field { display: flex; flex-direction: column; gap: 6px; }

    .field label {
      font-size: 11.5px;
      font-weight: 600;
      color: var(--text-2);
      letter-spacing: .06em;
      text-transform: uppercase;
    }

    .input-wrap { position: relative; }

    .input-icon {
      position: absolute;
      left: 13px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--text-3);
      pointer-events: none;
      transition: color .18s;
    }

    input[type="text"],
    input[type="password"] {
      width: 100%;
      background: var(--surface);
      border: 1.5px solid var(--border);
      border-radius: var(--radius-sm);
      padding: 10px 14px 10px 40px;
      font-family: 'Sora', sans-serif;
      font-size: 13.5px;
      color: var(--text-1);
      outline: none;
      transition: border-color .18s, box-shadow .18s;
    }

    input::placeholder { color: var(--text-3); }

    input:focus {
      border-color: var(--accent);
      box-shadow: 0 0 0 3px rgba(61,90,255,.1);
    }

    .input-wrap:focus-within .input-icon { color: var(--accent); }

    .submit-btn {
      width: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      background: var(--accent);
      color: #84abcf;
      border: none;
      border-radius: var(--radius-sm);
      padding: 11px 20px;
      font-family: 'Sora', sans-serif;
      font-size: 14px;
      font-weight: 600;
      cursor: pointer;
      transition: background .18s, transform .18s, box-shadow .18s;
      box-shadow: 0 3px 10px rgba(61,90,255,.3);
      margin-top: 4px;
      letter-spacing: -.01em;
    }

    .submit-btn:hover {
      background: #2a45e8;
      box-shadow: 0 6px 18px rgba(61,90,255,.4);
      transform: translateY(-1px);
    }

    .submit-btn:active { transform: translateY(0); }

    .page-footer {
      text-align: center;
      padding: 20px;
      font-size: 12px;
      color: var(--text-3);
    }
  </style>
</head>
<body>

<!-- NAV — identical to dashboard -->
<nav class="nav">
  <div class="nav-brand">
    <div class="nav-logo">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
        <path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/>
      </svg>
    </div>
    <div>
      <div class="nav-title">EduPanel</div>
      <div class="nav-sub">Student Management System</div>
    </div>
  </div>
</nav>

<div class="center">
  <div class="card">

    <div class="card-head">
      <div class="card-head-top">
        <div class="head-icon">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
            <rect x="3" y="11" width="18" height="11" rx="2"/>
            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
          </svg>
        </div>
        <div>
          <div class="card-title">Admin Login</div>
        </div>
      </div>
      <div class="card-meta">Sign in to access the student management dashboard</div>
    </div>

    <div class="card-body">

      <?php if (!empty($error)): ?>
      <div class="error-msg">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
          <circle cx="12" cy="12" r="10"/>
          <line x1="12" y1="8" x2="12" y2="12"/>
          <line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        <?= htmlspecialchars($error) ?>
      </div>
      <?php endif; ?>

      <form method="post" class="form">

        <div class="field">
          <label for="username">Username</label>
          <div class="input-wrap">
            <input type="text" id="username" name="username"
                   placeholder="Enter your username" required autocomplete="username">
            <svg class="input-icon" width="15" height="15" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="2">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
              <circle cx="12" cy="7" r="4"/>
            </svg>
          </div>
        </div>

        <div class="field">
          <label for="password">Password</label>
          <div class="input-wrap">
            <input type="password" id="password" name="password"
                   placeholder="Enter your password" required autocomplete="current-password">
            <svg class="input-icon" width="15" height="15" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="2">
              <rect x="3" y="11" width="18" height="11" rx="2"/>
              <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
            </svg>
          </div>
        </div>

        <button class="submit-btn" name="login" type="submit">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
            <polyline points="10 17 15 12 10 7"/>
            <line x1="15" y1="12" x2="3" y2="12"/>
          </svg>
          Sign In
        </button>

      </form>
    </div>

  </div>
</div>

<div class="page-footer">EduPanel &copy; <?= date('Y') ?> &mdash; Admin Access Only</div>

</body>
</html>