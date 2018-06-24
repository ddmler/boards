<template>
<div class="card">
    <header class="card-header board-list">
        <p class="card-header-title">
    <input v-if="editing" ref="edit" type="text" class="input" v-model="newName" @keyup.enter="updateList" @blur="editing = false">
    <span v-else>List: {{ list.name }} <span class="list-navs is-pulled-right"><a @click.prevent="clickEdit"><i class="fas fa-edit"></i></a> <a @click.prevent="deleteThis"><i class="fas fa-trash"></i></a></span></span>
</p>
</header>
<div class="card-content">
    <draggable v-model="list.cards" class="dragArea" :options="{group:'cards', ghostClass:'ghost'}" @end="updateOrder">
            <card v-for="card in orderedList" :card="card" @delete-card="deleteCard" :key="card.order + ',' + list.id + ',' + card.id" :id="card.id"></card>
    </draggable>
</div>
<footer class="card-footer">
    <input v-if="showNew" ref="new" type="text" class="input" placeholder="New Card name" v-model="name" @keyup.enter="createNew" @blur="showNew = false">
    <span v-else><a @click.prevent="clickNew">Create new Card</a></span>
</footer>
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
import draggable from 'vuedraggable';
import _ from 'lodash';

export default {
    name: 'List',
    components: {
        Card,
        draggable
    },
    data() {
        return {
            loading: false,
            error: null,
            name: "",
            editing: false,
            showNew: false,
            newName: "",
        };
    },
    props: {
        list: { type: Object, required: true }
    },
    computed: {
        orderedList: function() {
            return _.orderBy(this.list.cards, 'order');
        }
    },
    methods: {
    createNew() {
        this.error = null;
        this.loading = true;
        var order = this.list.cards.length;
        axios
            .post('/cards', { list_id: this.list.id, name: this.name, order: order })
            .then(response => {
                this.loading = false;
                this.list.cards.push(response.data);
                this.name = "";
                this.showNew = false;
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
