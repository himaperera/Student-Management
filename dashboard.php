<?php
session_start();
include "db.php";

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
}

/* ADD STUDENT */
if(isset($_POST['add'])){
    $name   = $_POST['name'];
    $email  = $_POST['email'];
    $phone  = $_POST['phone'];
    $course = $_POST['course'];

    $conn->query("INSERT INTO students(name,email,phone,course)
                  VALUES('$name','$email','$phone','$course')");
}

/* GET STUDENTS */
$students = $conn->query("SELECT * FROM students");
$total    = $students->num_rows;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Student Dashboard</title>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  :root {
    --bg:          #f0f2f7;
    --surface:     #ffffff;
    --surface-2:   #f7f8fc;
    --border:      #e3e7f0;
    --accent:      #3d5aff;
    --accent-soft: #eef0ff;
    --accent-2:    #00c48c;
    --text-1:      #111827;
    --text-2:      #4b5563;
    --text-3:      #9ca3af;
    --danger:      #ef4444;
    --danger-soft: #fef2f2;
    --warn:        #f59e0b;
    --warn-soft:   #fffbeb;
    --radius-sm:   8px;
    --radius:      14px;
    --radius-lg:   20px;
    --shadow-sm:   0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
    --shadow:      0 4px 16px rgba(0,0,0,.07), 0 1px 4px rgba(0,0,0,.05);
    --shadow-lg:   0 12px 40px rgba(0,0,0,.12), 0 3px 10px rgba(0,0,0,.06);
  }

  html, body { height: 100%; }

  body {
    font-family: 'Sora', sans-serif;
    background: var(--bg);
    color: var(--text-1);
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    animation: pageFade .4s ease both;
  }

  @keyframes pageFade {
    from { opacity: 0; transform: translateY(6px); }
    to   { opacity: 1; transform: translateY(0);   }
  }

  /* ── TOP NAV ── */
  .nav {
    background: var(--surface);
    border-bottom: 1px solid var(--border);
    padding: 0 32px;
    height: 64px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: sticky;
    top: 0;
    z-index: 100;
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

  .nav-right {
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .admin-chip {
    display: flex;
    align-items: center;
    gap: 8px;
    background: var(--surface-2);
    border: 1px solid var(--border);
    border-radius: 100px;
    padding: 6px 14px 6px 6px;
  }

  .admin-avatar {
    width: 28px; height: 28px;
    background: linear-gradient(135deg, var(--accent), #7c6fff);
    border-radius: 50%;
    display: grid;
    place-items: center;
    font-size: 11px;
    font-weight: 600;
    color: #fff;
  }

  .admin-name {
    font-size: 13px;
    font-weight: 500;
    color: var(--text-2);
  }

  .logout-btn {
    display: flex;
    align-items: center;
    gap: 6px;
    background: none;
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    padding: 7px 14px;
    font-family: 'Sora', sans-serif;
    font-size: 13px;
    font-weight: 500;
    color: var(--text-2);
    cursor: pointer;
    text-decoration: none;
    transition: all .18s;
  }

  .logout-btn:hover {
    background: var(--danger-soft);
    border-color: #fca5a5;
    color: var(--danger);
  }

  /* ── PAGE BODY ── */
  .page { padding: 32px; max-width: 1200px; margin: 0 auto; width: 100%; }

  /* ── STAT CARDS ── */
  .stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 16px;
    margin-bottom: 28px;
  }

  .stat-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 20px 22px;
    box-shadow: var(--shadow-sm);
    display: flex;
    align-items: center;
    gap: 16px;
    animation: slideUp .4s ease both;
  }

  .stat-card:nth-child(1) { animation-delay: .05s; }
  .stat-card:nth-child(2) { animation-delay: .10s; }
  .stat-card:nth-child(3) { animation-delay: .15s; }

  @keyframes slideUp {
    from { opacity: 0; transform: translateY(10px); }
    to   { opacity: 1; transform: translateY(0);    }
  }

  .stat-icon {
    width: 44px; height: 44px;
    border-radius: 12px;
    display: grid;
    place-items: center;
    flex-shrink: 0;
  }

  .stat-icon.blue  { background: var(--accent-soft); color: var(--accent); }
  .stat-icon.green { background: #d1fae5;            color: var(--accent-2); }
  .stat-icon.amber { background: var(--warn-soft);   color: var(--warn); }

  .stat-label { font-size: 12px; color: var(--text-3); font-weight: 500; letter-spacing: .04em; text-transform: uppercase; }
  .stat-value {
    font-size: 26px;
    font-weight: 700;
    color: var(--text-1);
    font-family: 'JetBrains Mono', monospace;
    letter-spacing: -.03em;
    line-height: 1;
    margin-top: 4px;
  }

  /* ── MAIN CARD ── */
  .main-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow);
    overflow: hidden;
    animation: slideUp .4s .2s ease both;
  }

  .card-head {
    padding: 22px 26px;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: var(--surface);
  }

  .card-title-wrap { display: flex; flex-direction: column; gap: 2px; }

  .card-title {
    font-size: 16px;
    font-weight: 600;
    color: var(--text-1);
    letter-spacing: -.02em;
  }

  .card-meta { font-size: 12.5px; color: var(--text-3); }

  .add-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    background: var(--accent);
    color: #fff;
    border: none;
    border-radius: var(--radius-sm);
    padding: 9px 18px;
    font-family: 'Sora', sans-serif;
    font-size: 13.5px;
    font-weight: 600;
    cursor: pointer;
    transition: all .18s;
    box-shadow: 0 3px 10px rgba(61,90,255,.3);
    letter-spacing: -.01em;
  }

  .add-btn:hover {
    background: #2a45e8;
    box-shadow: 0 6px 18px rgba(61,90,255,.4);
    transform: translateY(-1px);
  }

  .add-btn:active { transform: translateY(0); }

  /* ── ADD FORM PANEL ── */
  .form-panel {
    display: none;
    padding: 24px 26px;
    background: var(--surface-2);
    border-bottom: 1px solid var(--border);
    animation: expandDown .28s cubic-bezier(.22,.68,0,1.1) both;
  }

  .form-panel.open { display: block; }

  @keyframes expandDown {
    from { opacity: 0; transform: translateY(-8px); }
    to   { opacity: 1; transform: translateY(0);    }
  }

  .form-panel h3 {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-1);
    margin-bottom: 18px;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .form-panel h3::before {
    content: '';
    display: inline-block;
    width: 4px; height: 16px;
    background: var(--accent);
    border-radius: 4px;
  }

  .form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 14px;
    margin-bottom: 18px;
  }

  .field label {
    display: block;
    font-size: 11.5px;
    font-weight: 600;
    color: var(--text-2);
    letter-spacing: .06em;
    text-transform: uppercase;
    margin-bottom: 6px;
  }

  .field input {
    width: 100%;
    background: var(--surface);
    border: 1.5px solid var(--border);
    border-radius: var(--radius-sm);
    padding: 10px 14px;
    font-family: 'Sora', sans-serif;
    font-size: 13.5px;
    color: var(--text-1);
    outline: none;
    transition: border-color .18s, box-shadow .18s;
  }

  .field input::placeholder { color: var(--text-3); }

  .field input:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(61,90,255,.1);
  }

  .form-actions { display: flex; gap: 10px; }

  .save-btn {
    display: flex;
    align-items: center;
    gap: 7px;
    background: var(--accent);
    color: #fff;
    border: none;
    border-radius: var(--radius-sm);
    padding: 10px 22px;
    font-family: 'Sora', sans-serif;
    font-size: 13.5px;
    font-weight: 600;
    cursor: pointer;
    transition: all .18s;
    box-shadow: 0 2px 8px rgba(61,90,255,.3);
  }

  .save-btn:hover { background: #2a45e8; transform: translateY(-1px); }

  .cancel-btn {
    background: none;
    border: 1.5px solid var(--border);
    border-radius: var(--radius-sm);
    padding: 10px 18px;
    font-family: 'Sora', sans-serif;
    font-size: 13.5px;
    font-weight: 500;
    color: var(--text-2);
    cursor: pointer;
    transition: all .18s;
  }

  .cancel-btn:hover { background: var(--bg); border-color: #c8cfe0; }

  /* ── TABLE ── */
  .table-wrap { overflow-x: auto; }

  table {
    width: 100%;
    border-collapse: collapse;
  }

  thead {
    background: var(--surface-2);
    border-bottom: 1px solid var(--border);
  }

  th {
    padding: 12px 20px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: var(--text-3);
    text-align: left;
    white-space: nowrap;
  }

  th:first-child { padding-left: 26px; }
  th:last-child  { padding-right: 26px; text-align: right; }

  td {
    padding: 14px 20px;
    font-size: 13.5px;
    color: var(--text-2);
    border-bottom: 1px solid var(--border);
    vertical-align: middle;
  }

  td:first-child { padding-left: 26px; }
  td:last-child  { padding-right: 26px; text-align: right; }

  tr:last-child td { border-bottom: none; }

  tbody tr {
    transition: background .14s;
  }

  tbody tr:hover { background: var(--surface-2); }

  /* ID badge */
  .id-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: var(--accent-soft);
    color: var(--accent);
    font-family: 'JetBrains Mono', monospace;
    font-size: 12px;
    font-weight: 500;
    border-radius: 6px;
    padding: 2px 8px;
    min-width: 32px;
  }

  /* Name cell */
  .name-cell { font-weight: 600; color: var(--text-1); }

  /* Course tag */
  .course-tag {
    display: inline-block;
    background: var(--surface-2);
    border: 1px solid var(--border);
    border-radius: 100px;
    padding: 3px 10px;
    font-size: 12px;
    color: var(--text-2);
    font-weight: 500;
  }

  /* Action buttons */
  .actions { display: flex; justify-content: flex-end; gap: 8px; }

  .edit-btn, .delete-btn {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    border-radius: 7px;
    padding: 6px 12px;
    font-size: 12.5px;
    font-weight: 600;
    font-family: 'Sora', sans-serif;
    text-decoration: none;
    transition: all .16s;
    border: 1.5px solid transparent;
  }

  .edit-btn {
    background: var(--warn-soft);
    color: #92400e;
    border-color: #fde68a;
  }

  .edit-btn:hover {
    background: #fef3c7;
    border-color: var(--warn);
    transform: translateY(-1px);
  }

  .delete-btn {
    background: var(--danger-soft);
    color: var(--danger);
    border-color: #fecaca;
  }

  .delete-btn:hover {
    background: #fee2e2;
    border-color: var(--danger);
    transform: translateY(-1px);
  }

  /* ── EMPTY STATE ── */
  .empty-state {
    padding: 64px 20px;
    text-align: center;
  }

  .empty-icon {
    width: 56px; height: 56px;
    background: var(--surface-2);
    border-radius: 16px;
    display: grid;
    place-items: center;
    margin: 0 auto 16px;
    color: var(--text-3);
  }

  .empty-title { font-size: 15px; font-weight: 600; color: var(--text-2); margin-bottom: 6px; }
  .empty-sub   { font-size: 13px; color: var(--text-3); }
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
    <a href="logout.php" class="logout-btn">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
      </svg>
      Logout
    </a>
  </div>
</nav>

<!-- PAGE -->
<div class="page">

  <!-- STATS -->
  <div class="stats">
    <div class="stat-card">
      <div class="stat-icon blue">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
          <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
        </svg>
      </div>
      <div>
        <div class="stat-label">Total Students</div>
        <div class="stat-value"><?= $total ?></div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon green">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
        </svg>
      </div>
      <div>
        <div class="stat-label">Active</div>
        <div class="stat-value"><?= $total ?></div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon amber">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
        </svg>
      </div>
      <div>
        <div class="stat-label">This Month</div>
        <div class="stat-value"><?= $total ?></div>
      </div>
    </div>
  </div>

  <!-- MAIN TABLE CARD -->
  <div class="main-card">

    <div class="card-head">
      <div class="card-title-wrap">
        <div class="card-title">All Students</div>
        <div class="card-meta"><?= $total ?> record<?= $total !== 1 ? 's' : '' ?> found</div>
      </div>
      <button class="add-btn" onclick="toggleForm()">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
          <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Add Student
      </button>
    </div>

    <!-- ADD FORM -->
    <div id="form-panel" class="form-panel">
      <h3>New Student</h3>
      <form method="post">
        <div class="form-grid">
          <div class="field">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" placeholder="e.g. Jane Doe" required>
          </div>
          <div class="field">
            <label for="email">Email Address</label>
            <input type="text" id="email" name="email" placeholder="e.g. jane@email.com" required>
          </div>
          <div class="field">
            <label for="phone">Phone Number</label>
            <input type="text" id="phone" name="phone" placeholder="e.g. +1 555 0100" required>
          </div>
          <div class="field">
            <label for="course">Course</label>
            <input type="text" id="course" name="course" placeholder="e.g. Computer Science" required>
          </div>
        </div>
        <div class="form-actions">
          <button class="save-btn" name="add" type="submit">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
              <polyline points="20 6 9 17 4 12"/>
            </svg>
            Save Student
          </button>
          <button type="button" class="cancel-btn" onclick="toggleForm()">Cancel</button>
        </div>
      </form>
    </div>

    <!-- TABLE -->
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Course</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if($total === 0): ?>
          <tr>
            <td colspan="6">
              <div class="empty-state">
                <div class="empty-icon">
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                    <line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/>
                  </svg>
                </div>
                <div class="empty-title">No students yet</div>
                <div class="empty-sub">Click "Add Student" to enrol your first student.</div>
              </div>
            </td>
          </tr>
          <?php else: while($row = $students->fetch_assoc()): ?>
          <tr>
            <td><span class="id-badge"><?= $row['id'] ?></span></td>
            <td class="name-cell"><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['phone']) ?></td>
            <td><span class="course-tag"><?= htmlspecialchars($row['course']) ?></span></td>
            <td>
              <div class="actions">
                <a class="edit-btn" href="edit_student.php?id=<?= $row['id'] ?>">
                  <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                  </svg>
                  Edit
                </a>
                <a class="delete-btn" href="delete_student.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this student?')"
                
            onclick="return confirm('Delete <?= htmlspecialchars($row['name'], ENT_QUOTES) ?>?')">
                  <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                    <path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                  </svg>
                  Delete
                </a>
              </div>
            </td>
          </tr>
          <?php endwhile; endif; ?>
        </tbody>
      </table>
    </div>

  </div>
</div>

<script>
  function toggleForm() {
    const panel = document.getElementById('form-panel');
    const isOpen = panel.classList.toggle('open');
    if (isOpen) {
      panel.style.display = 'block';
      document.getElementById('name').focus();
    } else {
      panel.style.display = 'none';
    }
  }
</script>
</body>
</html>