<template>
  <nav class="d-flex justify-content-between align-items-center">
    <button
      class="btn btn-outline-secondary"
      :disabled="page <= 1"
      @click="$emit('update:page', page - 1)"
    >
      Prev
    </button>
    <span>Page {{ page }}</span>
    <button
      class="btn btn-outline-secondary"
      :disabled="!hasMore"
      @click="$emit('update:page', page + 1)"
    >
      Next
    </button>
  </nav>
</template>
<script setup>
defineProps({ page: { type: Number, default: 1 }, hasMore: { type: Boolean, default: false } });
</script>
