var table = undefined;
$(document).ready(function () {
  table = $("#table").DataTable({
    ajax: route("cm.list"),
    columns: [
      {
        data: "name",
      },
      {
        data: null,
        render: function (data) {
          return `
                        <a type="button" href="${route(
                          "cm-types.edit",
                          data.id
                        )}" class="btn btn-sm text-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                       
                        <button type="button" class="btn btn-sm text-primary" onclick="showAlertWithCallback(() => deleteCmType(${
                          data.id
                        }, table));">
                            <i class="fas fa-trash"></i>
                        </button>`;
        },
      },
    ],
  });
});
