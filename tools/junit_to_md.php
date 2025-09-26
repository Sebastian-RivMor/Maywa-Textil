<?php

$xmlPath = $argv[1] ?? 'tests/_output/report.xml';
$outPath = $argv[2] ?? 'tests/_output/report.md';

if (!file_exists($xmlPath)) {
  fwrite(STDERR, "No existe $xmlPath\n");
  exit(1);
}

$xml = simplexml_load_file($xmlPath);

// Resumen
$totalTests = 0; $totalFailures = 0; $totalErrors = 0; $totalSkipped = 0; $totalTime = 0.0;

foreach ($xml->testsuite as $suite) {
  $totalTests    += (int)($suite['tests'] ?? 0);
  $totalFailures += (int)($suite['failures'] ?? 0);
  $totalErrors   += (int)($suite['errors'] ?? 0);
  $totalSkipped  += (int)($suite['skipped'] ?? 0);
  $totalTime     += (float)($suite['time'] ?? 0);
}

$lines = [];
$lines[] = "# Reporte de pruebas (Codeception → Markdown)";
$lines[] = "";
$lines[] = "- **Total tests:** {$totalTests}";
$lines[] = "- **Fallos:** {$totalFailures}";
$lines[] = "- **Errores:** {$totalErrors}";
$lines[] = "- **Saltados:** {$totalSkipped}";
$lines[] = "- **Tiempo total (s):** " . number_format($totalTime, 3);
$lines[] = "";
$lines[] = "## Detalle por caso";
$lines[] = "";
$lines[] = "| Suite | Caso | Estado | Tiempo (s) | Mensaje |";
$lines[] = "|------|------|--------|------------:|---------|";

foreach ($xml->testsuite as $suite) {
  $suiteName = (string)($suite['name'] ?? 'suite');
  foreach ($suite->testcase as $tc) {
    $name  = (string)$tc['name'];
    $time  = (string)$tc['time'];
    $state = "✅ Passed";
    $msg   = "";

    if (isset($tc->failure)) {
      $state = "❌ Failed";
      $msg   = trim((string)$tc->failure);
    } elseif (isset($tc->error)) {
      $state = "💥 Error";
      $msg   = trim((string)$tc->error);
    } elseif (isset($tc->skipped)) {
      $state = "⏭️ Skipped";
      $msg   = trim((string)$tc->skipped);
    }

    // Limpiar texto para tabla MD
    $msg = preg_replace('/\s+/', ' ', $msg);
    $msg = str_replace('|', '/', $msg);

    $lines[] = "| {$suiteName} | {$name} | {$state} | {$time} | " . ($msg ?: "-") . " |";
  }
}

file_put_contents($outPath, implode("\n", $lines) . "\n");
echo "Markdown generado: $outPath\n";
