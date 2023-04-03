require('./bootstrap');
import Alpine from 'alpinejs'

window.Alpine = Alpine

Alpine.start()

import lifecycle from 'page-lifecycle';
window.lifecycle = lifecycle;
