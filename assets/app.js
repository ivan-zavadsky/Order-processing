import {Orders} from "./order.js";

document.addEventListener('DOMContentLoaded', () => {
    Orders.listen();
    Orders.productAutocomplete();
});




import * as order from './order.js';

// import './stimulus_bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
// import './styles/app.css';

// console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');
