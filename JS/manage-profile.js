window.onload = function () {
    var form = document.getElementById("profile-form");
    var saveButton = document.getElementById("submit");

    // Store initial values
    var initialValues = {};
    var inputs = form.querySelectorAll("input[type='text']");

    inputs.forEach(input => {
        initialValues[input.name] = input.value; // Store original values
    });

    // Function to check for changes
    function checkForChanges() {
        var isChanged = false;
        
        inputs.forEach(input => {
            if (input.value !== initialValues[input.name]) {
                isChanged = true;
            }
        });

        saveButton.disabled = !isChanged; // Enable button if changes detected
    }

    // Add event listeners to all inputs
    inputs.forEach(input => {
        input.addEventListener("input", checkForChanges);
    });

    // Ensure Save button is initially disabled
    saveButton.disabled = true;
};
