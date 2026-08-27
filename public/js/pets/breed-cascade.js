
async function loadBreedsForSpecies(speciesId, breedSelectId) {
  const breedSelect = document.getElementById(breedSelectId);
  if (!breedSelect) return;

  if (!speciesId) {
    breedSelect.innerHTML =
      '<option value="">Selecciona primero una especie</option>';
    breedSelect.disabled = true;
    return;
  }

  breedSelect.disabled = true;
  breedSelect.innerHTML = '<option value="">Cargando razas...</option>';

  try {
    const response = await fetch(route("breeds.data", speciesId));
    const breeds = await response.json();

    if (!breeds.length) {
      breedSelect.innerHTML =
        '<option value="">Sin razas registradas para esta especie</option>';
      breedSelect.disabled = true;
      return;
    }

    let html = '<option value="">Selecciona la raza</option>';
    breeds.forEach((breed) => {
      html += `<option value="${breed.id}">${breed.name}</option>`;
    });
    breedSelect.innerHTML = html;
    breedSelect.disabled = false;
  } catch (error) {
    breedSelect.innerHTML =
      '<option value="">No se pudieron cargar las razas</option>';
    breedSelect.disabled = true;
    console.error("Error al cargar razas:", error);
  }
}

// Conecta un <select> de especie con su <select> de raza dependiente: al
// cambiar de especie se recarga la raza y se limpia cualquier selección
// anterior (no tiene sentido conservar la raza de la especie previa). No
// dispara nada al cargar la página — Form Pet en modo edición ya llega con
// el <select> de raza prepoblado desde el servidor (ver PetController) para
// no perder la raza guardada con un fetch innecesario al abrir la vista.
function bindSpeciesBreedCascade(speciesSelectId, breedSelectId) {
  const speciesSelect = document.getElementById(speciesSelectId);
  if (!speciesSelect) return;

  speciesSelect.addEventListener("change", function () {
    loadBreedsForSpecies(this.value, breedSelectId);
  });
}
