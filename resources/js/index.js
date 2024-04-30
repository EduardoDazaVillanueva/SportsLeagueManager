var input = document.querySelector('.file input');
var check = document.querySelector('.fa-check');
var file = document.querySelector('.file');

export function activarInput() {
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
    var pago = document.getElementById("pago");
    var gratis = document.getElementById("gratis");
    var input = document.getElementById("precio");
    if (pago.checked) {
        input.style.display = "block"; // Muestra el input
        pago.style.display = "none";
    }
    if(gratis.checked) {
        input.style.display = "none";
        pago.style.display = "block"; 
    }
}