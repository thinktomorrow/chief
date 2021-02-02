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
    </script>
@endpush
