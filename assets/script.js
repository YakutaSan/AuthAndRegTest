document.addEventListener('DOMContentLoaded', function () {
  const registerForm = document.getElementById('register-form');
  const loginForm = document.getElementById('login-form');

  if (registerForm) {
    registerForm.addEventListener('submit', function (event) {
      event.preventDefault();

      const formData = new FormData(registerForm);

      fetch('../register.php', {
        method: 'POST',
        body: formData,
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            alert('Registration successful!');
            window.location.href = 'login.html';
          } else {
            displayErrors(registerForm, data.errors);
          }
          // console.log(data);
        })
        .catch((error) => console.error('Error:', error));
      // console.log();
      displayErrors(registerForm, data.errors);
    });
  } else if (loginForm) {
    loginForm.addEventListener('submit', function (event) {
      event.preventDefault();

      const formData = new FormData(loginForm);

      console.log(formData);

      fetch('../login.php', {
        method: 'POST',
        body: formData,
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            window.location.href = '/';
          } else {
            displayErrors(loginForm, data.errors);
          }
        })
        .catch((error) => console.error('Error:', error));
    });
  } else {
    fetch('home.php')
      .then((response) => response.json())
      .then((data) => {
        const name = document.querySelector(`#home #name`);
        console.log(name);
        name.textContent = data.name;
      });
  }

  function displayErrors(form, errors) {
    const errorMessageClear = document.querySelectorAll(
      `#${form.id} .form-group .error-message`
    );
    // errorMessageClear.textContent = '';
    errorMessageClear.forEach((element) => {
      element.textContent = '';
    });
    console.log(errorMessageClear);
    for (const field in errors) {
      const errorMessage = document.querySelector(
        `#${form.id} .form-group .${field}`
      );
      errorMessage.textContent = errors[field];
    }
  }
});
