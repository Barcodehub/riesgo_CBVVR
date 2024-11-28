// Selecciona el input y el contenedor de vista previa
document.getElementById('photos').addEventListener('change', function (event) {
    const preview = document.getElementById('preview');
    preview.innerHTML = ''; // Limpia la vista previa anterior
    const files = event.target.files;

    for (const file of files) {
        const reader = new FileReader();

        reader.onload = function (e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.style.width = '100px'; // Tamaño de las imágenes en la vista previa
            img.style.margin = '5px';
            preview.appendChild(img);
        };

        reader.readAsDataURL(file);
    }
});
