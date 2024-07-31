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
    
    <div class="form-row">
      <div class="form-group col-md-6">
        <label for="date">Date</label>
        <input type="date" class="form-control" id="date" v-model="timeKeep.date">
      </div>
      <div class="form-group col-md-6">
        <label for="time">Time</label>
        <input type="time" class="form-control" id="time" v-model="timeKeep.time">
      </div>
    </div>
    
    <button class="btn btn-primary mb-3" @click="addTimeKeep">Add TimeKeep</button>
    
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Date</th>
          <th>Weekday</th>
          <th>Time</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item, index) in timeKeepList" :key="index">
          <td>{{ item.date }}</td>
          <td>{{ new Date(item.date).toLocaleDateString('en-US', { weekday: 'long' }) }}</td>
          <td>{{ item.time }}</td>
          <td>
            <button class="btn btn-sm btn-warning" @click="editTimeKeep(index)">Edit</button>
            <button class="btn btn-sm btn-danger" @click="deleteTimeKeep(index)">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
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
      mockEmployees: [
        { id: '12345', RFID: '54321', lastName: 'Doe', firstName: 'John', position: 'Software Engineer', department: 'IT', empType: 'Full-time' },
        { id: '67890', RFID: '09876', lastName: 'Smith', firstName: 'Jane', position: 'Project Manager', department: 'Operations', empType: 'Full-time' },
        { id: '11223', RFID: '33211', lastName: 'Johnson', firstName: 'Jake', position: 'Designer', department: 'Marketing', empType: 'Part-time' },
        { id: '44556', RFID: '66554', lastName: 'Williams', firstName: 'James', position: 'Analyst', department: 'Finance', empType: 'Full-time' },
        { id: '78901', RFID: '10987', lastName: 'Brown', firstName: 'Jennifer', position: 'HR Manager', department: 'HR', empType: 'Full-time' },
        { id: '23456', RFID: '65432', lastName: 'Davis', firstName: 'Jessica', position: 'Administrator', department: 'Admin', empType: 'Full-time' }
      ],
      filteredEmployees: []
    };
  },
  methods: {
    searchEmployee() {
      const query = this.searchQuery.toLowerCase();
      this.filteredEmployees = this.mockEmployees.filter(emp =>
        emp.id.includes(query) ||
        emp.RFID.includes(query) ||
        emp.lastName.toLowerCase().includes(query) ||
        emp.firstName.toLowerCase().includes(query)
      );
    },
    selectEmployee(employee) {
      this.employee = employee;
      this.filteredEmployees = [];
      this.searchQuery = `${employee.lastName}, ${employee.firstName}`;
    },
    addTimeKeep() {
      if (this.timeKeep.date && this.timeKeep.time) {
        this.timeKeepList.push({ ...this.timeKeep });
        this.timeKeep.date = '';
        this.timeKeep.time = '';
      } else {
        alert('Please fill in both date and time');
      }
    },
    editTimeKeep(index) {
      this.timeKeep = { ...this.timeKeepList[index] };
      this.timeKeepList.splice(index, 1);
    },
    deleteTimeKeep(index) {
      this.timeKeepList.splice(index, 1);
    }
  }
};
</script>

<style scoped>
/* Add any styles you need here */
</style>
