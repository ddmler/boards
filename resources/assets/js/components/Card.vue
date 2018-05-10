<template>
    <div class="card">
        <strong>Card:</strong> {{ card.name }}
        <a href="#" v-on:click.prevent="deleteThis">(X)</a>
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
    }
}
}
</script>
