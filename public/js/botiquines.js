///////////////////////////////////////// BOTIQUINES//////////////////////////////////////

document.addEventListener("DOMContentLoaded", () => {
    let botiquinCounter = 1;

    // Escuchar el clic en el botón de añadir botiquín
    document.querySelectorAll('[id^="add-botiquin-"]').forEach((btn) => {
        btn.addEventListener("click", (e) => {
            const inspection = e.target.getAttribute('id').split('-')[2];

            const botiquinContainer = document.getElementById(`botiquin-container${inspection}`);
            const botiquinItem = document.querySelector(`#botiquin-container${inspection} .botiquin-item`);

            // Clonar el primer botiquín
            const newBotiquin = botiquinItem.cloneNode(true);
            botiquinCounter++;

            // Actualizar los IDs de los campos en el nuevo botiquín
            newBotiquin.querySelectorAll("select, input").forEach((field) => {
                const originalId = field.id.split('_')[0];
                field.id = `${originalId}_${inspection}_${botiquinCounter}`;
                field.name = `${originalId}_${inspection}_${botiquinCounter}`;
                field.value = ""; // Limpiar el valor del nuevo campo
            });

            // Crear el botón de eliminación solo en el nuevo botiquín
            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.classList.add("btn-close", "delete-extintor");
            removeButton.style.position = "absolute";
            removeButton.style.top = "10px";
            removeButton.style.right = "10px";
            removeButton.ariaLabel = "Close";

            // Añadir el botón de eliminación al nuevo botiquín
            newBotiquin.prepend(removeButton);

            // Agregar el nuevo botiquín al contenedor
            const newCol = document.createElement("div");
            newCol.classList.add("col-md-6");
            newCol.appendChild(newBotiquin);

            botiquinContainer.appendChild(newCol);

            // Agregar funcionalidad de eliminar al nuevo botiquín
            removeButton.addEventListener('click', () => {
                newCol.remove(); // Eliminar el contenedor del botiquín
            });
        });
    });
});

