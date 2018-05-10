<template>
    <div class="boards">
        <div class="loading" v-if="loading">
            Loading...
        </div>

        <div v-if="error" class="error">
            {{ error }}
        </div>

        <ul v-if="boards">
            <li v-for="{ id, name } in boards">
                <strong>Name:</strong> <router-link :to="{ name: 'board_view', params: { id : id }}">{{ name }}</router-link>
            </li>
        </ul>

        <div class="new-board">
            <form v-on:submit.prevent>
                <input type="text" placeholder="New Board name" v-model="name">
                <button v-on:click="createNew">Create</button>
            </form>
        </div>
    </div>
</template>
<script>
import axios from 'axios';
export default {
    data() {
        return {
            loading: false,
            boards: null,
            error: null,
            name: "",
        };
    },
    created() {
        this.fetchData();
    },
    methods: {
    fetchData() {
        this.error = this.users = null;
        this.loading = true;
        axios
            .get('/boards')
            .then(response => {
                this.loading = false;
                this.boards = response.data;
            }).catch(error => {
                this.loading = false;
                this.error = error.response.data.message || error.message;
            });
    },
    createNew() {
        this.error = null;
        this.loading = true;
        axios
            .post('/boards', { name: this.name })
            .then(response => {
                this.loading = false;
                this.boards.push(response.data);
            }).catch(error => {
                this.loading = false;
                this.error = error.response.data.message || error.message;
            });
    }
}
}
</script>
