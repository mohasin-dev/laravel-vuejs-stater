import HTTP from '../../helpers/http';
import router from "../../router";
import toast from '../../services/Notification';

const user = localStorage.getItem("user") ? JSON.parse(localStorage.getItem("user")) : null;

export default {
    namespaced: true,
    state: {
        currentUser: user,
        isLoggedIn: !!user,
        loading: false,
        auth_error: null,
        loginForm: {
            email: "admin@gmail.com",
            password: "secret",
        },
        commonErrors: []
    },
    getters: {
        isLoading(state) {
            return state.loading;
        },
        isLoggedIn(state) {
            return state.isLoggedIn;
        },
        currentUser(state) {
            return state.currentUser;
        },
        authError(state) {
            return state.auth_error;
        }
    },
    mutations: {
        SET_COMMON_ERRORS(state, errors) {
            state.commonErrors = errors
        },
        /** Login Email */
        //   SET_LOGIN_FORM_EMAIL(state, event) {
        //     state.loginForm.email = event.target.value
        // },
        SET_LOGIN_FORM_EMAIL(state, email) {
            state.loginForm.email = email
        },
        /** Login Password */
        SET_LOGIN_FORM_PASSWORD(state, event) {
            state.loginForm.password = event.target.value
        },
        login(state) {
            state.loading = true;
            state.auth_error = null;
        },
        loginSuccess(state, payload) {
            console.log(payload)
            state.auth_error = null;
            state.isLoggedIn = true;
            state.loading = false;
            state.currentUser = Object.assign({}, payload.data.user, { token: payload.data.access_token });
            localStorage.setItem("user", JSON.stringify(state.currentUser));
            console.log('login success complete');
        },
        loginFailed(state, payload) {
            state.loading = false;
            state.auth_error = payload.error;
        },
        logout(state) {
            localStorage.removeItem("user");
            state.isLoggedIn = false;
            state.currentUser = null;
        }
    },

    actions: {
        login({ commit, state }) {
            HTTP()
                .post("/api/auth/login", state.loginForm)
                .then((response) => {
                    commit("loginSuccess", response);
                    toast.success('Welcome back!');
                    router.push('/dashboard');
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        logout({ commit }) {
            HTTP()
                .post("/api/auth/logout")
                .then((response) => {
                    commit("logout");
                    router.push('/');
                })
                .catch((error) => {
                    console.log(error);
                });
        }
    }
};