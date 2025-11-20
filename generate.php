<?php
require 'db.php';
header('Content-Type: application/json; charset=utf-8');

// read POST JSON or form
$input = $_POST;
$title = trim($input['title'] ?? '');
$domain = trim($input['domain'] ?? 'General');
$goal = trim($input['goal'] ?? '');
$constraint = trim($input['constraint'] ?? '');
$metric = trim($input['metric'] ?? '');
$seed = trim($input['seed'] ?? '');

if (!$title || !$goal) {
    echo json_encode(['ok'=>false,'error'=>'Missing title or goal']);
    exit;
}

// construct canonical string to sign
$canonical = json_encode([
    'title'=>$title,
    'domain'=>$domain,
    'goal'=>$goal,
    'constraint'=>$constraint,
    'metric'=>$metric,
    'seed'=>$seed,
    'ts'=>gmdate('Y-m-d\TH:i:s\Z')
], JSON_UNESCAPED_SLASHES);

// signature: sha256 of canonical + server secret
// set a server secret (in production put in env var)
$secret = 'CHANGE_THIS_TO_A_SECURE_SECRET';
$signature = hash('sha256', $canonical . '|' . $secret);

// store canonical as compact (we store parts separately)
$ins = $pdo->prepare("INSERT INTO challenges (title,domain,goal,constraint,dataset_seed,metric,signature) VALUES (?,?,?,?,?,?,?)");
$ins->execute([$title,$domain,$goal,$constraint,$seed,$metric,$signature]);
$id = $pdo->lastInsertId();

echo json_encode(['ok'=>true,'id'=>$id,'signature'=>$signature,'canonical'=>$canonical]);
