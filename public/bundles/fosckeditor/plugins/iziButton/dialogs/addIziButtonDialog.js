CKEDITOR.dialog.add('addIziButtonDialog', function (editor) {
    return {
        title: 'Propriétés du bouton',
        minWidth: 500,
        minHeight: 200,
        getModel: function (a) {
            var b = (a = a.getSelection().getSelectedElement()) && "img" === a.getName(),
                c = a && "input" === a.getName() && "image" === a.getAttribute("type");
            return b || c ? a : null
        },
        onShow: function () {
            var a = this.getParentEditor(), b = a.getSelection(),
                c = b.getStartElement();

            var text = c.getText();
            var dialog = this;
            dialog.setValueOf('iziButton', 'idOriginal', c.getParent().getId());

            var color, size, outline, block = null;
            this.getContentElement('iziButton', 'color').items.forEach(function (item, index) {
                if (c.hasClass('btn-' + item[1])) {
                    dialog.setValueOf('iziButton', 'color', item[1]);
                    return;
                }
            });
            this.getContentElement('iziButton', 'size').items.forEach(function (item, index) {
                if (c.hasClass('btn-' + item[1])) {
                    dialog.setValueOf('iziButton', 'size', item[1]);
                    return;
                }
            });
            dialog.setValueOf('iziButton', 'outline', c.hasClass('btn-outline'));
            dialog.setValueOf('iziButton', 'block', c.hasClass('btn-block'));

            var href = c.getAttribute('href');
            if (href.includes('iziButton')) {
                dialog.setValueOf('iziButton', 'internal', c.getAttribute('href'));
            } else {
                dialog.setValueOf('iziButton', 'external', c.getAttribute('href'));
                CKEDITOR.dialog.getCurrent().setValueOf('iziButton', 'link', 'external');
            }

            changeButton();

        },
        contents: [{
            id: "iziButton", label: 'Bouton', accessKey: "B", elements: [{
                type: "vbox", padding: 0, children: [{
                    type: "fieldset", label: 'Paramètres', children: [{
                        type: "vbox", padding: 0, children: [{
                            type: 'text', id: 'idOriginal', className: 'hidden'
                        }, {
                            type: 'text', label: "Texte", id: 'text', 'default': 'Bouton',
                            onKeyUp: function () {
                                if (this.getValue()) {
                                    $('.render-btn a').html(this.getValue());
                                } else {
                                    $('.render-btn a').html(this.default);
                                }
                            },
                            onLoad: function () {
                                $('.render-btn a').html(this.default);
                            }
                        }, {
                            type: "hbox", padding: "5px 0 0 0", children: [{
                                type: 'select', label: "Couleur", id: 'color',
                                items: [
                                    ['Défaut', 'primary'],
                                    ['Couleur de thème', 'theme'],
                                    ['Information', 'info'],
                                    ['Attention', 'warning'],
                                    ['Danger', 'danger'],
                                    ['Succès', 'success'],
                                    ['Clair', 'light'],
                                    ['Sombre', 'dark']
                                ],
                                'default': 'primary',
                                onChange: function (api) {
                                    this.items.forEach(function (item, index) {
                                        $('.render-btn a').removeClass('btn-dialog-' + item[1]);
                                        $('.render-btn a').removeClass('btn-dialog-outline-' + item[1]);
                                    });

                                    changeButton();
                                }
                            }, {
                                type: 'select', label: "Taille", id: "size",
                                items: [
                                    ['Petit', 'sm'],
                                    ['Moyen', 'md'],
                                    ['Gros', 'lg']
                                ],
                                'default': 'md',
                                onChange: function (api) {
                                    this.items.forEach(function (item, index) {
                                        $('.render-btn a').removeClass('btn-dialog-' + item[1]);
                                    });
                                    changeButton();
                                }
                            }]
                        }, {
                            type: "hbox", padding: "5px 0 0 0", children: [{
                                type: 'checkbox', label: "Inversé", id: 'outline',
                                onChange: function (api) {
                                    changeButton();
                                }
                            }, {
                                type: 'checkbox', label: "Largeur max", id: 'block',
                                onChange: function (api) {
                                    changeButton();
                                }
                            }]
                        }]
                    }]
                }, {
                    type: "fieldset",
                    label: 'Lien',
                    style: "margin-top:10px",
                    children: [{
                        type: "vbox", padding: "5px 0 0 0", children: [{
                            type: "hbox", padding: "5px 0 0 0", children: [{
                                type: 'radio', id: 'link',
                                items: [['URL externe', 'external'], ['Page interne', 'internal']]
                            }]
                        }, {
                            type: "hbox", padding: "5px 0 0 0", children: [{
                                type: 'text', id: 'external', style: 'padding-right:5px',
                                onKeyUp: function (evt) {
                                    CKEDITOR.dialog.getCurrent().setValueOf('iziButton', 'link', 'external');
                                }
                            }, {
                                type: 'select', id: 'internal',
                                items: pageArray,
                                onChange: function (evt) {
                                    CKEDITOR.dialog.getCurrent().setValueOf('iziButton', 'link', 'internal');
                                }
                            }]
                        }]
                    }]
                }, {
                    type: "fieldset",
                    label: 'Rendu',
                    style: "margin-top:10px",
                    children: [{
                        type: 'html', html: '<div id="renderBtn" class="render-btn">' +
                            '<a type="button" class="btn-dialog btn-dialog-primary"></a>' +
                            '</div>'
                    }]
                }]
            }]
        }],
        onOk: function () {
            // Action quand on valide le formulaire et on insère dans l'éditeur
            var dialog = this;

            // Suppression du iziButton actuel
            if (dialog.getValueOf('iziButton', 'idOriginal')) {
                editor.document.getById(dialog.getValueOf('iziButton', 'idOriginal')).remove();
            }

            var text = dialog.getValueOf('iziButton', 'text');
            var color = dialog.getValueOf('iziButton', 'color');
            var size = dialog.getValueOf('iziButton', 'size');
            var outline = dialog.getValueOf('iziButton', 'outline');
            var block = dialog.getValueOf('iziButton', 'block');
            var link = dialog.getValueOf('iziButton', 'link');

            var p = editor.document.createElement('p');
            p.setAttribute("id", "cke_iziButton_" + Math.ceil(Math.random() * 10000));
            var a = editor.document.createElement('a');

            a.setAttribute('type', 'button');
            if (link === 'external') {
                a.setAttribute('href', dialog.getValueOf('iziButton', 'external'));
                a.setAttribute('target', 'blank');
            } else {
                a.setAttribute('href', dialog.getValueOf('iziButton', 'internal'));
            }

            a.addClass('btn');

            if (outline) {
                a.addClass('btn-outline-' + color);
            } else {
                a.addClass('btn-' + color);
            }

            if (block) {
                a.addClass('btn-block');
            }
            a.addClass('btn-' + size);

            a.setText(text);

            p.append(a);

            editor.insertElement(p);
        }
    };
});

changeButton = function () {
    var color = CKEDITOR.dialog.getCurrent().getValueOf('iziButton', 'color');
    var size = CKEDITOR.dialog.getCurrent().getValueOf('iziButton', 'size');
    var outline = CKEDITOR.dialog.getCurrent().getValueOf('iziButton', 'outline');
    var block = CKEDITOR.dialog.getCurrent().getValueOf('iziButton', 'block');

    if (outline) {
        $('.render-btn a').removeClass('btn-dialog-' + color);
        $('.render-btn a').addClass('btn-dialog-outline-' + color);
    } else {
        $('.render-btn a').removeClass('btn-dialog-outline-' + color);
        $('.render-btn a').addClass('btn-dialog-' + color);
    }

    if (block) {
        $('.render-btn a').addClass('btn-dialog-block');
    } else {
        $('.render-btn a').removeClass('btn-dialog-block');
    }
    $('.render-btn a').addClass('btn-dialog-' + size);
}