(() => {
    'use strict';

    function initialize() {
        document.querySelectorAll('[data-banner-editor]').forEach((editor) => {
            if (editor.dataset.ready) { return; }
            editor.dataset.ready = 'true';
            const list = editor.querySelector('[data-banner-items]');
            const template = editor.querySelector('[data-banner-template]');
            const add = editor.querySelector('[data-banner-add]');
            const objectUrls = new Map();

            function update() {
                Array.from(list.children).forEach((item, index) => {
                    item.querySelector('[data-banner-label]').textContent = 'Banner ' + (index + 1);
                    item.querySelectorAll('[data-banner-field]').forEach((field) => {
                        const key = field.dataset.bannerField;
                        field.name = `data[columns][${key}][${index}]` + (key === 'categories' ? '[]' : '');
                        if (key !== 'categories') { field.id = `banner-${key}-${index}`; }
                    });
                    item.querySelectorAll('[data-banner-label-for]').forEach((label) => { label.htmlFor = `banner-${label.dataset.bannerLabelFor}-${index}`; });
                    item.querySelector('[data-banner-remove]').disabled = list.children.length === 1;
                });
                add.disabled = list.children.length >= 4;
                editor.querySelector('[data-banner-count]').textContent = `${list.children.length} of 4 banners`;
            }

            add.addEventListener('click', () => {
                if (list.children.length >= 4) { return; }
                const item = template.content.firstElementChild.cloneNode(true);
                list.append(item);
                update();
                item.querySelector('input[type="file"]').focus();
            });
            list.addEventListener('click', (event) => {
                const button = event.target.closest('[data-banner-remove]');
                if (button && list.children.length > 1) {
                    const item = button.closest('[data-banner-item]');
                    item.querySelectorAll('input[type="file"]').forEach((input) => {
                        if (objectUrls.has(input)) { URL.revokeObjectURL(objectUrls.get(input)); objectUrls.delete(input); }
                    });
                    item.remove(); update(); add.focus();
                }
            });
            list.addEventListener('change', (event) => {
                const input = event.target;
                if (input.type !== 'file' || !input.files[0]) { return; }
                if (objectUrls.has(input)) { URL.revokeObjectURL(objectUrls.get(input)); }
                const fileUrl = URL.createObjectURL(input.files[0]);
                objectUrls.set(input, fileUrl);
                const kind = input.dataset.bannerField.startsWith('desktop') ? 'desktop' : 'mobile';
                const preview = input.closest('[data-banner-item]').querySelector(`[data-banner-preview="${kind}"]`);
                preview.src = fileUrl; preview.hidden = false; preview.style.display = 'block';
            });
            editor.closest('form').addEventListener('submit', update);
            update();
        });
    }

    if (document.readyState === 'loading') { document.addEventListener('DOMContentLoaded', initialize, { once: true }); }
    else { initialize(); }
})();
