<?php require_once __DIR__.'/config.php';
$id = intval($_GET['id'] ?? 0);
$stmt=$con->prepare("SELECT c.*, u.name as teacher FROM courses c JOIN users u ON u.id=c.teacher_id WHERE c.id=?");
$stmt->bind_param("i",$id); $stmt->execute(); $c=$stmt->get_result()->fetch_assoc();
if(!$c){ die("Course not found"); }

$enrolled = false; $paid=false;
if(is_logged_in()){
  $chk=$con->prepare("SELECT * FROM enrollments WHERE course_id=? AND student_id=?");
  $uid=user()['id']; $chk->bind_param("ii",$id,$uid); $chk->execute();
  if($en=$chk->get_result()->fetch_assoc()){ $enrolled=true; $paid = $en['paid']==1; $enrollment_id=$en['id']; }
}
include __DIR__.'/partials/header.php'; ?>
<div class="grid grid-2">
  <div class="card">
    <img class="responsive" src="<?php echo e($c['thumbnail'] ?: 'https://picsum.photos/seed/'.e($c['id']).'/640/360'); ?>">
    <h2><?php echo e($c['title']); ?></h2>
    <div class="flex">
      <span class="badge"><?php echo e($c['is_paid']?'Paid':'Free'); ?></span>
      <span class="badge"><?php echo e($c['price']); ?> INR</span>
      <span class="badge"><?php echo e($c['start_date']); ?></span>
    </div>
    <p><?php echo nl2br(e($c['description'])); ?></p>
    <?php if(!$enrolled){ ?>
      <?php if(!is_logged_in()){ ?>
        <div class="notice">Please <a href="/auth/login.php">login</a> to enroll.</div>
      <?php } else { ?>
        <form method="post" action="/enroll.php">
          <input type="hidden" name="course_id" value="<?php echo $c['id']; ?>">
          <button class="btn btn-primary"><?php echo $c['is_paid']?'Enroll & Pay':'Enroll'; ?></button>
        </form>
      <?php } ?>
    <?php } else { ?>
      <div class="alert <?php echo $paid?'alert-success':''; ?>">You are enrolled<?php echo $c['is_paid'] && !$paid ? ' (payment pending)' : ''; ?>.</div>
      <?php if($c['is_paid'] && !$paid){ ?>
        <a class="btn btn-primary" href="/payments/pay.php?enrollment_id=<?php echo $enrollment_id; ?>">Pay now</a>
      <?php } ?>
    <?php } ?>
  </div>

  <div class="card">
    <h3 data-t="course_materials">Course Materials</h3>
    <?php
    if($enrolled && (!$c['is_paid'] || $paid)){
      $m=$con->prepare("SELECT * FROM materials WHERE course_id=? ORDER BY created_at DESC");
      $m->bind_param("i",$id); $m->execute(); $res=$m->get_result();
      while($row=$res->fetch_assoc()){
        echo '<div class="notice"><b>'.e($row['title']).'</b><br>';
        if($row['type']=='pdf'){ echo '<a class="btn" target="_blank" href="'.e($row['filepath']).'">Open PDF</a>'; }
        if($row['type']=='video'){ echo '<video controls src="'.e($row['filepath']).'"></video>'; }
        if($row['type']=='note'){ echo '<a class="btn" href="'.e($row['filepath']).'">Download file</a>'; }
        if($row['type']=='text'){ echo '<div>'.nl2br(e($row['content'])).'</div>'; }
        echo '</div>';
      }
    } else {
      echo '<div class="notice">Enroll to access materials.</div>';
    }
    ?>
  </div>
</div>

<div class="card">
  <h3 data-t="chat">Discussion & Doubts 💬</h3>
  <?php if($enrolled){ ?>
  <div id="chatBox" style="max-height:320px;overflow:auto;border:1px solid #2b3c77;border-radius:8px;padding:8px"></div>
  <form id="chatForm" class="flex" onsubmit="return sendMsg()">
    <input class="input" id="msg" placeholder="Ask or reply with 😊📚" required>
    <button class="btn btn-primary">Send</button>
  </form>
  <script>
  async function loadChat(){
    const res = await fetch('/api/chat.php?course_id=<?php echo $id; ?>'); 
    document.getElementById('chatBox').innerHTML = await res.text();
    const box=document.getElementById('chatBox'); box.scrollTop=box.scrollHeight;
  }
  async function sendMsg(){
    const body=document.getElementById('msg').value; if(!body) return false;
    await fetch('/api/chat.php', {method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded'}, body:new URLSearchParams({course_id:'<?php echo $id; ?>', body})});
    document.getElementById('msg').value=''; loadChat(); return false;
  }
  loadChat(); setInterval(loadChat, 3000);
  </script>
  <?php } else { echo '<div class="notice">Enroll to join the discussion.</div>'; } ?>
</div>

<?php include __DIR__.'/partials/footer.php'; ?>
