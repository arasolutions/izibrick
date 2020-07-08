CKEDITOR.plugins.add('slider', {
    icons: 'slider',
    init: function (editor) {
        editor.addCommand('addSlider', new CKEDITOR.dialogCommand('addSliderDialog', {
            //allowedContent: a,
            requiredContent: "img[alt,src]",
            contentTransformations: ["img{width}: txtUrl"]
        }));

        editor.addCommand('removeSlider', {
            exec: function (element) {
                if (confirm('On le supprime ?')) {
                    var a = element, b = a.getSelection(),
                        c = (b = b && b.getStartElement())
                    if (isParent(b)) {
                        b.remove();
                        return;
                    }
                    var parent = b.getParents().filter(element => element.hasClass('carousel') && element.hasClass('slide'))
                    parent[0].remove();
                }
            }
        });

        editor.ui.addButton('Slider', {
            label: 'Ajouter un carousel',
            command: 'addSlider',
            toolbar: 'insert'
        });

        CKEDITOR.dialog.add('addSliderDialog', this.path + 'dialogs/addSliderDialog.js');

        var addSliderCommand = {
            label: 'Propriétés du carousel',
            command: "addSlider",
            group: 'slider',
            icon: "slider",
            order: 1
        };

        var removeSliderCommand = {
            label: 'Supprimer le carousel',
            command: 'removeSlider',
            group: 'slider',
            icon: "plugins/slider/icons/trash.png",
            order: 2
        };

        editor.contextMenu.addListener(function (element, selection) {
            if (element.getParent().hasClass('carousel-item')) {
                return {
                    addSliderCommand: CKEDITOR.TRISTATE_OFF,
                    removeSliderCommand: CKEDITOR.TRISTATE_OFF
                };
            }
        });

        editor.addMenuGroup('slider', 2);
        editor.addMenuItems(
            {'addSliderCommand': addSliderCommand}
        );
        editor.addMenuItems(
            {'removeSliderCommand': removeSliderCommand}
        );

        editor.on("doubleclick", function (b) {
            if (b.data.element.getParent().hasClass('carousel-item')) {
                b.data.dialog = 'addSliderDialog';
            }
        });
    }
});

isParent = function (element) {
    return element.hasClass('.carousel') && element.hasClass('.slide');
}