<template>
    <div class="container mt-4">
      <h4>Edit Student</h4>
      <form @submit.prevent="updateStudent">
        <div class="row">
          <div class="form-group col-md-4">
            <label for="studID">Student ID</label>
            <input type="text" id="studID" class="form-control" v-model="student.studID" disabled>
          </div>
          <div class="form-group col-md-4">
            <label for="lastName">Last Name</label>
            <input type="text" id="lastName" class="form-control" v-model="student.lastName">
          </div>
          <div class="form-group col-md-4">
            <label for="firstName">First Name</label>
            <input type="text" id="firstName" class="form-control" v-model="student.firstName">
          </div>
          <div class="form-group col-md-4">
            <label for="middleName">Middle Name</label>
            <input type="text" id="middleName" class="form-control" v-model="student.middleName">
          </div>
          <div class="form-group col-md-4">
            <label for="courseYrSec">Course/Year/Sec</label>
            <input type="text" id="courseYrSec" class="form-control" v-model="student.courseYrSec">
          </div>
          <div class="form-group col-md-4">
            <label for="department">Department</label>
            <select id="department" class="form-control" v-model="student.department">
              <option value="CSIT">CSIT</option>
              <option value="CBEA">CBEA</option>
              <option value="ENGG">ENGG</option>
            </select>
          </div>
          <div class="form-group col-md-4">
            <label for="bday">Birthday</label>
            <input type="date" id="bday" class="form-control" v-model="student.bday">
          </div>
          <div class="form-group col-md-4">
            <label for="isActive">Active Status</label>
            <select id="isActive" class="form-control" v-model="student.isActive">
              <option value="1">Active</option>
              <option value="0">Inactive</option>
            </select>
          </div>
          <div class="form-group col-md-4">
            <label for="image">Image URL</label>
            <input type="text" id="image" class="form-control" v-model="student.image">
          </div>
          <div class="form-group col-md-4">
            <label for="note">Note</label>
            <input type="text" id="note" class="form-control" v-model="student.note">
          </div>
          <div class="form-group col-md-4">
            <label for="parentID">Parent ID</label>
            <input type="text" id="parentID" class="form-control" v-model="student.parentID">
          </div>
          <div class="col-12 mt-3">
            <button type="submit" class="btn btn-primary">Update</button>
            <RouterLink to="/students/view" class="btn btn-secondary">Cancel</RouterLink>
          </div>
        </div>
      </form>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import axios from 'axios';
  import { useRoute, useRouter } from 'vue-router';
  
  const route = useRoute();
  const router = useRouter();
  const student = ref({
    studID: '',
    lastName: '',
    firstName: '',
    middleName: '',
    courseYrSec: '',
    department: '',
    bday: '',
    isActive: '',
    image: '',
    note: '',
    parentID: ''
  });
  
  onMounted(() => {
    const studID = route.params.studID;
    fetchStudent(studID);
  });
  
  function fetchStudent(studID) {
    axios.get(`https://rjprint10.com/entrancemonitoring/backend/studentapi.php?action=get_by_id&studid=${studID}`)
      .then((response) => {
        Object.assign(student.value, response.data);
      })
      .catch((error) => {
        console.error('Error fetching student:', error);
      });
  }
  
  function updateStudent() {
    const data = {
      action: 'update',
      ...student.value
    };
  
    axios.post('https://rjprint10.com/entrancemonitoring/backend/studentapi.php', data)
      .then((response) => {
        console.log(response.data.message);
        router.push('/students/view');
      })
      .catch((error) => {
        console.error('Error updating student:', error);
      });
  }
  </script>
  
  <style scoped>
  .container {
    max-width: 900px;
  }
  </style>
  