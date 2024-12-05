document.addEventListener('DOMContentLoaded', function () {
    const selectElement = document.getElementById('format_type_id');
    const generateButton = document.getElementById('generate-format-btn');
    
    // Define routes based on format_type_id
    const routes = {
        1: '{{ route("format.hospital", $pet->id) }}',
        2: '{{ route("format.alta", $pet->id) }}',
        3: '{{ route("format.surgery", $pet->id) }}',
       
       
    };

    // Update button href on selection change
    selectElement.addEventListener('change', function () {
        const selectedValue = selectElement.value;
        if (routes[selectedValue]) {
            generateButton.href = routes[selectedValue];
        } else {
            generateButton.href = '#'; // Default or empty value
        }
    });
});