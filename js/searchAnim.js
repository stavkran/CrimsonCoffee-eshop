const searchInput = document.getElementById("searchInput");
const searchIcon = document.getElementById("searchIcon");

function toggleSearch() {
    const currentState = searchInput.getAttribute("data-state");
    if (currentState === "open") {
        closeSearch();
    } else {
        openSearch();
    }
}

function openSearch() {
    searchInput.setAttribute("data-state", "open");
    searchInput.style.display = "inline-block";
    searchIcon.style.color = "black"; // Change the icon color when open
    searchInput.focus();
}

function closeSearch() {
    searchInput.setAttribute("data-state", "close");
    searchInput.style.display = "none";
    searchIcon.style.color = ""; // Reset the icon color when closed
}

searchIcon.addEventListener("click", toggleSearch);

closeSearch();
