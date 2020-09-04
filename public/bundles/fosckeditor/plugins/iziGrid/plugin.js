CKEDITOR.plugins.add('iziGrid', {
    icons: 'iziGrid',
    init: function (editor) {

        editor.addContentsCss(  this.path + 'styles/izigrid.css' );
        editor.addCommand('addIziGrid', new CKEDITOR.dialogCommand('addIziGridDialog', {
            //allowedContent: a,
            requiredContent: "img[alt,src]",
            contentTransformations: ["img{width}: txtUrl"]
        }));

        editor.addCommand('removeIziGrid', {
            exec: function (element) {
                if (confirm('On la supprime ?')) {
                    var a = element, b = a.getSelection(),
                        c = (b = b && b.getStartElement())
                    if (isIziGridParent(b)) {
                        b.getParents().find(element => element.hasClass('izigrid')).remove();
                        return;
                    }
                }
            }
        });

        editor.ui.addButton('iziGrid', {
            label: 'Ajouter un carousel d\'image',
            command: 'addIziGrid',
            toolbar: 'insert'
        });

        CKEDITOR.dialog.add('addIziGridDialog', this.path + 'dialogs/addIziGridDialog.js');

        var addIziGridCommand = {
            label: 'Modifier la grille',
            command: "addIziGrid",
            group: 'iziGrid',
            icon: "iziGrid",
            order: 1
        };

        var removeIziGridCommand = {
            label: 'Supprimer la grille',
            command: 'removeIziGrid',
            group: 'iziGrid',
            order: 2
        };

        editor.contextMenu.addListener(function (element, selection) {
            if (isIziGridParent(element)) {
                console.log(editor.getCommand("link"));
                return {
                    addIziGridCommand: CKEDITOR.TRISTATE_OFF,
                    removeIziGridCommand: CKEDITOR.TRISTATE_OFF
                };
            }
        });

        editor.addMenuGroup('iziGrid', 2);
        editor.addMenuItems(
            {'addIziGridCommand': addIziGridCommand}
        );
        editor.addMenuItems(
            {'removeIziGridCommand': removeIziGridCommand}
        );

        editor.on("doubleclick", function (b) {
            if (b.data.element.hasClass('cke_iziGrid')) {
                b.data.dialog = 'addIziGridDialog';
            }
        });
    },
});

isIziGridParent = function (element) {
    return element.getParent().getParent().hasClass('izigrid');
}
