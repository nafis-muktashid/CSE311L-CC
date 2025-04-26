/**
 * Job Postings Management JavaScript
 *
 * Handles job posting interactions including:
 * - Session validation
 * - Modal interactions
 * - Job application submission
 * - Status messages
 */

// ========== Session Management ==========

/**
 * Validates current user session
 * Redirects to login if session is invalid
 * @returns {Promise} Session data if valid
 */
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

// ========== Modal Elements ==========
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

// Track current job for application handling
let currentJobId = null;

/**
 * Displays job details in modal
 * Handles visibility of application section based on user type
 *
 * @param {string} jobId - ID of the job posting
 * @param {string} jobTitle - Title of the job
 * @param {string} jobDetails - Detailed job description
 * @param {string} companyName - Name of the posting company
 * @param {string} jobCompanyId - ID of the posting company
 * @param {string} rate - Hourly rate for the job
 * @param {string} userCompanyId - Current user's company ID
 */
function showJobDetails(
	jobId,
	jobTitle,
	jobDetails,
	companyName,
	jobCompanyId,
	rate,
	userCompanyId
) {
	checkSession().then(() => {
		// Set modal content
		currentJobId = jobId;
		modalJobTitle.textContent = jobTitle;
		modalCompanyName.textContent = companyName;
		modalJobDetails.textContent = jobDetails;

		// Show application section only if user is from a different company
		if (applicationSection) {
			const showApplication =
				userCompanyId &&
				userCompanyId !== "null" &&
				parseInt(userCompanyId) !== parseInt(jobCompanyId);

			applicationSection.style.display = showApplication
				? "block"
				: "none";
			if (showApplication && offerRate) {
				offerRate.value = rate;
			}
		}

		// Reset and show modal
		modal.style.display = "block";
		if (applicationMessage) {
			applicationMessage.textContent = "";
			applicationMessage.style.display = "none";
		}
	});
}

// ========== Application Handling ==========

// Initialize button event listeners
if (applyButton) {
	applyButton.onclick = () => submitApplication("apply");
}

if (offerButton) {
	offerButton.onclick = () => {
		if (!offerRate.value) {
			showMessage("Please enter your rate offer", "error");
			return;
		}
		submitApplication("offer");
	};
}

/**
 * Submits job application or rate offer
 * @param {string} type - Type of submission ("apply" or "offer")
 */
function submitApplication(type) {
	const data = {
		jobId: currentJobId,
		type: type,
		...(type === "offer" && { offerRate: offerRate.value }),
	};

	fetch("utilities/process_application.php", {
		method: "POST",
		headers: { "Content-Type": "application/json" },
		body: JSON.stringify(data),
	})
		.then((response) => {
			if (!response.ok) throw new Error("Network response was not ok");
			return response.json();
		})
		.then((data) => {
			if (data.success) {
				showMessage(data.message, "success");
				// Disable buttons after successful submission
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

/**
 * Displays status message in the modal
 * @param {string} message - Message to display
 * @param {string} type - Message type ("success" or "error")
 */
function showMessage(message, type) {
	if (applicationMessage) {
		applicationMessage.textContent = message;
		applicationMessage.className = `application-message ${type}`;
		applicationMessage.style.display = "block";
	}
}

// ========== Modal Close Handlers ==========

// Close button handler
if (closeBtn) {
	closeBtn.onclick = () => (modal.style.display = "none");
}

// Outside click handler
window.onclick = (event) => {
	if (event.target === modal) {
		modal.style.display = "none";
	}
};

/**
 * Integration Requirements:
 *
 * 1. HTML Structure:
 *    - Modal with ID "jobDetailsModal"
 *    - Elements with IDs: modalJobTitle, modalCompanyName, modalJobDetails
 *    - Close button with class "close"
 *    - Application section with ID "applicationSection"
 *    - Rate input with ID "offerRate"
 *    - Buttons with IDs: applyButton, offerButton
 *    - Message display with ID "applicationMessage"
 *
 * 2. Dependencies:
 *    - check_session.php for session validation
 *    - process_application.php for handling submissions
 *
 * 3. CSS Requirements:
 *    - .application-message class for status messages
 *    - .success and .error classes for message types
 */
