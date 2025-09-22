



document.addEventListener("DOMContentLoaded", function() {

    var whatsappButton = document.createElement("a");

    whatsappButton.href = "https://wa.me/9779841882611";

    whatsappButton.target = "_blank";

    whatsappButton.className = "whatsapp-button";

    whatsappButton.innerHTML = '<img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="Chat with us on WhatsApp">';

    document.body.appendChild(whatsappButton);

});

