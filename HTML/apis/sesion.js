const apiUrl = "http://127.0.0.1:8000"; // URL base de la API
function login(email, password) {

    // Solicitud al endpoint de inicio de sesión
    fetch(apiUrl + "/api/login", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({ email, password }),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.token) {
                // Almacenar el token en localStorage
                localStorage.setItem("token", data.token);
                //alert("Inicio de sesión exitoso. Redirigiendo...");
                console.log("Token almacenado:", data.token);

                // Redirigir al panel de usuario o página principal
                window.location.href = "../usuario/inicio.html";
            } else {
                console.error("Error al iniciar sesión:", data.message);
                alert("Error: " + data.message);
            }
        })
        .catch((error) => {
            console.error("Error al realizar la solicitud:", error);
            alert("Ocurrió un error inesperado. Intenta nuevamente más tarde.");
        });
}

// Evento de envío del formulario
document.querySelector("form").addEventListener("submit", function (event) {
    event.preventDefault();

    // Capturar datos del formulario
    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value.trim();

    if (!email || !password) {
        alert("Por favor, completa todos los campos.");
        return;
    }

    // Llamar a la función de inicio de sesión
    login(email, password);
});
