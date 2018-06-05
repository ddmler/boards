<template>
    <div>
        <div v-if="error">
            <p>It looks like those credentials are not working.</p>
        </div>
        <form autocomplete="off" @submit.prevent="login" method="post">
            <div>
                <label for="email">E-mail</label>
                <input type="email" id="email" placeholder="user@example.com" v-model="email" required>
            </div>
            <div>
                <label for="password">Password</label>
                <input type="password" id="password" v-model="password" required>
            </div>
            <button type="submit">Sign in</button>
        </form>
    </div>
</template>
<script>
  export default {
    data(){
      return {
        email: null,
        password: null,
        error: false
      }
    },
    methods: {
      login(){
        var app = this
        this.$auth.login({
            params: {
              email: app.email,
              password: app.password
            },
            success: function () {},
            error: function () { this.error = true; },
            rememberMe: true,
            redirect: '/dashboard',
            fetchUser: true,
        });
      },
    }
  }
</script>
