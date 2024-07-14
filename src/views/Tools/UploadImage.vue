<template>
  <div>
    <input type="file" ref="fileInput" accept=".jpg" multiple @change="handleFileUpload">
    <button @click="uploadImage">Upload Images</button>
    <div v-if="imageUrls.length">
      <div v-for="(url, index) in imageUrls" :key="index">
        <img :src="url" alt="Uploaded Image">
        <p>Dimensions: {{ imageDimensions[index].width }} x {{ imageDimensions[index].height }}</p>
        <p>File Size: {{ imageSizes[index] }} KB</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';

const fileInput = ref(null);
const imageUrls = ref([]);
const imageDimensions = ref([]);
const imageSizes = ref([]);

function handleFileUpload() {
  const files = fileInput.value.files;
  imageUrls.value = [];
  imageDimensions.value = [];
  imageSizes.value = [];

  const validExtensions = ['jpg'];

  for (const file of files) {
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
    imageSizes.value.push(fileSizeKB.toFixed(2));

    // Load image to check dimensions
    const img = new Image();
    img.onload = () => {
      if (img.width < 300 || img.width > 340 || img.height < 300 || img.height > 340) {
        alert('Image dimensions must be between 300x300 and 340x340 pixels.');
        fileInput.value.value = ''; // Clear the file input
        return;
      }

      imageDimensions.value.push({ width: img.width, height: img.height });

      const reader = new FileReader();
      reader.onload = () => {
        imageUrls.value.push(reader.result);
      };
      reader.readAsDataURL(file);
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
