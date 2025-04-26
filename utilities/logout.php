<?php
/**
 * Logout Handler
 * 
 * Handles user logout by:
 * 1. Clearing all session data
 * 2. Destroying session cookies
 * 3. Destroying the session
 * 4. Clearing authentication cookies
 * 5. Redirecting to login page
 * 
 * Security measures:
 * - Prevents caching of logout page
 * - Clears all session data
 * - Invalidates session cookies
 * - Uses secure cookie settings
 * - Implements proper session destruction
 * 
 * @return void Redirects to login page
 */

// Prevent page caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Expires: ' . gmdate('D, d M Y H:i:s', time() - 3600) . ' GMT');

// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get session cookie parameters for proper cleanup
$params = session_get_cookie_params();

// Clear all session variables
$_SESSION = array();

// Destroy the session cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(
        session_name(),
        '',
        time() - 3600,
        $params["path"],
        $params["domain"],
        true, // Secure flag
        true  // HTTPOnly flag
    );
}

// Clear PHPSESSID cookie with secure parameters
setcookie(
    'PHPSESSID',
    '',
    time() - 3600,
    '/',
    '',
    true, // Secure flag
    true  // HTTPOnly flag
);

// Destroy the session
session_destroy();

// Prevent Firefox from caching the redirect
header("Location: ../login.php", true, 303);
?>
