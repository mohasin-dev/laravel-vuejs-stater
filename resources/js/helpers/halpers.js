import axios from "axios";

export function initialize(store, router) {
    router.beforeEach((to, from, next) => {
        const requiresAuth = to.matched.some(record => record.meta.requiresAuth);
        const currentUser = store.state.auth.currentUser;

        if (currentUser) {
            console.log('Permission request sent');
            // axios.interceptors.request.use(function (config) {
            //     config.headers.authorization = `Bearer ${currentUser.token}`
            //     return config;
            //   }, function (error) {
            //     console.log('Axios interceptor error');
            //     return Promise.reject(error);
            //   });

              axios.interceptors.request.use(
                config => {
                    config.headers.authorization = `Bearer ${currentUser.token}`
                    return config;
                },
                error => Promise.reject(error)
            );
        }

        if (requiresAuth && !currentUser) {
            router.push('/');
        } else if (to.path == '/' && currentUser) {
            router.push('/dashboard');
        } else {
            next();
        }
    });

    axios.interceptors.response.use(null, (error) => {
        if (error.resposne.status == 401) {
            store.commit('logout');
            router.push('/login');
        }

        return Promise.reject(error);
    });
}