<div data-sidebar class="fixed inset-0" style="z-index: 50; display: none;">
    <div data-sidebar-backdrop data-sidebar-close class="absolute inset-0 bg-black opacity-25 fade-in"></div>

    <aside data-sidebar-content class="absolute top-0 right-0 bottom-0 bg-white shadow-lg p-16 slide-from-right overflow-auto">
        <div data-sidebar-close class="absolute top-0 right-0 m-4 cursor-pointer">
            <div class="rounded-full p-1 text-grey-500 bg-grey-50 hover:bg-grey-100 transition duration-150 ease-in-out">
                <svg width="16" height="16"><use xlink:href="#x"></use></svg>
            </div>
        </div>

        <div>
            {{ $slot }}
        </div>
    </aside>
</div>

@push('custom-scripts-after-vue')
    <script>
        class Sidebar {
            constructor(sidebarElementSelector) {
                this.sidebar = document.querySelector(sidebarElementSelector);
                this.sidebarBackdrop = this.sidebar.querySelector('[data-sidebar-backdrop]');
                this.sidebarContent = this.sidebar.querySelector('[data-sidebar-content]');

                Array.from(this.sidebar.querySelectorAll('[data-sidebar-close]')).forEach((el)=>{
                    el.addEventListener('click', this.close.bind(this));
                });
            }

            dom() {
                return this.sidebarContent;
            }

            open() {
                this.sidebar.style.display = "block";
            }

            close() {
                Promise.all([
                    this._closeElement(this.sidebarBackdrop, 'fade-in'),
                    this._closeElement(this.sidebarContent, 'slide-from-right')
                ]).then(() => {
                    this.sidebar.style.display = "none";
                }).catch((error) => {
                    console.log(error);
                });
            }

            _closeElement(element, animationName) {
                return new Promise((resolve, reject) => {
                    try {
                        element.style.animationDirection = 'reverse';
                        element.classList.remove(animationName);
                        void element.offsetWidth;
                        element.classList.add(animationName);

                        const onAnimationEnd = () => {
                            element.style.animationDirection = 'normal';
                            element.removeEventListener('animationend', onAnimationEnd);

                            resolve();
                        }

                        element.addEventListener('animationend', onAnimationEnd);
                    } catch(error) {
                        reject(error);
                    }
                });
            }
        }

        class SidebarPanels {
            constructor(sidebar, events, loadContentCallback, submitCallback) {
                this.sidebar = sidebar;
                this.events = events;
                this.loadContentCallback = loadContentCallback;
                this.submitCallback = submitCallback;

                this.panels = [];
                this.activePanel = null;
            }

            show(url) {
                const id = encodeURIComponent(url);

                // else load content.
                if(this._find(id)) {
                    // if present in panels, than show this panel.
                    console.log('existing id ' + id);
                    this._activate(id);
                    return 'existing';
                }

                this._addNewPanel(id, url);
            }

            _find(id) {
                return this.panels.find((panel) => panel.id === id );
            }

            _addNewPanel(id, url) {
                // Create DOM slot with url reference (or hash???)....
                const newPanelContainer = document.createElement('div');
                newPanelContainer.setAttribute('data-panel-id', id);
                this.sidebar.dom().appendChild(newPanelContainer);

                this.events.loadUrlContent(url, newPanelContainer, () => {

                    console.log('loading content for ' + url);

                    if(!this.activePanel) {
                        this.sidebar.open();
                    }

                    this.panels.push({
                        id: id,
                        url: url,
                        parent: this.activePanel ? this.activePanel : null,
                    });

                    this._activate(id);

                }, () => {
                    this.backOrClose();

                    if(this.submitCallback) {
                        this.submitCallback();
                    }
                })
            }

            _activate(id) {
                this.activePanel = this._find(id);

                Array.from(this.sidebar.dom().querySelectorAll('[data-panel-id]')).forEach(el => el.style.display = 'none');
                this.sidebar.dom().querySelector(`[data-panel-id="${id}"]`).style.display = "block";

                if(this.loadContentCallback) {
                    this.loadContentCallback();
                }
            }

            backOrClose() {

                console.log('closing...');
                console.log(this.activePanel.parent);
                if(this.activePanel.parent) {
                    this.show(this.activePanel.parent.url);
                    return;
                }

                // Only on the top level we close the sidebar
                // Check for unsaved content before clicking submit...
                this.sidebar.close();

                this._reset();
            }

            _reset() {
                this.panels = [];
                this.activePanel = null;

                // Remove all from dom
                this.sidebar.dom().innerHTML = '';
            }


        }


        const SidebarEvents = {

            listenForEditRequests: function() {
                const els = document.querySelectorAll('[data-edit-modal]');

                Array.from(els).forEach(function(el) {
                    el.removeEventListener('click', SidebarMain.editRequestHandler)
                    el.addEventListener('click', SidebarMain.editRequestHandler);
                });
            },

            loadUrlContent: function(url, container, callback, submitCallback)
            {
                fetch(url)
                    .then(response => { return response.text() })
                    .then(data => {
                        container.innerHTML = data;

                        // only mount Vue on our vue specific fields and not on the form element itself
                        // so that the submit event still works. I know this is kinda hacky.
                        new Vue({ el: container.querySelector('[data-vue-fields]')});

                        console.log('reloaded content');

                        this.listenForEditRequests();
                        this.listenForFormSubmits(container, submitCallback);

                        if(callback) callback();
                    })
                    .catch(error => {
                        console.log(error);
                    });
            },

            listenForFormSubmits: function(container, callback) {
                const form = container.querySelector('form');

                form.addEventListener('submit', function(event) {
                    event.preventDefault();

                    fetch(this.action, {
                        method: this.method,
                        body: new FormData(this),
                    })
                        .then(response => { return response.json() })
                        .then(data => {

                            if(callback) callback();

                        })
                        .catch(error => {
                            console.log(error);
                        });
                });
            }
        }
    </script>
@endpush
