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
    fetch(`fetch_models.php?marque=${selectedMarque}`)
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