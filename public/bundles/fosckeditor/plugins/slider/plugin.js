CKEDITOR.plugins.add('slider', {
    icons: 'slider',
    init: function (editor) {
        editor.addCommand('addSlider', new CKEDITOR.dialogCommand('addSliderDialog', {
            //allowedContent: a,
            requiredContent: "img[alt,src]",
            contentTransformations: ["img{width}: txtUrl"]
        }));
        editor.ui.addButton('Slider', {
            label: 'Tester le slider',
            command: 'addSlider',
            toolbar: 'insert'
        });
        CKEDITOR.dialog.add('addSliderDialog', this.path + 'dialogs/addSliderDialog.js');

        var addSliderCommand = {
            label: 'Propriétés du carousel',
            command: "addSlider",
            group: "slider",
            icon:"slider"
        };

        editor.contextMenu.addListener(function (element, selection) {
            if (element.getParent().hasClass('carousel-item')) {
                return {
                    addSliderCommand: CKEDITOR.TRISTATE_OFF
                };
            }
        });

        editor.addMenuGroup('slider', 2);
        editor.addMenuItem('addSliderCommand', addSliderCommand);

        editor.on("doubleclick", function (b) {
            if (b.data.element.getParent().hasClass('carousel-item')) {
                b.data.dialog = 'addSliderDialog';
            }
        });
    }
});