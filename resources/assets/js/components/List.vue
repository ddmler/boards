<template>
  <div class="list-wrapper">
    <div class="card list">
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

.list-navs {
  display: none;
  position: absolute;
  right: 12px;
  top: 12px;
}

.board-list:hover .list-navs {
  display: block;
}

.card-footer {
  padding-bottom: 7px;
  padding-top: 7px;
}

.card-footer div {
  margin: auto;
}

.list-wrapper {
    flex: 0 0 auto;
    width: 270px;
    margin: 5px;
}

.list-wrapper .card.list {
    min-width: 270px;
}
</style>
<script>
import axios from 'axios';
import Card from './Card.vue';
import draggable from 'vuedraggable';
import _ from 'lodash';

export default {
    name: 'List',
    components: {
        Card,
        draggable
    },
    props: {
        list: { type: Object, required: true }
    },
    data() {
        return {
            name: "",
            editing: false,
            showNew: false,
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
        var order = (this.list.cards === undefined ? 0 : this.list.cards.length);
        axios
            .post('/cards', { list_id: this.list.id, name: this.name, order: order })
            .then(response => {
                this.list.cards.push(response.data);
                this.name = "";
                this.showNew = false;
            }).catch(() => {});
    },
    deleteCard: function (card) {
        this.list.cards.splice(this.list.cards.indexOf(card), 1);
    },
    deleteThis() {
        axios
            .delete('/boardLists/' + this.list.id)
            .then(() => {
                this.$emit('delete-list', this.list);
            }).catch(() => {});
    },
    updateList() {
        this.editing = false;
        axios
            .put('/boardLists/' + this.list.id, { name: this.newName })
            .then((response) => {
                this.list.name = response.data.name;
            }).catch(() => {});
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
    }
}
}
</script>
