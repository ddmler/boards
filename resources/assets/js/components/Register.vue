<template>
  <div>
    <div 
      v-if="success" 
      class="notification is-success">
      <p>Registration successful. You can now <router-link :to="{name:'login'}">login.</router-link></p>
    </div>
    <form 
      v-if="!success" 
      autocomplete="off" 
      method="post" 
      @submit.prevent="register">
      <div class="field">
        <label 
          for="name" 
          class="label">Username</label>
        <input 
          id="name" 
          v-model="name" 
          :class="[{ 'is-danger': $root.error && $root.errors.name }, 'input']" 
          type="text" 
          required>
      </div>
      <div class="field">
        <label 
          for="email" 
          class="label">E-mail</label>
        <input 
          id="email" 
          v-model="email" 
          :class="[{ 'is-danger': $root.error && $root.errors.email }, 'input']" 
          type="email" 
          placeholder="user@example.com" 
          required>
      </div>
      <div class="field">
        <label 
          for="password" 
          class="label">Password</label>
        <input 
          id="password" 
          v-model="password" 
          :class="[{ 'is-danger': $root.error && $root.errors.password }, 'input']" 
          type="password" 
          required>
      </div>
      <button 
        type="submit" 
        class="button">Register</button>
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
                    error: function () {},
                    redirect: null
                });
            }
        }
    }
</script>
