'use strict';

import Component from '../components/sidebar/Component';

describe('a component', () => {
    test('gets triggers as elements', () => {
        document.body.innerHTML =
            '<div data-container><div data-component><a data-trigger href="/foobar"></a><a data-trigger href="/other"></a></div></div>';

        const component = new Component(
            document.querySelector('[data-container]'),
            document.querySelector('[data-component]'),
            ['[data-trigger]']
        );

        expect(component.getTriggerElements()).toBeInstanceOf(Array);
        expect(component.getTriggerElements()).toHaveLength(2);

        expect(component.getTriggerElements()[0].getAttribute('href')).toBe('/foobar');
        expect(component.getTriggerElements()[1].getAttribute('href')).toBe('/other');
    });

    test('recrawls DOM to get trigger elements', () => {
        document.body.innerHTML =
            '<div data-container><div data-component><a data-trigger href="/foobar"></a><a data-trigger href="/other"></a></div></div>';

        const component = new Component(
            document.querySelector('[data-container]'),
            document.querySelector('[data-component]'),
            ['[data-trigger]']
        );

        expect(component.getTriggerElements()).toBeInstanceOf(Array);
        expect(component.getTriggerElements()).toHaveLength(2);

        // Add a third trigger
        document
            .querySelector('[data-component]')
            .insertAdjacentHTML('beforeend', '<a data-trigger href="/third"></a>');

        expect(component.getTriggerElements()).toHaveLength(3);
    });
});
