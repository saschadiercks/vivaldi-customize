function updateUrl() {
	const checkboxes = document.querySelectorAll('input[type="checkbox"]:checked');
	const selected = Array.from(checkboxes).map(cb => cb.value);

	if (selected.length === 0) {
		history.replaceState({}, '', window.location.pathname);
	} else {
		history.replaceState({}, '', '?snippets=' + selected.join(','));
	}
}

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
			document.querySelector('#preview').textContent = data;
		})
		.catch(error => console.error('Error:', error));

	// Update URL with selected snippets
	updateUrl();
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

// Initialize checkboxes from URL parameter on page load
function initializeFromUrl() {
	const params = new URLSearchParams(window.location.search);
	const snippetsParam = params.get('snippets');

	if (snippetsParam) {
		const snippetIds = snippetsParam.split(',');
		document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
			if (snippetIds.includes(checkbox.value)) {
				checkbox.checked = true;
			}
		});
		// Trigger preview update for the pre-checked boxes
		updatePreview();
	}
}

// Run initialization when DOM is loaded
document.addEventListener('DOMContentLoaded', initializeFromUrl);
