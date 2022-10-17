function imageValidation() {
	var msg = document.getElementById("message");
	var file = document.getElementById("input-file");
	var valid = true;

	if (file.files.length == 0) {
		msg.innerHTML = "No files selected.";
		valid = false;
	}
	return valid;
}

function confirmDelete(id) {
	if (confirm("Are you sure you want to delete this image?")) {
		window.location.href = "delete.php?id="+id;
	}
}
