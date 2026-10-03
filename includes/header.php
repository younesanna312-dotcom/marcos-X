<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= htmlspecialchars($page_title ?? 'BABOUR SHOP') ?></title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header class="topbar">
  <a class="logo" href="index.php">BABOUR<span>SHOP</span></a>
  <nav>
    <a href="shop.php?category=vehicle">VEHICLE</a>
    <a href="shop.php?category=house">HOUSE</a>
    <a href="shop.php?category=toy">TOYS</a>
    <a href="shop.php?category=biz">BIZ</a>
    <a href="shop.php?category=admin">ADMIN</a>
  </nav>
  <div class="nav-right">
    <?php if (!empty($_SESSION['user_id'])): ?>
      <a href="account.php"><?= htmlspecialchars($_SESSION['username']) ?></a>
      <a href="cart.php">CART</a>
      <?php if (($_SESSION['role'] ?? '') === 'admin'): ?><a href="admin/index.php">ADMIN PANEL</a><?php endif; ?>
      <a href="logout.php">LOGOUT</a>
    <?php else: ?>
      <a href="login.php">LOGIN</a><a class="btn small" href="register.php">REGISTER</a>
    <?php endif; ?>
  </div>
</header>
<main class="container">
