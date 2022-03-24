export default {
    namespaced: true,
    state: {
        isLoading: false,
        isActive: {
            modal: false,
            name: ''
        },
        commonErrors: []
    },
    getters: {

    },
    mutations: {
        /** Set global loader */
        SET_IS_LOADING(state) {
            state.isLoading = !state.isLoading;
        },
        /** Set global modal activator*/
        SET_IS_ACTIVE(state, value) {
            state.isActive.modal = value.modal
            state.isActive.name = value.name
        },
        /** Errors Handling */
        SET_COMMON_ERRORS(state, errors) {
            state.commonErrors = errors
        },
    },
    actions: {},
};