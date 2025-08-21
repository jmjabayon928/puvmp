<?php
/**
 * db.php - mysqli connection using env-driven config.php
 * Requires: config.php (which reads from environment variables)
 */

$cfg = require __DIR__ . '/config.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$connection = mysqli_connect(
  $cfg['db']['host'],
  $cfg['db']['user'],
  $cfg['db']['password'],
  $cfg['db']['name'],
  $cfg['db']['port']
);

if (mysqli_connect_errno()) {
  http_response_code(500);
  die('Database connection failed.');
}

mysqli_set_charset($connection, $cfg['db']['charset']);
