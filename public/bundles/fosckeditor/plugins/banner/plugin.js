CKEDITOR.plugins.add('banner', {
    icons: 'banner',
    init: function (editor) {
        editor.addCommand('addBanner', new CKEDITOR.dialogCommand('addBannerDialog', {
            //allowedContent: a,
            requiredContent: "img[alt,src]",
            contentTransformations: ["img{width}: txtUrl"]
        }));

        editor.addCommand('removeBanner', {
            exec: function (element) {
                if (confirm('On le supprime ?')) {
                    var a = element, b = a.getSelection(),
                        c = (b = b && b.getStartElement())
                    if (isParent(b)) {
                        b.remove();
                        return;
                    }
                    var parent = b.getParents().filter(element => element.is('section') && element.hasClass('page-header'))
                    parent[0].remove();
                }
            }
        });

        editor.ui.addButton('Banner', {
            label: 'Ajouter un bandeau',
            command: 'addBanner',
            toolbar: 'insert'
        });
        CKEDITOR.dialog.add('addBannerDialog', this.path + 'dialogs/addBannerDialog.js');

        var addBannerCommand = {
            label: 'Propriétés du bandeau',
            command: "addBanner",
            group: "banner",
            icon:"banner",
            order: 1
        };

        var removeBannerCommand = {
            label: 'Supprimer le bandeau',
            command: 'removeBanner',
            group: 'banner',
            order: 2
        };

        editor.contextMenu.addListener(function (element, selection) {
            if (element.getParents().filter(element => element.is('section') && element.hasClass('page-header')).length > 0) {
                return {
                    addBannerCommand: CKEDITOR.TRISTATE_OFF,
                    removeBannerCommand: CKEDITOR.TRISTATE_OFF
                };
            }
        });

        editor.addMenuGroup('banner', 3);
        editor.addMenuItems({'addBannerCommand': addBannerCommand});
        editor.addMenuItems({'removeBannerCommand': removeBannerCommand});

        editor.on("doubleclick", function (b) {
            if (b.data.element.getParent().hasClass('carousel-item')) {
                b.data.dialog = 'addBannerDialog';
            }
        });
    }
});

isParent = function (element) {
    return element.is('section') && element.hasClass('page-header');
}