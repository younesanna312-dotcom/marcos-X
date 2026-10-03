<?php
require_once "config/database.php";
$category = $_GET['category'] ?? '';
$params = [];
$sql = "SELECT * FROM products WHERE active=1";
if ($category !== '') { $sql .= " AND category=?"; $params[] = $category; }
$sql .= " ORDER BY id DESC";
$stmt = $pdo->prepare($sql); $stmt->execute($params);
$products = $stmt->fetchAll();
$page_title = "Shop";
include "includes/header.php";
?>
<div class="page-head"><h1><?= $category ? strtoupper(htmlspecialchars($category)) : 'ALL PRODUCTS' ?></h1><p>Choose your item.</p></div>
<div class="grid">
<?php foreach($products as $p): ?>
<article class="card">
  <div class="product-image">
    <?php if ($p['image']): ?><img src="<?= htmlspecialchars($p['image']) ?>" alt=""><?php else: ?><span><?= strtoupper($p['category']) ?></span><?php endif; ?>
  </div>
  <div class="card-body">
    <small><?= strtoupper(htmlspecialchars($p['category'])) ?></small>
    <h2><?= htmlspecialchars($p['name']) ?></h2>
    <p><?= htmlspecialchars($p['description']) ?></p>
    <div class="price"><?= number_format($p['price'],2) ?> <small>COINS</small></div>
    <a class="btn" href="cart.php?action=add&id=<?= (int)$p['id'] ?>">ADD TO CART</a>
  </div>
</article>
<?php endforeach; ?>
</div>
<?php if (!$products): ?><div class="empty">No products available.</div><?php endif; ?>
<?php include "includes/footer.php"; ?>