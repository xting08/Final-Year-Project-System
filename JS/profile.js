function showResetArea() {
    var checkbox = document.getElementById('toggle-reset');
    var resetArea = document.getElementById('reset-area');
    var confirmResetArea = document.getElementById('confirm-reset-area');
    var showPassArea = document.getElementById('show-password-area');
    var saveArea = document.getElementById('save-changes-area');
    var submitButton = document.getElementById('submit');
    var showPassCheckbox = document.getElementById('show-password');
    var newPass = document.getElementById('new-password');
    var confirmPass = document.getElementById('confirm-password');

    if (checkbox.checked) {
        resetArea.style.display = 'table-row';
        confirmResetArea.style.display = 'table-row'; 
        showPassArea.style.display = 'table-row';
        saveArea.style.display = 'block';
        submitButton.disabled = true;
    } 
    else {
        resetArea.style.display = 'none'; 
        confirmResetArea.style.display = 'none';
        showPassArea.style.display = 'none';
        saveArea.style.display = 'none';
        submitButton.disabled = true;
        showPassCheckbox.checked = false;
        newPass.value = '';
        confirmPass.value = '';
    }
}

function showPassword() {
    var checkbox = document.getElementById('show-password');
    var newPass = document.getElementById('new-password');
    var confirmPass = document.getElementById('confirm-password');

    if (checkbox.checked) {
        newPass.type = 'text';
        confirmPass.type = 'text';
    }
    else {
        newPass.type = 'password';
        confirmPass.type = 'password';
    }
}

function enableSubmit() {
    var newPass = document.getElementById('new-password').value;
    var confirmPass = document.getElementById('confirm-password').value;
    var submitButton = document.getElementById('submit');

    if (newPass != "" && confirmPass != "" ) {
        submitButton.disabled = false;
    }
    else {
        submitButton.disabled = true;
    }
}

function validatePassword() {
    var newPass = document.getElementById('new-password').value;     
    var confirmPass = document.getElementById('confirm-password').value;

    if((newPass != "" || newPass != null) && (confirmPass != "" || confirmPass != null)) {
        if (newPass != confirmPass) {
            alert('Passwords do not match');
            return false;
        }
        else {
            return true;
        }

    }
}

window.onload = function() {
    var checkbox = document.getElementById('toggle-reset');
    checkbox.checked = false;

}