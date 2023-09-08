const sideNavDashboard = document.getElementById("mySidenavDashboard");
const closeBtnDashboard = document.getElementById("closeBtnDashboard");
const openBtnDashboard = document.getElementById("openBtnDashboard");

openBtnDashboard.onclick = openNavDashboard;
closeBtnDashboard.onclick = closeNavDashboard;

function openNavDashboard() {
  sideNavDashboard.classList.add("activeDashboard");
}

function closeNavDashboard() {
  sideNavDashboard.classList.remove("activeDashboard");
}
