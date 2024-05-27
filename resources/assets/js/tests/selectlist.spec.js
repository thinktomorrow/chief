describe('selectlist component', () => {
    beforeEach(() => {});

    test('can be constructed', () => {
        document.body.innerHTML = '<div id="js-sidebar-container">SIDEBAR CONTENT</div>';

        const sidebar = new Sidebar2();

        expect(sidebar).toBeInstanceOf(Sidebar2);
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
