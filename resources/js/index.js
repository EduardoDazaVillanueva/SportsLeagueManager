var input = document.querySelector('.file input');
var check = document.querySelector('.fa-check');
var file = document.querySelector('.file');

export function activarInput() {
    input.click();
}
console.log("prueba desde fuera");

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
    var checkbox = document.getElementById("pago");
    var input = document.getElementById("txt_inscripcion");
    var div_check = document.getElementById("container_checks");
    if (checkbox.checked) {
        input.style.display = "inline-block"; // Muestra el input
        div_check.style.display = "none";
    } else {
        input.style.display = "none"; // Oculta el input
    }
}