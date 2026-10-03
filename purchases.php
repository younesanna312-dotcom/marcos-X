<?php
require_once "includes/auth.php"; require_login();
$q=$pdo->prepare("SELECT * FROM orders WHERE user_id=? ORDER BY id DESC");$q->execute([$_SESSION['user_id']]);$orders=$q->fetchAll();
$page_title="Purchases";include "includes/header.php";
?>
<div class="page-head"><h1>PURCHASE HISTORY</h1></div>
<?php if(isset($_GET['success'])):?><div class="success">Purchase completed successfully.</div><?php endif;?>
<?php foreach($orders as $o):?><div class="cart-row"><div><b>ORDER #<?=$o['id']?></b><small><?=htmlspecialchars($o['created_at'])?></small></div><strong><?=number_format($o['total'],2)?> COINS</strong><span><?=htmlspecialchars($o['status'])?></span></div><?php endforeach;?>
<?php if(!$orders):?><div class="empty">No purchases yet.</div><?php endif;?>
<?php include "includes/footer.php"; ?>