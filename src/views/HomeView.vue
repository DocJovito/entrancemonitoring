<script setup>
import { ref } from 'vue';
// import { ref, defineEmits } from 'vue';
import MyModal from '@/components/MyModal.vue';

const isModalVisible = ref(false);

// Define emits to declare the toggleNav event
const emits = defineEmits(['toggleNav']);

// Function to show the modal
const showModal = () => {
  isModalVisible.value = true;
};

// Function to hide the modal
const hideModal = () => {
  isModalVisible.value = false;
};

// Function to toggle navigation (emitting toggleNav event)
const toggleNavigation = () => {
  emits('toggleNav');
};

</script>

<template>
  <h1>Home</h1>
  <div>
    <button @click="showModal">Open Modal</button>
    <MyModal v-if="isModalVisible" :isVisible="isModalVisible" title="My Custom Title" @close="hideModal">
      <p>This is the content of the modal.</p>
    </MyModal>
  </div>
</template>
