function logout() {
    const token = localStorage.getItem('token');
    fetch(apiUrl + '/api/logout', {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            token: token
        }),
    })
        .then((response)=> response.json())
        .then((data) => {
            if (data.response) {
                console.log(data.message);
                // Redirigir al usuario a la página de inicio de sesión
                alert("Se cerro sesión correctamente");
                localStorage.removeItem("token"); // Elimina el token local
                window.location.href = "../usuario/login.html";
            } else {
                console.error();
            }
        })
        .catch((error) => {
            console.error("Error al realizar solicitud", error);
        });
}
// Evento para el botón de cerrar sesión
document.addEventListener("DOMContentLoaded", function () {
    const logoutButton = document.getElementById("logoutButton");
    if (logoutButton) {
        logoutButton.addEventListener("click", function (event) {
            event.preventDefault();
            logout();
        });
    } else {
        console.error("El botón de cerrar sesión no se encontró en el DOM.");
    }
});