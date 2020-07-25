/*
*
*   Plugin developed by Dimitri Conejo
*   www.dimitriconejo.com
*
*/

CKEDITOR.plugins.add('videodetector', {
    requires: "dialog,fakeobjects",
    icons: 'videodetector',
    init: function (editor) {

        editor.addContentsCss('/assets/common/videodetector/css/videodetector.css');

        editor.addCommand('videodetector', new CKEDITOR.dialogCommand('videoDialog'));
        editor.ui.addButton('VideoDetector', {
            label: 'Ajouter une vidéo Youtube, Vimeo ou Dailymotion',
            command: 'videodetector',
            icon: CKEDITOR.plugins.getPath('videodetector') + 'icons/icon_black.png'
        });

        CKEDITOR.dialog.add('videoDialog', this.path + 'dialogs/videoDialog.js');

        var modifyVideoCommand = {
            label: 'Modification de la vidéo',
            command: "videodetector",
            group: 'video',
            icon: CKEDITOR.plugins.getPath('videodetector') + 'icons/icon_black.png',
            order: 1
        };

        editor.contextMenu.addListener(function (element, selection) {
            if (element.getParents().find(element => isVideo(element))) {
                return {
                    modifyVideoCommand: CKEDITOR.TRISTATE_OFF
                };
            }
        });

        editor.addMenuGroup('video', 2);
        editor.addMenuItems(
            {'modifyVideoCommand': modifyVideoCommand}
        );

        editor.on("doubleclick", function (b) {
            if (isVideo(b.data.element)) {
                b.data.dialog = 'videoDialog';
            }
        });

    },
    afterInit: function (editor) {
        function createFakeParserElement(realElement, className, type, resizable) {
            var attributes = {
                "class": className,
                "data-cke-realelement": encodeURIComponent(realElement.getOuterHtml()),
                "data-cke-real-node-type": 1,
                'data-cke-real-element-type': type,
                "data-cke-resizable": resizable,
                "src": realElement.attributes.thumbnail,
                "videosrc": realElement.attributes.videosrc,
                "style": "width:" + realElement.attributes.width + "px;height:" + realElement.attributes.height + "px"
            };
            return new CKEDITOR.htmlParser.element("img", attributes);
        }

        var dataProcessor = editor.dataProcessor,
            dataFilter = dataProcessor && dataProcessor.dataFilter;
        if (dataFilter) {
            dataFilter.addRules(
                {
                    elements:
                        {
                            'iframe': function (element) {
                                var attributes = element.attributes;

                                console.log(attributes);

                                if (attributes.class === 'video') {
                                    return createFakeParserElement(element, "cke_video", "video", !0);
                                }
                                return null;

                            }
                        }
                },
                5);
        }
    }

});

isVideo = function (element) {
    return (element.is('iframe') && element.hasClass('video'))|| (element.is('img') && element.hasClass('cke_video'));
}