import Sidebar2 from '../components/sidebar/Sidebar2';

global.fetch = require('jest-fetch-mock');

describe('a sidebar', () => {
    beforeEach(() => {
        fetch.resetMocks();
    });

    test('can be constructed', () => {
        document.body.innerHTML = '<div id="js-sidebar-container">SIDEBAR CONTENT</div>';

        const sidebar = new Sidebar2();

        expect(sidebar).toBeInstanceOf(Sidebar2);
    });

    test('fails if sidebar element is not present in DOM', () => {
        document.body.innerHTML = '<div></div>';

        const triggerError = () => {
            new Sidebar2();
        };

        expect(triggerError).toThrowError('No sidebar container element found in DOM.');
    });

    test('clicking trigger opens panel in sidebar', async () => {
        fetch.mockResponse('MOCKED RESPONSE');

        document.body.innerHTML = `${
            '<a id="trigger" href="foobar"></a>' + '<div id="js-sidebar-container"></div>'
        }${sidebarTemplate}`;

        const sidebar = new Sidebar2({
            triggerSelector: '#trigger',
        });

        sidebar.listenForEventsInDocument();

        await document.getElementById('trigger').click();

        expect(fetch.mock.calls.length).toEqual(1);
        const sidebarEl = document.body.querySelector('#js-sidebar-container');

        expect(sidebarEl.innerHTML).toEqual('fake response');

        // return Promise.resolve()
        //     .then(() => {
        //
        //     })
        //     .then(() => {
        //
        //     });
    });
});

const sidebarTemplate =
    '<template id="js-sidebar-template">' +
    '<div data-sidebar style="display:none;">' +
    '<div data-sidebar-backdrop data-sidebar-close></div>' +
    '<aside data-sidebar-aside><div data-sidebar-close data-sidebar-close-button></div>' +
    '<div data-sidebar-content><!-- panel content --></div>' +
    '</aside></div></template>' +
    '<template id="js-sidebar-close-button"><span></span></template>';
