$(document).ready(function () {
    $("#imgPerfilInput").change(function () {
        readURL(this);
    })
});

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#imgPerfil').attr('src', e.target.result);
            $('#lblSelImgPerfil').empty();
            $('#lblSelImgPerfil').append(input.files[0].name);
        }

        reader.readAsDataURL(input.files[0]);
    }
}
