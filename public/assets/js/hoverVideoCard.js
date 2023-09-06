const cardHovers = document.querySelectorAll(".cardHover");

cardHovers.forEach((cardHover) => {
  cardHover.addEventListener("mouseover", function () {
    cardHover.classList.add("hovered");
  });

  cardHover.addEventListener("mouseout", function () {
    cardHover.classList.remove("hovered");
  });
});
