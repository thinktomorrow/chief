// Make navigation collapsible
// Toggle to switch between collapsed and open state (default is collapsed, so maybe rename to expandable instead?)
// --> normal state and collapsed state
// Icons are always visible, other elements are hidden and showing them expands the navigation
// The navigation in expanded state has a fixed width
// If collapsed, clicking a navigation item with children will open a dropdown to the right
// If expanded, clicking a navigation item will show the underlying items under it (without dropdown)
// On mobile, the navigation is always expanded and works like a normal mobile navigation with hamburger icon
const initCollapsibleNavigation = (
    containerSelector = '[data-collapsible-navigation]',
    toggleSelector = '[data-toggle-collapsible-navigation]',
    collapsingElementSelector = '[data-hide-on-collapse]'
) => {
    const container = document.querySelector(containerSelector);
    const toggle = document.querySelector(toggleSelector);
    const collapsingElements = Array.from(document.querySelectorAll(collapsingElementSelector));

    toggle.addEventListener('click', () => {
        container.classList.toggle('w-64');

        collapsingElements.forEach((element) => {
            element.classList.toggle('hidden');
        });
    });

    console.log(container, toggle, collapsingElements);
};

export { initCollapsibleNavigation as default };
