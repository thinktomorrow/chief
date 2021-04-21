const initNavigation = function (
    itemSelector = '[data-navigation-item]',
    itemLabelSelector = '[data-navigation-item-label]',
    itemContentSelector = '[data-navigation-item-content]'
) {
    const items = Array.from(document.querySelectorAll(itemSelector));

    items.forEach((item) => {
        const itemLabel = item.querySelector(itemLabelSelector);
        const itemContent = item.querySelector(itemContentSelector);

        itemLabel.addEventListener('click', () => {
            if (itemContent.style.display === 'none') {
                itemContent.style.removeProperty('display');
            } else {
                itemContent.style.display = 'none';
            }
        });
    });
};

initNavigation();
