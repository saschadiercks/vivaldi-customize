<!DOCTYPE html>
<html lang="en-gb" dir="ltr" class="h-full overflow-x-clip scroll-smooth scrollbar-gutter-stable">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Mod generator for Vivaldi</title>
	<meta name="theme-color" content="#fdfdfd" media="(prefers-color-scheme: light)">
	<meta name="theme-color" content="#272e35" media="(prefers-color-scheme: dark)">
	<meta name="viewport" content="width=device-width, initial-scale=1" />

	<link rel="stylesheet" href="site.css" media="screen">
	<script src="script.js" defer></script>
</head>
<body class="h-full m-0 p-0 hyphens-auto">
<div class="grid grid-rows-[auto-1fr-auto] min-h-full px-1">
	<header>
		<h1>Mod generator for Vivaldi</h1>
		<button command="show-modal" commandfor="about-project">What is this?</button>
		<dialog id="about-project">
			<h2>What is this</h2>
			<p>With this tool you can select custom CSS snippets and download them as a single file to use in Vivaldi.</p>
			<p>Some adjustments are restricted to certain layouts/options. So, some are restricted to only work in unified, others don't make sense when Auto hide is on.
				Therefore some additional checks are added to the adjustments, so you don't have to worry when changing layout.</p>
		</dialog>
	</header>
	<main class="flex">
		<div class="input flex-1">
			<h2 class="mbs-0">Select CSS Snippets</h2>
			<?php require_once 'render-library.php'; ?>
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
</div>
</body>
</html>
