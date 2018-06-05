<template>
    <div>
        <div v-if="$auth.ready()">
                <nav class="navbar is-primary">
                    <div class="navbar-brand">
                        <router-link :to="{ name: 'home' }" v-if="!$auth.check()" class="navbar-item">Boards</router-link>
                        <router-link :to="{ name: 'dashboard' }" v-if="$auth.check()" class="navbar-item">Boards</router-link>
                    </div>
                    <div class="navbar-menu">
                        <div class="navbar-start">

                        </div>
                        <div class="navbar-end">
                            <router-link :to="{ name: 'login' }" v-if="!$auth.check()" class="navbar-item">Login</router-link>
                            <router-link :to="{ name: 'register' }" v-if="!$auth.check()" class="navbar-item">Register</router-link>
                            <a href="#" @click.prevent="$auth.logout()" v-if="$auth.check()" class="navbar-item">Logout ({{ $auth.user().name }})</a>
                        </div>
                    </div>
                </nav>
            <div>
                <router-view></router-view>
            </div>
            </div>
        <div v-if="!$auth.ready()">
            Site loading...
        </div>
    </div>
</template>
