<?php
//include_once $_SERVER["DOCUMENT_ROOT"] . "/classes/htmlparser.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/classes/factory.php";
$parser = Factory::getObject(Factory::TYPE_HTML_PARSER);
$parser->includeHeader(); ?>
<div class="container">
    <div style="height: 50px;">
        <div id="alert" class="notification" style="display: none">
            <div id="notification"></div>
        </div>
    </div>
    <div class="title-div">
        <h2>The Hanging Man</h2>
    </div>
    <div class="row">
        <div class="col-md-5 vol-lg-5 col-sm-12 offset-md-1 offset-lg-1">
            <div id="hanged-img">
                <img src="" id="hanging-man" alt="Hanging image">
            </div>
        </div>
        <div class="col-md-6 col-lg-6 col-sm-12">
            <label class="attempts-label">Attempt latter</label>
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Guess latter">
                <div class="input-group-append">
                    <button id="latter-button" class="btn btn-outline-secondary" type="button">Guess</button>
                </div>
            </div>
            <label class="attempts-label">Attempt solution</label>
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Attempt solution">
                <div class="input-group-append">
                    <button id="solution-button" class="btn btn-outline-secondary" type="button">Attempt</button>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="row" id="letters">
            <div class="col-md-1 col-lg-1">
                <input type="text" class="form-control" readonly>
            </div>
        </div>
    </div>
</div>
<script>
    const hangingManImage = document.getElementById("hanging-man");
    const attemptLaterButton = document.getElementById("latter-button");
    const attemptSolutionButton = document.getElementById("solution-button");
    const lattersDiv = document.getElementById("letters");

    window.onload = () => init();
    attemptLaterButton.addEventListener("click", attemptLater());
    attemptSolutionButton.addEventListener("click", attemptSolution());

    function init() {
        let xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                console.log(JSON.parse(this.responseText));
                doGameStep(JSON.parse(this.responseText).data);
            }
        };

        const http = getHttpParams();
        xhttp.open("GET", http.protocol + "//" + http.host + "/landing/route.php?source=init", true);
        xhttp.send();
    }

    function attemptLater() {

    }

    function attemptSolution() {

    }

    function doGameStep(data) {
        hangingManImage.src = data.image;
        console.log(data.image);
        lattersDiv.innerHTML = "";
        for(let i = 0; i < data.word_letters.length; i++) {
            createLatter(data.word_letters[i]);
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

    function getHttpParams() {
        return {
            "host": window.location.hostname,
            "protocol": window.location.protocol
        }
    }
</script>
<!--$parser->includeView('game');-->
<?php $parser->includeFooter(); ?>
