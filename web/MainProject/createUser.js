function checkPasswordRequirements() {
    password = document.getElementById('password').value;
    passwordVerify = document.getElementById('passwordVerify').value;
    matches = password.match(/\d+/);
    if (password.length < 7 || matches == null || password != passwordVerify) {
        document.getElementById('alertMessage').style.display = "initial";
        document.getElementById('submit').disabled=true;
    } else {
        document.getElementById('alertMessage').style.display = "none";
        document.getElementById('submit').disabled=false;
    }
}