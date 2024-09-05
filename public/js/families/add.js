window.onload = function () {
    BringPets(family_id);
    
}

$("#file").on("change", function () {
    if (document.getElementById("file").value) {
        let imgElmnt = this;

        let reader = new FileReader();
        reader.onload = function (imgElmnt) {
            $("#preview").attr("src", imgElmnt.target.result);
        }
        reader.readAsDataURL(imgElmnt.files[0]);
    } else {
        $("#preview").attr("src", imgDefault);
    }
});

async function BringPets($family) {
    let pet = await fetch(route("pets.preview", $family));
    let answer = await pet.json();
    let html = "";
    answer.forEach(e => {
        let fileRoute = e.file && e.file.route ? e.file.route : 'img/pic.png'; 
        fileRoute = fileRoute.startsWith('/') ? fileRoute.substring(1) : fileRoute;
        html += `
        <div class="col-2 mx-4 my-2">
            <div class="row  bg-white d-flex justify-content-between align-items-center"
                style="border-radius: 10px; ">
                <div class="col-4 my-2">
                    <img src="${ruta}${fileRoute}" alt="Foto de la Mascota"
                        style="width: 60px; height: 60px; object-fit: cover; border-radius: 10px; ">
                </div>
                <div class="col-5">
                    <p>${e.name}</p>
                </div>
                <div class="col-3">
                    <a href="${route('pets.edit', e.id)}"> <span class="mdi--edit-circle"></span></a>
                </div>
            </div>
         </div>
         `
    });
    document.getElementById("pet-list").innerHTML = html
}