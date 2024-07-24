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
  <div class="home-page">
    <header class="header">
      <h1 class="title">Immaculate Conception Polytechnic</h1>
      <h1 class="h4">Santa Maria | Marilao | Meycauayan | Balagtas | City of San Jose Del Monte</h1>
    </header>
    <div class="row-center mb-4">
      <div class="col-20">
        <img src="https://icpmymis.com/images/ICPLogo.JPG" alt="ICP Logo" class="logo">
      </div>
      <div class="col-80">
        <div class="info-card-wide">
          <h2>Welcome to ICP Entrance Monitoring</h2>
          <p>Our advanced entrance monitoring system ensures a secure and efficient way to manage and track all entries and exits within the campus. Experience the best in security and convenience with real-time monitoring, customizable alerts, and detailed reporting features.</p>
        </div>
      </div>
    </div>
    <section class="info-section">
      <div class="info-card">
        <h2>Real-Time Monitoring</h2>
        <p>Keep track of all entrances and exits in real-time with our advanced monitoring system.</p>
      </div>
      <div class="info-card">
        <h2>Secure and Reliable</h2>
        <p>Our system ensures the highest level of security and reliability for all users.</p>
      </div>
      <div class="info-card">
        <h2>Easy Integration</h2>
        <p>Seamlessly integrate with your existing infrastructure and systems.</p>
      </div>
      <div class="info-card">
        <h2>Customizable Alerts</h2>
        <p>Receive instant alerts for unauthorized access attempts and other critical events.</p>
      </div>
      <div class="info-card">
        <h2>Detailed Reports</h2>
        <p>Generate comprehensive reports to analyze entrance and exit patterns.</p>
      </div>
    </section>
    <footer class="footer">
      <button class="modal-button" @click="showModal">Open Modal</button>
    </footer>
    <MyModal v-if="isModalVisible" :isVisible="isModalVisible" title="My Custom Title" @close="hideModal">
      <p>This is the content of the modal.</p>
    </MyModal>
  </div>
</template>

<style scoped>
.home-page {
  font-family: Arial, sans-serif;
  color: #333;
}

.header {
  background: linear-gradient(90deg, #007BFF, #A0D2FF);
  padding: 20px;
  text-align: center;
  color: white;
}

.title {
  margin: 0;
  font-size: 2.5rem;
}

.text-center {
  text-align: center;
}

.mb-4 {
  margin-bottom: 1.5rem;
}

.row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px;
}

.col-20 {
  flex: 0 0 20%;
  text-align: center;
}

.col-80 {
  flex: 0 0 80%;
}

.logo {
  max-width: 100%;
  height: auto;
  border-radius: 10px;
  width: 300px; /* Adjust the width as needed */
  height: 300px; /* Adjust the height as needed */
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.info-card-wide {
  background: white;
  border-radius: 10px;
  padding: 20px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  text-align: center;
  margin: 10px;
  width: calc(100% - 20px); /* Adjust the width to fit the container */
}

.info-section {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-around;
  padding: 20px;
  background-color: #f7f7f7;
}

.info-card {
  background: white;
  border-radius: 10px;
  padding: 20px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  text-align: center;
  flex: 1;
  margin: 10px;
  max-width: 300px;
}

.info-card h2 {
  color: #007BFF;
  font-size: 1.5rem;
  margin-bottom: 10px;
}

.info-card p {
  font-size: 1rem;
  color: #666;
}

.footer {
  display: flex;
  justify-content: flex-end;
  padding: 20px;
  background-color: #A0D2FF;
}

.modal-button {
  background-color: #007BFF;
  color: white;
  border: none;
  padding: 10px 20px;
  font-size: 1rem;
  border-radius: 5px;
  cursor: pointer;
}

.modal-button:hover {
  background-color: #0056b3;
}
</style>
