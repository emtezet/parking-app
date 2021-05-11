<template>

    <div>
        <p class="h3">Strona logowania</p>
        <div class="col-12 col-md-6 pl-0">
            <form @submit.prevent="login" class="form">
                <div class="form-group">
<!--                    <label for="email">Email</label>-->
                    <input id="email" class="form-control" type="email" name="email" v-model="email" placeholder="Email"/>
                </div>
                <div class="form-group">
<!--                    <label for="password">Hasło</label>-->
                    <input id="password" class="form-control" type="password" name="password" v-model="password" placeholder="Hasło">
                </div>

                <button class="btn btn-warning btn-block" type="submit">Zaloguj</button>
            </form>
        </div>
    </div>
</template>

<script>
export default {
    name: "LoginPage",
    data() {
        return {
            email: '',
            password: ''
        }
    },
    methods: {
        login() {
            this.$store
                .dispatch('login', {
                    email: this.email,
                    password: this.password
                })
                .then(res => {
                    this.$router.push({name: 'homepage'})
                })
                .catch(err => {
                    alert(err.response.data.message)
                })
        }
    }
}
</script>

<style scoped>

</style>
