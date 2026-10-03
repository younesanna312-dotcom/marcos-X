<?php
require_once "includes/auth.php"; require_login();
if (session_status() === PHP_SESSION_NONE) session_start();
$ids=array_keys($_SESSION['cart']??[]); if(!$ids){header("Location: shop.php");exit;}
$in=implode(',',array_fill(0,count($ids),'?')); $q=$pdo->prepare("SELECT * FROM products WHERE id IN ($in) AND active=1");$q->execute($ids);$items=$q->fetchAll();
$total=array_sum(array_map(fn($x)=>(float)$x['price'],$items));
$u=$pdo->prepare("SELECT balance FROM users WHERE id=?");$u->execute([$_SESSION['user_id']]);$balance=(float)$u->fetchColumn();
$msg="";
if($_SERVER['REQUEST_METHOD']==='POST'){
 if($balance < $total) $msg="Insufficient balance.";
 else {
  $pdo->beginTransaction();
  $pdo->prepare("UPDATE users SET balance=balance-? WHERE id=?")->execute([$total,$_SESSION['user_id']]);
  $o=$pdo->prepare("INSERT INTO orders(user_id,total,status) VALUES(?,?, 'paid')");$o->execute([$_SESSION['user_id'],$total]);$oid=$pdo->lastInsertId();
  $oi=$pdo->prepare("INSERT INTO order_items(order_id,product_id,price) VALUES(?,?,?)");
  foreach($items as $i)$oi->execute([$oid,$i['id'],$i['price']]);
  $pdo->commit(); $_SESSION['cart']=[]; header("Location: purchases.php?success=1"); exit;
 }
}
$page_title="Checkout";include "includes/header.php";
?>
<div class="form-box"><h1>CHECKOUT</h1><p>Total: <b><?=number_format($total,2)?> COINS</b></p><p>Your balance: <b><?=number_format($balance,2)?> COINS</b></p>
<?php if($msg):?><div class="error"><?=htmlspecialchars($msg)?></div><?php endif;?>
<form method="post"><button class="btn">CONFIRM PURCHASE</button></form></div>
<?php include "includes/footer.php"; ?>