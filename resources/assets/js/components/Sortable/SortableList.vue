<template>
    <div>
        <!-- <slot name="header"></slot> -->
        <template v-for="item in sortedItems">
            <slot :item="item">Dit is de fallback, mag nooit tonen</slot>
        </template>
        <!-- <slot name="footer"></slot> -->
    </div>
</template>

<script>
import Sortable from 'sortablejs';

export default {
    props: {
        items: { type: Array, default: function(){ return [] }},
        sortableItemClass: { type: String, default: 'sortable-item' },
        sortableHandleClass: { type: String, default: 'sortable-handle' }
    },
    data() {
        return {
            sortedItems: this.items
        }
    },
    provide() {
        return {
            sortableItemClass: this.sortableItemClass,
            sortableHandleClass: this.sortableHandleClass
        }
    },
    mounted() {
        let sortable = new Sortable(this.$el, {
            animation: 150,
            draggable: `.${this.sortableItemClass}`,
            handle: `.${this.sortableHandleClass}`,
            onEnd: (evt) => {
                this.sortedItems = this.move(this.sortedItems, evt.oldIndex, evt.newIndex);
                // Bring the sortable instance up to date with sortedItems (basicly cancel the visual sort done by Sortable)
                this.updateSortable(sortable, evt.oldIndex, evt.newIndex);
            }
        });
    },
    methods: {
        move: function(items, oldIndex, newIndex) {
            const itemRemovedArray = [
                ...items.slice(0, oldIndex),
                ...items.slice(oldIndex + 1, items.length)
            ];
            return [
                ...itemRemovedArray.slice(0, newIndex),
                items[oldIndex],
                ...itemRemovedArray.slice(newIndex, itemRemovedArray.length)
            ];
        },
        updateSortable: function(sortable, oldIndex, newIndex) {
            const oldSort = sortable.toArray();
            let newSort = sortable.toArray();
            if(oldIndex < newIndex) {
                for(var i = oldIndex; i < newIndex; i++) {
                    newSort[i + 1] = oldSort[i];
                }
            } else {
                for(var i = newIndex + 1; i <= oldIndex; i++) {
                    newSort[i - 1] = oldSort[i];
                }
            }
            newSort[oldIndex] = oldSort[newIndex];
            sortable.sort(newSort);
        }
    },
    render() {
        return this.$slots.default[0];
    },
};
</script>
