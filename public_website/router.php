<?php
// Built-in PHP server router to support pretty URLs locally
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$docRoot = __DIR__;

// Serve existing files or directories as-is
if ($uri !== '/' && file_exists($docRoot . $uri)) {
    return false; // let the built-in server handle it
}

// Map "/path" to "/path.php" if it exists
$phpCandidate = rtrim($docRoot . $uri, '/') . '.php';
if (file_exists($phpCandidate)) {
    require $phpCandidate;
    return true;
}

// Otherwise, load index.php as the front controller
require $docRoot . '/index.php';
return true;

