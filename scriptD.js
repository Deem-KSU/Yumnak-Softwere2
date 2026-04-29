const signupForm = document.getElementById("signupForm");
const loginForm = document.getElementById("loginForm");

function showError(elementId, message) {
  document.getElementById(elementId).textContent = message;
}

function clearError(elementId) {
  document.getElementById(elementId).textContent = "";
}

function clearMultipleErrors(errorIds) {
  errorIds.forEach(id => clearError(id));
}

function getAge(dateString) {
  const birthDate = new Date(dateString);
  const today = new Date();

  let age = today.getFullYear() - birthDate.getFullYear();
  const monthDiff = today.getMonth() - birthDate.getMonth();

  if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
    age--;
  }

  return age;
}

function isValidEmail(email) {
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

function isValidPassword(password) {
  return /^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/.test(password);
}

if (signupForm) {
  const username = document.getElementById("username");
  const email = document.getElementById("email");
  const phone = document.getElementById("phone");
  const dob = document.getElementById("dob");
  const password = document.getElementById("password");
  const confirmPassword = document.getElementById("confirmPassword");

  const formError = document.getElementById("formError");
  const formSuccess = document.getElementById("formSuccess");

  signupForm.addEventListener("submit", function (e) {

    clearMultipleErrors([
      "usernameError",
      "emailError",
      "phoneError",
      "dobError",
      "passwordError",
      "confirmPasswordError"
    ]);

    if (formError) {
      formError.textContent = "";
      formError.style.display = "none";
    }

    if (formSuccess) {
      formSuccess.textContent = "";
      formSuccess.style.display = "none";
    }

    let isValid = true;

    if (username.value.trim() === "") {
      showError("usernameError", "Username is required");
      isValid = false;
    } else if (!/^[A-Za-z0-9_ ]{3,100}$/.test(username.value.trim())) {
      showError("usernameError", "Username must be at least 3 characters");
      isValid = false;
    }

    if (email.value.trim() === "") {
      showError("emailError", "Email is required");
      isValid = false;
    } else if (!isValidEmail(email.value.trim())) {
      showError("emailError", "Please enter a valid email address");
      isValid = false;
    }

    if (phone.value.trim() === "") {
      showError("phoneError", "Phone number is required");
      isValid = false;
    } else if (!/^05\d{8}$/.test(phone.value.trim())) {
      showError("phoneError", "Phone must start with 05 and be 10 digits");
      isValid = false;
    }

    if (dob.value === "") {
      showError("dobError", "Date of birth is required");
      isValid = false;
    } else if (getAge(dob.value) < 18) {
      showError("dobError", "You must be at least 18 years old");
      isValid = false;
    }

    if (password.value.trim() === "") {
      showError("passwordError", "Password is required");
      isValid = false;
    } else if (!isValidPassword(password.value)) {
      showError(
        "passwordError",
        "Password must be at least 8 characters long and include an uppercase letter, a number, and a special character"
      );
      isValid = false;
    }

    if (confirmPassword.value.trim() === "") {
      showError("confirmPasswordError", "Confirm password is required");
      isValid = false;
    } else if (password.value !== confirmPassword.value) {
      showError("confirmPasswordError", "Passwords do not match");
      isValid = false;
    }

    if (!isValid) {
      e.preventDefault();

      if (formError) {
        formError.textContent = "Please fix the errors below";
        formError.style.display = "block";
      }
    }

    // إذا كل شيء صحيح، ما نسوي preventDefault
    // عشان الفورم يروح لـ PHP ويحفظ بالداتابيس
  });

  const togglePassword = document.getElementById("togglePassword");
  const toggleConfirmPassword = document.getElementById("toggleConfirmPassword");

  if (togglePassword) {
    togglePassword.addEventListener("click", function () {
      password.type = password.type === "password" ? "text" : "password";
      this.classList.toggle("fa-eye");
      this.classList.toggle("fa-eye-slash");
    });
  }

  if (toggleConfirmPassword) {
    toggleConfirmPassword.addEventListener("click", function () {
      confirmPassword.type = confirmPassword.type === "password" ? "text" : "password";
      this.classList.toggle("fa-eye");
      this.classList.toggle("fa-eye-slash");
    });
  }
}

if (loginForm) {
  const loginUsername = document.getElementById("loginUsername");
  const loginPassword = document.getElementById("loginPassword");
  const loginError = document.getElementById("loginError");
  const adminBtn = document.getElementById("adminLoginBtn");

  function validateLogin() {
    clearMultipleErrors(["loginUsernameError", "loginPasswordError"]);
    loginError.textContent = "";
    loginError.style.display = "none";

    let isValid = true;

    if (loginUsername.value.trim() === "") {
      showError("loginUsernameError", "Username is required");
      isValid = false;
    }

    if (loginPassword.value.trim() === "") {
      showError("loginPasswordError", "Password is required");
      isValid = false;
    }

    if (!isValid) {
      loginError.textContent = "Please fill in all required fields";
      loginError.style.display = "block";
    }

    return isValid;
  }

  loginForm.addEventListener("submit", function (e) {
    e.preventDefault();

    if (validateLogin()) {
      window.location.href = "User-Dashboard.php";
    }
  });

  if (adminBtn) {
    adminBtn.addEventListener("click", function () {
      if (validateLogin()) {
        window.location.href = "Admin-Dashboard.php";
      }
    });
  }

  const loginTogglePassword = document.getElementById("loginTogglePassword");

  if (loginTogglePassword) {
    loginTogglePassword.addEventListener("click", function () {
      loginPassword.type = loginPassword.type === "password" ? "text" : "password";
      this.classList.toggle("fa-eye");
      this.classList.toggle("fa-eye-slash");
    });
  }
}

function goToAddRequest() {
  window.location.href = "Airport_Selection.php";
}

