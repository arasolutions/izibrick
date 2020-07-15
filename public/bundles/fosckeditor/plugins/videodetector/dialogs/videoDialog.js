/*
*
*   Plugin developed by Dimitri Conejo
*   www.dimitriconejo.com
*
*/

CKEDITOR.dialog.add('videoDialog', function (editor) {
    return {
        title: 'Ajouter une vidéo Youtube, Vimeo ou Dailymotion',
        minWidth: 400,
        minHeight: 100,
        contents: [
            {
                id: 'tab-basic',
                label: 'Basic Settings',
                elements: [
                    {
                        type: 'vbox',
                        padding: 0,
                        children: [{
                            type: 'text',
                            id: 'url_video',
                            label: 'URL Youtube, Vimeo, Dailymotion URL ou embed code',
                            validate: CKEDITOR.dialog.validate.notEmpty("Empty!")
                        }, {
                            type: 'hbox',
                            padding: 0,
                            children: [{
                                type: 'text',
                                id: 'width',
                                style: "display:inline-block",
                                label: 'Largeur (en px)',
                                validate: CKEDITOR.dialog.validate.number("La largeur doit être un nombre.")
                            }, {
                                type: 'text',
                                id: 'height',
                                style: "display:inline-block",
                                label: 'Hauteur (en px)',
                                validate: CKEDITOR.dialog.validate.number("La hauteur doit être un nombre.")
                            }]
                        }]
                    }
                ]
            }
        ],
        onShow: function () {
            var dialog = this;
            var a = this.getParentEditor(), b = a.getSelection(),
                c = b.getStartElement();
            console.log(c);
            if (c.is('img') && c.hasClass('cke_video')) {
                console.log(c.getAttributes());
                var url = c.getAttribute('data-video-src');
                dialog.setValueOf('tab-basic', 'url_video', url);
                dialog.setValueOf('tab-basic', 'width', c.getStyle('width').substr(0, c.getStyle('width').length - 2));
                dialog.setValueOf('tab-basic', 'height', c.getStyle('height').substr(0, c.getStyle('height').length - 2));
            } else {
                dialog.setValueOf('tab-basic', 'width', 640);
                dialog.setValueOf('tab-basic', 'height', 360);
            }
        },
        onOk: function () {
            var dialog = this;


            //detectamos el video
            var respuesta = detectar(dialog);
            var url = "";
            var thumbnail = "";

            if (respuesta.reproductor == "youtube") {
                url = "https://www.youtube.com/embed/" + respuesta.id_video + "?autohide=1&showinfo=0";
                thumbnail = "https://img.youtube.com/vi/" + respuesta.id_video + "/maxresdefault.jpg";
            } else if (respuesta.reproductor == "vimeo") {
                url = "https://player.vimeo.com/video/" + respuesta.id_video + "?portrait=0";
                thumbnail = "fuck";
            } else if (respuesta.reproductor == "dailymotion") {
                url = "https://www.dailymotion.com/embed/video/" + respuesta.id_video;
                thumbnail = "https://www.dailymotion.com/thumbnail/video/" + respuesta.id_video;
            }

            var iframe = new CKEDITOR.dom.element('iframe');
            iframe.setAttribute('src', url);
            iframe.setAttribute('data-video-src', dialog.getValueOf('tab-basic', 'url_video'));
            iframe.setAttribute('frameborder', '0');
            iframe.addClass('video');
            iframe.setAttribute('height', dialog.getValueOf('tab-basic', 'height'));
            iframe.setAttribute('width', dialog.getValueOf('tab-basic', 'width'));
            iframe.setAttribute('thumbnail', thumbnail);

            var newFakeImage = createFakeElement(iframe, 'cke_video', 'video', true);
            // p.append(edit_btn);
            editor.insertElement(newFakeImage);
            editor.insertElement(new CKEDITOR.dom.element('p'));
        }
    };
});


//funcion para detectar el id y la plataforma (youtube, vimeo o dailymotion) de los videos
function detectar(dialog) {
    var getDialog = dialog.parts.dialog;
    var url = getDialog.find('input').getItem(0).getValue();
    var id = '';
    var reproductor = '';
    var url_comprobar = '';

    if (url.indexOf('youtu.be') >= 0) {
        reproductor = 'youtube';
        id = url.substring(url.lastIndexOf("/") + 1, url.length);
    }
    if (url.indexOf("youtube") >= 0) {
        reproductor = 'youtube'
        if (url.indexOf("</iframe>") >= 0) {
            var fin = url.substring(url.indexOf("embed/") + 6, url.length)
            id = fin.substring(fin.indexOf('"'), 0);
        } else {
            if (url.indexOf("&") >= 0)
                id = url.substring(url.indexOf("?v=") + 3, url.indexOf("&"));
            else
                id = url.substring(url.indexOf("?v=") + 3, url.length);
        }
        url_comprobar = "https://gdata.youtube.com/feeds/api/videos/" + id + "?v=2&alt=json";
        //"https://gdata.youtube.com/feeds/api/videos/" + id + "?v=2&alt=json"
    }
    if (url.indexOf("vimeo") >= 0) {
        reproductor = 'vimeo'
        if (url.indexOf("</iframe>") >= 0) {
            var fin = url.substring(url.lastIndexOf('vimeo.com/"') + 6, url.indexOf('>'))
            id = fin.substring(fin.lastIndexOf('/') + 1, fin.indexOf('"', fin.lastIndexOf('/') + 1))
        } else {
            id = url.substring(url.lastIndexOf("/") + 1, url.length)
        }
        url_comprobar = 'http://vimeo.com/api/v2/video/' + id + '.json';
        //'http://vimeo.com/api/v2/video/' + video_id + '.json';
    }
    if (url.indexOf('dai.ly') >= 0) {
        reproductor = 'dailymotion';
        id = url.substring(url.lastIndexOf("/") + 1, url.length);
    }
    if (url.indexOf("dailymotion") >= 0) {
        reproductor = 'dailymotion';
        if (url.indexOf("</iframe>") >= 0) {
            var fin = url.substring(url.indexOf('dailymotion.com/') + 16, url.indexOf('></iframe>'))
            id = fin.substring(fin.lastIndexOf('/') + 1, fin.lastIndexOf('"'))
        } else {
            if (url.indexOf('_') >= 0)
                id = url.substring(url.lastIndexOf('/') + 1, url.indexOf('_'))
            else
                id = url.substring(url.lastIndexOf('/') + 1, url.length);
        }
        url_comprobar = 'https://api.dailymotion.com/video/' + id;
        // https://api.dailymotion.com/video/x26ezrb
    }
    return {'reproductor': reproductor, 'id_video': id};
}

function createFakeElement(realElement, className, type, resizable) {
    console.log(realElement);
    var attributes = {
        "class": className,
        "data-cke-realelement": encodeURIComponent(realElement.getOuterHtml()),
        "data-cke-real-node-type": 1,
        'data-cke-real-element-type': type,
        "data-cke-resizable": resizable,
        "src": realElement.getAttribute("thumbnail"),
        "data-video-src": realElement.getAttribute("data-video-src"),
        "style": "width:" + realElement.getAttribute("width") + "px;height:" + realElement.getAttribute("height") + "px"
    };
    return new CKEDITOR.dom.element("img").setAttributes(attributes);
}