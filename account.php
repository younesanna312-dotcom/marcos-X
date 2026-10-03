<?php
require_once "includes/auth.php"; require_login();
$q=$pdo->prepare("SELECT * FROM users WHERE id=?"); $q->execute([$_SESSION['user_id']]); $u=$q->fetch();
$page_title="My Account"; include "includes/header.php";
?>
<div class="account">
<h1>MY ACCOUNT</h1>
<div class="stats"><div><small>USERNAME</small><strong><?=htmlspecialchars($u['username'])?></strong></div><div><small>BALANCE</small><strong><?=number_format($u['balance'],2)?> COINS</strong></div><div><small>ORDERS</small><strong><a href="purchases.php">VIEW</a></strong></div></div>
</div>
<?php include "includes/footer.php"; ?>