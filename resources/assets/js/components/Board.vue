<template>
  <div class="boards">
    <modal 
      v-if="showModal" 
      :card="modalCard" 
      @close-modal="showModal = false"/>
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
        class="board-name"
        @click="editBoard">Board: {{ board.name }}</span>


      <div>
        <draggable 
          v-model="board.board_lists" 
          :options="{group:'lists', ghostClass:'ghost'}" 
          class="dragArea flex-wrapper" 
          @end="updateListOrder">
          <list 
            v-for="list in orderedList" 
            :list="list" 
            :key="list.order + ',' + board.id + ',' + list.id" 
            :id="list.order"
            @delete-list="deleteList" 
            @update-card-order="updateCardOrder"/>
          <div class="list new-list">
            <form @submit.prevent>
              <input 
                v-model="name" 
                type="text" 
                class="input" 
                placeholder="New List name"
                required>
              <button 
                class="button" 
                @click="createNew">Create</button>
            </form>
          </div>
        </draggable>
      </div>
    </ul>

  </div>
</template>
<style scoped>
.board-name {
    font-size: 1.5rem;
    padding-left: 10px;
}

.flex-wrapper {
    display: flex;
    flex-wrap: nowrap;
    overflow-x: auto;
}

.flex-wrapper .list {
    flex: 0 0 auto;
    width: 270px;
    margin: 5px;
}

.dragArea {
    min-height: 15px;
}
</style>
<script>
import axios from 'axios';
import List from './List.vue';
import Modal from './Modal.vue';
import { EventBus } from '../EventBus.js';
import draggable from 'vuedraggable';
import _ from 'lodash';

export default {
    name: 'Board',
    components: {
        List,
        Modal,
        draggable
    },
    data() {
        return {
            board: null,
            name: "",
            showModal: false,
            modalCard: null,
            editing: false,
            newName: "",
        };
    },
    computed: {
        orderedList: function() {
            return _.orderBy(this.board.board_lists, 'order');
        }
    },
    watch: {
        '$route': 'fetchData'
    },
    created() {
        this.fetchData();
    },
    mounted() {
        EventBus.$on('open-modal', card => {
            this.modalCard = card;
            this.showModal = true;
        });
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
      var order = (this.board.board_lists === undefined ? 0 : this.board.board_lists.length);
        axios
            .post('/boardLists', { board_id: this.board.id, name: this.name, order: order })
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
            .then((response) => {
                this.board.name = response.data.name;
            }).catch(() => {});
    },
    editBoard() {
        this.editing = true;
        this.newName = this.board.name
        this.$nextTick(() => this.$refs.edit.focus());
    },
    updateListOrder() {
        var i = 0;
        for (let list of this.board.board_lists) {
          list.order = i;
          i++;
        }

        axios
            .patch('/board/updateListOrder', { board: this.board })
            .then(() => {
                //
            }).catch(() => {});
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
