<?php require_once __DIR__.'/../config.php'; if(!is_admin()) redirect('/auth/login.php');
$teachers=$con->query("SELECT * FROM users WHERE role='teacher' ORDER BY created_at DESC");
$students=$con->query("SELECT * FROM users WHERE role='student' ORDER BY created_at DESC");
$payments=$con->query("SELECT p.*, u.name, c.title FROM payments p JOIN enrollments e ON e.id=p.enrollment_id JOIN users u ON u.id=e.student_id JOIN courses c ON c.id=e.course_id ORDER BY p.created_at DESC");
include __DIR__.'/../partials/header.php'; ?>
<div class="grid grid-3">
  <div class="card"><h2>Teachers</h2>
    <table class="table"><?php while($t=$teachers->fetch_assoc()){ ?>
      <tr><td><?php echo e($t['name']); ?></td><td><?php echo e($t['email']); ?></td><td><?php echo $t['verified']?'✅':'⏳'; ?></td>
      <td><a class="btn" href="/admin/verify.php?id=<?php echo $t['id']; ?>">Verify</a></td></tr><?php } ?>
    </table>
  </div>
  <div class="card"><h2>Students</h2>
    <table class="table"><?php while($s=$students->fetch_assoc()){ ?>
      <tr><td><?php echo e($s['name']); ?></td><td><?php echo e($s['email']); ?></td></tr><?php } ?>
    </table>
  </div>
  <div class="card"><h2>Payments</h2>
    <table class="table"><tr><th>Course</th><th>Student</th><th>Amount</th><th>Status</th><th>UTR</th></tr>
      <?php while($p=$payments->fetch_assoc()){ echo "<tr><td>".e($p['title'])."</td><td>".e($p['name'])."</td><td>".e($p['amount'])."</td><td>".e($p['status'])."</td><td>".e($p['upi_txn_id'])."</td></tr>"; } ?>
    </table>
  </div>
</div>
<?php include __DIR__.'/../partials/footer.php'; ?>
