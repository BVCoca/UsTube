const labels = document.querySelectorAll("label[for]");
const inputElements = document.querySelectorAll("input");
const textAreaElements = document.querySelectorAll("textarea");

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

textAreaElements.forEach((textAreaElement) => {
  textAreaElement.addEventListener("input", function () {
    const texteSaisi = textAreaElement.value;
    const textAreaId = textAreaElement.getAttribute("id");

    const label = document.querySelector(`label[for=${textAreaId}]`);

    if (texteSaisi.length > 0) {
      label.style.display = "none";
    } else {
      label.style.display = "block";
    }
  });
});
