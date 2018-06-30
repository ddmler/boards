<template>
  <div class="list-wrapper">
    <modal 
      v-if="showModal" 
      :card="modalCard" 
      @close-modal="showModal = false"/>
    <div class="card">
      <header class="card-header board-list">
        <p class="card-header-title">
          <input 
            v-if="editing" 
            ref="edit" 
            v-model="newName" 
            type="text" 
            class="input" 
            @keyup.enter="updateList" 
            @blur="editing = false">
          <span v-else>List: {{ list.name }} <span class="list-navs"><a @click.prevent="clickEdit"><i class="fas fa-edit"/></a> <a @click.prevent="deleteThis"><i class="fas fa-trash"/></a></span></span>
        </p>
      </header>
      <div class="card-content">
        <draggable 
          v-model="list.cards" 
          :options="{group:'cards', ghostClass:'ghost'}" 
          class="dragArea" 
          @end="updateOrder">
          <card 
            v-for="card in orderedList" 
            :card="card" 
            :key="card.order + ',' + list.id + ',' + card.id" 
            :id="card.order" 
            @open-modal="openModal" 
            @delete-card="deleteCard"/>
        </draggable>
      </div>
      <footer class="card-footer">
        <input 
          v-if="showNew" 
          ref="new" 
          v-model="name" 
          type="text" 
          class="input" 
          placeholder="New Card name" 
          @keyup.enter="createNew" 
          @blur="showNew = false">
        <div v-else><a @click.prevent="clickNew">Create new Card</a></div>
      </footer>
    </div>
  </div>
</template>
<style scoped>
.dragArea {
    min-height: 15px;
}
</style>
<script>
import axios from 'axios';
import Card from './Card.vue';
import Modal from './Modal.vue';
import draggable from 'vuedraggable';
import _ from 'lodash';

export default {
    name: 'List',
    components: {
        Card,
        Modal,
        draggable
    },
    props: {
        list: { type: Object, required: true }
    },
    data() {
        return {
            error: null,
            name: "",
            editing: false,
            showNew: false,
            showModal: false,
            modalCard: null,
            newName: "",
        };
    },
    computed: {
        orderedList: function() {
            return _.orderBy(this.list.cards, 'order');
        }
    },
    methods: {
    createNew() {
        this.error = null;
        var order = (this.list.cards === undefined ? 0 : this.list.cards.length);
        axios
            .post('/cards', { list_id: this.list.id, name: this.name, order: order })
            .then(response => {
                this.list.cards.push(response.data);
                this.name = "";
                this.showNew = false;
            }).catch(error => {
                this.error = error.response || error.message;
            });
    },
    deleteCard: function (card) {
        this.list.cards.splice(this.list.cards.indexOf(card), 1);
    },
    deleteThis() {
        this.error = null;
        axios
            .delete('/boardLists/' + this.list.id, { id: this.list.id })
            .then(() => {
                this.$emit('delete-list', this.list);
            }).catch(error => {
                this.error = error.response.data.message || error.message;
            });
    },
    updateList() {
        this.error = null;
        this.editing = false;
        axios
            .put('/boardLists/' + this.list.id, { name: this.newName })
            .then(() => {
            }).catch(error => {
                this.error = error.response.data.message || error.message;
            });
        this.list.name = this.newName;
    },
    clickEdit() {
        this.editing = true;
        this.newName = this.list.name;
        this.$nextTick(() => this.$refs.edit.focus());
    },
    clickNew() {
        this.showNew = true;
        this.$nextTick(() => this.$refs.new.focus());
    },
    updateOrder() {
        this.$emit('update-card-order', this);
    },
    openModal(card) {
        this.modalCard = card;
        this.showModal = true;
    }
}
}
</script>
