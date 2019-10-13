
// Forms-Text-Editor.js
// ====================================================================
// This file should not be included in your project.
// This is just a sample how to initialize plugins or components.
//
// - ThemeOn.net -


$(document).on('nifty.ready', function() {

    // SUMMERNOTE
    // =================================================================
    // Require Summernote
    // http://hackerwins.github.io/summernote/
    // =================================================================
    $('#demo-summernote, #demo-summernote-full-width').summernote({
        height : '230px'
    });




    // SUMMERNOTE AIR-MODE
    // =================================================================
    // Require Summernote
    // http://hackerwins.github.io/summernote/
    // =================================================================
    $('#demo-summernote-airmode').summernote({
        airMode: true
    });





    // SUMMERNOTE CLICK TO EDIT
    // =================================================================
    // Require Summernote
    // http://hackerwins.github.io/summernote/
    // =================================================================
    $('#demo-edit-text').on('click', function(){
        $('#demo-summernote-edit').summernote({focus: true});
        $('#demo-test-text').show();
        $('#demo-revert-text').hide();
        $('#demo-save-text').hide();
        $(this).hide();
    });


    $('#demo-test-text').on('click', function(){
        $('#demo-summernote-edit').summernote('destroy');
        itemContent.val($('#demo-summernote-edit').html());
        $('#demo-edit-text').show();
        $('#demo-revert-text').show();
        $('#demo-save-text').show();
        $(this).hide();
    });

    $('#demo-revert-text').on('click', function(){
        $('#demo-summernote-edit').summernote('destroy');
        $('#demo-edit-text').show();
        $('#demo-test-text').hide();
        $(this).hide();
        $('#demo-save-text').hide();
        itemContent.val(originalContent.html());
    });

    $('#demo-save-text').on('click', function(e){
        $('#demo-summernote-edit').summernote('destroy');
    });

})
