<script setup>
import { RouterView } from 'vue-router'
import NavBar from '@/components/NavBar.vue'

import { ref } from 'vue'

const showNav = ref(true);

const toggleNav = () => {
  showNav.value = !showNav.value;
};
</script>

<template>
  <NavBar v-if="showNav" />
  <RouterView @toggle-nav="toggleNav" />
</template>

<style scoped></style>
