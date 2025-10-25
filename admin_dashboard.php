<?php
session_start();

// ---------------- DB CONNECTION ---------------- //
$conn = new mysqli("localhost", "root", "", "car_paint_garage");
if ($conn->connect_error) { die("DB Connection failed: " . $conn->connect_error); }

// ---------------- SIGN UP HANDLER ---------------- //
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signup'])) {
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $password);

    if ($stmt->execute()) {
        $_SESSION['user'] = $username;
        header("Location: admin_dashboard.php");
        exit;
    } else {
        $error = "Username already taken.";
    }
}

// ---------------- LOGIN HANDLER ---------------- //
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res = $stmt->get_result();
    $user = $res->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $username;
        header("Location: admin_dashboard.php");
        exit;
    } else {
        $error = "Invalid login.";
    }
}

// ---------------- LOGOUT ---------------- //
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin_dashboard.php");
    exit;
}

// ---------------- API (your existing one) ---------------- //
if (isset($_GET['api'])) {
    // same API code you already wrote...
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>CarPaintPro — Admin</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body { font-family:'Oswald',sans-serif;letter-spacing:3px;margin:0;background:#000;color:#fff; }
    .auth-page { display:flex;justify-content:center;align-items:center;min-height:100vh; }
    .auth-box { background:#111;padding:40px;border-radius:12px;box-shadow:0 0 20px rgba(255,0,0,0.5);width:320px;text-align:center; }
    .auth-box h2 { margin-bottom:20px;color:#e53935; }
    .auth-box input { width:100%;padding:12px;margin-bottom:15px;border:none;border-radius:8px;background:#222;color:#fff; }
    .auth-box button { width:100%;padding:12px;border:none;border-radius:8px;background:#e53935;color:#fff;font-weight:600;cursor:pointer; }
    .auth-box .switch { margin-top:10px;font-size:14px;color:#bbb; }
    .auth-box .switch a { color:#ff6b6b;text-decoration:none; }
    .error { color:#ff6b6b;margin-bottom:10px; }
  </style>
</head>
<body>
<?php if (!isset($_SESSION['user'])): ?>
  <!-- AUTH PAGE -->
  <div class="auth-page">
    <form class="auth-box" method="post">
      <h2><?= isset($_GET['signup']) ? "Sign Up" : "Login" ?></h2>
      <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
      <input type="text" name="username" placeholder="Username" required>
      <input type="password" name="password" placeholder="Password" required>
      <?php if (isset($_GET['signup'])): ?>
        <button type="submit" name="signup">Sign Up</button>
        <div class="switch">Already have an account? <a href="admin_dashboard.php">Login</a></div>
      <?php else: ?>
        <button type="submit" name="login">Login</button>
        <div class="switch">Don’t have an account? <a href="admin_dashboard.php?signup=1">Sign Up</a></div>
      <?php endif; ?>
    </form>
  </div>
<?php else: ?>
  <!-- DASHBOARD -->
  <div class="app">
    <p>✅ Logged in as <?=htmlspecialchars($_SESSION['user'])?> — <a href="?logout=1">Logout</a></p>
    <!-- Here include your admin dashboard (sidebar, KPIs, calendar, charts) -->
  </div>
<?php endif; ?>
</body>
</html>
