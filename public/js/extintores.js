document.addEventListener("DOMContentLoaded", () => {
    let extintorCounter = 1;

    // Escuchar el clic en el botón de añadir extintor
    document.querySelectorAll('[id^="add-extintor-"]').forEach((btn) => {
        btn.addEventListener("click", (e) => {
            const inspectionId = e.target.getAttribute('id').split('-')[2];

            const extintorContainer = document.getElementById(`extintores-container-${inspectionId}`);
            const extintorItem = document.querySelector(`#extintores-container-${inspectionId} .extintor-item`);

            // Clonar el primer extintor
            const newExtintor = extintorItem.cloneNode(true);
            extintorCounter++;

            // Actualizar los IDs de los campos en el nuevo extintor
            newExtintor.querySelectorAll("select, input").forEach((field) => {
                const originalId = field.id.split('_')[0];
                field.id = `${originalId}_${inspectionId}_${extintorCounter}`;
                field.name = `${originalId}_${inspectionId}_${extintorCounter}`;
                field.value = ""; // Limpiar el valor del nuevo campo
            });

            // Crear el botón de eliminación solo en el nuevo extintor
            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.classList.add("btn-close", "delete-extintor");
            removeButton.style.position = "absolute";
            removeButton.style.top = "10px";
            removeButton.style.right = "10px";
            removeButton.ariaLabel = "Close";

            //removeButton.innerHTML = '<i class="bi bi-x-circle"></i>';

            // Añadir el botón de eliminación al nuevo extintor
            newExtintor.prepend(removeButton);

            // Agregar el nuevo extintor al contenedor
            const newCol = document.createElement("div");
            newCol.classList.add("col-md-6");
            newCol.appendChild(newExtintor);

            extintorContainer.appendChild(newCol);

            // Agregar funcionalidad de eliminar al nuevo extintor
            removeButton.addEventListener('click', () => {
                newCol.remove(); // Eliminar el contenedor del extintor
            });
        });
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
            var modalId = $(this).attr('id'); // Obtiene el ID del modal actual
            var inspectionId = $(this).data('inspection-id'); // Obtiene el ID de inspección
            var link = $(this).data('link'); // Ruta para los botiquines
            var url = $(this).data('url');  // Ruta para los extintores
            console.log("Modal ID:", modalId, "Inspection ID:", inspectionId); // Para depuración
    
            // Selecciona el contenedor específico del modal para los extintores y botiquines
            var tipoExtintorSelector = `#tipo_${inspectionId}`;
            var kitBotiquinSelector = `#kit_${inspectionId}`;
    
            // Carga los tipos de extintores
            $.ajax({
                url: url,
                method: "GET",
                success: function (response) {
                    $(tipoExtintorSelector).empty(); // Vaciamos el select correspondiente
                    $(tipoExtintorSelector).append('<option value="">Seleccione un tipo</option>');
                    response.forEach(function (extinguisher) {
                        $(tipoExtintorSelector).append('<option value="' + extinguisher.id + '">' + extinguisher.nombre + ' - ' + extinguisher.contenido + '</option>');
                    });
                },
                error: function () {
                    alert("No se pudieron cargar los tipos de extintores.");
                }
            });
    
            // Carga los tipos de botiquines
            $.ajax({
                url: link,
                method: "GET",
                success: function (response) {
                    // Busca dentro del contenedor específico del modal
                    var botiquinContainer = $(`#botiquin-container${inspectionId}`);
                    botiquinContainer.find('select').each(function () {
                        $(this).empty(); // Vacia el select correspondiente
                        $(this).append('<option value="">Seleccione un tipo</option>');
                        response.forEach(function (kit) {
                            $(this).append('<option value="' + kit.id + '">' + kit.descripcion + '</option>');
                        }.bind(this)); // Ajusta el contexto para que funcione dentro del loop
                    });
                },
                error: function () {
                    alert("No se pudieron cargar los tipos de botiquines.");
                }
            });
        });
    });    

});
