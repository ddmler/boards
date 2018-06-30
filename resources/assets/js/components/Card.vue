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
        <div @click="openModal">{{ card.name }} ({{ card.order }})
        <span v-if="card.description"><br><i class="fas fa-comment"/></span></div>
      </div>
    </div>
  </div>
</template>
<script>
import axios from 'axios';

export default {
    name: 'Card',
    props: {
        card: { type: Object, required: true }
    },
    data() {
        return {
            error: null,
            editing: false,
            newName: "",
        };
    },
    methods: {
    deleteThis() {
        this.error = null;
        axios
            .delete('/cards/' + this.card.id, { id: this.card.id })
            .then(() => {
                this.$emit('delete-card', this.card);
            }).catch(error => {
                this.error = error.response.data.message || error.message;
            });
    },
    updateCard() {
        this.error = null;
        this.editing = false;
        axios
            .put('/cards/' + this.card.id, { name: this.newName })
            .then(() => {
            }).catch(error => {
                this.error = error.response.data.message || error.message;
            });
        this.card.name = this.newName;
    },
    editCard() {
        this.editing = true;
        this.newName = this.card.name;
        this.$nextTick(() => this.$refs.edit.focus());
    },
    openModal() {
        this.$emit('open-modal', this.card);
    }
}
}
</script>
