CKEDITOR.dialog.add('addIziGridDialog', function (editor) {
        return {
            title: 'Propriétés de la grille',
            minWidth: 700,
            minHeight: 400,
            getModel: function (a) {
                var b = (a = a.getSelection().getSelectedElement()) && "img" === a.getName(),
                    c = a && "input" === a.getName() && "image" === a.getAttribute("type");
                return b || c ? a : null;
            },
            onShow: function () {
                var a = this.getParentEditor(), b = a.getSelection(),
                    c = b.getStartElement();

                var dialog = this;

                var render = CKEDITOR.document.getById('izigrid-render');


                var parent = c.getParents().find(element => element.hasClass('izigrid'));
                if(parent) {
                    render.setAttribute('data-id-original', parent.getAttribute('id'));
                    render.setHtml("");

                    parent.getChildren().toArray().forEach(function (rowOriginal, index) {
                        var row = editor.document.createElement('div');
                        row.addClass('dialog-row');

                        rowOriginal.getChildren().toArray().forEach(function (colOriginal, index) {
                            var col = editor.document.createElement('div');
                            var size = colOriginal.getAttribute('data-size');
                            col.addClass('dialog-col-' + size);
                            col.setAttribute('data-size', size);
                            col.setAttribute('data-id-original', colOriginal.getAttribute('id'));
                            col.setAttribute('onclick', 'selectCell(this)');
                            col.setHtml('<div></div>');
                            row.append(col);
                        });

                        render.append(row);
                    });
                }else{
                    render.setHtml("");
                    $('.dialog-izigrid-render-desktop').append(newLine());
                }

            },
            contents: [{
                id: "iziGridDesktop", label: 'Vue Bureau', accessKey: "B", elements: [{
                    type: "vbox", children: [{
                        type: "hbox", children: [{
                            type: "fieldset", label: 'Lignes', children: [{
                                type: "html",
                                html: "" +
                                    "<div class='dialog-izigrid-block-lines'>" +
                                    "<div class='block'><button class='dialog-izigrid-btn' onclick='insertLine(true)'><img src='" + CKEDITOR.plugins.getPath('iziGrid') + 'icons/insertLineBefore.png' + "'/></button><div class='text'>Insérer avant</div></div>" +
                                    "<div class='block'><button class='dialog-izigrid-btn' onclick='insertLine(false);'><img src='" + CKEDITOR.plugins.getPath('iziGrid') + 'icons/insertLineAfter.png' + "'/></button><div class='text'>Insérer après</div></div>" +
                                    "<div class='block'><button class='dialog-izigrid-btn' onclick='removeLine();'><img src='" + CKEDITOR.plugins.getPath('iziGrid') + 'icons/removeLine.png' + "'/></button><div class='text'>Supprimer</div></div>" +
                                    "</div>"
                            }]
                        },
                            {
                                type: "fieldset", label: 'Colonnes', children: [{
                                    type: "html",
                                    html: "" +
                                        "<div class='dialog-izigrid-block-cells'>" +
                                        "<div class='block'><button class='dialog-izigrid-btn' onclick='reduceCol();'><img src='" + CKEDITOR.plugins.getPath('iziGrid') + 'icons/reduceCol.png' + "'/></button><div class='text'>Réduire</div></div>" +
                                        "<div class='block'><button class='dialog-izigrid-btn' onclick='extendCol();'><img src='" + CKEDITOR.plugins.getPath('iziGrid') + 'icons/extendCol.png' + "'/></button><div class='text'>Agrandir</div></div>" +
                                        "<div class='block'><button class='dialog-izigrid-btn' onclick='addCol(true);'><img src='" + CKEDITOR.plugins.getPath('iziGrid') + 'icons/addColBefore.png' + "'/></button><div class='text'>Insérer avant</div></div>" +
                                        "<div class='block'><button class='dialog-izigrid-btn' onclick='addCol(false);'><img src='" + CKEDITOR.plugins.getPath('iziGrid') + 'icons/addColAfter.png' + "'/></button><div class='text'>Insérer après</div></div>" +
                                        "<div class='block'><button class='dialog-izigrid-btn' onclick='removeCol();'><img src='" + CKEDITOR.plugins.getPath('iziGrid') + 'icons/removeCol.png' + "'/></button><div class='text'>Supprimer</div></div>" +
                                        "</div>"
                                }]
                            }]

                    }, {
                        type: "fieldset", label: 'Rendu', children: [{
                            type: "html",
                            html:
                                "<div class='dialog-izigrid-ruler'>" +
                                "<div class='dialog-row'>" +
                                "<div class='dialog-col-1'><div></div></div>" +
                                "<div class='dialog-col-1'><div></div></div>" +
                                "<div class='dialog-col-1'><div></div></div>" +
                                "<div class='dialog-col-1'><div></div></div>" +
                                "<div class='dialog-col-1'><div></div></div>" +
                                "<div class='dialog-col-1'><div></div></div>" +
                                "<div class='dialog-col-1'><div></div></div>" +
                                "<div class='dialog-col-1'><div></div></div>" +
                                "<div class='dialog-col-1'><div></div></div>" +
                                "<div class='dialog-col-1'><div></div></div>" +
                                "<div class='dialog-col-1'><div></div></div>" +
                                "<div class='dialog-col-1'><div></div></div>" +
                                "</div>" +
                                "</div>" +
                                "<div class='dialog-izigrid-render-desktop' id='izigrid-render'></div>" +
                                "<div class='dialog-izigrid-ruler'>" +
                                "<div class='dialog-row'>" +
                                "<div class='dialog-col-1'><div></div></div>" +
                                "<div class='dialog-col-1'><div></div></div>" +
                                "<div class='dialog-col-1'><div></div></div>" +
                                "<div class='dialog-col-1'><div></div></div>" +
                                "<div class='dialog-col-1'><div></div></div>" +
                                "<div class='dialog-col-1'><div></div></div>" +
                                "<div class='dialog-col-1'><div></div></div>" +
                                "<div class='dialog-col-1'><div></div></div>" +
                                "<div class='dialog-col-1'><div></div></div>" +
                                "<div class='dialog-col-1'><div></div></div>" +
                                "<div class='dialog-col-1'><div></div></div>" +
                                "<div class='dialog-col-1'><div></div></div>" +
                                "</div>" +
                                "</div>"
                        }]
                    }]
                }]
            }],
            onOk: function () {
                // Action quand on valide le formulaire et on insère dans l'éditeur
                var dialog = this;

                var block = editor.document.createElement('div');
                block.addClass('izigrid');
                block.setAttribute('id', 'izigrid_block_' + Math.ceil(Math.random() * 10000));

                var render = CKEDITOR.document.getById('izigrid-render');
                if (render.getChildCount() > 1) {
                    render.getChildren().toArray().forEach(function (rowRef, index) {
                        var row = editor.document.createElement('div');
                        row.addClass('row');
                        row.setAttribute('id', 'izigrid_row_' + Math.ceil(Math.random() * 10000));

                        rowRef.getChildren().toArray().forEach(function (colRef, index) {
                            var col = editor.document.createElement('div');
                            var size = colRef.getAttribute('data-size');
                            var idOriginal = colRef.getAttribute('data-id-original');
                            col.addClass('col-md-' + size);
                            col.setAttribute('data-size', size);
                            if (idOriginal) {
                                col.setHtml(editor.document.getById(idOriginal).getHtml());
                            } else {
                                col.setHtml('&nbsp;');
                            }
                            col.setAttribute('id', 'izigrid_col_' + Math.ceil(Math.random() * 10000));
                            row.append(col);
                        });

                        block.append(row);

                    });
                }

                if (render.getAttribute('data-id-original')) {
                    editor.document.getById(render.getAttribute('data-id-original')).remove();
                }

                editor.insertElement(block);

            }
        }
            ;
    }
);

selectCell = function (cell) {
    $('.dialog-izigrid-render-desktop').children().children().removeClass('selected');
    $(cell).addClass('selected');
};

removeLine = function () {
    $('.dialog-izigrid-render-desktop').find('.selected').parent().remove();
};

insertLine = function (before) {
    var rowRef = $('.dialog-izigrid-render-desktop').find('.selected').parent();
    if (rowRef.length) {
        if (before) {
            $(newLine()).insertBefore(rowRef);
        } else {
            $(newLine()).insertAfter(rowRef);
        }
    } else {
        $('.dialog-izigrid-render-desktop').append(newLine());
    }
};

reduceCol = function () {
    var cell = $('.selected');
    var size = cell.data('size');
    if (size > 1) {
        changeClass(cell, size - 1);
    }
};

extendCol = function () {
    var cell = $('.selected');
    var size = cell.data('size');
    if (size < 12) {
        changeClass(cell, size + 1);
    }
};

addCol = function (before) {
    var cell = $('.selected');
    if (cell.length) {
        if (before) {
            $(newCol()).insertBefore(cell);
        } else {
            $(newCol()).insertAfter(cell);
        }
    }
};

removeCol = function () {
    var cell = $('.selected');
    if (cell.parent().children().length === 1) {
        cell.parent().remove();
    } else {
        cell.remove();
    }
};

changeClass = function (cell, size) {
    $(cell).data('size', size);
    $(cell).attr('data-size', size);
    $(cell).removeClass(function (index, className) {
        return (className.match(/(^|\s)dialog-col-\S+/g) || []).join(' ');
    });
    $(cell).addClass('dialog-col-' + size);
};

newLine = function () {
    return "<div class='dialog-row'>" +
        "<div class='dialog-col-6' data-size='6' onclick='selectCell(this)'><div></div></div>" +
        "<div class='dialog-col-6' data-size='6' onclick='selectCell(this)'><div></div></div>" +
        "</div>";
};

newCol = function () {
    return "<div class='dialog-col-6' data-size='6' onclick='selectCell(this)'><div></div></div>";
};