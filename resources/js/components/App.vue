<template>
    <div class="col-12">
        <p class="h1 text-center">{{ app_name }}</p>

        <nav class="navbar navbar-expand-md navbar-light navbar-laravel bg-success text-right">
            <div class="container ">
                <ul class="navbar-nav align-middle" v-if="isLogged === true">
                    Witaj {{ username }}
                    &nbsp;
                    <button class="btn btn-danger btn-sm" @click="logout()">Wyloguj</button>

                </ul>
                <ul class="navbar-nav" v-if="isLogged === false">
                    <router-link v-if="isLogged === false" :to="{ name: 'login' }" class="nav-link text-white">Zaloguj</router-link>
                </ul>
            </div>
        </nav>

        <router-view></router-view>
    </div>
</template>

<script>
import {mapGetters} from 'vuex'

export default {
    name: "App",
    props: [
        'app_name',

    ],
    data() {
        return {
            username: ''
        }
    },
    created() {
        const userInfo = localStorage.getItem('user');
        if (userInfo) {
            const userData = JSON.parse(userInfo);
            this.$store.commit('setUserData', userData)
        }
    },
    mounted() {
        if (localStorage.getItem('user')) {
            const userData = JSON.parse(localStorage.getItem('user'));
            this.$store.commit('setUserData', userData)
            this.username = userData.user.name
        }
    },
    computed: {
        ...mapGetters([
            'isLogged'
        ])
    },
    methods: {
        logout() {
            this.$store.dispatch('logout')
        }
    },
    updated() {
        if (localStorage.getItem('user')) {
            const userData = JSON.parse(localStorage.getItem('user'));
            this.username = userData.user.name
        }
    }
}
</script>

<style scoped>

</style>
