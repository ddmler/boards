<template>
  <div>
    <div 
      v-if="error" 
      class="notification is-danger">
      <p>It looks like those credentials are not working.</p>
    </div>
    <form 
      autocomplete="off" 
      method="post" 
      @submit.prevent="login">
      <div class="field">
        <label 
          for="email" 
          class="label">E-mail</label>
        <input 
          id="email" 
          v-model="email" 
          type="email" 
          placeholder="user@example.com" 
          class="input" 
          required>
      </div>
      <div class="field">
        <label 
          for="password" 
          class="label">Password</label>
        <input 
          id="password" 
          v-model="password" 
          type="password" 
          class="input" 
          required>
      </div>
      <button 
        type="submit" 
        class="button">Sign in</button>
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
