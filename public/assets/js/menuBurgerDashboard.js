const sideNav = document.getElementById("mySidenavDashboard");
const closeBtn = document.getElementById("closeBtnDashboard");
const openBtn = document.getElementById("openBtnDashboard");

openBtn.onclick = openNav;
closeBtn.onclick = closeNav;

function openNav() {
  sideNav.classList.add("active");
}

function closeNav() {
  sideNav.classList.remove("active");
}
