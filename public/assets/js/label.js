const labels = document.querySelectorAll("label[for]");
const inputElements = document.querySelectorAll("input");

inputElements.forEach((inputElement) => {
  inputElement.addEventListener("input", function () {
    const texteSaisi = inputElement.value;
    const inputId = inputElement.getAttribute("id");

    const label = document.querySelector(`label[for=${inputId}]`);

    if (texteSaisi.length > 0) {
      label.style.display = "none";
    } else {
      label.style.display = "block";
    }
  });
});
