<?php
/**
 * Debug script to test environment detection
 * Access via: http://wplayground/wp-content/themes/generatepress_child/debug-env.php
 */

// Simulate WordPress environment
$_SERVER['SERVER_NAME'] = $_SERVER['SERVER_NAME'] ?? 'wplayground';
$_SERVER['HTTP_HOST'] = $_SERVER['HTTP_HOST'] ?? 'wplayground';

echo "<h1>Environment Detection Debug</h1>";
echo "<h2>Server Variables</h2>";
echo "<pre>";
echo "SERVER_NAME: " . ($_SERVER['SERVER_NAME'] ?? 'not set') . "\n";
echo "HTTP_HOST: " . ($_SERVER['HTTP_HOST'] ?? 'not set') . "\n";
echo "</pre>";

echo "<h2>Hostname Resolution</h2>";
echo "<pre>";
$server_name = $_SERVER['SERVER_NAME'] ?? '';
$http_host = $_SERVER['HTTP_HOST'] ?? '';
$http_host_clean = preg_replace('/:\d+$/', '', $http_host);

echo "HTTP_HOST clean: " . $http_host_clean . "\n";

if ($server_name && !str_contains($server_name, ':')) {
    $resolved = gethostbyname($server_name);
    echo "SERVER_NAME ($server_name) resolves to: " . $resolved . "\n";
    echo "Is localhost: " . (($resolved !== $server_name && $resolved === '127.0.0.1') ? 'YES' : 'NO') . "\n";
}

if ($http_host_clean && !str_contains($http_host_clean, ':')) {
    $resolved = gethostbyname($http_host_clean);
    echo "HTTP_HOST ($http_host_clean) resolves to: " . $resolved . "\n";
    echo "Is localhost: " . (($resolved !== $http_host_clean && $resolved === '127.0.0.1') ? 'YES' : 'NO') . "\n";
}
echo "</pre>";

echo "<h2>Dev Server Detection</h2>";
echo "<pre>";
$host = '127.0.0.1';
$ports = [3000, 3001, 3002, 3003, 3004, 3005];

foreach ($ports as $port) {
    set_error_handler(function () {});
    $connection = fsockopen($host, $port, $errno, $errstr, 1);
    restore_error_handler();

    if ($connection) {
        fclose($connection);
        echo "Port $port: OPEN ✓\n";
    } else {
        echo "Port $port: closed ($errno: $errstr)\n";
    }
}
echo "</pre>";
