<template>
    <div class="card">
        <div v-if="editing">
            <textarea type="text" v-model="card.name"></textarea>
            <button @click="updateCard">Save</button>
        </div>
        <span v-else><strong>Card:</strong> {{ card.name }} <a href="#" @click.prevent="setEditing">(Edit)</a> <a href="#" @click.prevent="deleteThis">(X)</a></span>
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
            .put('/cards/' + this.card.id, { name: this.card.name })
            .then(response => {
                this.loading = false;
            }).catch(error => {
                this.loading = false;
                this.error = error.response.data.message || error.message;
            });
    },
    setEditing() {
        this.editing = true;
    }
}
}
</script>
