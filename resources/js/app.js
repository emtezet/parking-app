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
import excel from 'vue-export-excel';

Vue.use(VueRouter);
Vue.use(Vuex);
//Vue.use(excel);

axios.defaults.baseURL = 'http://parking.localhost/api';
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';


import App from './components/App'
import LoginPage from './components/LoginPage'
import VehicleType from "./components/VehicleType";
import Reservation from "./components/Reservation";
import Vehicle from "./components/Vehicle";
import PriceList from "./components/PriceList";
import Rent from "./components/Rent";
import Report from "./components/Report";

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            name: 'login',
            path: '/login',
            component: LoginPage,
            meta: {
                requiresRole: ['']
            }
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
            meta: {
                requiresRole: ['','admin']
            }
        },
        //Vehicle Type
        {
            name: 'vehicle_type',
            path: '/vehicle_type',
            component: VehicleType,
            meta: {
                requiresAuth: true,
                requiresRole: ['admin']
            }
        },
        //Reservation
        {
            name: 'reservation',
            path: '/reservation',
            component: Reservation,
            meta: {
                requiresAuth: true,
                requiresRole: ['insert']
            }
        },
        //Vehicle
        {
            name: 'vehicle',
            path: '/vehicle',
            component: Vehicle,
            meta: {
                requiresAuth: true,
                requiresRole: ['admin','insert']
            }
        },
        //Price List
        {
            name: 'price_list',
            path: '/price_list',
            component: PriceList,
            meta: {
                requiresAuth: true,
                requiresRole: ['admin']
            }
        },
        //Rent
        {
            name: 'rent',
            path: '/rent',
            component: Rent,
            meta: {
                requiresAuth: true,
                requiresRole: ['insert']
            }
        },
        //Report
        {
            name: 'report',
            path: '/report',
            component: Report,
            meta: {
                requiresAuth: true,
                requiresRole: ['admin']
            }
        },
    ],
});

const store = new Vuex.Store({
    state: {
        user: null
    },

    mutations: {
        setUserData(state, userData) {
            state.user = userData
            localStorage.setItem('user', JSON.stringify(userData))
            axios.defaults.headers.common.Authorization = `Bearer ${userData.token}`
        },

        clearUserData() {
            localStorage.removeItem('user');
            location.replace('/');
        }
    },

    actions: {
        login({commit}, credentials) {
            return axios
                .post('/login', credentials)
                .then(({data}) => {
                    commit('setUserData', data)
                })
        },

        logout({commit}) {
            commit('clearUserData')
        }
    },

    getters: {
        isLogged: state => !!state.user
    }
})

router.beforeEach((to, from, next) => {
    const loggedIn = localStorage.getItem('user');
    const userRole = loggedIn ? JSON.parse(localStorage.getItem('user')).user.role : '';

    if (to.matched.some(record => record.meta.requiresAuth) && !loggedIn) {
        next('/login')
        return
    }

    if (to.meta.requiresRole !== undefined && !to.meta.requiresRole.includes(userRole)) {
        next('/')
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
    created() {
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

//Error Modal
window.showErrorModal = function (errorsMessagesArray, reload = false) {
    $("#info-modal-body").empty();

    if (typeof errorsMessagesArray == 'string') {
        $("#info-modal-body").append('<div class="alert alert-danger" role="alert">' + errorsMessagesArray + '</div>');
    } else if (typeof errorsMessagesArray == 'object') {
        for (const [key, value] of Object.entries(errorsMessagesArray)) {

            value.forEach(function (element) {
                $("#info-modal-body").append('<div class="alert alert-danger" role="alert">' + element + '</div>');
            });
        }
    } else {
        errorsMessagesArray.forEach(function (element) {
            $("#info-modal-body").append('<div class="alert alert-danger" role="alert">' + element + '</div>');
        });
    }

    $('#info-modal').modal('show');

    if (reload === true) {
        $('#info-modal').on('hidden.bs.modal', function (e) {
            window.location.reload();
        })
    } else if (typeof reload === 'string' || reload instanceof String) {
        $('#info-modal').on('hidden.bs.modal', function (e) {
            window.location.replace(reload);
        })
    }

    $('#info-modal').keypress(function (e) {
        let keyCode = (event.keyCode ? event.keyCode : event.which);
        if (keyCode === 13) {
            $('#info-modal').trigger('click');
            return false;
        }
    });
};

//Success Modal
window.showSuccessModal = function (successMessage, reload = false) {
    $("#info-modal-body").empty().append('<div class="alert alert-success" role="alert">' + decodeURIComponent(successMessage) + '</div>');
    $('#info-modal').modal('show');

    if (reload === true) {
        $('#info-modal').on('hidden.bs.modal', function (e) {
            window.location.reload();
        })
    } else if (typeof reload === 'string' || reload instanceof String) {
        $('#info-modal').on('hidden.bs.modal', function (e) {
            window.location.replace(reload);
        })
    }

    $('#info-modal').keypress(function (e) {
        let keyCode = (event.keyCode ? event.keyCode : event.which);
        if (keyCode === 13) {
            $('#info-modal').trigger('click');
            return false;
        }
    });
};

//Confirm Modal
window.showConfirmModal = function (confirmMessage, yesCallbackFunction, params) {
    $("#confirm-modal-body").empty().append('<div class="alert alert-warning" role="alert">' + decodeURIComponent(confirmMessage) + '</div>');
    $("#confirm-modal-yes-btn").unbind("click").click(function () {
        yesCallbackFunction.apply(this, params);
    });

    $('#confirm-modal').modal('show');
};

//Prompt Modal
window.showPromptModal = function (promptMessage, okCallbackFunction, params, defaultValue = false) {
    $("#prompt-modal-body").empty().append(
        '<div class="form-group" role="alert">' +
        '<label for="prompt-input" class="col-form-label">' + decodeURIComponent(promptMessage) + '</label>' +
        '<input type="text" class="form-control" id="prompt-input" value=""/>' +
        '</div>'
    );
    $("#prompt-modal-ok-btn").unbind("click").click(function () {
        okCallbackFunction.apply(this, params);
    });

    $('#prompt-input').keypress(function (e) {
        let keyCode = (event.keyCode ? event.keyCode : event.which);
        if (keyCode === 13) {
            $("#prompt-modal-ok-btn").trigger('click');
            return false;
        }
    });


    $('#prompt-modal').modal('show');

    $('#prompt-modal-body input').focus().val(defaultValue !== false ? decodeURIComponent(defaultValue) : '');
};
