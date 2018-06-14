<template>
    <div class="card">
        <div class="card-content">
        <textarea v-if="editing" type="text" ref="edit" class="textarea" v-model="newName" @keyup.enter="updateCard" @blur="editing = false"></textarea>
        <span v-else>{{ card.name }} <a href="#" @click.prevent="editCard">(Edit)</a> <a class="delete" @click.prevent="deleteThis"></a></span>
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
    }
}
}
</script>
