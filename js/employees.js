/**
 * Employee Management JavaScript
 *
 * This module handles employee-related functionality including:
 * - Modal interactions
 * - Dynamic skill management
 * - Real-time employee search
 *
 * Dependencies:
 * - Font Awesome for icons
 * - edit_employee.php for form handling
 * - employees.php for employee listing
 */

// ========== Modal Management ==========
const modal = document.getElementById("employeeModal");
const closeBtn = document.getElementsByClassName("close")[0];
const modalTitle = document.getElementById("modalTitle");

/**
 * Handles modal close functionality via close button
 */
closeBtn.onclick = function () {
	modal.style.display = "none";
};

/**
 * Handles modal close functionality via outside click
 * @param {Event} event - The click event
 */
window.onclick = function (event) {
	if (event.target == modal) {
		modal.style.display = "none";
	}
};

// ========== Skills Management ==========

/**
 * Adds a new skill input row to the skills container
 * Creates a dynamic row with:
 * - Skill name input
 * - Experience level dropdown
 * - Remove button
 */
function addSkill() {
	const container = document.getElementById("skillsContainer");
	const skillRow = document.createElement("div");
	skillRow.className = "skill-row";

	skillRow.innerHTML = `
        <input type="text" 
               name="skills[]" 
               placeholder="Skill name" 
               required>
        <select name="skill_levels[]" required>
            <option value="beginner">Beginner</option>
            <option value="intermediate">Intermediate</option>
            <option value="expert">Expert</option>
        </select>
        <button type="button" 
                class="remove-skill" 
                onclick="removeSkill(this)">
            <i class="fas fa-times"></i>
        </button>
    `;

	container.appendChild(skillRow);
}

/**
 * Removes a skill row from the form
 * Prevents removal if it's the last remaining skill row
 * @param {HTMLElement} button - The remove button element that was clicked
 */
function removeSkill(button) {
	const skillRow = button.parentElement;
	if (document.getElementsByClassName("skill-row").length > 1) {
		skillRow.remove();
	}
}

// ========== Search Functionality ==========

/**
 * Initializes real-time employee search functionality
 * Features:
 * - Case-insensitive search
 * - Immediate results filtering
 * - Dynamic "No Results" message
 */
document.addEventListener("DOMContentLoaded", function () {
	const searchInput = document.getElementById("employeeSearch");

	if (searchInput) {
		searchInput.addEventListener("input", function () {
			const searchTerm = this.value.toLowerCase();
			const employeeCards = document.querySelectorAll(".employee-card");

			employeeCards.forEach((card) => {
				const employeeName = card
					.querySelector("h2")
					.textContent.toLowerCase();
				card.style.display = employeeName.includes(searchTerm)
					? ""
					: "none";
			});

			// Handle "No Employees Found" message visibility
			updateNoEmployeesMessage(employeeCards);
		});
	}
});

/**
 * Updates visibility of "No Employees Found" message
 * @param {NodeList} employeeCards - Collection of employee card elements
 */
function updateNoEmployeesMessage(employeeCards) {
	const noEmployeesDiv = document.querySelector(".no-employees");
	if (noEmployeesDiv) {
		const visibleCards = Array.from(employeeCards).some(
			(card) => card.style.display !== "none"
		);
		noEmployeesDiv.style.display = visibleCards ? "none" : "block";
	}
}

/**
 * Component Integration Notes:
 *
 * 1. Modal Integration:
 *    - Requires modal HTML structure in the page
 *    - Modal should have elements with IDs: employeeModal, modalTitle
 *    - Close button should have class "close"
 *
 * 2. Skills Management:
 *    - Requires container with ID "skillsContainer"
 *    - Used in both add_employee.php and edit_employee.php
 *    - Maintains at least one skill row at all times
 *
 * 3. Search Functionality:
 *    - Requires input element with ID "employeeSearch"
 *    - Requires employee cards with class "employee-card"
 *    - Requires div with class "no-employees" for no results message
 *
 * HTML Structure Requirements:
 *
 * <input id="employeeSearch" type="text">
 * <div class="employees-container">
 *   <div class="employee-card">
 *     <h2>Employee Name</h2>
 *     ...
 *   </div>
 * </div>
 * <div class="no-employees" style="display: none">
 *   No employees found
 * </div>
 *
 * Form Structure for Skills:
 *
 * <div id="skillsContainer">
 *   <div class="skill-row">
 *     <input name="skills[]">
 *     <select name="skill_levels[]">
 *     <button class="remove-skill">
 *   </div>
 * </div>
 */
