<template>
    <div class="col-12">
        <p class="h1 text-center">{{ app_name }}</p>

        <nav class="navbar navbar-expand-md navbar-light navbar-laravel bg-success ">
            <div class="container w-100 text-right">
                <ul class="align-middle w-100 text-right text-white mb-0" v-if="isLogged === true">
                    Witaj, {{ username }}
                    &nbsp;
                    <button class="btn btn-danger btn-sm d-inline-block" @click="logout()">Wyloguj</button>

                </ul>
                <ul class="w-100 text-right mb-0" v-if="isLogged === false">
                    <router-link v-if="isLogged === false" :to="{ name: 'login' }" class="text-white">Zaloguj</router-link>
                </ul>
            </div>
        </nav>

        <div class="links bg-warning p-1" v-if="user_role === ''">
            <router-link :to="{ name: 'parking' }" class="nav-link text-dark">Podgląd miejsc parkingowych</router-link>
        </div>

        <div class="links bg-warning p-1" v-if="user_role === 'insert'">
            <router-link :to="{ name: 'login' }" class="text-dark">Pojazdy</router-link>
            <router-link :to="{ name: 'login' }" class="text-dark">Parkowania</router-link>
        </div>

        <div class="links bg-warning p-1" v-if="user_role === 'admin'">
            <router-link :to="{ name: 'login' }" class="text-dark">Użytkownicy</router-link>
            <router-link :to="{ name: 'parking' }" class="text-dark">Parkingi</router-link>
            <router-link :to="{ name: 'login' }" class="text-dark">Cenniki</router-link>
            <router-link :to="{ name: 'login' }" class="text-dark">Raporty</router-link>
        </div>

        <router-view :user_role="user_role"></router-view>
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
            username: '',
            user_role: ''
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
            this.user_role = userData.user.role
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
            this.user_role = userData.user.role
        }
    }
}
</script>

<style scoped>
    .links > a {
        color: #636b6f;
        padding: 0 25px;
        font-size: 12px;
        font-weight: 600;
        letter-spacing: .1rem;
        text-decoration: none;
        text-transform: uppercase;
    }

</style>
