var table = undefined;
$(document).ready(function () {
    table = $('#table').DataTable({
        ajax: route('users.list'),
        columns: [
            {
                data: 'name',
            },
            {
                data: 'email',
            },
            {
                data: null,
                render: function (data) {
                    return data.roles.length >0 ? data.roles[0].name : 'Sin rol asignado';
                }
            },
            {
                data: null,
                render: function (data) {
                    return `
                        <a type="button" href="${route('users.edit', data.id)}" class="btn btn-sm text-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a type="button" class="btn btn-sm text-primary" onclick="getPermissions(${data.id})">
                            <i class="fas fa-lock"></i>
                        </a>
                        <button type="button" class="btn btn-sm text-primary" onclick="showAlertWithCallback(() => deleteUser(${data.id}, table));">
                            <i class="fas fa-trash"></i>
                        </button>`;
                }

            },
        ],
    });
});

// Trae los permisos del usuario seleccionado
const getPermissions = async (id) => {
    const url = route("users.permissions", id);
    const init = {
        method: "GET",
        headers: {
            Accept: "applications/json"
        }
    }
    const req = await fetch(url, init);
    if (req.ok) {
        document.getElementById("formPermissionCheck").reset();
        const res = await req.json();
        console.log(res);
        // document.getElementById("btnChangePermissions").onclick = changePermissions(res[1].id);
        document.getElementById("btnChangePermissions").setAttribute("onClick", "javascript: changePermissions(" + res[1].id + ");");
        document.getElementById("textUserName").innerText = res[1].name;
        document.getElementById("textUserType").innerText = res[1].roles[0].name;
        for (let i = 0; i < res[0].length; i++) {
            const permiso = res[0][i];
            // Marca los permisos de cada usuario
            document.getElementById("permissionCheck" + permiso.id).checked = true;
        }
    }else{
        showAlert("Parece que no tienes permiso para realizar esa acción.", "Error", "error");
    }
}

const changePermissions = async (id) => {
    let formData = new FormData(document.getElementById('formPermissionCheck'));
    const url = route("users.changePermissions", id);
    const init = {
        method: "POST",
        body: formData,
        headers: {
            Accept: "applications/json",
            "X-CSRF-TOKEN": $("#csrf").attr("content"),
        }
    }
    const req = await fetch(url, init);

    if (req.ok) {
        showAlert("Permisos actualizados correctamente.")
    } else {
        showAlert("Parece que no tienes permiso para realizar esa acción.", "Error", "error");
        getPermissions(id);
    }

}
