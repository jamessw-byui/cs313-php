function checkPasswordRequirements(password) {
    matches = password.match(/\d+/);
    if (password.length < 7 || matches == null) {
        document.getElementById('alertMessage').style.visibility = "visible";
    } else {
        document.getElementById('alertMessage').style.visibility = "hidden";
    }
}

function checkPasswordsMatch() {
    password = document.getElementById('password').value;
    passwordVerify = document.getElementById('passwordVerify').value;

    if (password != passwordVerify) {
        document.getElementById('alertMessage').style.visibility = "visible";
    } else {
        document.getElementById('alertMessage').style.visibility = "hidden";
    }
}