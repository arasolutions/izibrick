
// Forms-Text-Editor.js
// ====================================================================
// This file should not be included in your project.
// This is just a sample how to initialize plugins or components.
//
// - ThemeOn.net -


$(document).on('nifty.ready', function () {

    // SUMMERNOTE
    // =================================================================
    // Require Summernote
    // http://hackerwins.github.io/summernote/
    // =================================================================
    $('#summernote, #summernote-full-width').summernote({
        height: '230px'
    });




    // SUMMERNOTE AIR-MODE
    // =================================================================
    // Require Summernote
    // http://hackerwins.github.io/summernote/
    // =================================================================
    $('#summernote-airmode').summernote({
        airMode: true
    });





    // SUMMERNOTE CLICK TO EDIT
    // =================================================================
    // Require Summernote
    // http://hackerwins.github.io/summernote/
    // =================================================================
    $('#demo-edit-text').on('click', function () {
        $('#summernote-edit').summernote({focus: true});
        $('#demo-test-text').show();
        $('#form-revert').hide();
        $('#form-submit').hide();
        $(this).hide();
    });


    $('#demo-test-text').on('click', function(){
        $('#summernote-edit').summernote('destroy');
        itemContent.val($('#summernote-edit').html());
        $('#demo-edit-text').show();
        $('#form-revert').show();
        $('#form-submit').show();
        $(this).hide();
    });

    $('#form-revert').on('click', function () {
        $('#summernote-edit').summernote('destroy');
        $('#demo-edit-text').show();
        $('#demo-test-text').hide();
        $(this).hide();
        $('#form-submit').hide();
        itemContent.val(originalContent.html());
    });

    $('#form-submit').on('click', function (e) {
        $('#summernote-edit').summernote('destroy');
    });

    $('#form-submit-whitout-valid').on('click', function (e) {
        $('#summernote').summernote('destroy');
        itemContent.val($('#summernote').html());
    });

})
