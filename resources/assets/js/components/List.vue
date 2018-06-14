<template>
<div class="card">
    <div class="card-header">
        <p class="card-header-title">
    <input v-if="editing" ref="edit" type="text" class="input" v-model="newName" @keyup.enter="updateList" @blur="editing = false">
    <span v-else>List: {{ list.name }} <a href="#" @click.prevent="clickEdit">(Edit)</a> <a class="delete" @click.prevent="deleteThis"></a></span>
</p>
</div>
<div class="card-content">
    <ul>
        <li v-for="card in list.cards">
            <card :card="card" @delete-card="deleteCard"></card>
        </li>
    </ul>

    <div class="new-card">
        <form @submit.prevent>
            <input type="text" class="input" placeholder="New Card name" v-model="name">
            <button @click="createNew" class="button">Create</button>
        </form>
    </div>
</div>
</div>
</template>
<script>
import axios from 'axios';
import Card from './Card.vue';

export default {
    name: 'List',
    components: {
        Card
    },
    data() {
        return {
            loading: false,
            error: null,
            name: "",
            editing: false,
            newName: "",
        };
    },
    props: {
        list: { type: Object, required: true }
    },
    methods: {
    createNew() {
        this.error = null;
        this.loading = true;
        axios
            .post('/cards', { list_id: this.list.id, name: this.name })
            .then(response => {
                this.loading = false;
                this.list.cards.push(response.data);
            }).catch(error => {
                this.loading = false;
                this.error = error.response.data.message || error.message;
            });
    },
    deleteCard: function (card) {
        this.list.cards.splice(this.list.cards.indexOf(card), 1);
    },
    deleteThis() {
        this.error = null;
        this.loading = true;
        axios
            .delete('/boardLists/' + this.list.id, { id: this.list.id })
            .then(response => {
                this.loading = false;
                this.$emit('delete-list', this.list);
            }).catch(error => {
                this.loading = false;
                this.error = error.response.data.message || error.message;
            });
    },
    updateList() {
        this.error = null;
        this.loading = true;
        this.editing = false;
        axios
            .put('/boardLists/' + this.list.id, { name: this.newName })
            .then(response => {
                this.loading = false;
            }).catch(error => {
                this.loading = false;
                this.error = error.response.data.message || error.message;
            });
        this.list.name = this.newName;
    },
    clickEdit() {
        this.editing = true;
        this.newName = this.list.name;
        this.$nextTick(() => this.$refs.edit.focus());
    }
}
}
</script>
