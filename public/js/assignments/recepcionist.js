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
    if (data.hospital_discharges && data.hospital_discharges.id) {
        var dischargeId = data.hospital_discharges.id;
        var color = '';
        switch (dischargeId) {
            case 1:
                color = '#2BEA91'; 
                break;
            case 2:
                color = '#2DAAF8';
                break;
            default:
                color = ''; 
                break;
        }

        $(row).css('background-color', color);
    }
}
});
});
  
