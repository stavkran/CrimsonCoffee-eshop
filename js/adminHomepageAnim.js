// JavaScript code
const texts = ["Hey Admin! Welcome."];
const delay = 50; // Delay between each character (in milliseconds)
const refreshInterval = 5000; // Refresh interval in milliseconds (e.g., 5000ms = 5 seconds)
const textElement = document.getElementById("typewriter-text");
const cursorElement = document.getElementById("cursor");
let textIndex = 0;
let charIndex = 0;

function typeWriter() {
    if (charIndex < texts[textIndex].length) {
        textElement.innerHTML += texts[textIndex].charAt(charIndex);
        charIndex++;
        setTimeout(typeWriter, delay);
    } else {
        setTimeout(clearText, refreshInterval);
    }
}

function clearText() {
    textElement.innerHTML = "";
    charIndex = 0;
    textIndex = (textIndex + 1) % texts.length; 
    setTimeout(typeWriter, delay);
}

typeWriter();
