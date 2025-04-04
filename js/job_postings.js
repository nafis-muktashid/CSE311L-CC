// Get the modal
const modal = document.getElementById('jobDetailsModal');
const modalJobTitle = document.getElementById('modalJobTitle');
const modalCompanyName = document.getElementById('modalCompanyName');
const modalJobDetails = document.getElementById('modalJobDetails');
const closeBtn = document.querySelector('.close');

// Function to show job details in modal
function showJobDetails(jobTitle, jobDetails, companyName) {
    modalJobTitle.textContent = jobTitle;
    modalCompanyName.textContent = companyName;
    modalJobDetails.textContent = jobDetails;
    modal.style.display = 'block';
}

// Close modal when clicking (X) button
closeBtn.onclick = function() {
    modal.style.display = 'none';
}

// Close modal when clicking outside of it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}