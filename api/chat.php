<?php require_once __DIR__.'/../config.php';
if($_SERVER['REQUEST_METHOD']==='POST'){
  if(!is_logged_in()) { http_response_code(401); exit; }
  $cid=intval($_POST['course_id']); $uid=user()['id']; $body=trim($_POST['body']);
  if($body!==''){
    $stmt=$con->prepare("INSERT INTO messages (course_id,user_id,body) VALUES (?,?,?)");
    $stmt->bind_param("iis",$cid,$uid,$body); $stmt->execute();
  }
  exit;
}
$cid=intval($_GET['course_id'] ?? 0);
$res=$con->query("SELECT m.*, u.name FROM messages m JOIN users u ON u.id=m.user_id WHERE m.course_id=$cid ORDER BY m.created_at ASC");
while($m=$res->fetch_assoc()){
  echo "<div><b>".e($m['name']).":</b> ".nl2br(e($m['body']))." <small class='muted'>(".e($m['created_at']).")</small></div>";
}
