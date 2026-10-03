<?php
require_once "../includes/auth.php"; require_admin();
if($_SERVER['REQUEST_METHOD']==='POST'){
$q=$pdo->prepare("INSERT INTO products(name,category,description,price,image,active) VALUES(?,?,?,?,?,?)");
$q->execute([trim($_POST['name']),$_POST['category'],trim($_POST['description']),floatval($_POST['price']),trim($_POST['image']),isset($_POST['active'])?1:0]);
header("Location: index.php");exit;}
$page_title="New Product";include "../includes/header.php";?>
<div class="form-box"><h1>NEW PRODUCT</h1><form method="post">
<input name="name" placeholder="Product name" required>
<select name="category"><option value="vehicle">Vehicle</option><option value="house">House</option><option value="toy">Toy</option><option value="biz">Biz</option><option value="admin">Admin</option></select>
<textarea name="description" placeholder="Description"></textarea><input type="number" step="0.01" name="price" placeholder="Price" required><input name="image" placeholder="Image URL (optional)">
<label><input type="checkbox" name="active" checked> Active</label><button class="btn">CREATE</button></form></div>
<?php include "../includes/footer.php"; ?>