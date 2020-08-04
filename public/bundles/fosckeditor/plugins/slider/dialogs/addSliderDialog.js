CKEDITOR.dialog.add('addSliderDialog', function (editor) {
        return {
            title: 'Propriétés du carousel',
            minWidth: 400,
            minHeight: 200,
            getModel: function (a) {
                var b = (a = a.getSelection().getSelectedElement()) && "img" === a.getName(),
                    c = a && "input" === a.getName() && "image" === a.getAttribute("type");
                console.log(a);
                console.log(b);
                console.log(c);
                return b || c ? a : null
            },
            onShow: function () {
                var a = this.getParentEditor(), b = a.getSelection(),
                    c = b.getStartElement(),
                    d = c.getParents().find(element => element.hasClass('carousel') && element.hasClass('slide'));
                var pictures = CKEDITOR.document.getById('pictures');
                pictures.setHtml('');
                if (d) {
                    var carouselInner = d.findOne('div.carousel-inner');
                    if (carouselInner) {
                        carouselInner.getChildren().toArray().forEach(function (element, index) {
                            var row = new CKEDITOR.dom.element('div');
                            row.addClass('editor-slider-row');

                            var colActions = new CKEDITOR.dom.element('div');
                            colActions.addClass('editor-slider-col-action');

                            var deleteAction = new CKEDITOR.dom.element('i');
                            deleteAction.addClass('ti-trash');
                            deleteAction.addClass('text-danger');

                            deleteAction.on('click', function (element) {
                                //Suppression de la ligne
                                row.remove();
                            });

                            colActions.append(deleteAction);

                            var upAction = new CKEDITOR.dom.element('i');
                            upAction.addClass('ti-arrow-up');
                            upAction.on('click', function (element) {
                                // Remontée de la ligne
                                if (row.hasPrevious()) {
                                    row.insertBefore(row.getPrevious());
                                    //pictures.append(row, pictures.getChildren().getFirst());
                                } else {
                                    alert('c\'est le premier');
                                }
                            });
                            colActions.append(upAction);

                            var downAction = new CKEDITOR.dom.element('i');
                            downAction.addClass('ti-arrow-down');
                            downAction.on('click', function (element) {
                                // Descente de la ligne
                                if (row.hasNext()) {
                                    row.insertAfter(row.getNext());
                                } else {
                                    //C'est la dernière
                                    alert("C'est la dernière");
                                }
                            });
                            colActions.append(downAction);

                            row.append(colActions);

                            var colImg = new CKEDITOR.dom.element('div');
                            colImg.addClass('editor-slider-col-img');

                            var img = new CKEDITOR.dom.element('img');
                            var imgUrl = element.getStyle('background-image');
                            console.log(imgUrl);
                            img.setAttribute('src', imgUrl.substr(5, imgUrl.length - 7));

                            colImg.append(img);
                            row.append(colImg);

                            var colAlt = new CKEDITOR.dom.element('div');
                            colAlt.addClass('editor-slider-col-alt');

                            var inputAlt = new CKEDITOR.dom.element('input');
                            inputAlt.addClass('cke_dialog_ui_input_text');
                            inputAlt.addClass('dialog-slider-input');
                            inputAlt.setAttribute('placeholder', 'Description');
                            inputAlt.setAttribute('maxLength', 100);

                            if (element.find('div').getItem(0).find('p').count()) {
                                inputAlt.setAttribute('value', element.find('div').getItem(0).find('p').getItem(0).getText());
                            }

                            var inputTitle = new CKEDITOR.dom.element('input');
                            inputTitle.addClass('cke_dialog_ui_input_text');
                            inputTitle.addClass('dialog-slider-input');
                            inputTitle.setAttribute('placeholder', 'Titre');
                            inputTitle.setAttribute('maxLength', 20);

                            if (element.find('div').getItem(0).find('h2').count()) {
                                inputTitle.setAttribute('value', element.find('div').getItem(0).find('h2').getItem(0).getText());
                            }

                            colAlt.append(inputTitle);
                            colAlt.append(inputAlt);

                            row.append(colAlt);

                            pictures.append(row);
                        });
                    }

                    var height = carouselInner.getParent().getStyle('height');
                    if (height) {
                        this.setValueOf('settings', 'height', height.substr(0, height.length - 2));
                    }

                    var swipeTime = carouselInner.getParent().getAttribute('data-interval');
                    if (swipeTime) {
                        this.setValueOf('settings', 'swipeTime', swipeTime);
                    }

                    var enableAnimation = carouselInner.getParent().getAttribute('data-ride');
                    if (enableAnimation) {
                        this.setValueOf('settings', 'animate', enableAnimation);
                    }

                    // Gestion de l'alignement
                    var alignments = ['tl', 'tc', 'tr', 'ml', 'mc', 'mr', 'bl', 'bc', 'br'];
                    var caption = carouselInner.getChild(0).getChild(0);
                    alignments.forEach(function (alignment) {
                        if (caption.hasClass('align-' + alignment)) {
                            CKEDITOR.dialog.getCurrent().setValueOf('settings', 'alignment', alignment);
                            $('div.alignment img').removeClass('selected');
                            $('div.alignment img[data-alignment="' + alignment + '"]').addClass('selected');
                        }
                    });
                }
            },
            contents: [
                {
                    id: "info", label: 'Slides', accessKey: "I", elements: [{
                        type: "vbox", padding: 0, children: [{
                            type: "html", widths: ["280px",
                                "110px"], html: "<div><div id=\"pictures\"></div></div>", align: "right"
                        }, {
                            type: "hbox", widths: ["280px",
                                "110px"], children: [
                                {
                                    type: "button",
                                    id: "browse",
                                    style: "display:inline-block;margin-top:14px;",
                                    align: "center",
                                    label: 'Ajouter un slide',
                                    hidden: !0,
                                    filebrowser: "info:txtUrl"
                                },
                                {
                                    id: "txtUrl", type: "text", label: 'curl', required: !0, className: 'hidden',
                                    onChange: function () {
                                        var a = this.getDialog(), b = this.getValue();
                                        if (0 < b.length) {
                                            var a = this.getDialog(), c = a.originalElement;
                                            a.preview && a.preview.removeStyle("display");

                                            var pictures = CKEDITOR.document.getById('pictures');

                                            var row = new CKEDITOR.dom.element('div');
                                            row.addClass('editor-slider-row');

                                            var colActions = new CKEDITOR.dom.element('div');
                                            colActions.addClass('editor-slider-col-action');

                                            var deleteAction = new CKEDITOR.dom.element('i');
                                            deleteAction.addClass('ti-trash');
                                            deleteAction.addClass('text-danger');

                                            deleteAction.on('click', function (element) {
                                                //Suppression de la ligne
                                                row.remove();
                                            });

                                            colActions.append(deleteAction);

                                            var upAction = new CKEDITOR.dom.element('i');
                                            upAction.addClass('ti-arrow-up');
                                            upAction.on('click', function (element) {
                                                // Remontée de la ligne
                                                if (row.hasPrevious()) {
                                                    row.insertBefore(row.getPrevious());
                                                    //pictures.append(row, pictures.getChildren().getFirst());
                                                } else {
                                                    alert('c\'est le premier');
                                                }
                                            });
                                            colActions.append(upAction);

                                            var downAction = new CKEDITOR.dom.element('i');
                                            downAction.addClass('ti-arrow-down');
                                            downAction.on('click', function (element) {
                                                // Descente de la ligne
                                                if (row.hasNext()) {
                                                    row.insertAfter(row.getNext());
                                                } else {
                                                    //C'est la dernière
                                                    alert("C'est la dernière");
                                                }
                                            });
                                            colActions.append(downAction);

                                            row.append(colActions);

                                            var colImg = new CKEDITOR.dom.element('div');
                                            colImg.addClass('editor-slider-col-img');

                                            var img = new CKEDITOR.dom.element('img');
                                            img.setAttribute('src', b);

                                            colImg.append(img);
                                            row.append(colImg);

                                            var colAlt = new CKEDITOR.dom.element('div');
                                            colAlt.addClass('editor-slider-col-alt');

                                            var inputAlt = new CKEDITOR.dom.element('input');
                                            inputAlt.addClass('cke_dialog_ui_input_text');
                                            inputAlt.addClass('dialog-slider-input');
                                            inputAlt.setAttribute('placeholder', 'Description');
                                            inputAlt.setAttribute('maxLength', 1023);

                                            var inputTitle = new CKEDITOR.dom.element('input');
                                            inputTitle.addClass('cke_dialog_ui_input_text');
                                            inputTitle.addClass('dialog-slider-input');
                                            inputTitle.setAttribute('placeholder', 'Titre');
                                            inputTitle.setAttribute('maxLength', 1023);

                                            colAlt.append(inputTitle);
                                            colAlt.append(inputAlt);

                                            row.append(colAlt);

                                            pictures.append(row);
                                        }
                                    }, setup: function (a, b) {
                                        console.log('setup');
                                        if (1 == a) {
                                            var c = b.data("cke-saved-src") || b.getAttribute("src");
                                            this.getDialog().dontResetSize = !0;
                                            this.setValue(c);
                                            this.setInitValue();
                                        }
                                    }, commit: function (a, b) {
                                        console.log('on sort');
                                        1 == a && (this.getValue() || this.isChanged()) ? (b.data("cke-saved-src", this.getValue()), b.setAttribute("src", this.getValue())) : 8 == a && (b.setAttribute("src", ""), b.removeAttribute("src"))
                                    }
                                }
                            ]
                        }]
                    }]
                },
                {
                    id: "settings", label: 'Paramètres', accessKey: "S", elements: [{
                        type: "vbox", children: [{
                            type: "hbox", widths: ["280px", "110px"], children: [{
                                id: "animate", type: "checkbox", padding: 10, label: "Animation du carousel ?"
                            }]
                        }, {
                            type: "fieldset", label: 'Paramètres d\'animation', children: [
                                {
                                    type: "vbox", children: [{
                                        type: "hbox", widths: ["180px", "50px"], children: [{
                                            type: "html",
                                            html: "<strong>Temps d'attente sur une slide</strong>",
                                            style: 'line-height: 32px'
                                        },
                                            {id: "swipeTime", type: "text", labelStyle: 'display:none'},
                                            {type: "html", html: "ms", style: 'line-height: 32px'}
                                        ]
                                    },
                                    ]
                                }],
                        }, {
                            type: "hbox", widths: ["180px", "50px"], children: [{
                                type: "html",
                                html: "<strong>Hauteur</strong>",
                                style: 'line-height: 32px'
                            },
                                {
                                    id: "height",
                                    type: "text",
                                    labelStyle: 'display:none',
                                    default: '200',
                                    validate: CKEDITOR.dialog.validate.functions(
                                        CKEDITOR.dialog.validate.notEmpty(),
                                        CKEDITOR.dialog.validate.number(),
                                        'La hauteur doit être remplie'
                                    )
                                },
                                {type: "html", html: "px", style: 'line-height: 32px'}
                            ]
                        },
                            {
                                type: "vbox", padding: 0, children: [{
                                    type: "radio",
                                    id: "alignment",
                                    className: "hidden",
                                    items: [
                                        ['Coin haut gauche', 'tl'], ['En haut et centré', 'tc'], ['Coin haut droite', 'tr'],
                                        ['Centré gauche', 'cl'], ['Centré et centré', 'cc'], ['Centré droite', 'cr'],
                                        ['Coin bas gauche', 'bl'], ['En bas et centré', 'bc'], ['Coin bas droite', 'br']],
                                    //style: "display:inline-block;margin-top:14px;",
                                    align: "center",
                                    label: "Emplacement"
                                },
                                    {

                                        type: "fieldset", label: 'Alignement', children: [
                                            {
                                                type: "vbox", padding: 0,
                                                className: "element-centered", children: [{
                                                    type: "hbox", className: "element-centered", padding: 0, children: [{
                                                        type: "html",
                                                        html: "<div class='alignment'>" +
                                                            "<img onclick='selectAlignment($(this))' data-alignment='tl' src='" + CKEDITOR.plugins.getPath('slider') + 'icons/align-tl.png' + "'/>" +
                                                            "<img onclick='selectAlignment($(this))' data-alignment='tc' src='" + CKEDITOR.plugins.getPath('slider') + 'icons/align-tc.png' + "'/>" +
                                                            "<img onclick='selectAlignment($(this))' data-alignment='tr' src='" + CKEDITOR.plugins.getPath('slider') + 'icons/align-tr.png' + "'/>" +
                                                            "</div>",
                                                        align: "center",
                                                    }],
                                                }, {
                                                    type: "hbox", padding: 0, children: [{
                                                        type: "html",
                                                        html: "<div class='alignment'>" +
                                                            "<img onclick='selectAlignment($(this))' data-alignment='ml' src='" + CKEDITOR.plugins.getPath('slider') + 'icons/align-ml.png' + "'/>" +
                                                            "<img onclick='selectAlignment($(this))' data-alignment='mc' src='" + CKEDITOR.plugins.getPath('slider') + 'icons/align-mc.png' + "'/>" +
                                                            "<img onclick='selectAlignment($(this))' data-alignment='mr' src='" + CKEDITOR.plugins.getPath('slider') + 'icons/align-mr.png' + "'/>" +
                                                            "</div>",
                                                        align: "center",
                                                    }],
                                                }, {
                                                    type: "hbox", padding: 0, children: [{
                                                        type: "html",
                                                        html: "<div class='alignment'>" +
                                                            "<img onclick='selectAlignment($(this))' data-alignment='bl' src='" + CKEDITOR.plugins.getPath('slider') + 'icons/align-bl.png' + "'/>" +
                                                            "<img onclick='selectAlignment($(this))' data-alignment='bc' src='" + CKEDITOR.plugins.getPath('slider') + 'icons/align-bc.png' + "'/>" +
                                                            "<img onclick='selectAlignment($(this))' data-alignment='br' src='" + CKEDITOR.plugins.getPath('slider') + 'icons/align-br.png' + "'/>" +
                                                            "</div>",
                                                        align: "center",
                                                    }],
                                                }]
                                            }]
                                    }]
                            }],
                    }],
                }],
            onOk: function () {

                // Suppression du slider actuel
                var originalCarousel = editor.document.getById('carouselExampleIndicators');
                if (originalCarousel) originalCarousel.remove();

                // Action quand on valide le formulaire et on insère dans l'éditeur
                var dialog = this;

                var pictures = CKEDITOR.document.getById('pictures');
                console.log(pictures);

                var div = editor.document.createElement('div');
                div.setAttribute('id', 'carouselExampleIndicators');
                if (this.getContentElement('settings', 'height').getValue()) {
                    div.setAttribute('style', 'height:' + this.getContentElement('settings', 'height').getValue() + 'px');
                }
                if (this.getContentElement('settings', 'swipeTime').getValue()) {
                    div.setAttribute('data-interval', this.getContentElement('settings', 'swipeTime').getValue());
                }
                if (this.getContentElement('settings', 'animate').getValue()) {
                    div.setAttribute('data-ride', 'carousel');
                }
                div.addClass('carousel');
                div.addClass('slide');

                if (pictures.getChildCount() > 1) {
                    console.log('Ajout de la pagination');
                    var ol = editor.document.createElement('ol');
                    ol.addClass('carousel-indicators');

                    pictures.getChildren().toArray().forEach(function (element, index) {
                        var li = editor.document.createElement('li');
                        li.setAttribute('data-target', '#carouselExampleIndicators');
                        li.setAttribute('data-slide-to', index);
                        if (index === 0) {
                            li.addClass('active');
                        }
                        ol.append(li);
                    });
                    div.append(ol);
                }

                var divInner = editor.document.createElement('div');
                divInner.addClass('carousel-inner');
                divInner.setAttribute('style', 'height:100%');

                pictures.getChildren().toArray().forEach(function (element, index) {
                    var divItem = editor.document.createElement('div');
                    divItem.addClass('carousel-item');
                    divItem.setAttribute('style', 'height:100%');
                    if (index === 0) {
                        divItem.addClass('active');
                    }
                    divItem.setStyle('height', '100%');
                    divItem.setStyle('background-image', 'url(' + element.find('img').getItem(0).getAttribute('src') + ')');
                    divItem.setStyle('background-repeat', 'no-repeat');
                    divItem.setStyle('background-position', 'center center');
                    divItem.setStyle('background-size', 'cover');


                    var divTextItem = editor.document.createElement('div');
                    divTextItem.addClass('carousel-caption');
                    divTextItem.addClass('d-md-block');

                    if (element.find('input').getItem(0).getValue()) {
                        var textItemTitle = editor.document.createElement('h2');
                        textItemTitle.appendText(element.find('input').getItem(0).getValue());
                        divTextItem.append(textItemTitle);
                    }

                    if (element.find('input').getItem(1).getValue()) {
                        var textItemP = editor.document.createElement('p');
                        textItemP.appendText(element.find('input').getItem(1).getValue());
                        divTextItem.append(textItemP);
                    }
                    divItem.append(divTextItem);

                    divInner.append(divItem);
                });

                switch (this.getContentElement('settings', 'alignment').getValue()) {
                    case 'tl':
                        divInner.getChildren().toArray().forEach(function (element, index) {
                            element.getChild(0).addClass('align-tl');
                        });
                        break;
                    case 'tc':
                        divInner.getChildren().toArray().forEach(function (element, index) {
                            element.getChild(0).addClass('align-tc');
                        });
                        break;
                    case 'tr':
                        divInner.getChildren().toArray().forEach(function (element, index) {
                            element.getChild(0).addClass('align-tr');
                        });
                        break;
                    case 'ml':
                        divInner.getChildren().toArray().forEach(function (element, index) {
                            element.getChild(0).addClass('align-ml');
                        });
                        break;
                    case 'mc':
                        divInner.getChildren().toArray().forEach(function (element, index) {
                            element.getChild(0).addClass('align-mc');
                        });
                        break;
                    case 'mr':
                        divInner.getChildren().toArray().forEach(function (element, index) {
                            element.getChild(0).addClass('align-mr');
                        });
                        break;
                    case 'bl':
                        divInner.getChildren().toArray().forEach(function (element, index) {
                            element.getChild(0).addClass('align-bl');
                        });
                        break;
                    case 'bc':
                        divInner.getChildren().toArray().forEach(function (element, index) {
                            element.getChild(0).addClass('align-bc');
                        });
                        break;
                    case 'br':
                        divInner.getChildren().toArray().forEach(function (element, index) {
                            element.getChild(0).addClass('align-br');
                        });
                        break;
                    default:
                        $('.carousel-caption').addClass('align-bc');
                        break;
                }

                div.append(divInner);
                if (pictures.getChildCount() > 1) {
                    var aPrec = editor.document.createElement('a');
                    aPrec.addClass('carousel-control-prev');
                    aPrec.setAttribute('href', '#carouselExampleIndicators');
                    aPrec.setAttribute('role', 'button');
                    aPrec.setAttribute('data-slide', 'prev');

                    var spanIconPrec = editor.document.createElement('span');
                    spanIconPrec.addClass('carousel-control-prev-icon');
                    spanIconPrec.setAttribute('aria-hidden', 'true');

                    var spanPrec = editor.document.createElement('span');
                    spanPrec.addClass('sr-only');
                    spanPrec.appendText('Précédent');

                    aPrec.append(spanIconPrec);
                    aPrec.append(spanPrec);

                    var aNext = editor.document.createElement('a');
                    aNext.addClass('carousel-control-next');
                    aNext.setAttribute('href', '#carouselExampleIndicators');
                    aNext.setAttribute('role', 'button');
                    aNext.setAttribute('data-slide', 'next');

                    var spanIconNext = editor.document.createElement('span');
                    spanIconNext.addClass('carousel-control-next-icon');
                    spanIconNext.setAttribute('aria-hidden', 'true');
                    spanIconNext.appendText(' ');

                    var spanNext = editor.document.createElement('span');
                    spanNext.addClass('sr-only');
                    spanNext.appendText('Suivant');

                    aNext.append(spanIconNext);
                    aNext.append(spanNext);

                    div.append(aPrec);
                    div.append(aNext);
                }

                editor.insertElement(div);
            }
        };
    }
);

selectAlignment = function (selected) {
    $('div.alignment img').removeClass('selected');
    selected.addClass('selected');
    CKEDITOR.dialog.getCurrent().setValueOf('settings', 'alignment', selected.data('alignment'));
}