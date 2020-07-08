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
                    if (isBannerParent(b)) {
                        b.remove();
                        return;
                    }
                    var parent = b.getParents().find(element => isBannerParent(element));
                    if (parent) parent.remove();
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
            icon: "banner",
            order: 1
        };

        var removeBannerCommand = {
            label: 'Supprimer le bandeau',
            command: 'removeBanner',
            group: 'banner',
            icon: "plugins/banner/icons/trash.png",
            order: 2
        };

        editor.contextMenu.addListener(function (element, selection) {
            if (element.getParents().find(element => isBannerParent(element))) {
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

isBannerParent = function (element) {
    return element.is('section') && element.hasClass('page-header');
}