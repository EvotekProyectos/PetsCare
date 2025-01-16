const reasons = {
    "1": "#6CC3E3",
    "2": "#917AAC",
    "3": "#F8A693",
    "4": "#FFF7952",
    "5":"#95FFEA",
    "6": "#FF69B42",
    "7": "#A52A2A",
    "8":"#FF69B4"
};

const areas= {
    "1": "#079dd1",
    "2": "#f52528",
    "3": "#85c98b",
    "4": "#f8a693",
    "5": "#71459e",
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
        ajax: route('reception.appointments'),
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

var table = undefined;
$(document).ready(function () {
    table = $('#table2').DataTable({
        ajax: route('reception.hospitalizations'),
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
                        <button type="button" class="btn btn-sm text-primary" onclick="showAlertWithCallback(() => deleteReception(${data.id}, table));">
                            <i class="fas fa-trash"></i>
                        </button>`;
                }
            },
        ],
    });
}); 

var table = undefined;
$(document).ready(function () {
    table = $('#table3').DataTable({
        ajax: route('reception.groomings'),
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
                        <button type="button" class="btn btn-sm text-primary" onclick="showAlertWithCallback(() => deleteReception(${data.id}, table));">
                            <i class="fas fa-trash"></i>
                        </button>`;
                }
            },
        ],
    });
}); 

var table = undefined;
$(document).ready(function () {
    table = $('#table4').DataTable({
        ajax: route('reception.hotels'),
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
                        <button type="button" class="btn btn-sm text-primary" onclick="showAlertWithCallback(() => deleteReception(${data.id}, table));">
                            <i class="fas fa-trash"></i>
                        </button>`;
                }
            },
        ],
    });
}); 

var table = undefined;
$(document).ready(function () {
    table = $('#table5').DataTable({
        ajax: route('reception.cremations'),
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
                        <button type="button" class="btn btn-sm text-primary" onclick="showAlertWithCallback(() => deleteReception(${data.id}, table));">
                            <i class="fas fa-trash"></i>
                        </button>`;
                }
            },
        ],
    });
}); 