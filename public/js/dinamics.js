document.addEventListener("DOMContentLoaded", () => {
    let extintorCounter = 1; // Contador para extintores

    const extintorContainer = document.getElementById("extintores-container");
    const addExtintorBtn = document.getElementById("add-extintor");

    // Evento para añadir nuevos extintores
    if (addExtintorBtn) {
        addExtintorBtn.addEventListener("click", () => {
            const extintorItem = document.querySelector(".extintor-item");
            const newExtintor = extintorItem.cloneNode(true);

            // Actualizar IDs y nombres de los nuevos campos
            extintorCounter++;
            newExtintor.querySelectorAll("select, input").forEach((field) => {
                if (field.id) {
                    const originalId = field.id.split("_")[0]; // Extraer la base del ID
                    field.id = `${originalId}_${extintorCounter}`; // Crear un nuevo ID único
                    field.name = `${originalId}_${extintorCounter}`; // Actualizar el atributo name
                    field.value = ""; // Reiniciar el valor del campo
                }
            });

            // Agregar el ícono de eliminar solo si es un nuevo extintor
            if (!newExtintor.querySelector(".delete-extintor")) {
                const deleteButton = document.createElement("button");
                deleteButton.type = "button";
                deleteButton.classList.add("btn-close", "delete-extintor");
                deleteButton.style.position = "absolute";
                deleteButton.style.top = "10px";
                deleteButton.style.right = "10px";
                deleteButton.ariaLabel = "Close";

                // Evento para eliminar este extintor
                deleteButton.addEventListener("click", (e) => {
                    const extintorToDelete = e.target.closest(".col-md-6");
                    extintorToDelete.remove();
                });

                // Añadir el botón al nuevo extintor
                newExtintor.appendChild(deleteButton);
            }

            // Crear un nuevo contenedor de columna y añadir el nuevo extintor
            const newCol = document.createElement("div");
            newCol.classList.add("col-md-6");
            newCol.appendChild(newExtintor);

            extintorContainer.appendChild(newCol); // Añadir la nueva columna al contenedor
        });
    }

    // Evento para eliminar extintores (para elementos futuros)
    extintorContainer.addEventListener("click", (e) => {
        if (e.target.classList.contains("delete-extintor")) {
            const extintorToDelete = e.target.closest(".col-md-6");
            extintorToDelete.remove();
        }
    });


    // Evento para eliminar extintores existentes
    extintorContainer.addEventListener("click", (e) => {
        if (e.target.classList.contains("delete-extintor")) {
            const extintorToDelete = e.target.closest(".col-md-6");
            extintorToDelete.remove();
        }
    });

    ///////////////////////////////////////// BOTIQUINES//////////////////////////////////////

    let botiquinCounter = 1; // Contador para los botiquines

    const botiquinContainer = document.getElementById("botiquin-container");
    const addBotiquinBtn = document.getElementById("add-botiquin");

    // Evento para añadir nuevos botiquines
    if (addBotiquinBtn) {
        addBotiquinBtn.addEventListener("click", () => {
            const botiquinItem = document.querySelector(".botiquin-item"); // Clonamos el primer botiquín
            const newBotiquin = botiquinItem.cloneNode(true); // Clonamos el botiquín

            // Actualizar IDs y nombres de los nuevos campos
            botiquinCounter++;
            newBotiquin.querySelectorAll("select, input").forEach((field) => {
                if (field.id) {
                    const originalId = field.id.split("_")[0]; // Extraer la base del ID
                    field.id = `${originalId}_${botiquinCounter}`; // Crear un nuevo ID único
                    field.name = `${originalId}_${botiquinCounter}`; // Actualizar el atributo name
                    field.value = ""; // Reiniciar el valor del campo
                }
            });

            // Agregar el ícono de eliminar solo si es un nuevo botiquín
            if (!newBotiquin.querySelector(".delete-botiquin")) {
                const deleteButton = document.createElement("button");
                deleteButton.type = "button";
                deleteButton.classList.add("btn-close", "delete-botiquin");
                deleteButton.style.position = "absolute";
                deleteButton.style.top = "10px";
                deleteButton.style.right = "10px";
                deleteButton.ariaLabel = "Close";

                // Evento para eliminar este botiquín
                deleteButton.addEventListener("click", (e) => {
                    const botiquinToDelete = e.target.closest(".col-md-6");
                    botiquinToDelete.remove(); // Elimina el botiquín
                });

                // Añadir el botón al nuevo botiquín
                newBotiquin.appendChild(deleteButton);
            }

            // Crear un nuevo contenedor de columna y añadir el nuevo botiquín
            const newCol = document.createElement("div");
            newCol.classList.add("col-md-6");
            newCol.appendChild(newBotiquin);

            botiquinContainer.appendChild(newCol); // Añadir la nueva columna al contenedor
        });
    }

    // Evento para eliminar botiquines (para elementos futuros)
    botiquinContainer.addEventListener("click", (e) => {
        if (e.target.classList.contains("delete-botiquin")) {
            const botiquinToDelete = e.target.closest(".col-md-6");
            botiquinToDelete.remove(); // Elimina el botiquín
        }
    });


    //////////////////////////////FIN BOTIQUINES///////////////////////

    //Validaciones
    (() => {
        'use strict'

        const forms = document.querySelectorAll('.needs-validation');

        console.log(forms)

        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                console.log(event)
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
    })()

    ///////////Datos dinámicos
    $(document).ready(function () {
        // Al abrir el modal
        $('.modal').on('show.bs.modal', function () {
            console.log("Modal abierto para la inspección: " + inspectionId);
            var modalId = $(this).attr('id'); // Obtiene el ID del modal actual
            var inspectionId = $(this).data('inspection-id'); // Obtiene el ID de inspección
            var link = $(this).data('link');/////Obtenie la ruta del json de los botiquines
            var url = $(this).data('url'); ////la url para buscar el json de los extintores
            console.log("Modal ID:", modalId, "Inspection ID:", inspectionId); // Para depurar

            $.ajax({
                url: url,
                method: "GET",
                success: function (response) {
                    $('#tipo_1').empty(); // Vaciamos el select
                    $('#tipo_1').append('<option value="">Seleccione un tipo</option>');
                    response.forEach(function (extinguisher) {
                        $('#tipo_1').append('<option value="' + extinguisher.id + '">' + extinguisher.nombre + ' - ' + extinguisher.contenido + '</option>');
                    });
                },
                error: function () {
                    alert("No se pudieron cargar los tipos de extintores.");
                }
            });

            $.ajax({
                url: link,
                method: "GET",
                success: function (response) {
                    $('#kit_1').empty(); // Vaciamos el select
                    $('#kit_1').append('<option value="">Seleccione un tipo</option>');
                    response.forEach(function (kit) {
                        $('#kit_1').append('<option value="' + kit.id + '">' + kit.descripcion + '</option>');
                    });
                },
                error: function () {
                    alert("No se pudieron cargar los tipos de Botiquines.");
                }
            });
        });
    });

});
