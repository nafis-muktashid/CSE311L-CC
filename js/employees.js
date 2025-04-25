// Modal handling
const modal = document.getElementById("employeeModal");
const closeBtn = document.getElementsByClassName("close")[0];
const modalTitle = document.getElementById("modalTitle");

// Close modal functionality
closeBtn.onclick = function () {
	modal.style.display = "none";
};

window.onclick = function (event) {
	if (event.target == modal) {
		modal.style.display = "none";
	}
};

// Skills handling
function addSkill() {
	const container = document.getElementById("skillsContainer");
	const skillRow = document.createElement("div");
	skillRow.className = "skill-row";

	skillRow.innerHTML = `
        <input type="text" name="skills[]" placeholder="Skill name" required>
        <select name="skill_levels[]" required>
            <option value="beginner">Beginner</option>
            <option value="intermediate">Intermediate</option>
            <option value="expert">Expert</option>
        </select>
        <button type="button" class="remove-skill" onclick="removeSkill(this)">
            <i class="fas fa-times"></i>
        </button>
    `;

	container.appendChild(skillRow);
}

function removeSkill(button) {
	const skillRow = button.parentElement;
	if (document.getElementsByClassName("skill-row").length > 1) {
		skillRow.remove();
	}
}

// Add search functionality
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
				if (employeeName.includes(searchTerm)) {
					card.style.display = "";
				} else {
					card.style.display = "none";
				}
			});

			// Show/hide the "No Employees Found" message
			const noEmployeesDiv = document.querySelector(".no-employees");
			if (noEmployeesDiv) {
				const visibleCards = Array.from(employeeCards).some(
					(card) => card.style.display !== "none"
				);
				noEmployeesDiv.style.display = visibleCards ? "none" : "block";
			}
		});
	}
});
