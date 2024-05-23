<template>
  <div class="fast-gradient" />
</template>

<script setup>
defineProps({
  height: {
    type: String,
    default: "100px",
  },
});
</script>

<style lang="sass">
.fast-gradient
  background: linear-gradient(to right, rgba(255, 255, 255, 0.1), rgba(0, 0, 0, 0.4))
  background-size: 200% 200%
  animation: gradient 2s ease-in-out infinite
  min-height: v-bind(height)
  border-radius: 4px

@keyframes gradient
  0%
    background-position: 0% 50%
  50%
    background-position: 100% 50%
  100%
    background-position: 0% 50%
</style>
