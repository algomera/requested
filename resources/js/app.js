import './bootstrap';

import Sortable from "sortablejs";
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse'
import mask from '@alpinejs/mask'
import persist from '@alpinejs/persist'
import focus from '@alpinejs/focus'

Alpine.plugin(collapse)
Alpine.plugin(mask)
Alpine.plugin(persist)
Alpine.plugin(focus)

import tippy from "tippy.js";
import 'tippy.js/dist/tippy.css';

window.Sortable = Sortable;
window.Alpine = Alpine;
document.addEventListener('alpine:init', () => {
    Alpine.directive('tooltip', (el, {expression}) => {
        tippy(el, {content: expression})
    })
})

Alpine.start();
