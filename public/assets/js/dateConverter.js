// Récupérez tous les éléments p par classe
const dateElements = document.querySelectorAll(".date");

// Parcourez chaque élément p
dateElements.forEach((dateElement) => {
  const id = dateElement.id.split("-")[1]; // Récupérez l'ID de la vidéo

  const dateText = dateElement.textContent.trim();
  const dateParts = dateText.split("/");

  // Récupérez la date de création à partir de l'élément p
  const createdAt = new Date(`${dateParts[1]}-${dateParts[0]}-${dateParts[2]}`);

  // Calculez la différence entre la date actuelle et la date de création en secondes
  const currentDate = new Date();
  const timeDifference = Math.floor((currentDate - createdAt) / 1000); // Convertir en secondes

  if (timeDifference >= 31536000) {
    // Plus d'un an, affichez la date en années
    const yearsDifference = Math.floor(timeDifference / 31536000);
    dateElement.textContent = `Il y a ${yearsDifference} an${
      yearsDifference > 1 ? "s" : ""
    }`;
  } else if (timeDifference >= 2592000) {
    // Plus d'un mois, affichez la date en mois
    const monthsDifference = Math.floor(timeDifference / 2592000);
    dateElement.textContent = `Il y a ${monthsDifference} mois`;
  } else if (timeDifference >= 86400) {
    // Plus d'un jour, affichez la date en jours
    const daysDifference = Math.floor(timeDifference / 86400);
    dateElement.textContent = `Il y a ${daysDifference} jour${
      daysDifference > 1 ? "s" : ""
    }`;
  } else if (timeDifference >= 3600) {
    // Plus d'une heure, affichez la date en heures
    const hoursDifference = Math.floor(timeDifference / 3600);
    dateElement.textContent = `Il y a ${hoursDifference} heure${
      hoursDifference > 1 ? "s" : ""
    }`;
  } else if (timeDifference >= 60) {
    // Plus d'une minute, affichez la date en minutes
    const minutesDifference = Math.floor(timeDifference / 60);
    dateElement.textContent = `Il y a ${minutesDifference} minute${
      minutesDifference > 1 ? "s" : ""
    }`;
  } else {
    // Moins d'une minute, affichez la date en secondes
    dateElement.textContent = `Il y a ${timeDifference} seconde${
      timeDifference > 1 ? "s" : ""
    }`;
  }
});
