<!DOCTYPE html>
<html lang="en-gb" dir="ltr" class="h-full overflow-x-clip scroll-smooth scrollbar-gutter-stable">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Snippet builder for Vivaldi</title>
	<meta name="theme-color" content="#fdfdfd" media="(prefers-color-scheme: light)">
	<meta name="theme-color" content="#272e35" media="(prefers-color-scheme: dark)">
	<link rel="stylesheet" href="site.css" media="screen">

	<!-- mobile scaling -->
	<meta name="viewport" content="width=device-width, initial-scale=1" />

	<script src="script.js" defer></script>
</head>
<body class="h-full m-0 p-0 hyphens-auto">
<div class="grid grid-rows-[auto-1fr-auto] min-h-full px-1">
	<header>
		<h1>Snippet builder for Vivaldi</h1>
	</header>
	<main class="flex">
		<div class="input flex-1">
			<h2 class="mbs-0">Select CSS Snippets</h2>
<?php
// Function to recursively find all CSS files in snippets folder
function findCSSFiles($dir) {
    $files = [];
    $items = scandir($dir);

    foreach ($items as $item) {
        if ($item === '.' || $item === '..' || $item === '.DS_Store') {
            continue;
        }

        $path = $dir . '/' . $item;

        // If it's a directory, recursively search it
        if (is_dir($path)) {
            $cssFiles = glob($path . '/*.css');
            foreach ($cssFiles as $cssFile) {
                $files[] = $cssFile;
            }
        }
    }

    sort($files);
    return $files;
}

// Function to get the label for a CSS file
function getLabel($cssFile) {
    $dir = dirname($cssFile);
    $readmeFile = $dir . '/README.md';

    // If README.md file exists, try to get title from front matter, then first heading
    if (file_exists($readmeFile)) {
        $readmeContent = file_get_contents($readmeFile);

        // Try to extract title from front matter (YAML format between ---)
        if (preg_match('/^---[\s\S]*?title:\s*([^\n]+)[\s\S]*?---/i', $readmeContent, $matches)) {
            $title = trim($matches[1]);
            if (!empty($title)) {
                return $title;
            }
        }

        // Extract the first Markdown heading (# Title)
        if (preg_match('/^#+\s+(.+)$/m', $readmeContent, $matches)) {
            $title = trim($matches[1]);
            if (!empty($title)) {
                return $title;
            }
        }
    }

    // Fallback to filename
    return basename($cssFile, '.css');
}

// Get all CSS files
$cssFiles = findCSSFiles(__DIR__ . '/snippets');

// Output the fieldsets with checkboxes
foreach ($cssFiles as $file) {
    $filename = basename($file);
    $relativePath = str_replace(__DIR__ . '/snippets/', '', $file);
    // Escape path for use as value (replace / with |)
    $value = str_replace('/', '|', $relativePath);
    $label = getLabel($file);
    ?>
    <fieldset>
        <label>
            <input type="checkbox" name="css_<?php echo htmlspecialchars($value); ?>" value="<?php echo htmlspecialchars($value); ?>">
            <?php echo htmlspecialchars($label); ?>
        </label>
    </fieldset>
    <?php
}
?>
	</div>
	<div class="output flex flex-1 flex-col sticky top-0">
		<div class="flex-1 flex flex-col">
			<h3 class="mbs-0">Preview</h2>
			<textarea id="preview" class="flex-1" readonly></textarea>
		</div>
		<button id="downloadBtn" class="flex-shrink-0">Download</button>
	</div>
</main>
<footer class="flex justify-between p-1">
	<div>Made by <a href="https://saschadiercks.de" rel="noreferrer">Sascha Diercks</a></div>
	<div>Made with ❤️ in 🇪🇺</div>
</footer>
</body>
</html>
