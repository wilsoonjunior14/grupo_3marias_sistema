const characters = "abcdefghijklmnopqrstuvxwyz0123456789";
var passCode = "";

/**
 * Initializes the captcha component.
 */
function initCaptcha(elementId) {
    setTimeout(() => {
        setupCaptcha(elementId);
        _onFillInputCaptcha(null);
    }, 1000);
};

/**
 * Setups captcha.
 * @param elementId 
 */
function setupCaptcha(elementId) {
    const domElement = document.getElementById(elementId);

    if (!domElement) {
        return;
    }

    domElement.innerHTML = "";
    _insertCanvasPanel(domElement);
    _insertInputField(domElement);
    resetCaptchaPanel();
}

/**
 * Inserts the canvas panel inside html page.
 * @param domElement The dom element.
 */
function _insertCanvasPanel(domElement) {
    domElement.innerHTML = 
        domElement.innerHTML + 
        "<div class='row' style='margin: -12px'>" +
        "   <div class='col-sm-4'>" +
        "       <canvas id='canvas-captcha' style='border: 1px solid lightgray; max-height: 80px' />" +
        "   </div>" +
        "   <div class='col-sm-2'>" +
        "       <button type='button' onclick='resetCaptchaPanel()' id='refresh-captcha-button' style='margin-top: 22px'>" +
        "           <i class='material-icons'>refresh</i>" +
        "       </button>" +
        "   </div>" +
        "</div>";
}

/**
 * Inserts the input field about captcha validation.
 * @param domElement The dom element.
 */
function _insertInputField(domElement) {
    domElement.innerHTML = 
        domElement.innerHTML + 
        "<div class='input-group'>" +
        "   <input id='input-captcha' class='form-control' type='text' required placeholder='Qual código você está vendo acima?' />" +
        "</div>";

    document.getElementById('input-captcha').addEventListener("keyup", _onFillInputCaptcha);
}

/**
 * Events when user fills some character inside input data.
 * @param event The event data.
 */
function _onFillInputCaptcha(event) {
    const currentPassCode = document.getElementById('input-captcha').value;
    const btnElement = document.getElementById('btnLogin');

    if (currentPassCode === passCode) {
        btnElement.classList.add('btn-success');
        btnElement.classList.remove('btn-light');
        btnElement.style.pointerEvents = null;
    } else {
        btnElement.classList.remove('btn-success');
        btnElement.classList.add('btn-light');
        btnElement.style.pointerEvents = "none";
    }
}

/**
 * The reset captcha canvas.
 */
function resetCaptchaPanel() {
    var canvas = document.getElementById('canvas-captcha');
    var context = canvas.getContext("2d");
    context.clearRect(0, 0, canvas.width, canvas.height);

    context.font = "80px Arial";
    context.textAlign = "center";
    var initialPosition = 50;
    for (var i=0; i<5; i++) {
        const char = getChar();
        passCode = passCode + "" + char;
        const yPosition = 80 + Math.round(Math.random(100));

        context.strokeText(char, initialPosition + (40 * i), yPosition);

        context.moveTo(0, 0);
        context.lineTo(Math.round(Math.random(500)), Math.round(Math.random(500)));
        context.stroke();
    }
    
}

function getChar() {
    if (Math.random() > 0.5) {
        return characters[Math.round(Math.random(characters.length - 1) * 10)].toUpperCase();
    } else {
        return characters[Math.round(Math.random(characters.length - 1) * 10)];
    }
}

function getPassCode() {
    return passCode;
}

