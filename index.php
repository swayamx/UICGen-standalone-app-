<?php
require 'db.php';

// fetch recent challenges
$stm = $pdo->query("SELECT id,title,domain,metric,signature,created_at FROM challenges ORDER BY created_at DESC LIMIT 50");
$challenges = $stm->fetchAll();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>UICGen — Unique Interview Challenge Generator</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header>
  <h1>UICGen</h1>
  <p class="muted">Generate a reproducible, signed interview challenge</p>
</header>

<main class="container">
  <section class="panel">
    <h2>Create a unique challenge</h2>
    <form id="genForm">
      <label>Title<br><input name="title" placeholder="e.g. Find anomalies in telemetry" required></label>

      <label>Domain<br>
        <select name="domain">
          <option>Data Engineering</option>
          <option>Algorithms</option>
          <option>Web</option>
          <option>DevOps</option>
          <option>Security</option>
          <option>Embedded</option>
        </select>
      </label>

      <label>Goal (short)<br><input name="goal" placeholder="e.g. detect spikes in 1M rows" required></label>
      <label>Constraint (optional)<br><input name="constraint" placeholder="e.g. memory < 50MB / Node.js only"></label>
      <label>Metric (how to measure success)<br><input name="metric" placeholder="e.g. F1 / max latency / memory usage"></label>

      <div class="row">
        <button type="submit">Generate</button>
        <button type="button" id="regenSeed">Randomize Seed</button>
      </div>
    </form>
    <div id="genMsg"></div>
  </section>

  <section class="panel">
    <h2>Recent challenges</h2>
    <ul id="list">
      <?php foreach($challenges as $c): ?>
        <li>
          <a href="view.php?id=<?= $c['id'] ?>"><?= htmlspecialchars($c['title']) ?></a>
          <div class="meta"><?= htmlspecialchars($c['domain']) ?> • <?= htmlspecialchars($c['metric']) ?>
            <span class="sig">sig: <?= htmlspecialchars(substr($c['signature'],0,16)) ?>…</span>
          </div>
        </li>
      <?php endforeach; ?>
    </ul>
  </section>
</main>

<script src="assets/app.js"></script>
</body>
</html>
