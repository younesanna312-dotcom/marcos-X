<?php
require_once "includes/auth.php"; require_login();
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['cart'])) $_SESSION['cart']=[];
if(($_GET['action']??'')==='add'){
 $id=(int)($_GET['id']??0); $q=$pdo->prepare("SELECT id FROM products WHERE id=? AND active=1"); $q->execute([$id]);
 if($q->fetch()) $_SESSION['cart'][$id]=1;
 header("Location: cart.php"); exit;
}
if(isset($_GET['remove'])){ unset($_SESSION['cart'][(int)$_GET['remove']]); header("Location: cart.php"); exit; }
$ids=array_keys($_SESSION['cart']); $items=[]; $total=0;
if($ids){$in=implode(',',array_fill(0,count($ids),'?'));$q=$pdo->prepare("SELECT * FROM products WHERE id IN ($in)");$q->execute($ids);$items=$q->fetchAll();}
foreach($items as $i)$total+=(float)$i['price'];
$page_title="Cart"; include "includes/header.php";
?>
<div class="page-head"><h1>YOUR CART</h1></div>
<?php foreach($items as $i): ?><div class="cart-row"><div><b><?=htmlspecialchars($i['name'])?></b><small><?=htmlspecialchars($i['category'])?></small></div><strong><?=number_format($i['price'],2)?> COINS</strong><a href="cart.php?remove=<?=$i['id']?>">REMOVE</a></div><?php endforeach; ?>
<div class="checkout"><h2>TOTAL: <?=number_format($total,2)?> COINS</h2><a class="btn" href="checkout.php">CHECKOUT</a></div>
<?php if(!$items): ?><div class="empty">Your cart is empty.</div><?php endif; ?>
<?php include "includes/footer.php"; ?>