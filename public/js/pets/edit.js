window.onload = function () {
    let fileRoute = Pic_route.startsWith('/') ? Pic_route.substring(1) : Pic_route;
   
    if (Pic_id !== null) {
        $("#preview").attr("src", ruta + fileRoute);
    }
    else {
        $("#preview").attr("src", imgDefault);
    }


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