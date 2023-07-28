@php use Illuminate\Support\Str; @endphp
<div x-cloak x-data="tabs()" class="space-y-3">
    <ul x-show="!hideNav" role="tablist" class="flex w-full pl-0 list-none border-b border-grey-100">
        <template x-for="(tab,index) in items('{{ $tabsId = Str::random() }}')">
            <li role="presentation">
                <a
                    x-on:click="activeTab = index"
                    x-text="tab.name"
                    x-bind:href.="`#${tab.id}`"
                    x-bind:aria-controls="`#${tab.id}`"
                    x-bind:aria-selected="index === activeTab"
                    role="tab"
                    class="block px-3 pb-2 text-grey-600 with-bottomline"
                    x-bind:class="{ 'active': index === activeTab }"
                ></a>
            </li>
        </template>

    </ul>

    <div id="{{ $tabsId }}">
        {{ $slot }}
    </div>
</div>

{{--{ hideTabs: false, tabs: [{ label: 'nl', hash: 'nl', isActive: false, content: 'This is the content' }] }--}}

{{--<script>--}}
{{--    document.addEventListener('alpine:init', () => {--}}
{{--        Alpine.data('tabs', () => ({--}}
{{--            console.log('gooo');--}}
{{--            // $el.--}}
{{--            open: false,--}}

{{--            toggle() {--}}
{{--                this.open = !this.open--}}
{{--            }--}}
{{--        }))--}}
{{--    })--}}
{{--</script>--}}
