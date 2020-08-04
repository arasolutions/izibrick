CKEDITOR.plugins.add('cards', {
    icons: 'cards',
    init: function (editor) {
        editor.addCommand('addCards', new CKEDITOR.dialogCommand('addCardsDialog', {
            //allowedContent: a,
            requiredContent: "img[alt,src]",
            contentTransformations: ["img{width}: txtUrl"]
        }));

        editor.addCommand('removeCards', {
            exec: function (element) {
                if (confirm('On supprime tout ?')) {
                    var a = element, b = a.getSelection(),
                        c = (b = b && b.getStartElement())
                    if (isParent(b)) {
                        b.remove();
                        return;
                    }
                    var parent = b.getParents().filter(element => isCardDeck(element))
                    parent[0].remove();
                }
            }
        });


        editor.addCommand('removeCard', {
            exec: function (element) {
                if (confirm('On le supprime ?')) {
                    var a = element, b = a.getSelection(),
                        c = (b = b && b.getStartElement())
                    if (isParent(b)) {
                        b.remove();
                        return;
                    }
                    var parent = b.getParents().filter(element => isCard(element))
                    parent[0].remove();
                }
            }
        });

        editor.addCommand('modifyCardBg', new CKEDITOR.dialogCommand('modifyColorCardDialog', {
            requiredContent: "img[alt,src]",
            contentTransformations: ["img{width}: txtUrl"]
        }));

        editor.addCommand('modifyCardsBg', new CKEDITOR.dialogCommand('modifyColorCardsDialog', {
            requiredContent: "img[alt,src]",
            contentTransformations: ["img{width}: txtUrl"]
        }));

        editor.ui.addButton('cards', {
            label: 'Ajouter des cartes tarifaires',
            command: 'addCards',
            toolbar: 'insert'
        });

        CKEDITOR.dialog.add('addCardsDialog', this.path + 'dialogs/addCardsDialog.js');
        CKEDITOR.dialog.add('modifyColorCardDialog', this.path + 'dialogs/modifyColorCardDialog.js');
        CKEDITOR.dialog.add('modifyColorCardsDialog', this.path + 'dialogs/modifyColorCardsDialog.js');

        var removeCardsCommand = {
            label: 'Supprimer les cartes',
            command: 'removeCards',
            group: 'cards',
            icon: "plugins/cards/icons/trash.png",
            order: 1
        };

        var removeCardCommand = {
            label: 'Supprimer la carte',
            command: 'removeCard',
            group: 'cards',
            icon: "plugins/cards/icons/trash.png",
            order: 2
        };

        var modifyCardCommand = {
            label: 'Modifier le fond de la carte',
            command: 'modifyCardBg',
            group: 'cards',
            icon: "plugins/cards/icons/modify.png",
            order: 3
        };

        var modifyCardsCommand = {
            label: 'Modifier le fond de toutes les cartes',
            command: 'modifyCardsBg',
            group: 'cards',
            icon: "plugins/cards/icons/modify.png",
            order: 4
        };

        editor.contextMenu.addListener(function (element, selection) {
            if (element.getParents().filter(element => isCard(element)).length > 0) {
                return {
                    removeCardCommand: CKEDITOR.TRISTATE_OFF,
                    removeCardsCommand: CKEDITOR.TRISTATE_OFF,
                    modifyCardCommand: CKEDITOR.TRISTATE_OFF,
                    modifyCardsCommand: CKEDITOR.TRISTATE_OFF
                };
            }
        });

        editor.addMenuGroup('cards', 3);
        editor.addMenuItems({'removeCardCommand': removeCardCommand});
        editor.addMenuItems({'removeCardsCommand': removeCardsCommand});
        editor.addMenuItems({'modifyCardCommand': modifyCardCommand});
        editor.addMenuItems({'modifyCardsCommand': modifyCardsCommand});
    }
});

isCardDeck = function (element) {
    return element.is('div') && element.hasClass('card-deck');
}

isCard = function (element) {
    return element.is('div') && element.hasClass('card');
}