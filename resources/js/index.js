const input = document.querySelector('.file input');
const check = document.querySelector('.fa-check');
const file = document.querySelector('.file');

export function acticonstInput() {
    input.click();
}

export function checkFile(element) {
    if (element.files.length > 0) {
        check.classList.remove('hidden');
        file.classList.add('selected');

    } else {
        check.classList.add('hidden');
        file.classList.remove('selected');
    }
}

export function toggleInscripcion() {
    const pago = document.getElementById("pago");
    const gratis = document.getElementById("gratis");
    const input = document.getElementById("precio");
    if (pago.checked) {
        input.style.display = "block"; // Muestra el input
        pago.style.display = "none";
    }
    if (gratis.checked) {
        input.style.display = "none";
        pago.style.display = "block";
    }
}


export function cerrar() {
    // Obtén el elemento de alerta
    const alerta = document.getElementById("alerta");

    // Asegúrate de que el elemento existe antes de intentar cambiar su estilo
    if (alerta) {
        alerta.style.display = "none"; // Oculta el elemento
    }
}