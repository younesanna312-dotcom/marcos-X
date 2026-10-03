<?php
require_once "../includes/auth.php"; require_admin();
$products=$pdo->query("SELECT * FROM products ORDER BY id DESC")->fetchAll();
$page_title="Admin Panel"; include "../includes/header.php";
?>
<div class="page-head"><h1>ADMIN PANEL</h1><p>Manage BABOUR SHOP products.</p></div>
<div class="admin-links"><a class="btn" href="product_new.php">+ NEW PRODUCT</a></div>
<div class="admin-table"><div class="tr head"><span>ID</span><span>NAME</span><span>CATEGORY</span><span>PRICE</span><span>ACTIVE</span><span>ACTION</span></div>
<?php foreach($products as $p):?><div class="tr"><span><?=$p['id']?></span><span><?=htmlspecialchars($p['name'])?></span><span><?=htmlspecialchars($p['category'])?></span><span><?=number_format($p['price'],2)?></span><span><?=$p['active']?'YES':'NO'?></span><span><a href="product_edit.php?id=<?=$p['id']?>">EDIT</a></span></div><?php endforeach;?></div>
<?php include "../includes/footer.php"; ?>