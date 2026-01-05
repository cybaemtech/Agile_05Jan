<?php
// CORS Headers - Improved for production
// Allow specific origins with credentials

$allowedOrigins = [
    'http://localhost:5000',
    'http://localhost:3000',
    'http://localhost:4173',  // Vite preview port
    'http://127.0.0.1:5000',
    'http://127.0.0.1:4173',  // Vite preview port
    'https://cybaemtech.in',
    'https://www.cybaemtech.in'
];

// Get the origin from the request
$requestOrigin = $_SERVER['HTTP_ORIGIN'] ?? '';

// Log CORS debug info
error_log('CORS Debug - Origin: ' . $requestOrigin . ', Method: ' . $_SERVER['REQUEST_METHOD']);

// Determine which origin to allow
$allowedOrigin = '';

if (in_array($requestOrigin, $allowedOrigins)) {
    $allowedOrigin = $requestOrigin;
    error_log('CORS: Allowing exact match origin ' . $requestOrigin);
} elseif (!empty($requestOrigin)) {
    // Check for localhost variants
    if (strpos($requestOrigin, 'localhost') !== false || strpos($requestOrigin, '127.0.0.1') !== false) {
        $allowedOrigin = $requestOrigin;
        error_log('CORS: Allowing localhost origin ' . $requestOrigin);
    } elseif (strpos($requestOrigin, 'cybaemtech.') !== false) {
        $allowedOrigin = $requestOrigin;
        error_log('CORS: Allowing cybaemtech domain origin ' . $requestOrigin);
    } else {
        $allowedOrigin = 'https://cybaemtech.in';
        error_log('CORS: Unknown origin ' . $requestOrigin . ', defaulting to https://cybaemtech.in');
    }
} else {
    $allowedOrigin = 'https://cybaemtech.in';
    error_log('CORS: No origin provided, defaulting to https://cybaemtech.in');
}

// Set CORS headers
header("Access-Control-Allow-Origin: {$allowedOrigin}");
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Max-Age: 86400');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header("Access-Control-Allow-Methods: GET, POST, PATCH, DELETE, PUT, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
    http_response_code(200);
    exit(0);
}

header('Content-Type: application/json');
?>