<template>
  <div>
    <input type="file" ref="fileInput" accept=".jpg" @change="handleFileUpload">
    <button @click="uploadImage">Upload Image</button>
    <div v-if="imageUrl">
      <img :src="imageUrl" alt="Uploaded Image">
      <p>Dimensions: {{ imageDimensions.width }} x {{ imageDimensions.height }}</p>
      <p>File Size: {{ imageSize }} KB</p>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';

const fileInput = ref(null);
const imageUrl = ref(null);
const imageDimensions = ref({ width: 0, height: 0 });
const imageSize = ref(0);

function handleFileUpload() {
  const file = fileInput.value.files[0];
  const validExtensions = ['jpg'];

  if (file) {
    const fileExtension = file.name.split('.').pop().toLowerCase();
    
    if (!validExtensions.includes(fileExtension)) {
      alert('Only .jpg files are allowed.');
      fileInput.value.value = ''; // Clear the file input
      return;
    }

    // Check file size (25KB to 50KB)
    const fileSizeKB = file.size / 1024;
    if (fileSizeKB < 25 || fileSizeKB > 50) {
      alert('File size must be between 25KB and 50KB.');
      fileInput.value.value = ''; // Clear the file input
      return;
    }
    imageSize.value = fileSizeKB.toFixed(2);

    // Load image to check dimensions
    const img = new Image();
    img.onload = () => {
      imageDimensions.value.width = img.width;
      imageDimensions.value.height = img.height;

      if (img.width < 300 || img.width > 340 || img.height < 300 || img.height > 340) {
        alert('Image dimensions must be between 300x300 and 340x340 pixels.');
        fileInput.value.value = ''; // Clear the file input
        return;
      }

      const reader = new FileReader();
      reader.onload = () => {
        imageUrl.value = reader.result;
      };
      reader.readAsDataURL(file);
    };
    img.src = URL.createObjectURL(file);
  }
}

async function uploadImage() {
  const file = fileInput.value.files[0];
  
  if (!file) {
    alert('Please select a file before uploading.');
    return;
  }
  
  const formData = new FormData();
  formData.append('image', file);

  try {
    const response = await axios.post('https://icpmymis.com/entrancemonitoring/backend/upload-image.php', formData, {
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
