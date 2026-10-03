<?php
require_once "config/database.php";
if (session_status() === PHP_SESSION_NONE) session_start();
$error="";
if($_SERVER['REQUEST_METHOD']==='POST'){
 $q=$pdo->prepare("SELECT * FROM users WHERE username=?"); $q->execute([trim($_POST['username']??'')]); $u=$q->fetch();
 if($u && password_verify($_POST['password']??'', $u['password'])){
  $_SESSION['user_id']=$u['id']; $_SESSION['username']=$u['username']; $_SESSION['role']=$u['role'];
  header("Location: account.php"); exit;
 }
 $error="Invalid username or password.";
}
$page_title="Login"; include "includes/header.php";
?>
<div class="form-box"><h1>Welcome back</h1>
<?php if($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<form method="post"><input name="username" placeholder="Username" required><input type="password" name="password" placeholder="Password" required><button class="btn">LOGIN</button></form>
</div>
<?php include "includes/footer.php"; ?>