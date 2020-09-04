/**
 * @license Copyright (c) 2003-2019, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */


CKEDITOR.editorConfig = function (config) {
    config.skin = 'office2013';
    config.extraPlugins = 'background,slider,banner,cards,videodetector,resetcontent,slicky,iziButton,iziGrid';
    config.height = '600px';
    config.toolbarGroups = [
        {name: 'document', groups: ['mode', 'document', 'doctools']},
        {name: 'clipboard', groups: ['clipboard', 'undo']},
        {name: 'editing', groups: ['find', 'selection', 'spellchecker', 'editing']},
        {name: 'forms', groups: ['forms']},
        '/',
        {name: 'basicstyles', groups: ['basicstyles', 'cleanup']},
        {name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi', 'paragraph']},
        {name: 'links', groups: ['links']},
        {name: 'insert', groups: ['insert']},
        '/',
        {name: 'styles', groups: ['styles']},
        {name: 'colors', groups: ['colors']},
        {name: 'tools', groups: ['tools']},
        {name: 'others', groups: ['others']},
        {name: 'about', groups: ['about']}
    ];

    config.removeButtons = 'Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Save,NewPage,Preview,Print,About';

    config.contentsCss = [CKEDITOR.getUrl("contents.css"), '/assets/common/bootstrap/css/bootstrap.min.css', '/assets/plugins/themify-icons/themify-icons.css', '/assets/bo/css/wysiwyg.css'];

    config.extraAllowedContent = 'p[id];ol[data-*](*);li[data-*](*);div[data-*][style];a(*)[type,href,data-*,role];span(*)[aria-hidden];input[placeholder];section(*)[*]{*};h4(*)[*];ul(*)[*];iframe(*)[*];img[data-*];button(*)[*]{*}';
};
CKEDITOR.dtd.$removeEmpty['span'] = false;
CKEDITOR.dtd.$removeEmpty['p'] = false;

CKEDITOR.addCss(':root {--colorTheme: ' + getComputedStyle(document.documentElement).getPropertyValue("--colorTheme") + ';--textColor: ' + getComputedStyle(document.documentElement).getPropertyValue("--textColor") + ';}');