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
$requestOrigin = $_SERVER['HTTP_ORIGIN'] ?? $_SERVER['HTTP_REFERER'] ?? '';

// Extract domain from referer if origin is not set
if (empty($requestOrigin) && !empty($_SERVER['HTTP_REFERER'])) {
    $requestOrigin = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_SCHEME) . '://' . parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
}

// Log CORS debug info
error_log('CORS Debug - Origin: ' . $requestOrigin . ', Method: ' . $_SERVER['REQUEST_METHOD']);

// Allow the origin if it's in our allowed list
if (in_array($requestOrigin, $allowedOrigins)) {
    header("Access-Control-Allow-Origin: {$requestOrigin}");
    header('Access-Control-Allow-Credentials: true');
    error_log('CORS: Allowing origin ' . $requestOrigin);
} else {
    // For unknown origins, allow the specific origin if it's from localhost (dev environment)
    // This helps with various development scenarios
    if (strpos($requestOrigin, 'localhost') !== false || strpos($requestOrigin, '127.0.0.1') !== false) {
        header("Access-Control-Allow-Origin: {$requestOrigin}");
        header('Access-Control-Allow-Credentials: true');
        error_log('CORS: Allowing localhost origin ' . $requestOrigin);
    } else {
        // For production, allow the requesting origin if it's from cybaemtech domains
        if (strpos($requestOrigin, 'cybaemtech.in') !== false || strpos($requestOrigin, 'cybaemtech.net') !== false) {
            header("Access-Control-Allow-Origin: {$requestOrigin}");
            header('Access-Control-Allow-Credentials: true');
            error_log('CORS: Allowing cybaemtech origin ' . $requestOrigin);
        } else {
            // Last resort - allow the main domain but log the issue
            header("Access-Control-Allow-Origin: https://cybaemtech.in");
            header('Access-Control-Allow-Credentials: true');
            error_log('CORS: WARNING - Unknown origin ' . $requestOrigin . ', defaulting to https://cybaemtech.in');
        }
    }
}

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