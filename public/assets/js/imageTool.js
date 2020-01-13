function readURL(input, keepDisplay) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#' + input.id + '-img').attr('src', e.target.result).removeClass('no-display');
            if(!keepDisplay) {
                $('#' + input.id + '_filer_title').addClass('no-display');
            }s
        }

        reader.readAsDataURL(input.files[0]);
    }
}
