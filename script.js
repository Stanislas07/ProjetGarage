// formulaire de connexion et de création de compte
// Partie connexion à la partie backoffice
const wrapper = document.querySelector('.wrapper');
const loginLink = document.querySelector('.login-link');
const registerLink = document.querySelector('.register-link');
const btnPopup = document.querySelector('.btnLogin-popup');
const iconClose = document.querySelector('.icon-close');

// Ajoute la classe 'active' pour afficher le formulaire d'inscription
registerLink.addEventListener('click', () => {
    wrapper.classList.add('active');
});

// Enlève la classe 'active' pour afficher le formulaire de connexion
loginLink.addEventListener('click', () => {
    wrapper.classList.remove('active');
});

// Ajoute la classe 'active-popup' pour afficher le popup de connexion
btnPopup.addEventListener('click', () => {
    wrapper.classList.add('active-popup');
});

// Enlève la classe 'active-popup' pour masquer le popup de connexion
iconClose.addEventListener('click', () => {
    wrapper.classList.remove('active-popup');
});

// Vérifie que la classe 'active' est présente et si la classe 'active-popup' n'est pas présente
// Si l'argument est vrai on supprime la classe 'active' ce qui ferme le popup de connexion
// Il s'ouvre automatiquement sur le formulaire de connexion
function checkAndRemoveActive() {
    if (wrapper.classList.contains('active') && !wrapper.classList.contains('active-popup')) {
        wrapper.classList.remove('active');
    }
}

// Vérification de l'état après les changements
registerLink.addEventListener('click', checkAndRemoveActive);
iconClose.addEventListener('click', checkAndRemoveActive);
