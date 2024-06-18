document.addEventListener('DOMContentLoaded', () => {
    const uploadForm = document.getElementById('uploadForm');
    const locationInput = document.getElementById('location');
    const dropArea = document.getElementById('drop-area');
    const fileInput = document.getElementById('photo');
    const preview = document.getElementById('preview');
    const resetButton = document.querySelector('input[type="reset"]');
    const latitudeInput = document.createElement('input');
    const longitudeInput = document.createElement('input');


    // Initialize Google Maps Places Autocomplete
    let autocomplete = new google.maps.places.Autocomplete(locationInput);

    autocomplete.addListener('place_changed', () => {
        let place = autocomplete.getPlace();
        if (place.geometry) {
            latitudeInput.value = place.geometry.location.lat();
            longitudeInput.value = place.geometry.location.lng();
        }
    });

    // Prevent default drag behaviors
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, preventDefaults, false);
    });

    // Highlight drop area when item is dragged over it
    ['dragenter', 'dragover'].forEach(eventName => {
        dropArea.addEventListener(eventName, () => dropArea.classList.add('dragover'), false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, () => dropArea.classList.remove('dragover'), false);
    });

    // Handle dropped files
    dropArea.addEventListener('drop', handleDrop, false);

    // Handle file input change
    fileInput.addEventListener('change', () => handleFiles(fileInput.files), false);

    // Handle reset button click
    resetButton.addEventListener('click', clearPreview, false);

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    function handleDrop(e) {
        let dt = e.dataTransfer;
        let files = dt.files;
        handleFiles(files);
    }

    function handleFiles(files) {
        if (files.length > 0) {
            fileInput.files = files;
            displayPreview(files[0]);
        }
    }

    function displayPreview(file) {
        preview.innerHTML = '';

        let reader = new FileReader();
        reader.readAsDataURL(file);

        reader.onloadend = function() {
            let img = document.createElement('img');
            img.src = reader.result;
            preview.appendChild(img);
        };

        reader.onerror = function() {
            console.error('File reading error');
        };

        reader.onabort = function() {
            console.error('File reading aborted');
        };
    }

    function clearPreview() {
        preview.innerHTML = '';
        fileInput.value = '';
    }
});