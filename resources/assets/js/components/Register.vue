<template>
    <div>
        <div v-if="error && !success" class="notification is-danger">
            <p>There was an error. Please check the error messages below.</p>
        </div>
        <div v-if="success" class="notification is-success">
            <p>Registration successful. You can now <router-link :to="{name:'login'}">login.</router-link></p>
        </div>
        <form autocomplete="off" @submit.prevent="register" v-if="!success" method="post">
            <div class="field">
                <label for="name" class="label">Username</label>
                <input type="text" id="name" v-model="name" required :class="[{ 'is-danger': error && errors.name }, 'input']">
                <div v-if="error && errors.name" class="message is-danger is-small"><div class="message-body">{{ errors.name }}</div></div>
            </div>
            <div class="field">
                <label for="email" class="label">E-mail</label>
                <input type="email" id="email" placeholder="user@example.com" v-model="email" required :class="[{ 'is-danger': error && errors.email }, 'input']">
                <div v-if="error && errors.email" class="message is-danger is-small"><div class="message-body">{{ errors.email }}</div></div>
            </div>
            <div class="field">
                <label for="password" class="label">Password</label>
                <input type="password" id="password" v-model="password" required :class="[{ 'is-danger': error && errors.password }, 'input']">
                <div v-if="error && errors.password" class="message is-danger is-small"><div class="message-body">{{ errors.password }}</div></div>
            </div>
            <button type="submit" class="button">Register</button>
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
