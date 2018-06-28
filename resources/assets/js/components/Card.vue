<template>
    <div class="card board-card">
        <div class="card-content">
        <textarea v-if="editing" type="text" ref="edit" class="textarea" v-model="newName" @keyup.enter="updateCard" @blur="editing = false"></textarea>
        <div v-else>
            <span class="card-navs is-pulled-right"><a @click.prevent="editCard"><i class="fas fa-edit"></i></a> <a @click.prevent="deleteThis"><i class="fas fa-trash"></i></a></span>
            <div @click="openModal">{{ card.name }}
            <span v-if="card.description"><br><i class="fas fa-comment"></i></span></div>
        </div>
        </div>
    </div>
</template>
<script>
import axios from 'axios';

export default {
    name: 'Card',
    data() {
        return {
            loading: false,
            error: null,
            editing: false,
            newName: "",
        };
    },
    props: {
        card: { type: Object, required: true }
    },
    methods: {
    deleteThis() {
        this.error = null;
        this.loading = true;
        axios
            .delete('/cards/' + this.card.id, { id: this.card.id })
            .then(response => {
                this.loading = false;
                this.$emit('delete-card', this.card);
            }).catch(error => {
                this.loading = false;
                this.error = error.response.data.message || error.message;
            });
    },
    updateCard() {
        this.error = null;
        this.loading = true;
        this.editing = false;
        axios
            .put('/cards/' + this.card.id, { name: this.newName })
            .then(response => {
                this.loading = false;
            }).catch(error => {
                this.loading = false;
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
