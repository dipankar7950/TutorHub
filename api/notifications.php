<?php require_once __DIR__.'/../config.php';
if(!is_logged_in()){ echo "Login to see notifications."; exit; }
$uid=user()['id']; $role=user()['role'];
if($role==='teacher'){
  $res=$con->query("SELECT CONCAT('New enrollment in ', c.title) msg, e.joined_at ts FROM enrollments e JOIN courses c ON c.id=e.course_id WHERE c.teacher_id=$uid ORDER BY e.joined_at DESC LIMIT 10");
} else {
  $res=$con->query("SELECT CONCAT('Upcoming class: ', l.title, ' at ', l.start_time) msg, l.start_time ts FROM live_classes l JOIN enrollments e ON e.course_id=l.course_id WHERE e.student_id=$uid ORDER BY l.start_time DESC LIMIT 10");
}
while($r=$res->fetch_assoc()){
  echo "<div class='notice'>".e($r['msg'])."</div>";
}
