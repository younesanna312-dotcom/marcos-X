<?php
require_once "../includes/auth.php"; require_admin();
$id=(int)($_GET['id']??0);$q=$pdo->prepare("SELECT * FROM products WHERE id=?");$q->execute([$id]);$p=$q->fetch();if(!$p)exit("Product not found.");
if($_SERVER['REQUEST_METHOD']==='POST'){
$q=$pdo->prepare("UPDATE products SET name=?,category=?,description=?,price=?,image=?,active=? WHERE id=?");
$q->execute([trim($_POST['name']),$_POST['category'],trim($_POST['description']),floatval($_POST['price']),trim($_POST['image']),isset($_POST['active'])?1:0,$id]);header("Location: index.php");exit;}
$page_title="Edit Product";include "../includes/header.php";?>
<div class="form-box"><h1>EDIT PRODUCT</h1><form method="post">
<input name="name" value="<?=htmlspecialchars($p['name'])?>" required>
<select name="category"><?php foreach(['vehicle','house','toy','biz','admin'] as $c):?><option <?=$p['category']===$c?'selected':''?>><?=$c?></option><?php endforeach;?></select>
<textarea name="description"><?=htmlspecialchars($p['description'])?></textarea><input type="number" step="0.01" name="price" value="<?=$p['price']?>" required><input name="image" value="<?=htmlspecialchars($p['image'])?>">
<label><input type="checkbox" name="active" <?=$p['active']?'checked':''?>> Active</label><button class="btn">SAVE</button></form></div>
<?php include "../includes/footer.php"; ?>