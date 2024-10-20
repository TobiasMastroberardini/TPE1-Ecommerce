// Obtener el formulario y agregar un evento de submit
document
  .getElementById("registerForm")
  .addEventListener("submit", function (event) {
    const password = document.getElementById("password").value;
    const message = document.getElementById("passwordMessage");

    const isValidPassword = validatePassword(password);

    if (!isValidPassword) {
      message.classList.remove("hidden"); // Mostrar el mensaje si la contraseña es insegura
      event.preventDefault(); // Evitar el envío del formulario
    } else {
      message.classList.add("hidden"); // Ocultar el mensaje si la contraseña es segura
    }
  });

// Validar la contraseña al escribir
document.getElementById("password").addEventListener("input", function () {
  const password = this.value;
  const message = document.getElementById("passwordMessage");

  const isValidPassword = validatePassword(password);

  if (!isValidPassword) {
    message.classList.remove("hidden"); // Mostrar el mensaje si la contraseña es insegura
  } else {
    message.classList.add("hidden"); // Ocultar el mensaje si la contraseña es segura
  }
});

// Función de validación de contraseña
function validatePassword(password) {
  const minLength = 8;
  const hasUpperCase = /[A-Z]/.test(password);
  const hasLowerCase = /[a-z]/.test(password);
  const hasNumber = /[0-9]/.test(password);
  const hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(password);

  return (
    password.length >= minLength &&
    hasUpperCase &&
    hasLowerCase &&
    hasNumber &&
    hasSpecialChar
  );
}
