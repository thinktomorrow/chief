import Sortable from 'sortablejs';

const IndexSorting = function(options){
    this.Sortables = [];
    this.sortableGroupEl = options.sortableGroupEl || document.getElementById('js-sortable');
    this.sortableTypeAttribute = options.sortableType || 'data-sortable-type';
    this.sortableIdAttribute = options.sortableId || 'data-sortable-id';
    this.endpoint = options.endpoint || '/admin/api/sort';

    // Toggle
    this.isSorting = options.isSorting || false;
    this.sortToggles = Array.from(document.querySelectorAll('[data-sortable-toggle]'));
    this.hiddenWhenSortingEls = Array.from(document.querySelectorAll('[data-sortable-hide-when-sorting]'));
    this.showWhenSortingEls = Array.from(document.querySelectorAll('[data-sortable-show-when-sorting]'));

    this._init();
};

IndexSorting.prototype.toggle = function(e){

    this.isSorting = !this.isSorting;

    if(this.isSorting) {
        e.target.innerText = 'Stop met sorteren';
        this.showSorting();
    } else {
        e.target.innerText = 'Sorteer handmatig';
        this.hideSorting();
    }
}

IndexSorting.prototype.showSorting = function(){

    this.hiddenWhenSortingEls.forEach((el) => {
        el.classList.add('hidden');
    });

    this.showWhenSortingEls.forEach((el) => {
        el.classList.remove('hidden');
    });

    this.Sortables.forEach((sortableInstance) => {
        sortableInstance.option("disabled", false)
    })
}

IndexSorting.prototype.hideSorting = function(){

    this.hiddenWhenSortingEls.forEach((el) => {
        el.classList.remove('hidden');
    });

    this.showWhenSortingEls.forEach((el) => {
        el.classList.add('hidden');
    });

    this.Sortables.forEach((sortableInstance) => {
        sortableInstance.option("disabled", true)
    })
}


IndexSorting.prototype._init = function() {

    let self = this;

    this.Sortables.push(Sortable.create(this.sortableGroupEl, {
        group: 'models',
        animation: 150,
        fallbackOnBody: true,
        swapThreshold: 0.65,
        dataIdAttr: this.sortableIdAttribute,

        store: {
            set: function(sortable){

                fetch(self.endpoint, {
                    method: 'post',
                    body: JSON.stringify({
                        "modelType": self.sortableGroupEl.getAttribute(self.sortableTypeAttribute),
                        "indices": sortable.toArray(),
                    }),
                    headers: {
                        'Content-Type': 'application/json'
                    },
                }).then(function(response) {
                    return response.json();
                }).catch(function(error){
                    console.error(error);
                });
            },
        },
        // Called by any change to the list (add / update / remove)
        // onEnd: function (evt) {
        //     let itemEl = evt.item;  // dragged HTMLElement
        //
        //     fetch(self.endpoint, {
        //         method: 'post',
        //         body: JSON.stringify({
        //             "modelType": itemEl.getAttribute(self.sortableTypeAttribute),
        //             "modelId": itemEl.getAttribute(self.sortableIdAttribute),
        //             "index": evt.newIndex, // evt.to, evt.from, evt.oldIndex, evt.newIndex
        //             "all_indices": '',
        //         }),
        //         headers: {
        //             'Content-Type': 'application/json'
        //         },
        //     }).then(function(response) {
        //         return response.json();
        //     }).catch(function(error){
        //         console.error(error);
        //     });
        // }
    }));

    this.sortToggles.forEach((toggle) => {
        toggle.addEventListener('click', this.toggle.bind(this))
    });

    // Default view
    (this.isSorting) ? this.showSorting() : this.hideSorting();
}

export {IndexSorting};
