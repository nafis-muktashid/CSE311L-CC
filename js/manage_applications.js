function updateApplicationStatus(applicationId, status) {
	fetch("utilities/update_application_status.php", {
		method: "POST",
		headers: {
			"Content-Type": "application/json",
		},
		body: JSON.stringify({
			applicationId: applicationId,
			status: status,
		}),
	})
		.then((response) => {
			if (!response.ok) {
				throw new Error("Network response was not ok");
			}
			return response.json();
		})
		.then((data) => {
			if (data.success) {
				// Show success message before reload
				alert(data.message);
				// Delay reload slightly to show message
				setTimeout(() => {
					location.reload();
				}, 500);
			} else {
				alert(data.message);
			}
		})
		.catch((error) => {
			console.error("Error:", error);
			alert("An error occurred. Please try again.");
		});
}
