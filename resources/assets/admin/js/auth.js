const togglePassword = document.querySelector("#togglePassword");
const password = document.querySelector("#password");
const eyeIcon = togglePassword.querySelector("i");

togglePassword.addEventListener("click", function () {
    const type =
        password.getAttribute("type") === "password" ? "text" : "password";
    password.setAttribute("type", type);

    eyeIcon.classList.toggle("fa-eye-slash");
    eyeIcon.classList.toggle("fa-eye");
});
