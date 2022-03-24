import Vue from 'vue';
import Vuex from 'vuex';

import helpers from './helpers/helpers'
import auth from './modules/auth'
import user from './modules/user'

Vue.use(Vuex);

export default new Vuex.Store({
    //strict: true,
    state: {
        baseUrl: '/',
        isLoading: false,
    },
    modules: {
        helpers,
        auth,
        user
    },
    getters: {},
    mutations: {},
    actions: {}
})
