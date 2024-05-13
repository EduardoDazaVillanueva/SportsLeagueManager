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
        input.style.display = "block";
        pago.style.display = "none";
    }
    if (gratis.checked) {
        input.style.display = "none";
        pago.style.display = "block";
    }
}


export function cerrar() {
    const alerta = document.getElementById("alerta");

    if (alerta) {
        alerta.style.display = "none";
    }
}

export function checkInput(input) {
    let value = input.value;

    if (value < 0) {
        input.value = 0;
    }

    if (value > 7) {
        input.value = 7;
    }
}

export function carrusel(elemeto, direccion){
    let elemento = document.querySelector(elemeto);

    console.log(direccion);

    if(direccion == "right"){
        elemento.style.transform = 'translateX(0%)';
    }else{
        elemento.style.transform = 'translateX(-50%)';
    }
}