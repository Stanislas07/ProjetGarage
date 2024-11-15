// header
let header = document.querySelector('header');

window.addEventListener('scroll', () => {
    header.classList.toggle('shadow', window.scrollY > 0);
});

//filtre
function fetchModels() {
    const marqueSelect = document.getElementById('marque-select');
    const modeleSelect = document.getElementById('modele-select');
    const selectedMarque = marqueSelect.value;

    // Fetch les modèles en relation avec le marque selectionné
    fetch(`fetch/fetch_models.php?marque=${selectedMarque}`)
        .then(response => response.json())
        .then(data => {
            modeleSelect.innerHTML = '<option value="">Selectionner un modèle</option>';
            data.forEach(model => {
                modeleSelect.innerHTML += `<option value="${model.id_modele}">${model.nom_modele}</option>`;
            });
        });
}
//renvoie le montant du prix de l'input de type range
function updatePriceValue(value) {
    document.getElementById('prix-value').innerText = '€' + value;
    document.getElementById('prix-input').value = value; // Mettre à jour le champ caché
}

// Vérifie si les champs marque, modèle et couleur sont remplis correctement

    document.addEventListener("DOMContentLoaded", function () {
        // Récupère le formulaire
        const form = document.querySelector("form");
        
        // Écoute l'événement de soumission du formulaire
        form.addEventListener("submit", function (event) {
            // Récupère les champs de sélection et d'entrée de texte pour la marque
            const marqueSelect = document.getElementById("marque_select");
            const marqueInput = document.getElementById("marque_input");
            
            // Récupère les champs de sélection et d'entrée de texte pour le modèle
            const modeleSelect = document.getElementById("modele_select");
            const modeleInput = document.getElementById("modele_input");
            
            // Vérifie que soit le champ de sélection, soit le champ d'entrée texte est rempli pour la marque
            if (marqueSelect.value === "" && marqueInput.value === "") {
                alert("Veuillez sélectionner ou entrer une marque.");
                event.preventDefault(); // Empêche la soumission du formulaire
                return;
            }
            
            // Vérifie que soit le champ de sélection, soit le champ d'entrée texte est rempli pour le modèle
            if (modeleSelect.value === "" && modeleInput.value === "") {
                alert("Veuillez sélectionner ou entrer un modèle.");
                event.preventDefault(); // Empêche la soumission du formulaire
            }
        });
    });
