<template>
  <div class="container mt-5">
    <h2>File Maintenance Time Keep</h2>
    
    <div class="form-group">
      <label for="searchQuery">Search by Employee ID, RFID, Last Name, or First Name</label>
      <input type="text" class="form-control" id="searchQuery" v-model="searchQuery" @input="searchEmployee" placeholder="Enter ID, RFID, Last Name, or First Name">
    </div>

    <div v-if="filteredEmployees.length" class="list-group mb-3">
      <button type="button" class="list-group-item list-group-item-action" 
              v-for="employee in filteredEmployees" 
              :key="employee.id" 
              @click="selectEmployee(employee)">
        {{ employee.lastName }}, {{ employee.firstName }} - {{ employee.position }}
      </button>
    </div>

    <div v-if="employee" class="card mb-3">
      <div class="card-body">
        <h5 class="card-title">{{ employee.lastName }}, {{ employee.firstName }}</h5>
        <p class="card-text">Position: {{ employee.position }}</p>
        <p class="card-text">Department: {{ employee.department }}</p>
        <p class="card-text">Employee Type: {{ employee.empType }}</p>
      </div>
    </div>

    <div v-if="employee" class="form-row">
      <div class="form-group col-md-6">
        <label for="date">Date</label>
        <input type="date" class="form-control" id="date" v-model="timeKeep.date">
      </div>
      <div class="form-group col-md-6">
        <label for="time">Time</label>
        <input type="time" class="form-control" id="time" v-model="timeKeep.time">
      </div>
    </div>

    <button v-if="employee" class="btn btn-primary mb-3" @click="addTimeKeep">Add TimeKeep</button>

    <div v-if="timeKeepList.length">
      <h3>Time Keep Records</h3>
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Date</th>
            <th>Weekday</th>
            <th>Time</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, index) in timeKeepList" :key="index">
            <td>{{ item.date }}</td>
            <td>{{ new Date(item.date).toLocaleDateString('en-US', { weekday: 'long' }) }}</td>
            <td>{{ item.time }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      searchQuery: '',
      employee: null,
      timeKeep: {
        date: '',
        time: ''
      },
      timeKeepList: [],
      filteredEmployees: []
    };
  },
  methods: {
    async searchEmployee() {
      if (this.searchQuery.trim()) {
        try {
          const response = await axios.get(`https://icpmymis.com/entrancemonitoring/backend/fmtimekeepapi.php`, {
            params: { query: this.searchQuery }
          });
          this.filteredEmployees = response.data;
        } catch (error) {
          console.error('Error fetching employees:', error);
        }
      } else {
        this.filteredEmployees = [];
      }
    },
    selectEmployee(employee) {
      this.employee = employee;
      this.filteredEmployees = [];
      this.searchQuery = `${employee.lastName}, ${employee.firstName}`;
    },
    async addTimeKeep() {
      if (this.timeKeep.date && this.timeKeep.time && this.employee) {
        const newTimeKeep = { employeeID: this.employee.id, date: this.timeKeep.date, time: this.timeKeep.time };
        try {
          const response = await axios.post(`https://icpmymis.com/entrancemonitoring/backend/fmtimekeepapi.php`, newTimeKeep);
          if (response.data.message) {
            alert(response.data.message);
            this.timeKeepList.push({ ...this.timeKeep });
            this.timeKeep.date = '';
            this.timeKeep.time = '';
          }
        } catch (error) {
          console.error('Error adding timekeeping record:', error);
        }
      } else {
        alert('Please fill in both date and time');
      }
    }
  }
};
</script>

<style scoped>
/* Add any styles you need here */
</style>
