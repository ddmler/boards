<template>
    <div>
        <div v-if="error" class="notification is-danger">
            <p>It looks like those credentials are not working.</p>
        </div>
        <form autocomplete="off" @submit.prevent="login" method="post">
            <div class="field">
                <label for="email" class="label">E-mail</label>
                <input type="email" id="email" placeholder="user@example.com" v-model="email" class="input" required>
            </div>
            <div class="field">
                <label for="password" class="label">Password</label>
                <input type="password" id="password" v-model="password" class="input" required>
            </div>
            <button type="submit" class="button is-primary">Sign in</button>
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
