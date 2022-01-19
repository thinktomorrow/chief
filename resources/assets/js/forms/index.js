import Sidebar from './sidebar/Sidebar';
import Forms from './forms';

document.addEventListener('DOMContentLoaded', () => {
    new Forms(new Sidebar()).load(document);
});
