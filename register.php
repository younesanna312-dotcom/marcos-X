<?php
require_once "config/database.php";
if (session_status() === PHP_SESSION_NONE) session_start();
$error = "";
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if (strlen($username)<3 || strlen($password)<6) $error="Username must be 3+ characters and password 6+ characters.";
    else {
        $q=$pdo->prepare("SELECT id FROM users WHERE username=? OR email=?"); $q->execute([$username,$email]);
        if($q->fetch()) $error="Username or email already exists.";
        else {
            $hash=password_hash($password,PASSWORD_DEFAULT);
            $q=$pdo->prepare("INSERT INTO users(username,email,password,balance,role) VALUES(?,?,?,0,'user')");
            $q->execute([$username,$email,$hash]);
            $_SESSION['user_id']=$pdo->lastInsertId(); $_SESSION['username']=$username; $_SESSION['role']='user';
            header("Location: account.php"); exit;
        }
    }
}
$page_title="Register"; include "includes/header.php";
?>
<div class="form-box"><h1>Create account</h1>
<?php if($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<form method="post">
<input name="username" placeholder="Username" required>
<input type="email" name="email" placeholder="Email" required>
<input type="password" name="password" placeholder="Password" required>
<button class="btn">REGISTER</button>
</form></div>
<?php include "includes/footer.php"; ?>