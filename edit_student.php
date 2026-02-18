<?php
session_start();
include "db.php";

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
}

$id = $_GET['id'];

if(isset($_POST['update'])){
    $name   = $_POST['name'];
    $email  = $_POST['email'];
    $phone  = $_POST['phone'];
    $course = $_POST['course'];

    $conn->query("UPDATE students SET
        name='$name',
        email='$email',
        phone='$phone',
        course='$course'
        WHERE id=$id");

    header("Location: dashboard.php");
}

$result = $conn->query("SELECT * FROM students WHERE id=$id");
$row = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>EduPanel — Edit Student</title>
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
      --warn:        #f59e0b;
      --warn-soft:   #fffbeb;
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

    body { display: flex; flex-direction: column; min-height: 100vh; }

    /* ── NAV ── */
    .nav {
      background: var(--surface);
      border-bottom: 1px solid var(--border);
      padding: 0 32px;
      height: 64px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      box-shadow: var(--shadow-sm);
      position: sticky; top: 0; z-index: 100;
    }

    .nav-brand { display: flex; align-items: center; gap: 12px; }

    .nav-logo {
      width: 36px; height: 36px;
      background: var(--accent);
      border-radius: 10px;
      display: grid; place-items: center;
    }
    .nav-logo svg { color: #fff; }

    .nav-title  { font-size: 15px; font-weight: 600; color: var(--text-1); letter-spacing: -.02em; }
    .nav-sub    { font-size: 11.5px; color: var(--text-3); font-weight: 400; margin-top: 1px; }

    .nav-right { display: flex; align-items: center; gap: 12px; }

    .admin-chip {
      display: flex; align-items: center; gap: 8px;
      background: var(--surface-2);
      border: 1px solid var(--border);
      border-radius: 100px;
      padding: 6px 14px 6px 6px;
    }
    .admin-avatar {
      width: 28px; height: 28px;
      background: linear-gradient(135deg, var(--accent), #7c6fff);
      border-radius: 50%;
      display: grid; place-items: center;
      font-size: 11px; font-weight: 600; color: #fff;
    }
    .admin-name { font-size: 13px; font-weight: 500; color: var(--text-2); }

    .back-btn {
      display: flex; align-items: center; gap: 6px;
      background: none;
      border: 1px solid var(--border);
      border-radius: var(--radius-sm);
      padding: 7px 14px;
      font-family: 'Sora', sans-serif;
      font-size: 13px; font-weight: 500;
      color: var(--text-2);
      cursor: pointer;
      text-decoration: none;
      transition: all .18s;
    }
    .back-btn:hover { background: var(--surface-2); border-color: #c8cfe0; }

    /* ── PAGE ── */
    .page {
      flex: 1;
      padding: 36px 32px;
      max-width: 760px;
      margin: 0 auto;
      width: 100%;
      animation: fadeUp .4s ease both;
    }

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(12px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    /* breadcrumb */
    .breadcrumb {
      display: flex; align-items: center; gap: 8px;
      font-size: 12.5px; color: var(--text-3);
      margin-bottom: 20px;
    }
    .breadcrumb a { color: var(--text-3); text-decoration: none; transition: color .15s; }
    .breadcrumb a:hover { color: var(--accent); }
    .breadcrumb svg { color: var(--text-3); flex-shrink: 0; }
    .breadcrumb span { color: var(--text-2); font-weight: 500; }

    /* ── MAIN CARD ── */
    .card {
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: var(--radius-lg);
      box-shadow: var(--shadow);
      overflow: hidden;
    }

    .card-head {
      padding: 22px 28px;
      border-bottom: 1px solid var(--border);
      display: flex; align-items: center; gap: 14px;
    }

    .head-icon {
      width: 42px; height: 42px;
      background: var(--warn-soft);
      border: 1px solid #fde68a;
      border-radius: 11px;
      display: grid; place-items: center;
      color: var(--warn);
      flex-shrink: 0;
    }

    .card-title { font-size: 16px; font-weight: 600; color: var(--text-1); letter-spacing: -.02em; }
    .card-meta  { font-size: 12.5px; color: var(--text-3); margin-top: 2px; }

    /* student ID badge in header */
    .id-badge {
      margin-left: auto;
      display: inline-flex; align-items: center;
      background: var(--accent-soft);
      color: var(--accent);
      font-size: 12px; font-weight: 600;
      border-radius: 7px;
      padding: 4px 10px;
      letter-spacing: .02em;
    }

    /* ── FORM ── */
    .card-body { padding: 28px; }

    .form-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 18px;
      margin-bottom: 24px;
    }

    @media (max-width: 560px) { .form-grid { grid-template-columns: 1fr; } }

    .field { display: flex; flex-direction: column; gap: 6px; }

    .field label {
      font-size: 11.5px; font-weight: 600;
      color: var(--text-2);
      letter-spacing: .06em;
      text-transform: uppercase;
    }

    .input-wrap { position: relative; }

    .input-icon {
      position: absolute; left: 13px; top: 50%; transform: translateY(-50%);
      color: var(--text-3); pointer-events: none; transition: color .18s;
    }

    input[type="text"] {
      width: 100%;
      background: var(--surface);
      border: 1.5px solid var(--border);
      border-radius: var(--radius-sm);
      padding: 10px 14px 10px 40px;
      font-family: 'Sora', sans-serif;
      font-size: 13.5px; color: var(--text-1);
      outline: none;
      transition: border-color .18s, box-shadow .18s;
    }

    input::placeholder { color: var(--text-3); }

    input:focus {
      border-color: var(--accent);
      box-shadow: 0 0 0 3px rgba(61,90,255,.1);
    }

    .input-wrap:focus-within .input-icon { color: var(--accent); }

    /* ── DIVIDER ── */
    .divider { height: 1px; background: var(--border); margin-bottom: 22px; }

    /* ── ACTIONS ── */
    .form-actions { display: flex; align-items: center; gap: 10px; }

    .update-btn {
      display: flex; align-items: center; gap: 7px;
      background: var(--accent); color: #fff;
      border: none; border-radius: var(--radius-sm);
      padding: 11px 24px;
      font-family: 'Sora', sans-serif;
      font-size: 13.5px; font-weight: 600;
      cursor: pointer;
      transition: background .18s, transform .18s, box-shadow .18s;
      box-shadow: 0 3px 10px rgba(61,90,255,.3);
      letter-spacing: -.01em;
    }
    .update-btn:hover {
      background: #2a45e8;
      box-shadow: 0 6px 18px rgba(61,90,255,.4);
      transform: translateY(-1px);
    }
    .update-btn:active { transform: translateY(0); }

    .cancel-link {
      display: flex; align-items: center; gap: 6px;
      background: none;
      border: 1.5px solid var(--border);
      border-radius: var(--radius-sm);
      padding: 10px 18px;
      font-family: 'Sora', sans-serif;
      font-size: 13.5px; font-weight: 500;
      color: var(--text-2);
      text-decoration: none;
      transition: all .18s;
    }
    .cancel-link:hover { background: var(--bg); border-color: #c8cfe0; }

    .delete-link {
      display: flex; align-items: center; gap: 6px;
      margin-left: auto;
      background: var(--danger-soft);
      border: 1.5px solid #fecaca;
      border-radius: var(--radius-sm);
      padding: 10px 16px;
      font-family: 'Sora', sans-serif;
      font-size: 13px; font-weight: 600;
      color: var(--danger);
      text-decoration: none;
      transition: all .18s;
    }
    .delete-link:hover {
      background: #fee2e2; border-color: var(--danger);
      transform: translateY(-1px);
    }

    /* ── FOOTER ── */
    .page-footer {
      text-align: center; padding: 20px;
      font-size: 12px; color: var(--text-3);
    }
  </style>
</head>
<body>

<!-- NAV -->
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

  <div class="nav-right">
    <div class="admin-chip">
      <div class="admin-avatar"><?= strtoupper(substr($_SESSION['admin'], 0, 1)) ?></div>
      <span class="admin-name"><?= htmlspecialchars($_SESSION['admin']) ?></span>
    </div>
    <a href="logout.php" class="back-btn">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
        <polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
      </svg>
      Logout
    </a>
  </div>
</nav>

<!-- PAGE -->
<div class="page">

  <!-- Breadcrumb -->
  <div class="breadcrumb">
    <a href="dashboard.php">Dashboard</a>
    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <polyline points="9 18 15 12 9 6"/>
    </svg>
    <span>Edit Student</span>
  </div>

  <div class="card">

    <!-- Card header -->
    <div class="card-head">
      <div class="head-icon">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
          <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
          <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
        </svg>
      </div>
      <div>
        <div class="card-title">Edit Student</div>
        <div class="card-meta">Update the details below and click Save Changes</div>
      </div>
      <span class="id-badge">ID #<?= htmlspecialchars($id) ?></span>
    </div>

    <!-- Form -->
    <div class="card-body">
      <form method="post">

        <div class="form-grid">

          <div class="field">
            <label for="name">Full Name</label>
            <div class="input-wrap">
              <input type="text" id="name" name="name"
                     value="<?= htmlspecialchars($row['name']) ?>" required>
              <svg class="input-icon" width="15" height="15" viewBox="0 0 24 24"
                   fill="none" stroke="currentColor" stroke-width="2">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                <circle cx="12" cy="7" r="4"/>
              </svg>
            </div>
          </div>

          <div class="field">
            <label for="email">Email Address</label>
            <div class="input-wrap">
              <input type="text" id="email" name="email"
                     value="<?= htmlspecialchars($row['email']) ?>" required>
              <svg class="input-icon" width="15" height="15" viewBox="0 0 24 24"
                   fill="none" stroke="currentColor" stroke-width="2">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                <polyline points="22,6 12,13 2,6"/>
              </svg>
            </div>
          </div>

          <div class="field">
            <label for="phone">Phone Number</label>
            <div class="input-wrap">
              <input type="text" id="phone" name="phone"
                     value="<?= htmlspecialchars($row['phone']) ?>" required>
              <svg class="input-icon" width="15" height="15" viewBox="0 0 24 24"
                   fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.4 2 2 0 0 1 3.6 1.22h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.81a16 16 0 0 0 6.29 6.29l.95-.95a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
              </svg>
            </div>
          </div>

          <div class="field">
            <label for="course">Course</label>
            <div class="input-wrap">
              <input type="text" id="course" name="course"
                     value="<?= htmlspecialchars($row['course']) ?>" required>
              <svg class="input-icon" width="15" height="15" viewBox="0 0 24 24"
                   fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                <path d="M6 12v5c3 3 9 3 12 0v-5"/>
              </svg>
            </div>
          </div>

        </div>

        <div class="divider"></div>

        <div class="form-actions">
          <button class="update-btn" name="update" type="submit">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
              <polyline points="20 6 9 17 4 12"/>
            </svg>
            Save Changes
          </button>

          <a href="dashboard.php" class="cancel-link">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
              <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
            Cancel
          </a>

          <a href="delete_student.php?id=<?= htmlspecialchars($id) ?>"
             class="delete-link"
             onclick="return confirm('Delete <?= htmlspecialchars($row['name'], ENT_QUOTES) ?>? This cannot be undone.')">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
              <polyline points="3 6 5 6 21 6"/>
              <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
              <path d="M10 11v6M14 11v6"/>
            </svg>
            Delete Student
          </a>
        </div>

      </form>
    </div>

  </div>
</div>

<div class="page-footer">EduPanel &copy; <?= date('Y') ?> &mdash; Admin Access Only</div>

</body>
</html>