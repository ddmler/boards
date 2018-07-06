<template>
  <div class="card board-card">
    <div class="card-content">
      <textarea 
        v-if="editing" 
        ref="edit" 
        v-model="newName" 
        type="text" 
        class="textarea" 
        @keyup.enter="updateCard" 
        @blur="editing = false"/>
      <div v-else>
        <span class="card-navs"><a @click.prevent="editCard"><i class="fas fa-edit"/></a> <a @click.prevent="deleteThis"><i class="fas fa-trash"/></a></span>
        <div @click="openModal">{{ card.name }}
        <span v-if="card.description"><br><i class="fas fa-comment"/></span></div>
      </div>
    </div>
  </div>
</template>
<style>
.card-navs {
  display: none;
  position: absolute;
  right: 12px;
}

.board-card {
  background-color: #363636;
  margin-bottom: 5px;
}

.board-card:hover .card-navs {
  display: block;
}

.board-card .card-content {
  padding: 0.75rem;
}
</style>
<script>
import axios from 'axios';
import { EventBus } from '../EventBus.js';

export default {
    name: 'Card',
    props: {
        card: { type: Object, required: true }
    },
    data() {
        return {
            editing: false,
            newName: "",
        };
    },
    methods: {
    deleteThis() {
        axios
            .delete('/cards/' + this.card.id)
            .then(() => {
                this.$emit('delete-card', this.card);
            }).catch(() => {});
    },
    updateCard() {
        this.editing = false;
        axios
            .put('/cards/' + this.card.id, { name: this.newName })
            .then((response) => {
                this.card.name = response.data.name;
            }).catch(() => {});
    },
    editCard() {
        this.editing = true;
        this.newName = this.card.name;
        this.$nextTick(() => this.$refs.edit.focus());
    },
    openModal() {
        EventBus.$emit('open-modal', this.card);
    }
}
}
</script>
