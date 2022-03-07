import Sidebar from './sidebar/Sidebar';
import Forms from './Forms';

document.addEventListener('DOMContentLoaded', () => {
    new Forms(new Sidebar()).load(document);
});
