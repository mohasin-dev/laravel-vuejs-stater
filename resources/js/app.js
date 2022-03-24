require('./bootstrap');
import Vue from 'vue';
import router from './router'
import store from './store';
import App from './components/layouts/App.vue';
import { initialize } from './helpers/halpers';
Vue.component('full-page-Loder', require('./components/partials/FullPageLoder.vue').default);
Vue.component('pagination', require('laravel-vue-pagination'));

import vSelect from "vue-select";
Vue.component("v-select", vSelect);

initialize(store, router);

const app = new Vue({
    el: '#app',
    router,
    store,
    components: {
        App
    }
});