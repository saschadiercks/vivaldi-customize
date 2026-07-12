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

// Function to get tags from README.md front matter
function getTags($cssFile) {
    $dir = dirname($cssFile);
    $readmeFile = $dir . '/README.md';
    $tags = [];

    if (file_exists($readmeFile)) {
        $readmeContent = file_get_contents($readmeFile);

        // Try to extract tags from front matter (YAML format between ---)
        if (preg_match('/^---[\s\S]*?tags:\s*\[([^\]]+)\][\s\S]*?---/i', $readmeContent, $matches)) {
            $tagsString = $matches[1];
            // Extract individual tags, handling quoted and unquoted values
            if (preg_match_all('/["\']?([^"\',\s]+)["\']?/', $tagsString, $tagMatches)) {
                $tags = array_map('trim', $tagMatches[1]);
            }
        }
    }

    return $tags;
}

// Function to check if a CSS file has the recommended tag
function isRecommended($cssFile) {
    $tags = getTags($cssFile);
    return in_array('recommended', array_map('strtolower', $tags));
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
    $isRecommended = isRecommended($file);
    ?>
    <fieldset>
        <label>
            <input type="checkbox" name="css_<?php echo htmlspecialchars($value); ?>" value="<?php echo htmlspecialchars($value); ?>">
            <?php echo htmlspecialchars($label); ?><?php if ($isRecommended) echo ' ⭐'; ?>
        </label>
    </fieldset>
    <?php
}
?>
