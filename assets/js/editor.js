(function () {
    var iconSelect = document.querySelector('.poiju-icon-select');
    var iconPreview = document.querySelector('.poiju-icon-preview');

    iconSelect.addEventListener('change', function () {
        var icon = iconSelect.options[this.selectedIndex].value;
        iconPreview.classList.add('poiju-icon-preview--loading');
        iconPreview.addEventListener('load', function () {
            iconPreview.classList.remove('poiju-icon-preview--loading');
        });
        iconPreview.src = poijuEditorData.iconUrls[icon].url;
        iconPreview.srcset = poijuEditorData.iconUrls[icon].urlHidpi + ' 2x';
    });
})();
