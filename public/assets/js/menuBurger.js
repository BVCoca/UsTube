const sideNav = document.getElementById("mySidenav");
const closeBtn = document.getElementById("closeBtn");
const openBtn = document.getElementById("openBtn");

openBtn.onclick = openNav;
closeBtn.onclick = closeNav;

function openNav() {
  sideNav.classList.add("active");
}

function closeNav() {
  sideNav.classList.remove("active");
}
