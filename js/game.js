const hangingManImage = document.getElementById("hanging-man");
const latter = document.getElementById("letter");
const attemptLaterButton = document.getElementById("latter-button");
const solution = document.getElementById("solution")
const attemptSolutionButton = document.getElementById("solution-button");
const lattersDiv = document.getElementById("letters");
const attemptsDiv = document.getElementById("attempts-div");
const finishDiv = document.getElementById("finish-div");
const finishTitle = document.getElementById("finish-div-title");
const newGameButton = document.getElementById("new-game-button");
const hangButton = document.getElementById("hang-button");

window.onload = () => init();
attemptLaterButton.addEventListener("click", () => attemptLetter());
attemptSolutionButton.addEventListener("click", () => attemptSolution());
newGameButton.addEventListener("click", () => startNewGame());
hangButton.addEventListener("click", () => hangYourself());

function init() {
    const endpoint = "/landing/route.php?source=init";
    makeAjaxCall(endpoint, 1);
}

function attemptLetter() {
    if(latter.value === "") {
        addNotification("error", "Latter missing");
    } else if(latter.value.length > 1) {
        addNotification("error", "Please use only 1 latter");
    } else {
        const endpoint = "/landing/route.php?source=attemptLatter&latter=" + latter.value;
        makeAjaxCall(endpoint);
    }
}

function attemptSolution() {
    if(solution.value === "") {
        addNotification("error", "Solution empty")
    } else if(solution.value.length < 12) {
        addNotification("error", "Solution must be word with 12 characters")
    } else {
        const endpoint = "/landing/route.php?source=attemptSolution&solution=" + solution.value;
        makeAjaxCall(endpoint);
    }
}

function startNewGame() {
    const http = getHttpParams();
    window.location.href = http.protocol + "//" + http.host + "/views/start.php";
}

function hangYourself() {
    const endpoint = "/landing/route.php?source=concedeDefeat";
    makeAjaxCall(endpoint);
}

function makeAjaxCall(endpoint, init=0) {
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            afterAjaxCallSteps(JSON.parse(this.responseText), init);
        }
    };

    const http = getHttpParams();
    xhttp.open("GET", http.protocol + "//" + http.host + endpoint, true);
    xhttp.send();
}

function afterAjaxCallSteps(result, init) {
    if(result.error === 1) {
        if(init === 1 && result.message === 'Could not find a game') {
            startNewGame();
        } else {
            addNotification("error", result.message);
        }
    } else {
        if(init === 0) {
            if(result.data.hit === 1) {
                addNotification("success", "You did good!");
            } else {
                addNotification("error", "Swing and miss!");
            }
        }
        doGameStep(result.data);
    }
}

function doGameStep(data) {
    hangingManImage.src = data.image;
    console.log(data.image);
    lattersDiv.innerHTML = "";
    for(let i = 0; i < data.word_letters.length; i++) {
        createLatter(data.word_letters[i]);
    }
    if(data.win === 1) {
        finishGame(true)
    }
    if(data.loss === 1) {
        finishGame(false)
    }
}

function finishGame(win) {
    attemptsDiv.style.display = "none";
    finishDiv.style.display = "block";
    if(win) {
        finishTitle.innerHTML = "Run Free!"
    } else {
        finishTitle.innerHTML = "Hanged..."
    }
}

function createLatter(value) {
    const wrapperDiv = document.createElement("div");
    wrapperDiv.setAttribute("class", "col-md-1 col-lg-1 col-sm-1 col-xs-1");

    const input = document.createElement("input");
    input.setAttribute("type", "text");
    input.setAttribute("class", "form-control");
    input.setAttribute("value", value);
    input.setAttribute("readonly", "readonly");

    wrapperDiv.appendChild(input);
    lattersDiv.appendChild(wrapperDiv);
}