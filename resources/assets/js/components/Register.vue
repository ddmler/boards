<template>
    <div>
        <div v-if="error && !success">
            <p>There was an error.</p>
        </div>
        <div v-if="success">
            <p>Registration successful. You can now <router-link :to="{name:'login'}">login.</router-link></p>
        </div>
        <form autocomplete="off" @submit.prevent="register" v-if="!success" method="post">
            <div :class="{ 'has-error': error && errors.name }">
                <label for="name">Username</label>
                <input type="text" id="name" v-model="name" required>
                <span v-if="error && errors.name">{{ errors.name }}</span>
            </div>
            <div :class="{ 'has-error': error && errors.email }">
                <label for="email">E-mail</label>
                <input type="email" id="email" placeholder="user@example.com" v-model="email" required>
                <span v-if="error && errors.email">{{ errors.email }}</span>
            </div>
            <div :class="{ 'has-error': error && errors.password }">
                <label for="password">Password</label>
                <input type="password" id="password" v-model="password" required>
                <span v-if="error && errors.password">{{ errors.password }}</span>
            </div>
            <button type="submit">Register</button>
        </form>
    </div>
</template>
<script>
    export default {
        data(){
            return {
                name: '',
                email: '',
                password: '',
                error: false,
                errors: {},
                success: false
            };
        },
        methods: {
            register(){
                var app = this
                this.$auth.register({
                    params: {
                        name: app.name,
                        email: app.email,
                        password: app.password
                    },
                    success: function () {
                        app.success = true
                    },
                    error: function (resp) {
                        app.error = true;
                        app.errors = resp.response.data.errors;
                    },
                    redirect: null
                });
            }
        }
    }
</script>
