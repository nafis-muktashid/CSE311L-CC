<?php
/**
 * Session Validation Endpoint
 * 
 * This endpoint provides session status information for client-side validation.
 * It returns JSON containing session validity and user details.
 * 
 * Response Format:
 * {
 *    "valid": boolean,        // Whether user is logged in
 *    "user_type": string,    // Type of user (company/admin/null)
 *    "company_id": int|null  // Company ID if applicable
 * }
 * 
 * @return JSON
 */

// Set response headers
header('Content-Type: application/json');

// Prevent caching of response
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Initialize session
session_start();

// Prepare response data
$response = [
    'valid' => isset($_SESSION['email']),
    'user_type' => $_SESSION['user_type'] ?? null,
    'company_id' => $_SESSION['companyId'] ?? null
];

// Send JSON response
echo json_encode($response);
exit;
