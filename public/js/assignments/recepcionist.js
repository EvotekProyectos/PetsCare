$(document).ready(function () {
    table = $('#table').DataTable({
        ajax: route('assignment.altas'),
        responsive: true,
        order: [0, 'desc'],
        columns: [
            {
                data: null,
                render: function (data) {
                    return data.reception ? data.reception.entry_date : '';
                }
            },
            {
                data: null,
                render: function (data) {
                    return data.reception ? data.reception.vet.name : '';
                }
            },

            {
                data: null,
                render: function (data) {
                    return data.reception ? data.reception.admission_type.name : '';
                }
            },
            {
                data: null,
                render: function (data) {
                    return data.reception.family ? data.reception.family.name : '';
                }
            },
            {
                data: null,
                render: function (data) {
                    return data.reception.family ? data.reception.family.phone : '';
                }
            },
            {
                data: null,
                render: function (data) {
                    return data.reception ? data.reception.pet.name : '';
                }
            },
            {
                data: null,
                render: function (data) {
                    return data.reception ? data.reception.pet.raza : '';
                }
            },
           
            {
                data: 'exit_date',
            },
            {
                data: null,
                render: function (data) {
               return data.hospital_discharges ? data.hospital_discharges.name : '';
                }
            },
],
createdRow: function (row, data, dataIndex) {
    // Aplicar estilos en línea según el tipo de alta
    if (data.hospital_discharges && data.hospital_discharges.id === "1") {
        $(row).css('background-color', '#ffcccc'); // Rojo claro
    } else if (data.hospital_discharges && data.hospital_discharges.id=== "2") {
        $(row).css('background-color', '#ccffcc'); // Verde claro
    } else if (data.hospital_discharges && data.hospital_discharges.id === "3") {
        $(row).css('background-color', '#ccccff'); // Azul claro
    }
}
});
});
  
