<template>
  <div>
    <h1>Your Boards</h1>
    <div class="flex-wrapper">
      <div 
        v-for="board in boards"
        :key="board.id" 
        class="flex-item board">

        <div class="card">
          <div class="card-content">
            <router-link :to="{ name: 'board_view', params: { id : board.id }}">{{ board.name }}</router-link>
            <span class="board-navs">
              <a @click.prevent="deleteThis(board)"><i class="fas fa-trash"/></a>
            </span>
          </div>
        </div>
      </div>

      <div class="flex-item">
        <form @submit.prevent>
          <input 
            v-model="name" 
            type="text" 
            class="input" 
            placeholder="New Board name"
            required>
          <button 
            class="button" 
            @click="createNew">Create</button>
        </form>
      </div>
    </div>
  </div>
</template>
<style scoped>
h1 {
    font-size: 1.75rem;
    margin-left: 10px;
}

.flex-wrapper {
    display: flex;
    flex-wrap: wrap;
}

.flex-item {
    flex: 0 0 auto;
    width: 270px;
    margin: 5px;
}

.board-navs {
  display: none;
  position: absolute;
  right: 12px;
  top: 25px;
}

.board:hover .board-navs {
  display: block;
}
</style>
<script>
import axios from 'axios';

export default {
    data() {
        return {
            boards: null,
            name: "",
        };
    },
    created() {
        this.fetchData();
    },
    methods: {
    fetchData() {
        axios
            .get('/boards')
            .then(response => {
                this.boards = response.data;
            }).catch(() => {});
    },
    createNew() {
        axios
            .post('/boards', { name: this.name })
            .then(response => {
                this.boards.push(response.data);
            }).catch(() => {});
    },
    deleteThis: function(board) {
        axios
            .delete('/boards/' + board.id)
            .then(() => {
                this.boards.splice(this.boards.indexOf(board), 1);
            }).catch(() => {});
    }
}
}
</script>
