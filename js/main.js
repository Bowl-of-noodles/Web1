var x, y, r;
const maxLength = 10;
const dataType = 'html';


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
            'timezone': new Date().getTimezoneOffset(),
            'wholeTable': false,
            'dataType': dataType
        }).done(function (data) {
            if (dataType === 'html') {
                $('#result_table tr:first').after(data);
            }
        });
    }
}

function generateRowFromElem(elem) {
    let newRow = elem.isHit ? '<tr class="hit-yes">' : '<tr class="hit-no">';
    newRow += '<td>' + elem.x + '</td>';
    newRow += '<td>' + elem.y + '</td>';
    newRow += '<td>' + elem.r + '</td>';
    newRow += '<td>' + elem.currentTime + '</td>';
    newRow += '<td>' + elem.execTime + '</td>';
    newRow += '<td>' + (elem.isHit ? 'Да' : 'Нет') + '</td>';

    return newRow;
}
