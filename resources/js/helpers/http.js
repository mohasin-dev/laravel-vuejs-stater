/**
 * Module to handle AXIOS Requests
 */
import axios from 'axios'
import store from '../store'

export default () => {
    const instance = axios.create({
        baseURL: store.state.baseUrl,
        headers: {
            Accept: '*/*',
            Authorization: store.state.auth.currentUser ? `Bearer ${store.state.auth.currentUser.token}` : '',
            locale: 'en'
        },
    })

    /** Global loader */
    store.commit('helpers/SET_IS_LOADING', true, { root: true });

    /** Axios Interceptors */
    instance.interceptors.response.use(
        function (response) {
            /** Global loader */
            store.commit('helpers/SET_IS_LOADING', true, { root: true });

            return response
        },
        function (error) {

            // const originalRequest = error.config

            // if (error.response.status === 401) {
            //     store.commit('auth/logout')
            //     window.location.replace("/");
            // }

            /** Global loader */
            store.commit('helpers/SET_IS_LOADING', true, { root: true })
            store.commit('helpers/SET_COMMON_ERRORS', error.response.data, { root: true })

            return Promise.reject(error)
        }
    )

    /** End of Interceptors */
    return instance
}