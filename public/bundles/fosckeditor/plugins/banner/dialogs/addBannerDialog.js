CKEDITOR.dialog.add('addBannerDialog', function (editor) {
        return {
            title: 'Propriétés du bandeau',
            minWidth: 400,
            minHeight: 200,
            getModel: function (a) {
                var b = (a = a.getSelection().getSelectedElement()) && "img" === a.getName(),
                    c = a && "input" === a.getName() && "image" === a.getAttribute("type");
                return b || c ? a : null
            },
            onShow: function () {
                var a = this.getParentEditor(), b = a.getSelection(),
                    c = b.getStartElement(),
                    d = c.getParents().filter(element => element.is('section') && element.hasClass('page-header'))[0];
                var renderImg = CKEDITOR.document.getById('render');
                if (d) {
                    this.setValueOf('banner', 'idOriginal', d.getAttribute("id"));
                    this.setValueOf('banner', 'height', d.getStyle('height').substr(0, d.getStyle('height').length - 2));
                    if (d.getAttribute('data-image-src')) {
                        // C'est une image de fond
                        this.setValueOf('banner', 'typeFond', 'image');
                        this.setValueOf('banner', 'colorBackground', '#FFFFFF');
                        CKEDITOR.document.getById('render').setAttribute("src", d.getAttribute('data-image-src'));
                        CKEDITOR.document.getById('render').removeClass("hidden");
                    } else {
                        var hexaColor = toHexa(d.getChild(0).getStyle('background-color'));
                        CKEDITOR.document.getById('render').removeAttribute("src");
                        CKEDITOR.document.getById('render').addClass("hidden");
                        CKEDITOR.document.getById('renderBg').setStyle("background-color", hexaColor);
                        this.setValueOf('banner', 'colorBackground', hexaColor);
                        this.setValueOf('banner', 'typeFond', 'color');
                    }

                    if (d.getElementsByTag('h2').count() > 0) {
                        this.setValueOf('banner', 'description', d.getElementsByTag('h2').getItem(0).getText());
                    }
                    if (d.getElementsByTag('h1').count() > 0) {
                        this.setValueOf('banner', 'title', d.getElementsByTag('h1').getItem(0).getText());
                    }

                } else {
                    CKEDITOR.document.getById('renderBg').setStyle("background-color", "#FFFFFF");
                    this.setValueOf('banner', 'colorBackground', '#FFFFFF');
                    this.setValueOf('banner', 'height', '100');
                    this.setValueOf('banner', 'typeFond', 'color');
                }
            },
            contents: [
                {
                    id: "banner", label: 'Bandeau', accessKey: "I", elements: [{
                        type: "vbox", padding: 0, children: [{
                            type: "fieldset", padding: 0, 'label': "Fond du bandeau", children: [{
                                type: "vbox", padding: 0, className: "radiogroup", children: [{
                                    type: "radio",
                                    className: "hide-label",
                                    id: "typeFond",
                                    items: [['Image', 'image'], ['Couleur', 'color']]
                                }, {
                                    type: "text",
                                    id: "idOriginal",
                                    className: "hidden"
                                }, {
                                    type: "hbox", padding: 0, children: [{
                                        type: "vbox", padding: 0, className: "element-centered", children: [{
                                            type: "button",
                                            id: "browse",
                                            style: "display:inline-block;margin-top:14px;",
                                            align: "center",
                                            label: 'Choisir',
                                            hidden: !0,
                                            filebrowser: "banner:txtUrl"
                                        }, {
                                            id: "txtUrl",
                                            type: "text",
                                            label: 'curl',
                                            required: !0,
                                            className: 'hidden',
                                            onChange: function () {
                                                var a = this.getDialog(), b = this.getValue();
                                                if (0 < b.length) {
                                                    CKEDITOR.document.getById('render').setAttribute("src", b);
                                                    CKEDITOR.document.getById('render').removeClass("hidden");
                                                    this.getDialog().setValueOf('banner', 'typeFond', 'image');
                                                }
                                            },
                                            setup: function (a, b) {
                                                console.log('setup');
                                                if (1 == a) {
                                                    var c = b.data("cke-saved-src") || b.getAttribute("src");
                                                    this.getDialog().dontResetSize = !0;
                                                    this.setValue(c);
                                                    this.setInitValue();
                                                }
                                            },
                                            commit: function (a, b) {
                                                console.log('on sort');
                                                1 == a && (this.getValue() || this.isChanged()) ? (b.data("cke-saved-src", this.getValue()), b.setAttribute("src", this.getValue())) : 8 == a && (b.setAttribute("src", ""), b.removeAttribute("src"))
                                            }
                                        }]
                                    }, {
                                        type: "vbox", padding: 0, className: "element-centered", children: [{
                                            type: "text",
                                            id: "colorBackground",
                                            style: "display:inline-block;margin-top:14px;",
                                            className: "hide-label",
                                            align: "center",
                                            maxLength: 7,
                                            size: 7,
                                            validate: CKEDITOR.dialog.validate.regex(/^\#[A-Fa-f0-9]{6}$/, "Erreur"),
                                            onKeyUp: function (value) {
                                                CKEDITOR.document.getById('render').removeAttribute("src");
                                                CKEDITOR.document.getById('render').addClass("hidden");
                                                CKEDITOR.document.getById('renderBg').setStyle("background-color", this.getValue());
                                                this.getDialog().setValueOf('banner', 'typeFond', 'color');
                                            }
                                        }]
                                    }]
                                }, {
                                    id: "render",
                                    type: "html",
                                    html: "<div id=\"renderBg\" style=\"margin:5px;height:100px;border: solid 1px #DDDDDD;\"><img id=\"render\"/></div>"
                                }, {
                                    id: "height",
                                    type: "text",
                                    label: "Hauteur (en px)",
                                    style: "display:inline-block",
                                    validate: CKEDITOR.dialog.validate.functions(
                                        CKEDITOR.dialog.validate.notEmpty(),
                                        CKEDITOR.dialog.validate.number(),
                                        'La hauteur doit être remplie'
                                    )
                                }]
                            }]
                        }, {
                            type: "fieldset",
                            padding: 0,
                            'label': "Texte du bandeau",
                            style: "margin-top:10px",
                            children: [{
                                type: "hbox", padding: 0, children: [{
                                    type: "vbox", padding: 0, children: [{
                                        type: "text",
                                        id: "title",
                                        style: "display:inline-block",
                                        align: "center",
                                        maxLength: 50,
                                        size: 100,
                                        label: "Titre"
                                    }, {
                                        type: "text",
                                        id: "description",
                                        style: "display:inline-block;margin-top:14px;",
                                        align: "center",
                                        maxLength: 100,
                                        size: 100,
                                        label: "Description"
                                    }]
                                }]
                            }]
                        }]
                    }]
                }
            ],
            onOk: function () {
                // Action quand on valide le formulaire et on insère dans l'éditeur
                var dialog = this;

                // Suppression du bandeau actuel
                var originalBanner = editor.document.getById(dialog.getValueOf('banner', 'idOriginal'));
                if (originalBanner) originalBanner.remove(false);


                var section = editor.document.createElement('section');
                section.setAttribute("id", "section_" + Math.ceil(Math.random() * 10000));
                section.addClass("page-header");
                section.addClass("parallax");
                section.addClass("page-header-text-light");
                section.addClass("page-header-crumbs-light-2");
                section.addClass("py-0");
                section.setStyle("position", "relative");
                section.setStyle("overflow", "hidden");
                section.setStyle("height", dialog.getValueOf('banner', 'height') + "px");
                if (dialog.getValueOf('banner', 'typeFond') === 'image') {
                    section.addClass("overlay");
                    section.addClass("overlay-color-dark");
                    section.addClass("overlay-show");
                    section.addClass("overlay-op-3");
                    section.setAttribute("data-image-src", CKEDITOR.document.getById('render').getAttribute("src"));
                }

                var divParallax = editor.document.createElement('div');
                divParallax.addClass("parallax-background");
                if (dialog.getValueOf('banner', 'typeFond') === 'image') {
                    divParallax.setStyle("background-image", "url('" + CKEDITOR.document.getById('render').getAttribute("src") + "')");
                    divParallax.setStyle("background-size", "cover");
                    divParallax.setStyle("background-position", "50% center");
                } else {
                    divParallax.setStyle("background-color", dialog.getValueOf('banner', 'colorBackground'));
                }
                divParallax.setStyle("position", "absolute");
                divParallax.setStyle("top", "0px");
                divParallax.setStyle("left", "0px");
                divParallax.setStyle("width", "100%");
                divParallax.setStyle("height", "180%");
                divParallax.setStyle("transform", "translate3d(0px, -54.8571px, 0px)");
                section.append(divParallax);

                var divContainer = editor.document.createElement('div');
                divContainer.addClass("container");
                divContainer.addClass("py-9");
                divContainer.setStyle("position", "absolute");
                divContainer.setStyle("top", "50%");
                divContainer.setStyle("margin-left", "50px");
                divContainer.setStyle("transform", "translateY(-50%)");

                var divRow = editor.document.createElement('div');
                divRow.addClass("row");
                divRow.addClass("text-left");

                var divCol12 = editor.document.createElement('div');
                divCol12.addClass("col-lg-12");

                var title = editor.document.createElement('h1');
                title.setHtml(dialog.getValueOf('banner', 'title'));
                divCol12.append(title);

                if (dialog.getValueOf('banner', 'description')) {
                    var description = editor.document.createElement('h2');
                    description.setHtml(dialog.getValueOf('banner', 'description'));
                    divCol12.append(description);
                }

                divRow.append(divCol12);
                divContainer.append(divRow);
                section.append(divContainer);

                editor.insertElement(section);
                editor.insertElement(editor.document.createElement('p'));
            }
        }
            ;
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
