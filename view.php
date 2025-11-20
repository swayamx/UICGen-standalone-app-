<?php
require 'db.php';
$id = (int)($_GET['id'] ?? 0);
$stm = $pdo->prepare("SELECT * FROM challenges WHERE id = ?");
$stm->execute([$id]);
$c = $stm->fetch();
if (!$c) { echo "Challenge not found"; exit; }
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8"><title><?=htmlspecialchars($c['title'])?></title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<main class="container">
  <a href="index.php">← Back</a>
  <article class="panel">
    <h1><?=htmlspecialchars($c['title'])?></h1>
    <div class="meta"><?=htmlspecialchars($c['domain'])?> • <?=htmlspecialchars($c['metric'])?> • created <?=htmlspecialchars($c['created_at'])?></div>
    <h3>Goal</h3>
    <p><?=nl2br(htmlspecialchars($c['goal']))?></p>
    <?php if ($c['constraint']): ?>
      <h3>Constraint</h3><p><?=nl2br(htmlspecialchars($c['constraint']))?></p>
    <?php endif; ?>
    <?php if ($c['dataset_seed']): ?>
      <h3>Dataset seed</h3><pre><?=htmlspecialchars($c['dataset_seed'])?></pre>
    <?php endif; ?>
    <h3>Signature</h3>
    <pre id="sig"><?=htmlspecialchars($c['signature'])?></pre>
    <div id="verify"></div>
    <div class="row">
      <a class="btn" href="export.php?id=<?= $c['id'] ?>">Export (.txt)</a>
    </div>
  </article>
</main>

<script>
  // small client-side verifier (asks server to show canonical if needed)
  // but we can show quick verification by asking server endpoint (secure)
  async function verify(){
    const res = await fetch('verify.php?id=<?= $c['id'] ?>', {method:'GET'});
    const j = await res.json();
    document.getElementById('verify').textContent = j.ok ? 'Signature valid ✔' : ('Invalid signature: '+ (j.error||'unknown'));
  }
  verify();
</script>
</body>
</html>
