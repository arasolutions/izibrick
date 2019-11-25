$('#edit_global_parameters_headerBackgroundColor, #edit_global_parameters_headerTextColor,' +
    '#edit_global_parameters_contentBackgroundColor, #edit_global_parameters_contentTextColor,' +
    '#edit_global_parameters_footerBackgroundColor, #edit_global_parameters_footerTextColor').colorPicker({
    opacity: false
});

$(document).on('blur', '#edit_global_parameters_headerBackgroundColor', function () {
    $('#pattern-template-header').css('backgroundColor', $(this).val());
});
$(document).on('blur', '#edit_global_parameters_headerTextColor', function () {
    $('#pattern-template-header').css('color', $(this).val());
});

$(document).on('blur', '#edit_global_parameters_contentBackgroundColor', function () {
    $('#pattern-template-content').css('backgroundColor', $(this).val());
});
$(document).on('blur', '#edit_global_parameters_contentTextColor', function () {
    $('#pattern-template-content').css('color', $(this).val());
});

$(document).on('blur', '#edit_global_parameters_footerBackgroundColor', function () {
    $('#pattern-template-footer').css('backgroundColor', $(this).val());
});
$(document).on('blur', '#edit_global_parameters_footerTextColor', function () {
    $('#pattern-template-footer').css('color', $(this).val());
});

// Initialisation
$('#pattern-template-header').css('backgroundColor', $('#edit_global_parameters_headerBackgroundColor').val());
$('#pattern-template-header').css('color', $('#edit_global_parameters_headerTextColor').val());
$('#pattern-template-content').css('backgroundColor', $('#edit_global_parameters_contentBackgroundColor').val());
$('#pattern-template-content').css('color', $('#edit_global_parameters_contentTextColor').val());
$('#pattern-template-footer').css('backgroundColor', $('#edit_global_parameters_footerBackgroundColor').val());
$('#pattern-template-footer').css('color', $('#edit_global_parameters_footerTextColor').val());

// Modification de la couleur principale du thème
$(document).on('blur', '#edit_global_parameters_colorTheme', function () {
    $('#edit_global_parameters_headerBackgroundColor').colorPicker({
        opacity: false,
        color:'#CCC'
    });
});
