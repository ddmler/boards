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
        <div 
          v-if="$root.error" 
          class="message is-danger">
          <div class="message-body">
            <span class="error-close">
              <a @click.prevent="closeErrors"><i class="fas fa-times"/></a>
            </span>
            Oops we have some errors: <ul>
              <li 
                v-for="(e,index) in $root.errors" 
                :key="index">
                <span 
                  v-for="message in e" 
                  :key="message">{{ message }}</span>
              </li>
            </ul>
          </div>
        </div>
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
<style>
.navbar.is-primary .navbar-end > a.navbar-item:hover,
.navbar.is-primary .navbar-brand > a.navbar-item:hover {
  background-color: #363636;
}

.navbar-laravel {
  background-color: #fff;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
}

.fa-user {
  margin-right: 5px;
}

.error-close {
  position: absolute;
  right: 25px;
}

.error-close i {
  font-size: 1.75rem;
}
</style>