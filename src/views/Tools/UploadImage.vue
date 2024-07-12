<template>
    <div>
      <input type="file" ref="fileInput" @change="handleFileUpload">
      <button @click="uploadImage">Upload Image</button>
      <div v-if="imageUrl">
        <img :src="imageUrl" alt="Uploaded Image">
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import axios from 'axios';
  
  const fileInput = ref(null);
  const imageUrl = ref(null);
  
  function handleFileUpload() {
    const file = fileInput.value.files[0];
    const reader = new FileReader();
    reader.onload = () => {
      imageUrl.value = reader.result;
    };
    reader.readAsDataURL(file);
  }
  
  async function uploadImage() {
    const formData = new FormData();
    formData.append('image', fileInput.value.files[0]);
  
    try {
      const response = await axios.post('https://rjprint10.com/entrancemonitoring/backend/upload-image.php', formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      });
      alert('Image uploaded successfully');
      console.log(response.data);
    } catch (error) {
      alert('Failed to upload image');
      console.error(error);
    }
  }
  </script>
  