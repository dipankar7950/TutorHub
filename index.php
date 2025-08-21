<?php require_once __DIR__.'/config.php'; ?>
<?php include __DIR__.'/partials/header.php'; ?>
<div class="card">
  <div class="flex">
    <input class="input" data-ph="search" id="search" placeholder="Search courses...">
    <?php if(is_teacher()){ ?><a class="btn btn-primary" href="/teacher/course_new.php">+ Create Course</a><?php } ?>
  </div>
</div>
<div class="row" id="courseList">
<?php
$q = $_GET['q'] ?? '';
$stmt = $con->prepare("SELECT c.*, u.name as teacher FROM courses c JOIN users u ON u.id=c.teacher_id WHERE c.title LIKE CONCAT('%',?,'%') ORDER BY c.created_at DESC");
$stmt->bind_param("s",$q);
$stmt->execute(); $res=$stmt->get_result();
while($c=$res->fetch_assoc()){ ?>
  <div class="col">
    <div class="card">
      <img class="responsive" src="<?php echo e($c['thumbnail'] ?: 'https://picsum.photos/seed/'.e($c['id']).'/640/360'); ?>" alt="thumb">
      <h3><?php echo e($c['title']); ?></h3>
      <div class="flex">
        <span class="badge"><?php echo e($c['is_paid']?'Paid':'Free'); ?></span>
        <span class="badge"><?php echo e($c['price']); ?> INR</span>
        <span class="badge"><?php echo e($c['start_date']); ?></span>
      </div>
      <small class="muted" data-t="by">By</small> <?php echo e($c['teacher']); ?>
      <p><?php echo nl2br(e(substr($c['description'],0,180))); ?>...</p>
      <a class="btn btn-primary" href="/course.php?id=<?php echo $c['id']; ?>"><span data-t="continue">Continue</span></a>
    </div>
  </div>
<?php } ?>
</div>
<script>
document.getElementById('search').addEventListener('change', e=>{
  location.href='/?q='+encodeURIComponent(e.target.value);
});
</script>
<?php include __DIR__.'/partials/footer.php'; ?>
