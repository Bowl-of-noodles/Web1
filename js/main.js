var x, y, r;
const maxLength = 10;
let table = document.getElementById("tbody");

function setX(element) {
    x = element.value;
    [...document.getElementsByName("x")].forEach(function (btn) {
        btn.style.transform = "";
    });
    element.style.transform = "scale(1.3)";
}


function errorMessage(messerror) {
    alert(messerror);
}

function isNumber(input) {
    return !isNaN(parseFloat(input)) && isFinite(input);
}

function inLength(input) {
    return input.length <= maxLength;
}

function validateX() {
    if (x) return true;

    errorMessage("Выберите x");
    return false;
}


function validateY() {

    var valY = document.getElementById("y_container").value;
    valY = valY.replace(",", ".");

    if (valY === undefined) {
        errorMessage("Поле Y не заполнено");
        return false;
    }
    if (!isNumber(valY) ||!((valY >= -5) && (valY <= 5))) {
        errorMessage("Y должен быть числом в промежутке [-5;5]");
        return false;
    }
    if (!inLength(valY)) {
        errorMessage(`Длина числа - не более ${maxLength}`);
        return false;
    }
    y = valY;
    return true;
}

function validateR() {
    const checkboxes = [...document.querySelectorAll("input[name=r]:checked")];
    if (checkboxes.length === 0) {
        errorMessage("Выберите хотя бы один R");
        return false;
    }
    r = checkboxes.map(r => r.getAttribute('value')).join(",");
    return true;
}



function submit() {
    if (validateX() && validateY() && validateR()) {
        $.get("php/main.php", {
            'x': x,
            'y': y,
            'r': r,
        }).done(function (data) {
            let table = document.getElementById("tbody");
            table.insertAdjacentHTML('afterend', data);

        });
    }
}

$(document).ready(function () {
    $.ajax({
        url: "php/restore.php",
        async: true,
        type: "GET",
        success: function (response){
            let table = document.getElementById("first");
            table.insertAdjacentHTML('afterend', response);
        }
    })
})

$(document).ready(function () {
    $('[data-reset]').on('click', function (e) {
        e.preventDefault();
        $.ajax({
            url: "php/reset.php",
            async: true,
            type: "GET",
            data: {},
            cache: false,
            success: function(response) {
                table.innerHTML = `
                <tr id="first">
                    <th class="coords-col">X</th>
                        <th class="coords-col">Y</th>
                        <th class="coords-col">R</th>
                        <th class="time-col">Время запроса</th>
                        <th class="time-col">Время исполнения</th>
                        <th class="hitres-col">Попадание</th>
                </tr>
                `
            }
        });
    })
})