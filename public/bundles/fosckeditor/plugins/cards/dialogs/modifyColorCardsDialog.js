CKEDITOR.dialog.add('modifyColorCardsDialog', function (editor) {
        return {
            title: 'Modification de la carte tarifaire',
            minWidth: 220,
            minHeight: 90,
            getModel: function (a) {
                var b = (a = a.getSelection().getSelectedElement()) && "img" === a.getName(),
                    c = a && "input" === a.getName() && "image" === a.getAttribute("type");
                return b || c ? a : null
            },
            onShow: function () {
                var a = this.getParentEditor(), b = a.getSelection(),
                    c = b.getStartElement();
                var cardDeckElement = c.getParents().find(element => element.is('div') && element.hasClass('card-deck'));
                this.setValueOf('cards', 'idOriginal', cardDeckElement.getAttribute("id"));
                if (cardDeckElement) {
                    this.setValueOf('cards', 'bgColor', toHexa(cardDeckElement.getChild(0).getChild(0).getStyle('backgroundColor')));
                    CKEDITOR.document.getById('renderCardsBg').setStyle("background-color", this.getValueOf('cards', 'bgColor'));
                }
            },
            contents: [
                {
                    id: "cards", label: 'Cartes', accessKey: "I", elements: [{
                        type: "vbox", padding: 0, children: [{
                            type: "hbox", padding: 0, children: [{
                                type: 'text',
                                id: 'bgColor',
                                label: 'Couleur d\'entête',
                                style: "display:inline-block;margin-top:14px;text-align:right",
                                size: 7,
                                default: '#FFFFFF',
                                maxLength: 7,
                                onKeyUp: function (value) {
                                    CKEDITOR.document.getById('renderCardsBg').setStyle("background-color", this.getValue());
                                }
                            }, {
                                id: "render",
                                type: "html",
                                html: "<div id=\"renderCardsBg\" style=\"margin:5px;border: solid 1px #DDDDDD;width:50px;height:50px;float: right;\"></div>"
                            }]
                        }, {
                            type: "text",
                            id: "idOriginal",
                            className: "hidden"
                        }]
                    }]
                }],
            onOk: function () {
                // Action quand on valide le formulaire et on insère dans l'éditeur
                var dialog = this;

                var originalDeckCard = editor.document.getById(dialog.getValueOf('cards', 'idOriginal'));
                var newColor = this.getValueOf('cards', 'bgColor');
                originalDeckCard.getChildren().toArray().forEach(function (card, index) {
                    card.getChild(0).setStyle("background-color", newColor);
                });
            }
        };
    }
);

toHexa = function (rgb) {
    const regex = /^rgb\((\d*), (\d*), (\d*)\)$/;
    if (regex.test(rgb)) {
        var array = regex.exec(rgb);
        console.log(array);
        return "#" + (parseInt(array[1]).toString(16) + parseInt(array[2]).toString(16) + parseInt(array[3]).toString(16)).toUpperCase();
    }
    return "#FFFFFF";
}