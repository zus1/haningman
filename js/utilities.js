const alert = document.getElementById("alert");
const notification = document.getElementById("notification");

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