<template>
<div class="board-list">
    <strong>List:</strong> {{ list.name }} <a href="#" v-on:click.prevent="deleteThis">(X)</a>

    <!-- Create new card, delete list -->

    <ul>
        <li v-for="card in list.cards">
            <card :card="card" v-on:delete-card="deleteCard"></card>
        </li>
    </ul>

    <div class="new-card">
        <form v-on:submit.prevent>
            <input type="text" placeholder="New Card name" v-model="name">
            <button v-on:click="createNew">Create</button>
        </form>
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
    }
}
}
</script>
