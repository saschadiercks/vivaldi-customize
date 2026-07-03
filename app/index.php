<!DOCTYPE html>
<html lang="en-gb" dir="ltr">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Snippet builder for Vivaldi</title>
	<meta name="theme-color" content="#fdfdfd" media="(prefers-color-scheme: light)">
	<meta name="theme-color" content="#272e35" media="(prefers-color-scheme: dark)">
	<link rel="stylesheet" href="site.css" media="screen">

	<!-- mobile scaling -->
	<meta name="viewport" content="width=device-width, initial-scale=1" />
</head>
<body>
	<h1>Snippet builder for Vivaldi</h1>
	<div class="flex">
		<div class="input">
			<h2>Select CSS Snippets</h2>
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

    // If README.md file exists, try to get the first heading
    if (file_exists($readmeFile)) {
        $readmeContent = file_get_contents($readmeFile);
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
	<div class="output">
		<h3>Preview</h3>
		<div class="preview">
		</div>
		<button id="downloadBtn">Download</button>
	</div>
</div>

<script>
// Function to update the preview
function updatePreview() {
    // Get all checked checkboxes
    const checkboxes = document.querySelectorAll('input[type="checkbox"]:checked');
    const selected = Array.from(checkboxes).map(cb => cb.value);

    // Send AJAX request to get CSS content
    fetch('collect-css.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ selected: selected })
    })
    .then(response => response.text())
    .then(data => {
        // Update the preview div with the CSS content
        document.querySelector('.preview').textContent = data;
    })
    .catch(error => console.error('Error:', error));
}

// Add event listeners to all checkboxes
document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
    checkbox.addEventListener('change', updatePreview);
});

// Download button functionality
document.getElementById('downloadBtn').addEventListener('click', function() {
    // Get all checked checkboxes
    const checkboxes = document.querySelectorAll('input[type="checkbox"]:checked');
    const selected = Array.from(checkboxes).map(cb => cb.value);

    if (selected.length === 0) {
        alert('Please select at least one snippet to download.');
        return;
    }

    // Send POST request to download-css.php
    fetch('download-css.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ selected: selected })
    })
    .then(response => response.blob())
    .then(blob => {
        // Create a temporary download link and click it
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'ui-mod.css';
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
        document.body.removeChild(a);
    })
    .catch(error => console.error('Error:', error));
});
</script>
</body>
</html>
