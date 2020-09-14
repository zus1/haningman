<?php
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
        <h2>Start new Hanging man game</h2>
    </div>
    <div class="coos-lang">
        Choose language
    </div>
    <div id="lang-div" class="row mx-1 px-1 no-gutters">
        <div class="col-lg-2 col-sm-6 col-md-2">
            <input type="radio" id="en" name="language" value="en" checked>&nbsp;<b>En</b>
        </div>
        <div class="col-lg-2 col-sm-6 col-md-2">
            <input type="radio" id="hr" name="language" value="hr">&nbsp;<b>Hr</b>
        </div>
        <div class="col-lg-2 col-sm-6 col-md-2">
            <input type="radio" id="de" name="language" value="de">&nbsp;<b>De</b>
        </div>
        <div class="col-lg-2 col-sm-6 col-md-2">
            <input type="radio" id="fr" name="language" value="fr">&nbsp;<b>Fr</b>
        </div>
        <div class="col-lg-2 col-sm-6 col-md-2">
            <input type="radio" id="it" name="language" value="it">&nbsp;<b>It</b>
        </div>
        <div class="col-lg-2 col-sm-6 col-md-2">
            <input type="radio" id="es" name="language" value="es">&nbsp;<b>Es</b>
        </div>
    </div>
    <div id="button-div" class="row">
        <button type="button" class="btn btn-lg btn-primary" id="start">Start</button>
    </div>
</div>
<script>
    const start = document.getElementById("start");
    const languages = document.getElementsByName("language");
    const notification = document.getElementById("notification");
    const alert = document.getElementById("alert");

    start.addEventListener("click", () => {
        initGame();
    });

    function initGame() {
        let xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                handlePostInit(JSON.parse(this.responseText));
            }
        };

        const http = getHttpParams();
        let checked = "";
        languages.forEach(language => {
           if(language.checked) {
               checked = language.value;
           }
        });
        xhttp.open("GET", http.protocol + "//" + http.host + "/landing/route.php?source=start&language=" + checked, true);
        xhttp.send();
    }

    function handlePostInit(ajaxResponse) {
        if(ajaxResponse.error === 1) {
            addNotification("error", ajaxResponse.message);
        } else {
            const http = getHttpParams();
            window.location.href = http.protocol + "//" + http.host + "/views/game.php";
        }
    }

    function getHttpParams() {
        return {
            "host": window.location.hostname,
            "protocol": window.location.protocol
        }
    }

    function addNotification(type, text) {
        notification.innerHTML = text;
        if(type === "error") {
            alert.className = "alert alert-danger";
        } else if(type === "success") {
            alert.className = "alert alert-success";
        }

        $("#alert").show().delay(5000).fadeOut();
    }
</script>
<!--$parser->includeView('start');-->
<?php $parser->includeFooter(); ?>

