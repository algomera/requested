import './bootstrap';

import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse'
import mask from '@alpinejs/mask'
import persist from '@alpinejs/persist'

Alpine.plugin(collapse)
Alpine.plugin(mask)
Alpine.plugin(persist)

import tippy from "tippy.js";
import 'tippy.js/dist/tippy.css';

window.Alpine = Alpine;
document.addEventListener('alpine:init', () => {
    Alpine.directive('tooltip', (el, {expression}) => {
        tippy(el, {content: expression})
    })
})

Alpine.start();
