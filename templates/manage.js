function checkAvailability() {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "http://34.101.204.135:4444/", true);
    xhr.onreadystatechange = function () {
        var parentElement = document.getElementById("sibi-status");
        var button = document.getElementById("sibi-button");
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                console.log(xhr.responseText); // Process the response as needed
                var info = JSON.parse(xhr.responseText);

                var paragraph = document.createElement("p");

                // Create the text nodes
                var textNode1 = document.createTextNode("Service status: " + info.status + ". ");
                paragraph.appendChild(textNode1);
                
                if (info.targetUrl !== "")
                {
                    var textNode2 = document.createTextNode("Your file will be available at: ");
                    var link = document.createElement("a");
                    link.href = info.targetUrl;
                    link.textContent = info.targetUrl;
                    link.target = "_blank";
                    
                    paragraph.appendChild(textNode2);
                    paragraph.appendChild(link);
                }

                if (info.status === "Ready") {
                    button.disabled = false;
                } else {
                    button.disabled = true;
                }

                parentElement.innerHTML = paragraph.innerHTML;
            } else {
                console.error("Request failed with status: " + xhr.status);
                button.disabled = true;
                parentElement.innerHTML = "Text-To-Gesture service not found. Request failed with status: " + xhr.status;
            }
        }
    };
    xhr.send();
}

checkAvailability();
setInterval(checkAvailability, 1000);
