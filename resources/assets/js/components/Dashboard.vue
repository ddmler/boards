<template>
    <div class="boards">
        <div class="loading" v-if="loading">
            Loading...
        </div>

        <div v-if="error" class="error">
            {{ error }}
        </div>

        <ul v-if="boards">
            <li v-for="{ name } in boards">
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
            boards: null,
            error: null,
        };
    },
    created() {
        this.fetchData();
    },
    methods: {
    fetchData() {
        this.error = this.users = null;
        this.loading = true;
        axios
            .get('/boards')
            .then(response => {
                this.loading = false;
                this.boards = response.data;
            }).catch(error => {
                this.loading = false;
                this.error = error.response.data.message || error.message;
            });
    }
}
}
</script>
