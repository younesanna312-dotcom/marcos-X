<?php
require_once "config/database.php";
$page_title = "BABOUR SHOP";
include "includes/header.php";
?>
<section class="hero">
  <div>
    <p class="eyebrow">BABOUR ROLEPLAY STORE</p>
    <h1>YOUR WORLD.<br><span>YOUR COLLECTION.</span></h1>
    <p>Buy exclusive vehicles, houses, toys, businesses and admin products.</p>
    <a class="btn" href="shop.php">OPEN SHOP</a>
  </div>
</section>
<section class="categories">
<?php
$cats = [
 ['vehicle','VEHICLES','🚗'],['house','HOUSES','🏠'],['toy','TOYS','🧸'],['biz','BUSINESSES','🏢'],['admin','ADMIN','👑']
];
foreach($cats as $c): ?>
<a class="category" href="shop.php?category=<?= $c[0] ?>"><b><?= $c[2] ?></b><span><?= $c[1] ?></span></a>
<?php endforeach; ?>
</section>
<?php include "includes/footer.php"; ?>