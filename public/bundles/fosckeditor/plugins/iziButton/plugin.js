CKEDITOR.plugins.add('iziButton', {
    icons: 'iziButton',
    init: function (editor) {

        editor.addContentsCss(  this.path + 'styles/bootstrap_btn.css' );
        editor.addCommand('addIziButton', new CKEDITOR.dialogCommand('addIziButtonDialog', {
            //allowedContent: a,
            requiredContent: "img[alt,src]",
            contentTransformations: ["img{width}: txtUrl"]
        }));

        editor.addCommand('removeIziButton', {
            exec: function (element) {
                if (confirm('On le supprime ?')) {
                    var a = element, b = a.getSelection(),
                        c = (b = b && b.getStartElement())
                    if (isIziButtonParent(b)) {
                        b.remove();
                        return;
                    }
                }
            }
        });

        editor.ui.addButton('iziButton', {
            label: 'Ajouter un carousel d\'image',
            command: 'addIziButton',
            toolbar: 'insert'
        });

        CKEDITOR.dialog.add('addIziButtonDialog', this.path + 'dialogs/addIziButtonDialog.js');

        var addIziButtonCommand = {
            label: 'Modifier le bouton',
            command: "addIziButton",
            group: 'iziButton',
            icon: "iziButton",
            order: 1
        };

        var removeIziButtonCommand = {
            label: 'Supprimer le bouton',
            command: 'removeIziButton',
            group: 'iziButton',
            //icon: "plugins/iziButton/icons/trash.png",
            order: 2
        };

        editor.contextMenu.addListener(function (element, selection) {
            if (isIziButtonParent(element)) {
                console.log(editor.getCommand("link"));
                return {
                    addIziButtonCommand: CKEDITOR.TRISTATE_OFF,
                    removeIziButtonCommand: CKEDITOR.TRISTATE_OFF
                };
            }
        });

        editor.addMenuGroup('iziButton', 2);
        editor.addMenuItems(
            {'addIziButtonCommand': addIziButtonCommand}
        );
        editor.addMenuItems(
            {'removeIziButtonCommand': removeIziButtonCommand}
        );

        editor.on("doubleclick", function (b) {
            if (b.data.element.hasClass('cke_iziButton')) {
                b.data.dialog = 'addIziButtonDialog';
            }
        });
    },
});

isIziButtonParent = function (element) {
    return element.hasClass('btn');
}
