<?php require_once __DIR__.'/config.php'; if(!is_logged_in()) redirect('/auth/login.php');
$course_id = intval($_POST['course_id'] ?? 0);
$uid = user()['id'];
$stmt=$con->prepare("INSERT IGNORE INTO enrollments (course_id, student_id, paid) VALUES (?,?,0)");
$stmt->bind_param("ii",$course_id,$uid);
$stmt->execute();
$enroll_id = $con->insert_id;
$c = $con->query("SELECT * FROM courses WHERE id=".$course_id)->fetch_assoc();
if($c['is_paid']){
  // create payment pending
  $e = $con->query("SELECT * FROM enrollments WHERE course_id=$course_id AND student_id=$uid")->fetch_assoc();
  $enroll_id = $e['id'];
  $con->query("INSERT INTO payments (enrollment_id, amount, status) VALUES ($enroll_id, ".floatval($c['price']).", 'pending')");
  redirect('/payments/pay.php?enrollment_id='.$enroll_id);
} else {
  redirect('/course.php?id='.$course_id);
}
