function createBoleto() {
    const token = localStorage.getItem("token");
    if (!token) {
        console.error("No hay token disponible, por favor inicia sesión primero.");
        alert("Por favor, inicia sesión para continuar.");
        return;
    }

    console.log("Token:", token);

    // Solicitar detalles de la corrida
    fetch("/corrida/detalles", {
        method: "GET",
        headers: {
            "Content-Type": "application/json",
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log("Detalles de la corrida:", data);

        // Asegúrate de tener un asiento seleccionado
        const selectedSeat = document.querySelector('.seat.selected');
        if (!selectedSeat) {
            alert("Por favor, selecciona un asiento.");
            return;
        }

        // Verifica si el usuario aceptó los términos y condiciones
        const termsAccepted = document.getElementById('terminos').checked;
        if (!termsAccepted) {
            alert("Por favor, acepta los términos y condiciones.");
            return;
        }

        // Obtener el total del boleto
        const total = document.getElementById('total').textContent;

        // Preparar datos para enviar al servidor
        const boletoData = {
            asiento: selectedSeat.dataset.seat,
            corridaId: data.id, // Asumiendo que 'id' es el identificador de la corrida
            total: total,
            usuarioId: token, // Aquí puedes incluir el ID del usuario si lo tienes almacenado
        };

        // Enviar los datos del boleto al backend
        fetch("/compra/crear", {
            method: "POST",
            headers: {
                "Authorization": `Bearer ${token}`,
                "Content-Type": "application/json",
            },
            body: JSON.stringify(boletoData),
        })
        .then(response => response.json())
        .then(responseData => {
            console.log("Boleto creado:", responseData);
            alert("Boleto comprado exitosamente.");
        })
        .catch(error => {
            console.error("Error al crear el boleto:", error);
            alert("Ocurrió un error, por favor intenta nuevamente.");
        });
    })
    .catch(error => {
        console.error("Error al obtener los detalles de la corrida:", error);
        alert("Ocurrió un error al obtener los detalles de la corrida.");
    });
}
