function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#' + input.id + '-img').attr('src', e.target.result).removeClass('no-display');
            $('#' + input.id + '_filer_title').addClass('no-display');
        }

        reader.readAsDataURL(input.files[0]);
    }
}
