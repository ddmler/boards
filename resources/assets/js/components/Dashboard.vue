<template>
  <div class="boards">
    <div 
      v-if="loading" 
      class="loading">
      Loading...
    </div>

    <div 
      v-if="error" 
      class="error">
      {{ error }}
    </div>

    <ul v-if="boards">
      <li 
        v-for="board in boards" 
        :key="board.id">
        <strong>Board: </strong>
        <router-link :to="{ name: 'board_view', params: { id : board.id }}">{{ board.name }}</router-link> <a @click.prevent="deleteThis(board)"><i class="fas fa-trash"/></a>
      </li>
    </ul>

    <div class="new-board">
      <form @submit.prevent>
        <input 
          v-model="name" 
          type="text" 
          class="input" 
          placeholder="New Board name">
        <button 
          class="button" 
          @click="createNew">Create</button>
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
    },
    deleteThis: function(board) {
        this.error = null;
        this.loading = true;
        axios
            .delete('/boards/' + board.id, { id: board.id })
            .then(() => {
                this.loading = false;
                this.boards.splice(this.boards.indexOf(board), 1);
            }).catch(error => {
                this.loading = false;
                this.error = error.response.data.message || error.message;
            });
    }
}
}
</script>
