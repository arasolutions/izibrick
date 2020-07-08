CKEDITOR.dialog.add('addCardsDialog', function (editor) {
        return {
            title: 'Ajout de carte tarifaire',
            minWidth: 200,
            minHeight: 160,
            getModel: function (a) {
                var b = (a = a.getSelection().getSelectedElement()) && "img" === a.getName(),
                    c = a && "input" === a.getName() && "image" === a.getAttribute("type");
                return b || c ? a : null
            },
            onShow: function () {
            },
            contents: [
                {
                    id: "cards", label: 'Cartes', accessKey: "I", elements: [{
                        type: "vbox", padding: 0, children: [{
                            type: 'select',
                            id: 'nbCards',
                            label: 'Nombre de cartes',
                            items: [['1', 1], ['2', 2], ['3', 3], ['4', 4]],
                            'default': '3',
                        }, {
                            type: "hbox", padding: 0, children: [{
                                type: 'text',
                                id: 'bgColor',
                                label: 'Couleur d\'entête',
                                style: "display:inline-block;margin-top:14px;text-align:right",
                                size: 7,
                                default: '#FFFFFF',
                                maxLength: 7,
                                onKeyUp: function (value) {
                                    CKEDITOR.document.getById('renderBg').setStyle("background-color", this.getValue());
                                }
                            }, {
                                id: "render",
                                type: "html",
                                html: "<div id=\"renderBg\" style=\"margin:5px;border: solid 1px #DDDDDD;width:50px;height:50px;float: right;\"></div>"
                            }]
                        }]
                    }]
                }],
            onOk: function () {
                // Action quand on valide le formulaire et on insère dans l'éditeur
                var dialog = this;

                var divParent = editor.document.createElement('div');
                divParent.setAttribute("id", "cke_cards_" + Math.ceil(Math.random() * 10000));
                divParent.addClass("card-deck");
                divParent.addClass("mb-3");
                divParent.addClass("text-center");

                for (var i = 0; i < dialog.getValueOf('cards', 'nbCards'); i++) {
                    var card = editor.document.createElement('div');
                    card.setAttribute("id", "cke_card_" + Math.ceil(Math.random() * 10000));
                    card.addClass("card");
                    card.addClass("mb-4");
                    card.addClass("box-shadow");

                    var cardHeader = editor.document.createElement('div');
                    cardHeader.addClass("card-header");
                    cardHeader.setStyle("background-color", dialog.getValueOf('cards', 'bgColor'));

                    var cardTitle = editor.document.createElement('h4');
                    cardTitle.addClass("my-0");
                    cardTitle.addClass("font-weight-normal");
                    cardTitle.setText("Titre " + (i + 1));
                    cardHeader.append(cardTitle);

                    card.append(cardHeader);

                    var cardBody = editor.document.createElement('div');
                    cardBody.addClass("card-body");

                    var cardPrice = editor.document.createElement('h1');
                    cardPrice.addClass("card-title");
                    cardPrice.addClass("pricing-card-title");
                    cardPrice.setText("0€ / mois");
                    cardBody.append(cardPrice);

                    var carList = editor.document.createElement('ul');
                    carList.addClass("list-unstyled");
                    carList.addClass("mt-3");
                    carList.addClass("mb-4");

                    for (var j = 0; j < 3; j++) {
                        var listItem = editor.document.createElement('li');
                        listItem.setText("Option " + (j + 1));
                        carList.append(listItem);
                    }

                    cardBody.append(carList);

                    card.append(cardBody);

                    divParent.append(card);
                }

                editor.insertElement(divParent);
                editor.insertElement(editor.document.createElement('p'));
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