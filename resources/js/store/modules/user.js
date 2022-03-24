import HTTP from '../../helpers/http'
import router from "../../router";
import toast from '../../services/Notification';

export default {
    namespaced: true,
    state: {
        users: [],
        // isLoggedIn: !!user,
        loading: false,
        auth_error: null,
        commonErrors: []
    },
    getters: {
        isLoading(state) {
            return state.loading;
        },
        isLoggedIn(state) {
            return state.isLoggedIn;
        },
        GET_USERS(state) {
            return state.users;
        },
        authError(state) {
            return state.auth_error;
        }
    },
    mutations: {
        SET_COMMON_ERRORS(state, errors) {
            state.commonErrors = errors
        },
        SET_USERS(state, users) {
            state.users = users;
        },
        /** Add Product After Complete */
        ADD_PRODUCT_AFTER_COMPLETE(state, user) {
            state.users.unshift(user);
        },
        /** Delete Product */
        UPDATE_USERS_AFTER_DELETE(state, identifier) {
            const index = state.users.map(user => user.id).indexOf(identifier);
            if (index > -1) state.users.splice(index, 1);
        },
    },

    actions: {
        all({ commit }) {

            HTTP()
                .get("/api/users")
                .then((response) => {
                    //setAuthorization(response.data.access_token);
                    commit("SET_USERS", response.data.users);
                    // router.push('/dashboard');
                })
                .catch((error) => {
                    console.log(error);
                    //this.$store.commit("loginFailed", { error });
                    // console.log(error);

                    //commit("loginFailed", { error });
                });
        },
        store({ commit, state }, formData) {
            HTTP()
                .post("/api/user/create", formData)
                .then((response) => {
                    commit('ADD_PRODUCT_AFTER_COMPLETE', response.data.user);
                    //toast.success('User successfully created');
                    // tost here
                    //setAuthorization(response.data.access_token);
                    //commit("loginSuccess", response);
                    //router.push('/dashboard');
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        delete({ commit, state }, id) {
            HTTP()
                .post(`/api/user/delete/${id}`)
                .then((response) => {
                    commit('UPDATE_USERS_AFTER_DELETE', id);
                    console.log(response.data);
                    //commit('ADD_PRODUCT_AFTER_COMPLETE', response.data.user);
                    //toast.success('User successfully created');
                    // tost here
                    //setAuthorization(response.data.access_token);
                    //commit("loginSuccess", response);
                    //router.push('/dashboard');
                })
                .catch((error) => {
                    console.log(error);
                });
        },

    }
};