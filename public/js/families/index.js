var table = undefined;
$(document).ready(function () {
    table = $('#table').DataTable({
        ajax: route('families.list'),
        responsive: true,
        order: [0, 'desc'],
        columns: [
            {
                data: 'name',
            },
            {
                data: 'phone',
            },
            {
                data: 'email',
            },
            // {
            //     data: 'address',
            // },
            // {
            //     data: 'contact_name',
            // },
            // {
            //     data: 'contact_number',
            // },
            {
                data: null,
                render: function (data) {
                    return data.fam_classification ? data.fam_classification.name : '';
                }
            },
            {
                data: null,
                render: function(data) {
                    let petNames = "";
                    data.pets.forEach(pet => {
                        petNames += `
                            <a type="button"href="${route('pet-history.index', data.id)}" class="btn btn-sm text-primary">
                                <span class="mdi--pets"></span> ${pet.name}
                            </a> <br> 
                        `;
                    });
                    return petNames.trim(); 
                }
            },
            {
                data: null,
                render: function (data) {
                    return `
                        <a type="button" href="${route('families.edit', data.id)}" class="btn btn-sm text-primary">
                            <i class="fas fa-edit"></i>
                        </a>

                        <button type="button" class="btn btn-sm text-primary" onclick="showAlertWithCallback(() => deleteFamily(${data.id}, table));">
                            <i class="fas fa-trash"></i>
                        </button>`;
                }
            },
        ],
    });
}); 
