// Récupérez tous les éléments p par classe
const dateElements = document.querySelectorAll(".card-text");

// Parcourez chaque élément p
dateElements.forEach((dateElement) => {
  const id = dateElement.id.split("-")[1]; // Récupérez l'ID de la vidéo

  // Récupérez la date de création à partir de l'élément p
  const createdAt = new Date(dateElement.textContent.trim());

  // Calculez la différence entre la date actuelle et la date de création
  const currentDate = new Date();
  const timeDifference = currentDate - createdAt;
  const hoursDifference = Math.floor(timeDifference / (1000 * 60 * 60));

  // Mettez à jour le contenu de l'élément p avec la date relative
  dateElement.textContent = `Il y a ${hoursDifference} heures depuis ${createdAt.toLocaleDateString()} à ${createdAt.toLocaleTimeString()}`;
});
