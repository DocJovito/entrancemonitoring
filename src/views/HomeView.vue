<script setup>
import { ref } from 'vue';
import MyModal from '@/components/MyModal.vue';

const isModalVisible = ref(false);

const showModal = () => {
  isModalVisible.value = true;
};

const hideModal = () => {
  isModalVisible.value = false;
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