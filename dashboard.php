<?php require_once __DIR__.'/config.php'; if(!is_logged_in()) redirect('/auth/login.php'); ?>
<?php include __DIR__.'/partials/header.php'; ?>
<div class="grid grid-2">
  <div class="card">
    <h2>Welcome, <?php echo e(user()['name']); ?> 👋</h2>
    <p>Your role: <b><?php echo e(user()['role']); ?></b></p>
    <?php if(is_teacher()){ ?>
      <a class="btn btn-primary" href="/teacher/course_new.php">Create Course</a>
      <a class="btn" href="/teacher/my_courses.php">Manage Courses</a>
    <?php } else { ?>
      <a class="btn" href="/student/my_courses.php">My Courses</a>
    <?php } ?>
    <?php if(is_admin()){ ?>
      <a class="btn" href="/admin/index.php">Admin Panel</a>
    <?php } ?>
  </div>
  <div class="card">
    <h3>Notifications 🔔</h3>
    <div id="notifs"></div>
  </div>
</div>
<script>
async function loadNotifs(){
  const r = await fetch('/api/notifications.php'); document.getElementById('notifs').innerHTML = await r.text();
}
loadNotifs(); setInterval(loadNotifs, 10000);
</script>
<?php include __DIR__.'/partials/footer.php'; ?>
