const areas= {
    "1": "#079BCE", // Quirúrgicos (Azul)
    "2": "#F54245", // Cuidado Intensivo (Rojo)
    "3": "#2ecb56", // Internos (Verde)
    "4": "#ff7855", // Felinos (Salmón)
    "5": "#8C65B5", // Infecciosos (Morado)
};

// var table = undefined;
// $(document).ready(function () {
//     table = $('#table').DataTable({
//         ajax: route('reception.list'),
//         responsive: true,
//         order: [0, 'desc'],
//         columns: [
//             {
//                 data: 'entry_date',
//             },

//             {
//                 data: null,
//                 render: function (data) {
//                     return data.reception_type ? data.reception_type.name : '';
//                 }
//             },

//             {
//                 data: null,
//                 render: function (data) {
//                     return data.family ? data.family.name : '';
//                 }
//             },
//             {
//                 data: null,
//                 render: function (data) {
//                     return data.pet ? data.pet.name : '';
//                 }
//             },
//             {
//                 data: null,
//                 render: function (data) {
//                     if (data && data.reason_id  ) {
//                         return `<span style="background-color: ${data.reason.color}; padding: 5px; color: black; border-radius: 5px;">
//                                     ${data.reason.name}
//                                 </span>`;
//                     }
//                     return '';
//                 }
//             },  
//              {
//                 data: null,
//                 render: function (data) {
//                     return data.room ? data.room.name : '';
//                 }
//             },
//             {
//                 data: null,
//                 render: function (data) {
//                     if (!data || data.area === null) {
//                         return '';
//                     }
//                 if (areas[data.area_id]) {
//                         return `<span style="background-color: ${areas[data.area_id]}; padding: 5px; color: white; border-radius: 5px;">${data.area.name}</span>`;
//                     }
//                     return data || '';
//                 }
            
//             },
//             {
//                 data: null,
//                 render: function (data) {
//                     return `
//                         <a type="button" href="${route('receptions.edit', data.id)}" class="btn btn-sm text-primary">
//                             <i class="fas fa-edit"></i>
//                         </a>
//                         <button type="button" class="btn btn-sm text-primary" onclick="showAlertWithCallback(() => deleteReception(${data.id}, table));">
//                             <i class="fas fa-trash"></i>
//                         </button>`;
//                 }
//             },
//         ],
//     });
// }); 


var table = undefined;
$(document).ready(function () {
    table = $('#table').DataTable({
        ajax: route('reception.list', 1),
        responsive: true,
        order: [0, 'desc'],
        columns: [
            {
                data: 'entry_date',
            },

            {
                data: null,
                render: function (data) {
                    return data.vet ? data.vet.name : '';
                }
            },

            {
                data: null,
                render: function (data) {
                    return data.family ? data.family.name : '';
                }
            },
            {
                data: null,
                render: function (data) {
                    return data.pet ? data.pet.name : '';
                }
            },
            {
                data: null,
                render: function (data) {
                    if (data && data.reason_id  ) {
                        return `<span style="background-color: ${data.reason.color}; padding: 5px; color: black; border-radius: 5px;">
                                    ${data.reason.name}
                                </span>`;
                    }
                    return '';
                }
            },  
             {
                data: null,
                render: function (data) {
                    return data.room ? data.room.name : '';
                }
            },
            {
                data: null,
                render: function (data) {
                    return `
                        <a type="button" href="${route('receptions.edit', data.id)}" class="btn btn-sm text-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" class="btn btn-sm text-primary" onclick="showAlertWithCallback(() => deleteReception(${data.id}, table));">
                            <i class="fas fa-trash"></i>
                        </button>`;
                }
            },
        ],
    });
}); 

var table2 = undefined;
$(document).ready(function () {
    table2 = $('#table2').DataTable({
        ajax: route('reception.list', 2),
        responsive: true,
        order: [0, 'desc'],
        columns: [
            {
                data: 'entry_date',
            },

            {
                data: null,
                render: function (data) {
                    return data.vet ? data.vet.name : '';
                }
            },

            {
                data: null,
                render: function (data) {
                    return data.family ? data.family.name : '';
                }
            },
            {
                data: null,
                render: function (data) {
                    return data.pet ? data.pet.name : '';
                }
            }, 
             {
                data: null,
                render: function (data) {
                    return data.admission_type ? data.admission_type.name : '';
                }
            },
            {
                data: null,
                render: function (data) {
                    if (!data || data.area === null) {
                        return '';
                    }
                if (areas[data.area_id]) {
                        return `<span style="background-color: ${areas[data.area_id]}; padding: 5px; color: white; border-radius: 5px;">${data.area.name}</span>`;
                    }
                    return data || '';
                }
            
            },
            {
                data: null,
                render: function (data) {
                    return `
                        <a type="button" href="${route('receptions.edit', data.id)}" class="btn btn-sm text-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a type="button" href="${route('advance-payments.add', data.id)}" class="btn btn-sm text-primary" title="Crear Anticipo">
                            <span class="lets-icons--paper-fill"></span> 
                        </a>
                        <button type="button" class="btn btn-sm text-primary" onclick="showAlertWithCallback(() => deleteReception(${data.id}, table2));">
                            <i class="fas fa-trash"></i>
                        </button>`;
                }
            },
        ],
    });
}); 

var table3 = undefined;
$(document).ready(function () {
    table3 = $('#table3').DataTable({
        ajax: route('reception.list', 3),
        responsive: true,
        order: [0, 'desc'],
        columns: [
            {
                data: 'entry_date',
            },

            {
                data: null,
                render: function (data) {
                    return data.vet ? data.vet.name : '';
                }
            },

            {
                data: null,
                render: function (data) {
                    return data.family ? data.family.name : '';
                }
            },
            {
                data: null,
                render: function (data) {
                    return data.pet ? data.pet.name : '';
                }
            }, 
             {
                data: 'exit_date',
            },
            {
                data: null,
                render: function (data) {
                    return `
                        <a type="button" href="${route('receptions.edit', data.id)}" class="btn btn-sm text-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" class="btn btn-sm text-primary" onclick="showAlertWithCallback(() => deleteReception(${data.id}, table3));">
                            <i class="fas fa-trash"></i>
                        </button>`;
                }
            },
        ],
    });
}); 

var table4 = undefined;
$(document).ready(function () {
    table4 = $('#table4').DataTable({
        ajax: route('reception.list', 4),
        responsive: true,
        order: [0, 'desc'],
        columns: [
            {
                data: 'entry_date',
            },

            {
                data: null,
                render: function (data) {
                    return data.vet ? data.vet.name : '';
                }
            },

            {
                data: null,
                render: function (data) {
                    return data.family ? data.family.name : '';
                }
            },
            {
                data: null,
                render: function (data) {
                    return data.pet ? data.pet.name : '';
                }
            }, 
             {
                data: 'exit_date',
            },
            {
                data: null,
                render: function (data) {
                    return `
                        <a type="button" href="${route('receptions.edit', data.id)}" class="btn btn-sm text-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a type="button" href="${route('advance-payments.add', data.id)}" class="btn btn-sm text-primary" title="Crear Anticipo">
                            <span class="lets-icons--paper-fill"></span> 
                        </a>
                        <button type="button" class="btn btn-sm text-primary" onclick="showAlertWithCallback(() => deleteReception(${data.id}, table4));">
                            <i class="fas fa-trash"></i>
                        </button>`;
                }
            },
        ],
    });
}); 

var table5 = undefined;
$(document).ready(function () {
    table5 = $('#table5').DataTable({
        ajax: route('reception.list', 5),
        responsive: true,
        order: [0, 'desc'],
        columns: [
            {
                data: 'entry_date',
            },

            {
                data: null,
                render: function (data) {
                    return data.receptionist ? data.receptionist.name : '';
                }
            },

            {
                data: null,
                render: function (data) {
                    return data.family ? data.family.name : '';
                }
            },
            {
                data: null,
                render: function (data) {
                    return data.pet ? data.pet.name : '';
                }
            }, 
            {
                data: null,
                render: function (data) {
                    return `
                        <a type="button" href="${route('receptions.edit', data.id)}" class="btn btn-sm text-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" class="btn btn-sm text-primary" onclick="showAlertWithCallback(() => deleteReception(${data.id}, table5));">
                            <i class="fas fa-trash"></i>
                        </button>`;
                }
            },
        ],
    });
}); 