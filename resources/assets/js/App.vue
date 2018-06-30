<template>
  <div>
    <div v-if="$auth.ready()">
      <nav class="navbar is-primary">
        <div class="navbar-brand">
          <router-link 
            v-if="!$auth.check()" 
            :to="{ name: 'home' }" 
            class="navbar-item">Boards</router-link>
          <router-link 
            v-if="$auth.check()" 
            :to="{ name: 'dashboard' }" 
            class="navbar-item">Boards</router-link>
        </div>
        <div class="navbar-menu">
          <div class="navbar-start"/>
          <div class="navbar-end">
            <div 
              v-if="$root.loading" 
              class="navbar-item">
              <i 
                class="fas fa-spinner fa-spin fa-lg" 
                title="Loading/Saving"/>
            </div>
            <div 
              v-if="!$root.loading" 
              class="navbar-item">
              <i 
                class="fas fa-cloud fa-lg" 
                title="Everything is saved"/>
            </div>
            <router-link 
              v-if="!$auth.check()" 
              :to="{ name: 'login' }" 
              class="navbar-item">Login</router-link>
            <router-link 
              v-if="!$auth.check()" 
              :to="{ name: 'register' }" 
              class="navbar-item">Register</router-link>
            <div 
              v-if="$auth.check()" 
              class="navbar-item"><i class="fas fa-user"/> {{ $auth.user().name }}</div>
            <a 
              v-if="$auth.check()" 
              href="#" 
              class="navbar-item" 
              @click.prevent="$auth.logout()">Logout</a>
          </div>
        </div>
      </nav>
      <div class="main-wrapper">
        <router-view/>
      </div>
    </div>
    <div v-if="!$auth.ready()">
      <section class="hero is-fullheight">
        <div class="hero-head"/>
        <div class="hero-body">
          <div class="container has-text-centered">
            <i class="fas fa-spinner fa-spin fa-3x"/>
          </div>
        </div>
        <div class="hero-foot"/>
      </section>
    </div>
  </div>
</template>
