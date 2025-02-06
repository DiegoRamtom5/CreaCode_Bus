function createCorrida(corridaData) {
    const token = localStorage.getItem("token");

    if (!token) {
        console.error("No hay token disponible, por favor inicia sesión primero.");
        alert("Por favor, inicia sesión para continuar.");
        return;
    }

    console.log("Token:", token);

    const apiUrl = "http://127.0.0.1:8000"; // URL base
    fetch(apiUrl + "/api/corrida/create", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            token: token,
            id_autobus: corridaData.id_autobus,
            origen: corridaData.origen,
            destino: corridaData.destino,
            fecha: corridaData.fecha,
            hora_salida: corridaData.hora_salida,
            hora_estima_llegada: corridaData.hora_estima_llegada,
            tipo_corrida: corridaData.tipo_corrida,
            asientos_totales: corridaData.asientos_totales,
            precio: corridaData.precio
        }),
    })
        .then(response => response.json())
        .then(data => {
            if (data.response) {
                console.log("Corrida creada exitosamente:", data.message);
                alert("Corrida creada exitosamente");
            } else {
                console.error("Error al crear la corrida:", data.message);
                alert("Hubo un problema al crear la corrida. Verifica los datos e inténtalo nuevamente.");
            }
        })
        .catch(error => {
            console.error("Error al realizar la solicitud:", error);
            alert("Ocurrió un error inesperado. Intenta nuevamente más tarde.");
        });
}

// Evento de envío del formulario
document.getElementById("corridaForm").addEventListener("submit", function (event) {
    event.preventDefault();

    // Capturar los datos del formulario
    const corridaData = {
        id_autobus: parseInt(document.getElementById("autobus").value),
        origen: document.getElementById("origen").value,
        destino: document.getElementById("destino").value,
        fecha: document.getElementById("fecha").value,
        hora_salida: document.getElementById("horaSalida").value,
        hora_estima_llegada: document.getElementById("horaLlegada").value,
        tipo_corrida: parseInt(document.getElementById("tipoCorrida").value),
        asientos_totales: parseInt(document.getElementById("asientos").value),
        precio: parseFloat(document.getElementById("precio").value)
    };

    // Llamar a la función para crear la corrida
    createCorrida(corridaData);
});