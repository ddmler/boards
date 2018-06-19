<template>
    <div class="boards">
        <div class="loading" v-if="loading">
            Loading...
        </div>

        <div v-if="error" class="error">
            {{ error }}
        </div>

        <ul v-if="board">
            <input v-if="editing" ref="edit" type="text" class="input" v-model="newName" @keyup.enter="updateBoard" @blur="editing = false">
            <span v-else>Board: {{ board.name }} <a href="#" @click.prevent="editBoard">(Edit)</a></span>


            <div class="flex_wrapper">
            <div class="list" v-for="list in board.board_lists">
                <list :list="list" @delete-list="deleteList"></list>
            </div>
            <div class="list new-list">
                <form @submit.prevent>
                    <input type="text" class="input" placeholder="New List name" v-model="name">
                    <button @click="createNew" class="button">Create</button>
                </form>
            </div>
            </div>
        </ul>

    </div>
</template>
<style scoped>
.flex_wrapper {
    display: flex;
    flex-wrap: nowrap;
    overflow-x: auto;
}
.flex_wrapper .list {
    flex: 0 0 auto;
    width: 270px;
    margin: 5px;
}
</style>
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
            newName: "",
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
            .put('/boards/' + this.board.id, { name: this.newName })
            .then(response => {
                this.loading = false;
            }).catch(error => {
                this.loading = false;
                this.error = error.response.data.message || error.message;
            });
        this.board.name = this.newName;
    },
    editBoard() {
        this.editing = true;
        this.newName = this.board.name
        this.$nextTick(() => this.$refs.edit.focus());
    }
}
}
</script>
