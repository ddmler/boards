<template>
    <div>
        <div class="panel panel-default" v-if="$auth.ready()">
            <div class="panel-heading">
                <nav>
                    <ul class="list-inline">
                        <li>
                            <router-link :to="{ name: 'home' }">Home</router-link>
                        </li>
                        <li v-if="!$auth.check()" class="pull-right">
                            <router-link :to="{ name: 'login' }">Login</router-link>
                        </li>
                        <li v-if="!$auth.check()" class="pull-right">
                            <router-link :to="{ name: 'register' }">Register</router-link>
                        </li>
                        <li v-if="$auth.check()" class="pull-right">
                            <a href="#" @click.prevent="$auth.logout()">Logout ({{ $auth.user().name }})</a>
                        </li>
                    </ul>
                </nav>
            </div>
            <div class="panel-body">
                <router-view></router-view>
            </div>
        </div>
        <div v-if="!$auth.ready()">
            Site loading...
        </div>
    </div>
</template>
