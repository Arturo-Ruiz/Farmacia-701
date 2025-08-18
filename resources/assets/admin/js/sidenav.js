let iconNavbarSidenav = document.getElementById("iconNavbarSidenav"),
    iconSidenav = document.getElementById("iconSidenav"),
    sidenav = document.getElementById("sidenav-main"),
    body = document.getElementsByTagName("body")[0],
    className = "g-sidenav-pinned";
function toggleSidenav() {
    body.classList.contains(className)
        ? (body.classList.remove(className),
          setTimeout(function () {
              sidenav.classList.remove("bg-white");
          }, 100),
          sidenav.classList.remove("bg-transparent"))
        : (body.classList.add(className),
          sidenav.classList.add("bg-white"),
          sidenav.classList.remove("bg-transparent"),
          iconSidenav.classList.remove("d-none"));
}
iconNavbarSidenav && iconNavbarSidenav.addEventListener("click", toggleSidenav),
    iconSidenav && iconSidenav.addEventListener("click", toggleSidenav);
let referenceButtons = document.querySelector("[data-class]");
function navbarColorOnResize() {
    1200 < window.innerWidth
        ? referenceButtons?.classList.contains("active") &&
          "bg-transparent" === referenceButtons?.getAttribute("data-class")
            ? sidenav.classList.remove("bg-white")
            : sidenav.classList.add("bg-white")
        : (sidenav.classList.add("bg-white"),
          sidenav.classList.remove("bg-transparent"));
}
function sidenavTypeOnResize() {
    var e = document.querySelectorAll('[onclick="sidebarType(this)"]');
    window.innerWidth < 1200
        ? e.forEach(function (e) {
              e.classList.add("disabled");
          })
        : e.forEach(function (e) {
              e.classList.remove("disabled");
          });
}
window.addEventListener("resize", function () {
    navbarColorOnResize(), sidenavTypeOnResize();
});

window.addEventListener("load", function () {
    navbarColorOnResize(), sidenavTypeOnResize();
});
