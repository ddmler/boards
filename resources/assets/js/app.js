import Vue from 'vue';
import VueRouter from 'vue-router';
import axios from 'axios';
import VueAxios from 'vue-axios';
import App from './App.vue';
import Dashboard from './components/Dashboard.vue';
import Board from './components/Board.vue';
import Home from './components/Home.vue';
import Register from './components/Register.vue';
import Login from './components/Login.vue';


Vue.use(VueRouter);
Vue.use(VueAxios, axios);
axios.defaults.baseURL = document.head.querySelector('meta[name="api-base-url"]').content + '/api';

const router = new VueRouter({
    routes: [{
        path: '/',
        name: 'home',
        component: Home
    },{
        path: '/register',
        name: 'register',
        component: Register,
        meta: {
            auth: false
        }
    },{
        path: '/login',
        name: 'login',
        component: Login,
        meta: {
            auth: false
        }
    },{
        path: '/dashboard',
        name: 'dashboard',
        component: Dashboard,
        meta: {
            auth: true
        }
    },{
        path: '/board/:id',
        name: 'board_view',
        component: Board,
        meta: {
            auth: true
        }
    }]
});


Vue.router = router
Vue.use(require('@websanova/vue-auth'), {
   auth: require('@websanova/vue-auth/drivers/auth/bearer.js'),
   http: require('@websanova/vue-auth/drivers/http/axios.1.x.js'),
   router: require('@websanova/vue-auth/drivers/router/vue-router.2.x.js'),
});
App.router = Vue.router
App.data = {
    loading: false,
    error: false,
    errors: {},
}
App.methods = {
    closeErrors() {
        this.error = false;
        this.errors = {};
    }
}
const vueApp = new Vue(App).$mount('#app');

axios.interceptors.request.use(config => {
    vueApp.loading = true;
    return config;
});

axios.interceptors.response.use(config => {
    vueApp.loading = false;
    vueApp.error = false;
    return config;
}, error => {
    vueApp.loading = false;
    vueApp.errors = error.response.data.errors || { "none": [error.response.data.message] };
    vueApp.error = true;
    return Promise.reject(error);
});
