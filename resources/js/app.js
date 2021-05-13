/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import Parking from "./components/Parking";

require('./bootstrap');

//window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

//Vue.component('example-component', require('./components/ExampleComponent.vue').default);
//Vue.component('navbar-component', require('./components/Navbar').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import Vue from 'vue'
import VueRouter from "vue-router";
import Vuex from 'vuex';
import axios from 'axios';

Vue.use(VueRouter);
Vue.use(Vuex);

axios.defaults.baseURL = 'http://parking.localhost/api';

import App from './components/App'
import LoginPage from './components/LoginPage'
import VehicleType from "./components/VehicleType";
import Reservation from "./components/Reservation";

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            name: 'login',
            path: '/login',
            component: LoginPage,
        },
        {
            name: 'homepage',
            path: '/',
        },
        //Parking
        {
            name: 'parking',
            path: '/parking',
            component: Parking,
        },
        //Vehicle Type
        {
            name: 'vehicle_type',
            path: '/vehicle_type',
            component: VehicleType,
        },
        //Reservation
        {
            name: 'reservation',
            path: '/reservation',
            component: Reservation,
        },
    ],
});

const store = new Vuex.Store({
    state: {
        user: null
    },

    mutations: {
        setUserData (state, userData) {
            state.user = userData
            localStorage.setItem('user', JSON.stringify(userData))
            axios.defaults.headers.common.Authorization = `Bearer ${userData.token}`
        },

        clearUserData () {
            localStorage.removeItem('user');
            location.replace('/');
        }
    },

    actions: {
        login ({ commit }, credentials) {
            return axios
                .post('/login', credentials)
                .then(({ data }) => {
                    commit('setUserData', data)
                })
        },

        logout ({ commit }) {
            commit('clearUserData')
        }
    },

    getters : {
        isLogged: state => !!state.user
    }
})

router.beforeEach((to, from, next) => {
    const loggedIn = localStorage.getItem('user')

    if (to.matched.some(record => record.meta.auth) && !loggedIn) {
        next('/login')
        return
    }
    next()
})

Vue.config.productionTip = false;

const app = new Vue({
    el: '#app',
    mode: 'history',
    components: {App},
    router,
    store,
    created () {
        const userInfo = localStorage.getItem('user')
        if (userInfo) {
            const userData = JSON.parse(userInfo)
            this.$store.commit('setUserData', userData)
        }
        axios.interceptors.response.use(
            response => response,
            error => {
                if (error.response.status === 401) {
                    this.$store.dispatch('logout')
                }
                return Promise.reject(error)
            }
        )
    },
});
