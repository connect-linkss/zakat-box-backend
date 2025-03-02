document.addEventListener("DOMContentLoaded", () => {
    let body = document.getElementsByTagName("body")[0];
    let isMini =
        window.localStorage.getItem("hs-navbar-vertical-aside-mini") === null
            ? false
            : window.localStorage.getItem("hs-navbar-vertical-aside-mini");
    if (isMini) {
        body.classList.add("navbar-vertical-aside-mini-mode");
    }
});
