<script setup>
import { defineEmits } from 'vue'

const emit = defineEmits(['toggle-nav'])
</script>

<template>
  <h1>Home</h1>
  <button @click="emit('toggle-nav')">Toggle Nav</button>
</template>
