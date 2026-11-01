(() => {
    'use strict';

    function initialize() {
        document.querySelectorAll('[data-trust-editor]').forEach((editor) => {
            if (editor.dataset.initialized === 'true') {
                return;
            }
            editor.dataset.initialized = 'true';
            const list = editor.querySelector('[data-trust-items]');
            const template = editor.querySelector('[data-trust-template]');
            const addButton = editor.querySelector('[data-trust-add]');
            const iconTemplates = document.querySelector('[data-trust-icon-templates]');

            function update() {
                const items = Array.from(list.children);
                items.forEach((item, index) => {
                    item.querySelector('[data-trust-item-title]').textContent = 'Service item ' + (index + 1);
                    item.querySelectorAll('[data-trust-field]').forEach((field) => {
                        const key = field.dataset.trustField;
                        field.name = `services[items][${index}][${key}]`;
                        if (field.type !== 'hidden') {
                            field.id = `trust-${key}-${index}`;
                        }
                        if (key === 'order') {
                            field.value = index;
                        }
                    });
                    item.querySelectorAll('[data-trust-label]').forEach((label) => {
                        label.htmlFor = `trust-${label.dataset.trustLabel}-${index}`;
                    });
                    item.querySelector('[data-trust-up]').disabled = index === 0;
                    item.querySelector('[data-trust-down]').disabled = index === items.length - 1;
                    item.querySelector('[data-trust-remove]').disabled = items.length === 1;
                });
                addButton.disabled = items.length >= 12;
                editor.querySelector('[data-trust-count]').textContent = `${items.length} of 12 items`;
            }

            addButton.addEventListener('click', () => {
                if (list.children.length >= 12) {
                    return;
                }
                const item = template.content.firstElementChild.cloneNode(true);
                list.appendChild(item);
                update();
                item.querySelector('[data-trust-field="title"]').focus();
            });

            list.addEventListener('click', (event) => {
                const button = event.target.closest('button');
                const item = button?.closest('[data-trust-item]');
                if (!item) {
                    return;
                }
                if (button.hasAttribute('data-trust-up') && item.previousElementSibling) {
                    list.insertBefore(item, item.previousElementSibling);
                } else if (button.hasAttribute('data-trust-down') && item.nextElementSibling) {
                    list.insertBefore(item.nextElementSibling, item);
                } else if (button.hasAttribute('data-trust-remove') && list.children.length > 1) {
                    const neighbor = item.nextElementSibling || item.previousElementSibling;
                    item.remove();
                    neighbor.querySelector('[data-trust-field="title"]').focus();
                }
                update();
            });

            list.addEventListener('change', (event) => {
                if (event.target.dataset.trustField !== 'icon' || !iconTemplates) {
                    return;
                }
                const icon = iconTemplates.content.querySelector(`[data-icon="${event.target.value}"]`);
                if (icon) {
                    const preview = event.target.closest('[data-trust-item]').querySelector('[data-trust-icon-preview]');
                    preview.replaceChildren(icon.firstElementChild.cloneNode(true));
                }
            });

            editor.closest('form').addEventListener('submit', update);
            update();
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initialize, { once: true });
    } else {
        initialize();
    }
})();
