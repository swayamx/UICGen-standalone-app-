<?php
require 'db.php';
$id = (int)($_GET['id'] ?? 0);
$stm = $pdo->prepare("SELECT * FROM challenges WHERE id=?");
$stm->execute([$id]);
$c = $stm->fetch();
if (!$c) { echo "Not found"; exit; }

$txt = "UICGen Challenge\n";
$txt .= "Title: " . $c['title'] . "\n";
$txt .= "Domain: " . $c['domain'] . "\n";
$txt .= "Goal:\n" . $c['goal'] . "\n\n";
if ($c['constraint']) $txt .= "Constraint:\n" . $c['constraint'] . "\n\n";
if ($c['dataset_seed']) $txt .= "Dataset seed:\n" . $c['dataset_seed'] . "\n\n";
$txt .= "Metric: " . $c['metric'] . "\n";
$txt .= "Signature: " . $c['signature'] . "\n";
$filename = "uicg-challenge-".$c['id'].".txt";
header('Content-Type: text/plain');
header('Content-Disposition: attachment; filename="'.$filename.'"');
echo $txt;
