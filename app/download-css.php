<?php
// Accept selected snippets via POST (JSON format)
$rawData = file_get_contents('php://input');
$data = json_decode($rawData, true);
$selected = isset($data['selected']) && is_array($data['selected']) ? $data['selected'] : [];

// Validate input - only allow alphanumeric, hyphens, underscores, pipes, and slashes
$selected = array_filter($selected, function($item) {
    return is_string($item) && preg_match('/^[a-zA-Z0-9_\-|\/]+\.css$/i', $item);
});

$cssContent = '';

// Collect CSS content from selected files
foreach ($selected as $relativePath) {
    // Convert pipe delimiter back to forward slash
    $relativePath = str_replace('|', '/', $relativePath);
    $file = __DIR__ . '/snippets/' . $relativePath;

    // Security check - ensure file exists and is in snippets folder
    $realFile = realpath($file);
    $realSnippetsDir = realpath(__DIR__ . '/snippets/');

    if ($realFile && $realSnippetsDir && strpos($realFile, $realSnippetsDir) === 0 && file_exists($realFile)) {
        $cssContent .= file_get_contents($realFile) . "\n\n";
    }
}

// Return the CSS file as a download
header('Content-Type: text/css; charset=utf-8');
header('Content-Disposition: attachment; filename="ui-mod.css"');
echo $cssContent;
?>

