<template>
    <div class="col-12">
        <p class="h1 text-center">{{ app_name }}</p>

        <nav class="navbar navbar-expand-md navbar-light navbar-laravel bg-success ">
            <div class="container">
                <ul class="navbar-nav">
                    <router-link :to="{ name: 'login' }" class="nav-link text-white">Zaloguj</router-link>
                </ul>
            </div>
        </nav>

        <router-view></router-view>
    </div>
</template>

<script>
export default {
    name: "App",
    props: [
        'app_name'
    ]
}
</script>

<style scoped>

</style>
