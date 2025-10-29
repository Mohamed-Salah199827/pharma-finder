<template>
  <div class="card h-100">
    <div class="card-body">
      <h5 class="card-title">{{ product.name }}</h5>
      <p v-if="product.description" class="card-text text-muted">{{ product.description }}</p>
      <slot name="footer"></slot>
    </div>
  </div>
</template>

<script setup>
defineProps({ product: { type: Object, required: true } });
</script>
