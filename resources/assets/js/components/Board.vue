<template>
  <div class="boards">
    <ul v-if="board">
      <input 
        v-if="editing" 
        ref="edit" 
        v-model="newName" 
        type="text" 
        class="input" 
        @keyup.enter="updateBoard" 
        @blur="editing = false">
      <span 
        v-else 
        @click="editBoard">Board: {{ board.name }}</span>


      <div class="flex_wrapper">
        <div 
          v-for="list in board.board_lists" 
          :key="list.id" 
          class="list">
          <list 
            :list="list" 
            @delete-list="deleteList" 
            @update-card-order="updateCardOrder"/>
        </div>
        <div class="list new-list">
          <form @submit.prevent>
            <input 
              v-model="name" 
              type="text" 
              class="input" 
              placeholder="New List name">
            <button 
              class="button" 
              @click="createNew">Create</button>
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
            board: null,
            name: "",
            editing: false,
            newName: "",
        };
    },
    watch: {
        '$route': 'fetchData'
    },
    created() {
        this.fetchData();
    },
    methods: {
    fetchData() {
        axios
            .get('/boards/' + this.$route.params.id)
            .then(response => {
                this.board = response.data;
            }).catch(() => {});
    },
    createNew() {
        axios
            .post('/boardLists', { board_id: this.board.id, name: this.name })
            .then(response => {
                this.board.board_lists.push(response.data);
            }).catch(() => {});
            this.name = "";
    },
    deleteList: function (boardlist) {
        this.board.board_lists.splice(this.board.board_lists.indexOf(boardlist), 1);
    },
    updateBoard() {
        this.editing = false;
        axios
            .put('/boards/' + this.board.id, { name: this.newName })
            .then(() => {
            }).catch(() => {});
        this.board.name = this.newName;
    },
    editBoard() {
        this.editing = true;
        this.newName = this.board.name
        this.$nextTick(() => this.$refs.edit.focus());
    },
    updateCardOrder() {
        var i = 0;
        for (let list of this.board.board_lists) {
            i = 0;
            for (let c of list.cards) {
                c.order = i;
                i++;
            }
        }

        axios
            .patch('/board/updateOrder', { board: this.board })
            .then(() => {
                //
            }).catch(() => {});
    }
}
}
</script>
