CKEDITOR.plugins.add('background', {
    icons: 'background',
    init: function (editor) {
        editor.addCommand('changeBg', {
            exec: function (editor) {
                var iframe = $('#cke_' + editor.name + ' iframe')[0];
                var style = iframe.contentWindow.document.children[0].children[1].style;
                if (style.backgroundColor === 'rgb(50, 50, 50)') {
                    style.backgroundColor = 'rgb(255, 255, 255)';
                    this.setState(CKEDITOR.TRISTATE_OFF);
                } else {
                    style.backgroundColor = 'rgb(50, 50, 50)';
                    this.setState(CKEDITOR.TRISTATE_ON);
                }
            }
        });
        editor.ui.addButton('Background', {
            label: 'Passer le fond en foncé',
            command: 'changeBg',
            toolbar: 'insert'
        });
    }
});