<template>
<div class="board-list">
    <strong>List:</strong> {{ list.name }}

    <!-- Create new card, delete list -->

    <ul>
        <li v-for="card in list.cards">
            <card :card="card"></card>
        </li>
    </ul>
</div>
</template>
<script>
import Card from './Card.vue';

export default {
    name: 'List',
    components: {
        Card
    },
    props: {
        list: { type: Object, required: true }
    }
}
</script>
