<template>
    <div class="boards">
        <div class="loading" v-if="loading">
            Loading...
        </div>

        <div v-if="error" class="error">
            {{ error }}
        </div>

        <ul v-if="board">
            {{ board.name }}
            <li v-for="{ name } in board.board_lists">
                <strong>Name:</strong> {{ name }}
            </li>
        </ul>
    </div>
</template>
<script>
import axios from 'axios';
export default {
    data() {
        return {
            loading: false,
            board: null,
            error: null,
        };
    },
    created() {
        this.fetchData();
    },
    watch: {
        '$route': 'fetchData'
    },
    methods: {
    fetchData() {
        this.error = this.users = null;
        this.loading = true;
        axios
            .get('/boards/' + this.$route.params.id)
            .then(response => {
                this.loading = false;
                this.board = response.data;
            }).catch(error => {
                this.loading = false;
                this.error = error.response.data.message || error.message;
            });
    }
}
}
</script>
