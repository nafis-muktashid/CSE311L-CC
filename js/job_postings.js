// Add this at the beginning of your file
function checkSession() {
	return fetch("utilities/check_session.php")
		.then((response) => response.json())
		.then((data) => {
			if (!data.valid) {
				window.location.href = "login.php";
			}
			return data;
		});
}

// Modal functionality
const modal = document.getElementById("jobDetailsModal");
const modalJobTitle = document.getElementById("modalJobTitle");
const modalCompanyName = document.getElementById("modalCompanyName");
const modalJobDetails = document.getElementById("modalJobDetails");
const closeBtn = document.querySelector(".close");
const applicationSection = document.getElementById("applicationSection");
const offerRate = document.getElementById("offerRate");
const applyButton = document.getElementById("applyButton");
const offerButton = document.getElementById("offerButton");
const applicationMessage = document.getElementById("applicationMessage");

let currentJobId = null;

function showJobDetails(
	jobId,
	jobTitle,
	jobDetails,
	companyName,
	jobCompanyId,
	rate,
	userCompanyId
) {
	// Check session before proceeding
	checkSession().then((sessionData) => {
		console.log("Session data:", sessionData);

		currentJobId = jobId;
		modalJobTitle.textContent = jobTitle;
		modalCompanyName.textContent = companyName;
		modalJobDetails.textContent = jobDetails;

		if (applicationSection) {
			if (
				userCompanyId &&
				userCompanyId !== "null" &&
				parseInt(userCompanyId) !== parseInt(jobCompanyId)
			) {
				applicationSection.style.display = "block";
				if (offerRate) offerRate.value = rate;
			} else {
				applicationSection.style.display = "none";
			}
		}

		modal.style.display = "block";
		if (applicationMessage) {
			applicationMessage.textContent = "";
			applicationMessage.style.display = "none";
		}
	});
}

// Handle button clicks
if (applyButton) {
	applyButton.onclick = function () {
		submitApplication("apply");
	};
}

if (offerButton) {
	offerButton.onclick = function () {
		if (!offerRate.value) {
			showMessage("Please enter your rate offer", "error");
			return;
		}
		submitApplication("offer");
	};
}

function submitApplication(type) {
	const data = {
		jobId: currentJobId,
		type: type,
	};

	if (type === "offer") {
		data.offerRate = offerRate.value;
	}

	fetch("utilities/process_application.php", {
		method: "POST",
		headers: {
			"Content-Type": "application/json",
		},
		body: JSON.stringify(data),
	})
		.then((response) => {
			if (!response.ok) {
				throw new Error("Network response was not ok");
			}
			return response.json();
		})
		.then((data) => {
			if (data.success) {
				showMessage(data.message, "success");
				if (applyButton) applyButton.disabled = true;
				if (offerButton) offerButton.disabled = true;
			} else {
				showMessage(data.message, "error");
			}
		})
		.catch((error) => {
			console.error("Error:", error);
			showMessage("An error occurred. Please try again.", "error");
		});
}

function showMessage(message, type) {
	if (applicationMessage) {
		applicationMessage.textContent = message;
		applicationMessage.className = "application-message " + type;
		applicationMessage.style.display = "block";
	}
}

// Close modal functionality
if (closeBtn) {
	closeBtn.onclick = function () {
		modal.style.display = "none";
	};
}

window.onclick = function (event) {
	if (event.target == modal) {
		modal.style.display = "none";
	}
};
