<template>
    <div class="card">
        <strong>Card:</strong> {{ card.name }}
        <!-- delete card -->
    </div>
</template>
<script>
export default {
    name: 'Card',
    props: {
        card: { type: Object, required: true }
    }
}
</script>
