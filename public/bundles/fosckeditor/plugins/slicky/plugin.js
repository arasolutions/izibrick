CKEDITOR.plugins.add('slicky', {
    icons: 'slicky',
    init: function (editor) {
        editor.addCommand('addSlicky', new CKEDITOR.dialogCommand('addSlickyDialog', {
            //allowedContent: a,
            requiredContent: "img[alt,src]",
            contentTransformations: ["img{width}: txtUrl"]
        }));

        editor.addCommand('removeSlicky', {
            exec: function (element) {
                if (confirm('On le supprime ?')) {
                    var a = element, b = a.getSelection(),
                        c = (b = b && b.getStartElement())
                    if (isSlickyParent(b)) {
                        b.remove();
                        return;
                    }
                    var parent = b.getParents().find(element => isSlickyParent(element));
                    if (parent) parent.remove();
                }
            }
        });

        editor.ui.addButton('Slicky', {
            label: 'Ajouter un carousel d\'image',
            command: 'addSlicky',
            toolbar: 'insert'
        });

        CKEDITOR.dialog.add('addSlickyDialog', this.path + 'dialogs/addSlickyDialog.js');

        var addSlickyCommand = {
            label: 'Propriétés du carousel d\'image',
            command: "addSlicky",
            group: 'slicky',
            icon: "slicky",
            order: 1
        };

        var removeSlickyCommand = {
            label: 'Supprimer le carousel d\'image',
            command: 'removeSlicky',
            group: 'slicky',
            icon: "plugins/slicky/icons/trash.png",
            order: 2
        };

        editor.contextMenu.addListener(function (element, selection) {
            if (element.getParents().find(element => isSlickyParent(element))) {
                return {
                    addSlickyCommand: CKEDITOR.TRISTATE_OFF,
                    removeSlickyCommand: CKEDITOR.TRISTATE_OFF
                };
            }
        });

        editor.addMenuGroup('slicky', 2);
        editor.addMenuItems(
            {'addSlickyCommand': addSlickyCommand}
        );
        editor.addMenuItems(
            {'removeSlickyCommand': removeSlickyCommand}
        );

        editor.on("doubleclick", function (b) {
            if (b.data.element.hasClass('cke_slicky')) {
                b.data.dialog = 'addSlickyDialog';
            }
        });
    },

    afterInit: function (editor) {
        function createFakeParserElement(realElement, className, type, resizable) {

            var attributes = {
                "class": className,
                "data-cke-realelement": encodeURIComponent(realElement.getOuterHtml()),
                "data-cke-real-node-type": 1,
                'data-cke-real-element-type': type,
                "data-cke-resizable": resizable,
                "style": "width:" + realElement.styles.width + ";height:" + realElement.styles.height,
                "src":"data:image/gif;base64,R0lGODlhAQABAPABAP///wAAACH5BAEKAAAALAAAAAABAAEAAAICRAEAOw=="
            };

            return new CKEDITOR.htmlParser.element("img",attributes);
        }

        var dataProcessor = editor.dataProcessor,
            dataFilter = dataProcessor && dataProcessor.dataFilter;
        if (dataFilter) {
            dataFilter.addRules(
                {
                    elements:
                        {
                            'div': function (element) {
                                var attributes = element.attributes;

                                if (attributes.class === 'slicky') {
                                    return createFakeParserElement(element, "cke_slicky", "slicky", !0);
                                }
                                return null;

                            }
                        }
                },
                5);
        }
    }
});

isSlickyParent = function (element) {
    return element.hasClass('cke_slicky');
}
