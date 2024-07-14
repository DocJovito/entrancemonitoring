<template>
  <div>
    <input type="file" ref="fileInput" accept=".jpg" multiple @change="handleFileUpload">
    <button @click="uploadImage">Upload Images</button>
    <div class="image-grid" v-if="imageUrls.length">
      <div class="image-item" v-for="(image, index) in imageUrls" :key="index">
        <img :src="image.url" alt="Uploaded Image">
        <p>Filename: {{ image.filename }}</p> <!-- Display filename -->
        <p>Dimensions: {{ image.dimensions.width }} x {{ image.dimensions.height }}</p>
        <p>File Size: {{ image.size }} KB</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';

const fileInput = ref(null);
const imageUrls = ref([]);
const validExtensions = ['jpg']; // Valid file extensions
const maxFileSizeKB = 1000; // Maximum file size in KB

function handleFileUpload() {
  const files = fileInput.value.files;

  // Clear previous data
  imageUrls.value = [];

  for (const file of files) {
    const fileExtension = file.name.split('.').pop().toLowerCase();

    if (!validExtensions.includes(fileExtension)) {
      alert('Only .jpg files are allowed.');
      fileInput.value.value = ''; // Clear the file input
      return;
    }

    // Check file size
    const fileSizeKB = file.size / 1024;
    if (fileSizeKB > maxFileSizeKB) {
      alert(`File size must be less than ${maxFileSizeKB} KB.`);
      fileInput.value.value = ''; // Clear the file input
      return;
    }

    // Load image to get dimensions
    const img = new Image();
    img.onload = () => {
      const dimensions = { width: img.width, height: img.height };

      if (dimensions.width < 300 || dimensions.width > 340 || dimensions.height < 300 || dimensions.height > 340) {
        alert('Image dimensions must be between 300x300 and 340x340 pixels.');
        fileInput.value.value = ''; // Clear the file input
        return;
      }

      // Add image data to array
      imageUrls.value.push({
        url: URL.createObjectURL(file),
        filename: file.name,
        dimensions: dimensions,
        size: fileSizeKB.toFixed(2)
      });
    };
    img.src = URL.createObjectURL(file);
  }
}

async function uploadImage() {
  const files = fileInput.value.files;

  if (!files.length) {
    alert('Please select files before uploading.');
    return;
  }

  const formData = new FormData();
  for (const file of files) {
    formData.append('images[]', file);
  }

  try {
    const response = await axios.post('https://icpmymis.com/entrancemonitoring/backend/upload-image.php', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });
    alert('Images uploaded successfully');
    console.log(response.data);
  } catch (error) {
    alert('Failed to upload images');
    console.error(error);
  }
}
</script>

<style scoped>
.image-grid {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  /* Adjust as needed */
}

.image-item {
  width: calc(20% - 10px);
  /* 5 images in a row */
  text-align: center;
}
</style>
