<template>
    <div class="boards">
        <div class="loading" v-if="loading">
            Loading...
        </div>

        <div v-if="error" class="error">
            {{ error }}
        </div>

        <ul v-if="board">
            Board:
            <input v-if="editing" type="text" v-model="board.name" @keyup.enter="updateBoard">
            <span v-else>{{ board.name }} <a href="#" @click.prevent="editing = true">(Edit)</a></span>

            <!-- Create new List -->

            <li v-for="list in board.board_lists">
                <list :list="list" @delete-list="deleteList"></list>
            </li>
        </ul>

        <div class="new-list">
            <form @submit.prevent>
                <input type="text" placeholder="New List name" v-model="name">
                <button @click="createNew">Create</button>
            </form>
        </div>
    </div>
</template>
<script>
import axios from 'axios';
import List from './List.vue';

export default {
    name: 'Board',
    components: {
        List
    },
    data() {
        return {
            loading: false,
            board: null,
            error: null,
            name: "",
            editing: false,
        };
    },
    created() {
        this.fetchData();
    },
    watch: {
        '$route': 'fetchData'
    },
    methods: {
    fetchData() {
        this.error = this.users = null;
        this.loading = true;
        axios
            .get('/boards/' + this.$route.params.id)
            .then(response => {
                this.loading = false;
                this.board = response.data;
            }).catch(error => {
                this.loading = false;
                this.error = error.response.data.message || error.message;
            });
    },
    createNew() {
        this.error = null;
        this.loading = true;
        axios
            .post('/boardLists', { board_id: this.board.id, name: this.name })
            .then(response => {
                this.loading = false;
                this.board.board_lists.push(response.data);
            }).catch(error => {
                this.loading = false;
                this.error = error.response.data.message || error.message;
            });
    },
    deleteList: function (boardlist) {
        this.board.board_lists.splice(this.board.board_lists.indexOf(boardlist), 1);
    },
    updateBoard() {
        this.error = null;
        this.loading = true;
        this.editing = false;
        axios
            .put('/boards/' + this.board.id, { name: this.board.name })
            .then(response => {
                this.loading = false;
            }).catch(error => {
                this.loading = false;
                this.error = error.response.data.message || error.message;
            });
    }
}
}
</script>
