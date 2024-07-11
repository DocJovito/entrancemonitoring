<script setup>
import { RouterView, useRoute } from 'vue-router'
import NavBar from '@/components/NavBar.vue'
import { computed } from 'vue'


// Get the current route
const route = useRoute();

// Compute whether to show the NavBar based on the current route
const showNav = computed(() => route.name !== 'timekeeping');

</script>

<template>
  <NavBar v-if="showNav" />
  <RouterView @toggle-nav="toggleNav" />
</template>

<style scoped></style>
