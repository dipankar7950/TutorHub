<?php
// Update these for your hosting
$DB_HOST = getenv('DB_HOST') ?: 'sql201.hstn.me';
$DB_USER = getenv('DB_USER') ?: 'mseet_39704050';
$DB_PASS = getenv('DB_PASS') ?: 'NH0iQLvpzxFo';
$DB_NAME = getenv('DB_NAME') ?: 'mseet_39704050_dipankaraiworld';

$con = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($con->connect_errno) { die("DB Connection failed: ".$con->connect_error); }
$con->set_charset("utf8mb4");
session_start();

function is_logged_in(){ return isset($_SESSION['user']); }
function user(){ return $_SESSION['user'] ?? null; }
function is_teacher(){ return is_logged_in() && $_SESSION['user']['role']==='teacher'; }
function is_admin(){ return is_logged_in() && $_SESSION['user']['role']==='admin'; }
function e($s){ return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); }
function redirect($path){ header("Location: $path"); exit; }
?>
