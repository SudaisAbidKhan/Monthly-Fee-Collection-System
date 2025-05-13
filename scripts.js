// Wait for the DOM to load
document.addEventListener("DOMContentLoaded", function () {
    // Bootstrap 5 form validation
    const forms = document.querySelectorAll(".needs-validation");

    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener("submit", function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }

            form.classList.add("was-validated");
        }, false);
    });

    // Auto-hide flash messages after 5 seconds
    const alertBox = document.querySelector(".alert");
    if (alertBox) {
        setTimeout(() => {
            alertBox.classList.add("fade");
            setTimeout(() => alertBox.remove(), 500);
        }, 5000);
    }

    // Confirm before deleting student/payment
    const deleteLinks = document.querySelectorAll(".delete-confirm");
    deleteLinks.forEach(link => {
        link.addEventListener("click", function (e) {
            if (!confirm("Are you sure you want to delete this record?")) {
                e.preventDefault();
            }
        });
    });
});
document.addEventListener("DOMContentLoaded", function () {
    const contactInput = document.getElementById("guardian_contact");
    const errorDiv = document.getElementById("contactError");
    const form = document.querySelector("form"); // Assumes one form per page; update selector if needed

    if (contactInput && form) {
        contactInput.addEventListener("input", function () {
            validateContact();
        });

        form.addEventListener("submit", function (e) {
            if (!validateContact()) {
                e.preventDefault(); // Stop form submission
            }
        });

        function validateContact() {
            const input = contactInput.value;
            const pattern = /^03[0-9]{9}$/;

            if (input === "" || !pattern.test(input)) {
                contactInput.classList.add("is-invalid");
                errorDiv.style.display = "block";
                return false;
            } else {
                contactInput.classList.remove("is-invalid");
                errorDiv.style.display = "none";
                return true;
            }
        }
    }
});
