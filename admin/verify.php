<?php require_once __DIR__.'/../config.php'; if(!is_admin()) redirect('/auth/login.php');
$id=intval($_GET['id']??0); $con->query("UPDATE users SET verified=1 WHERE id=$id"); redirect('/admin/index.php');
