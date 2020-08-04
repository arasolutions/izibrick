CKEDITOR.plugins.add('resetcontent', {
    icons: 'resetcontent',
    init: function (editor) {
        editor.addCommand('resetcontent', {
            exec: function (element) {
                if (confirm('On supprime tout ?')) {
                    editor.document.getBody().setHtml('<p></p>')
                }
            }
        });

        editor.ui.addButton('ResetContent', {
            label: 'Effacer tout le contenu',
            command: 'resetcontent',
            toolbar: 'insert'
        });
    }
});