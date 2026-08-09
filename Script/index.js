const usernameInput = document.querySelector('.username');
const passwordInput = document.querySelector('.password');
const errorMsg = document.querySelector('.error-msg');
const flashMsg = document.querySelector('.flash');
const form = document.querySelector('form');

if (form) {
  form.addEventListener('submit', function (e) {

    if (usernameInput.value.trim() === '') {
      e.preventDefault();
      if (errorMsg) {
        errorMsg.style.visibility = 'visible';
        errorMsg.textContent = 'Please enter your username.';
      }
      if (flashMsg) {
        flashMsg.style.display = 'none';
      }
      usernameInput.focus();
      return;
    }

    if (passwordInput.value.trim() === '') {
      e.preventDefault();
      if (errorMsg) {
        errorMsg.style.visibility = 'visible';
        errorMsg.textContent = 'Please enter your password.';
      }
      if (flashMsg) {
        flashMsg.style.display = 'none';
      }
      passwordInput.focus();
      return;
    }

    if (errorMsg) {
      errorMsg.style.visibility = 'hidden';
      errorMsg.textContent = '';
    }
  });
}