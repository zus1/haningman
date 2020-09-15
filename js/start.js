const start = document.getElementById("start");
const languages = document.getElementsByName("language");

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
