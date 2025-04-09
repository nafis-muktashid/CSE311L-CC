<?php
header('Content-Type: application/json');
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

session_start();

$response = [
    'valid' => isset($_SESSION['email']),
    'user_type' => $_SESSION['user_type'] ?? null,
    'company_id' => $_SESSION['companyId'] ?? null
];

echo json_encode($response);
exit;