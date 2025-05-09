import initBulkActions from './bulk-actions';

/* Alpine directives */
import bulkselect from '../alpine-directives/bulkselect';
import tableFilters from '../alpine-directives/tablefilters';

document.addEventListener('DOMContentLoaded', () => {
    initBulkActions();
});

window.Alpine.data('bulkselect', bulkselect);
window.Alpine.data('tableFilters', tableFilters);
