/**
 * List here all the js utilities needed to be
 * loaded after the Vue instantiation
 */

require('./utilities/form-submit');

/** Sortable */
import {IndexSorting} from "./utilities/sortable";
if(document.getElementById('js-sortable')) {
    new IndexSorting({
        // any options go here
    });
}
