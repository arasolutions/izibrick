CKEDITOR.dialog.add('addSlickyDialog', function (editor) {
        return {
            title: 'Propriétés du carousel',
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

                var slickyUl = CKEDITOR.document.getById('slicky_sortable');
                slickyUl.setHtml('');

                if (c.is('img') && c.hasClass('cke_slicky')) {
                    var realElement = decodeURIComponent(c.getAttribute('data-cke-realelement'));

                    var t = new CKEDITOR.dom.element('div');
                    t.setHtml(realElement);

                    var options = JSON.parse(t.getChild(0).getAttribute('data-slick'));

                    this.setValueOf('settings', 'height', c.getStyle('height'));
                    this.setValueOf('settings', 'width', c.getStyle('width'));

                    this.setValueOf('settings', 'infinite', options.infinite);
                    this.setValueOf('settings', 'dots', options.dots);
                    this.setValueOf('settings', 'autoplay', options.autoplay);
                    this.setValueOf('settings', 'autoplaySpeed', options.autoplaySpeed);
                    this.setValueOf('settings', 'slidesToShow', options.slidesToShow);
                    this.setValueOf('settings', 'slidesToScroll', options.slidesToScroll);
                    this.setValueOf('settings', 'speed', options.speed);
                    this.setValueOf('settings', 'fade', options.fade);

                    var divs = t.getChild(0).find('div.slicky-item-block');
                    if (divs.count() > 0) {
                        divs.toArray().forEach(function (element, index) {

                            var image = new CKEDITOR.dom.element('li');
                            image.setAttribute('onclick', 'selectImg($(this))');
                            image.setAttribute('data-id', 'slicky_item_' + index);

                            var title = element.find('div.slicky-item-title');
                            if (title.count()) {
                                if (title.getItem(0).find('strong').count())
                                    image.setAttribute('data-title', title.getItem(0).find('strong').getItem(0).getText());
                                if (title.getItem(0).find('div.desc').count())
                                    image.setAttribute('data-desc', title.getItem(0).find('div.desc').getItem(0).getText());
                            }
                            var link = element.find('a');
                            if (link.count())
                                image.setAttribute('data-link', link.getItem(0).getAttribute('href'));

                            var divBlock = new CKEDITOR.dom.element('div');
                            divBlock.addClass('block_img');

                            var removeAction = new CKEDITOR.dom.element('i');
                            removeAction.addClass('ti-trash');
                            removeAction.addClass('text-danger');
                            removeAction.on('click', function (element) {
                                image.remove();
                            });

                            var imgSrc = element.find('img');
                            var img = new CKEDITOR.dom.element('img');
                            img.setAttribute('src', imgSrc.getItem(0).getAttribute('src'));

                            divBlock.append(img);
                            image.append(divBlock);
                            image.append(removeAction);

                            slickyUl.append(image);

                        });

                        $("#slicky_sortable").sortable();

                        $("li[data-id=slicky_item_0]").click();
                    }

                }
            },
            contents: [{
                id: "images", label: 'Images', accessKey: "I", elements: [{
                    type: "vbox", padding: 0, children: [{
                        type: "fieldset", label: 'Images', children: [{
                            type: "vbox", padding: 0, children: [{
                                type: "hbox", widths: ["280px", "110px"], children: [
                                    {
                                        type: "button",
                                        id: "browse",
                                        style: "display:inline-block;margin-top:14px;",
                                        align: "center",
                                        label: 'Ajouter une image',
                                        hidden: !0,
                                        filebrowser: "images:txtUrl"
                                    },
                                    {
                                        id: "txtUrl",
                                        type: "text",
                                        label: 'curl',
                                        required: !0,
                                        className: 'hidden',
                                        onChange: function () {
                                            var a = this.getDialog(), b = this.getValue();
                                            if (0 < b.length) {
                                                var a = this.getDialog(), c = a.originalElement;
                                                a.preview && a.preview.removeStyle("display");

                                                var slickyUl = CKEDITOR.document.getById('slicky_sortable');

                                                var newId = 'slicky_item_' + slickyUl.find('li').count();

                                                var image = new CKEDITOR.dom.element('li');
                                                image.setAttribute('onclick', 'selectImg($(this))');
                                                image.setAttribute('data-id', newId);
                                                image.setAttribute('data-title', '');
                                                image.setAttribute('data-desc', '');
                                                image.setAttribute('data-link', '');

                                                var divBlock = new CKEDITOR.dom.element('div');
                                                divBlock.addClass('block_img');

                                                var removeAction = new CKEDITOR.dom.element('i');
                                                removeAction.addClass('ti-trash');
                                                removeAction.addClass('text-danger');
                                                removeAction.on('click', function (element) {
                                                    image.remove();
                                                });

                                                var img = new CKEDITOR.dom.element('img');
                                                img.setAttribute('src', b);

                                                divBlock.append(img);
                                                image.append(divBlock);
                                                image.append(removeAction);

                                                slickyUl.append(image);

                                                $("li[data-id=" + newId + "]").click();

                                                $("#slicky_sortable").animate({scrollLeft: $("#slicky_sortable").get(0).scrollWidth}, 300);
                                            }
                                        },
                                        setup: function (a, b) {
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
                                type: "html",
                                widths: ["280px", "110px"],
                                html: "<div><div id=\"slicky_pictures\">" +
                                    "<ul id='slicky_sortable'>" +
                                    "</ul>" +
                                    "</div></div>",
                                align: "right"
                            }]
                        }]
                    }, {
                        type: "fieldset", className: "slickyParam", label: 'Paramètres de l\'image ', children: [{
                            type: "vbox", padding: "0", children: [{
                                type: "text", className: "hidden", id: "currentId"
                            }, {
                                type: "text", label: "Nom", id: "currentTitle",
                                onKeyUp: function () {
                                    $('#slicky_sortable li.selected').attr('data-title', this.getValue());
                                }
                            }, {
                                type: "text", label: "Description", id: "currentDesc",
                                onKeyUp: function () {
                                    $('#slicky_sortable li.selected').attr('data-desc', this.getValue());
                                }
                            }, {
                                type: "text", label: "Lien", id: "currentLink",
                                onKeyUp: function () {
                                    $('#slicky_sortable li.selected').attr('data-link', this.getValue());
                                }
                            }]
                        }]
                    }]
                }]
            }, {
                id: "settings", label: 'Paramètres', accessKey: "S", elements: [{
                    type: "vbox", children: [
                        {
                            type: "fieldset", label: 'Dimensions', children: [
                                {
                                    type: "hbox", children: [
                                        {
                                            id: "width",
                                            type: "text",
                                            label: "Largeur (en px ou %)",
                                            validate: CKEDITOR.dialog.validate.regex(/^\d*(px|%)$/, 'La largeur n\'est pas au bon format')
                                        },
                                        {
                                            id: "height", type: "text", label: "Hauteur (en px ou %)", default: '150px',
                                            validate: CKEDITOR.dialog.validate.regex(/^\d*(px|%)$/, 'La hauteur n\'est pas au bon format')
                                        }
                                    ]
                                }],
                        },
                        {
                            type: "fieldset", label: 'Animations', children: [
                                {
                                    type: "vbox", children: [
                                        {
                                            type: "hbox", children: [
                                                {
                                                    id: "infinite",
                                                    type: "checkbox",
                                                    label: "Carrousel infini",
                                                    default: true
                                                },
                                                {id: "dots", type: "checkbox", label: "Pagination"},
                                            ]
                                        },
                                        {
                                            type: "hbox", children: [
                                                {
                                                    id: "autoplay",
                                                    type: "checkbox",
                                                    label: "Animation automatique",
                                                    default: true
                                                },
                                                {
                                                    id: "autoplaySpeed",
                                                    type: "text",
                                                    label: "Délai d'animation (en ms)",
                                                    default: 1000,
                                                    validate:CKEDITOR.dialog.validate.number( 'Le délai d\'animation doit être un nombre' )
                                                },
                                            ]
                                        },
                                        {
                                            type: "hbox", children: [
                                                {
                                                    id: "slidesToShow",
                                                    type: "text",
                                                    label: "Nombre d'images en vue",
                                                    validate:CKEDITOR.dialog.validate.number( 'Le nombre d\'image en vue doit être un nombre' ),
                                                    default: 6
                                                },
                                                {
                                                    id: "slidesToScroll",
                                                    type: "text",
                                                    label: "Nombre d'images à décaler",
                                                    validate:CKEDITOR.dialog.validate.number( 'Le nombre d\'images à décaler doit être un nombre' ),
                                                    default: 1
                                                }
                                            ]
                                        },
                                        {
                                            type: "hbox", children: [
                                                {
                                                    id: "speed",
                                                    type: "text",
                                                    label: "Vitesse d'animation (en ms)",
                                                    validate:CKEDITOR.dialog.validate.number( 'La vitesse d\'animation doit être un nombre' ),
                                                    default: 300
                                                },
                                            ]
                                        },
                                        {
                                            type: "hbox", children: [
                                                {id: "fade", type: "checkbox", label: "Fondu"},
                                            ]
                                        }]
                                }],
                        }],
                }],
            }],
            onOk: function () {
                // TODO: Suppression du slicky actuel

                // Action quand on valide le formulaire et on insère dans l'éditeur
                var dialog = this;

                var options = [];
                if (dialog.getValueOf('settings', 'dots')) {
                    options.push('"dots":true');
                }
                if (dialog.getValueOf('settings', 'infinite')) {
                    options.push('"infinite":true');
                }
                if (dialog.getValueOf('settings', 'autoplay')) {
                    options.push('"autoplay":true');
                    if (dialog.getValueOf('settings', 'autoplaySpeed')) {
                        options.push('"autoplaySpeed":' + dialog.getValueOf('settings', 'autoplaySpeed'));
                    } else {
                        options.push('"autoplaySpeed":1000');
                    }
                }
                if (dialog.getValueOf('settings', 'slidesToShow')) {
                    options.push('"slidesToShow":' + dialog.getValueOf('settings', 'slidesToShow'));
                }
                if (dialog.getValueOf('settings', 'slidesToScroll')) {
                    options.push('"slidesToScroll":' + dialog.getValueOf('settings', 'slidesToScroll'));
                }
                if (dialog.getValueOf('settings', 'speed')) {
                    options.push('"speed":' + dialog.getValueOf('settings', 'speed'));
                } else {
                    options.push('"speed":300');
                }
                if (dialog.getValueOf('settings', 'fade')) {
                    options.push('"fade":true');
                }

                var divParent = editor.document.createElement('div');
                divParent.addClass('slicky');
                //divParent.setAttribute('data-slick', '{"slidesToShow":2}');
                divParent.setAttribute('data-slick', '{' + options.join(',') + '}');

                if (dialog.getValueOf('settings', 'height')) {
                    divParent.setStyle('height', dialog.getValueOf('settings', 'height'));
                }
                if (dialog.getValueOf('settings', 'width')) {
                    divParent.setStyle('width', dialog.getValueOf('settings', 'width'));
                } else {
                    divParent.setStyle('width', '100%');
                }

                var images = CKEDITOR.document.getById('slicky_sortable');

                if (images.getChildCount() > 0) {
                    images.getChildren().toArray().forEach(function (element, index) {
                        var child = editor.document.createElement('div');
                        child.addClass('slicky-item-block');
                        child.setHtml(element.getChild(0).getHtml());
                        child.find('img').getItem(0).setStyle('height', dialog.getValueOf('settings', 'height'));

                        if (element.getAttribute('data-title') || element.getAttribute('data-desc')) {
                            var blockTitle = editor.document.createElement('div');
                            blockTitle.addClass('slicky-item-title');
                            blockTitle.setStyle('display', 'none');

                            if (element.getAttribute('data-title')) {
                                var title = editor.document.createElement('strong');
                                title.setText(element.getAttribute('data-title'));
                                blockTitle.append(title);
                            }

                            if (element.getAttribute('data-desc')) {
                                var desc = editor.document.createElement('div');
                                desc.addClass('desc');
                                desc.setText(element.getAttribute('data-desc'));
                                blockTitle.append(desc);
                            }

                            child.append(blockTitle);
                        }

                        if (element.getAttribute('data-link')) {
                            var link = editor.document.createElement('a');
                            link.setAttribute('href', element.getAttribute('data-link'));
                            link.setAttribute('target', "_blank");
                            var html = child.getHtml();
                            link.setHtml(html);
                            child.setHtml('');
                            child.append(link);
                            divParent.append(child);
                        } else {
                            divParent.append(child);
                        }
                    });
                }

                var fakeSlicky = createFakeElement(divParent, 'cke_slicky', 'slicky', true);
                // p.append(edit_btn);
                editor.insertElement(fakeSlicky);
            }
        }
    }
);

selectImg = function (selected) {
    $('#slicky_sortable li').removeClass('selected');
    selected.addClass('selected');
    CKEDITOR.dialog.getCurrent().setValueOf('images', 'currentTitle', selected.attr('data-title'));
    CKEDITOR.dialog.getCurrent().setValueOf('images', 'currentDesc', selected.attr('data-desc'));
    CKEDITOR.dialog.getCurrent().setValueOf('images', 'currentLink', selected.attr('data-link'));
    CKEDITOR.dialog.getCurrent().setValueOf('images', 'currentId', selected.attr('data-id'));
}

function createFakeElement(realElement, className, type, resizable) {
    var attributes = {
        "class": className,
        "data-cke-realelement": encodeURIComponent(realElement.getOuterHtml()),
        "data-cke-real-node-type": 1,
        'data-cke-real-element-type': type,
        "data-cke-resizable": resizable,
        "style": "width:" + realElement.getStyle('width') + ";height:" + realElement.getStyle('height'),
        "src": "data:image/gif;base64,R0lGODlhAQABAPABAP///wAAACH5BAEKAAAALAAAAAABAAEAAAICRAEAOw=="
    };

    return new CKEDITOR.dom.element("img").setAttributes(attributes);
}