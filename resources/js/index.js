var input = document.querySelector('.file input');
var check = document.querySelector('.fa-check');
var file = document.querySelector('.file');

function activarInput() {
    input.click();
}

function checkFile(element) {
    if (element.files.length > 0) {
        check.classList.remove('hidden');
        file.classList.add('selected');

    } else {
        check.classList.add('hidden');
        file.classList.remove('selected');
    }
}