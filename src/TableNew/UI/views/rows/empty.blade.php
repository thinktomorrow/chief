<tr>
    <td colspan="9999">
        <div class="flex animate-dialog-pop-in items-center justify-center py-16">
            <div class="mx-auto max-w-2xl space-y-4 text-center">
                <svg viewBox="0 0 24 24" color="currentColor" fill="none" class="inline size-10 text-grey-500">
                    <path
                        d="M13 21H12C7.28595 21 4.92893 21 3.46447 19.5355C2 18.0711 2 15.714 2 11V7.94427C2 6.1278 2 5.21956 2.38032 4.53806C2.65142 4.05227 3.05227 3.65142 3.53806 3.38032C4.21956 3 5.1278 3 6.94427 3C8.10802 3 8.6899 3 9.19926 3.19101C10.3622 3.62712 10.8418 4.68358 11.3666 5.73313L12 7M8 7H16.75C18.8567 7 19.91 7 20.6667 7.50559C20.9943 7.72447 21.2755 8.00572 21.4944 8.33329C21.9796 9.05942 21.9992 10.0588 22 12"
                        stroke="currentColor"
                        stroke-width="1.5"
                        stroke-linecap="round"
                    />
                    <path
                        d="M22 21L19.8529 18.8529M19.8529 18.8529C19.9675 18.7384 20.0739 18.6158 20.1714 18.486C20.602 17.913 20.8571 17.2006 20.8571 16.4286C20.8571 14.535 19.3221 13 17.4286 13C15.535 13 14 14.535 14 16.4286C14 18.3221 15.535 19.8571 17.4286 19.8571C18.3753 19.8571 19.2325 19.4734 19.8529 18.8529Z"
                        stroke="currentColor"
                        stroke-width="1.5"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    />
                </svg>
                <div class="space-y-1">
                    <h2 class="font-medium text-grey-950">Geen resultaten gevonden</h2>

                    <p class="body text-balance text-sm text-grey-500">
                        We konden geen resultaten vinden voor je gekozen filters.
                        <br />
                        Probeer eens een andere filtering.
                    </p>
                </div>

                <div class="flex justify-center">
                    <button type="button" wire:click="clearFilters()">
                        <x-chief-table-new::button
                            size="sm"
                            iconLeft='<svg viewBox="0 0 24 24" color="currentColor" fill="none"> <path d="M20.9987 4.5C20.9869 4.06504 20.8956 3.75346 20.672 3.5074C20.2111 3 19.396 3 17.7657 3H6.23433C4.60404 3 3.7889 3 3.32795 3.5074C2.86701 4.0148 2.96811 4.8008 3.17033 6.3728C3.22938 6.8319 3.3276 7.09253 3.62734 7.44867C4.59564 8.59915 6.36901 10.6456 8.85746 12.5061C9.08486 12.6761 9.23409 12.9539 9.25927 13.2614C9.53961 16.6864 9.79643 19.0261 9.93278 20.1778C10.0043 20.782 10.6741 21.2466 11.226 20.8563C12.1532 20.2006 13.8853 19.4657 14.1141 18.2442C14.1986 17.7934 14.3136 17.0803 14.445 16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /> <path d="M21 7L15 13M21 13L15 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /> </svg>'
                        >
                            Reset filters
                        </x-chief-table-new::button>
                    </button>
                </div>
            </div>
        </div>
    </td>
</tr>
