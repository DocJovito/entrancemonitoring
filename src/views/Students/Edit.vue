<template>
  <div class="container mt-4">
    <h4>Edit Student</h4>
    <div v-if="loading" class="alert alert-info">Loading...</div>
    <div v-if="error" class="alert alert-danger">{{ error }}</div>
    <form v-if="!loading && !error" @submit.prevent="updateRecord">
      <div class="form-group">
        <label for="studID">Student ID</label>
        <input type="text" id="studID" class="form-control" v-model="studID" readonly>
      </div>
      <div class="form-group">
        <label for="lastName">Last Name</label>
        <input type="text" id="lastName" class="form-control" v-model="lastName" name="lastName">
      </div>
      <div class="form-group">
        <label for="firstName">First Name</label>
        <input type="text" id="firstName" class="form-control" v-model="firstName" name="firstName">
      </div>
      <div class="form-group">
        <label for="middleName">Middle Name</label>
        <input type="text" id="middleName" class="form-control" v-model="middleName" name="middleName">
      </div>
      <div class="form-group">
        <label for="courseYrSec">Course, Year & Section</label>
        <input type="text" id="courseYrSec" class="form-control" v-model="courseYrSec" name="courseYrSec">
      </div>
      <div class="form-group">
        <label for="department">Department</label>
        <input type="text" id="department" class="form-control" v-model="department" name="department">
      </div>
      <div class="form-group">
        <label for="bday">Birthday</label>
        <input type="date" id="bday" class="form-control" v-model="bday" name="bday">
      </div>
      <div class="form-group">
        <label for="isActive">Is Active</label>
        <input type="checkbox" id="isActive" v-model="isActive" name="isActive">
      </div>
      <div class="form-group">
        <label for="image">Image</label>
        <input type="text" id="image" class="form-control" v-model="image" name="image">
      </div>
      <div class="form-group">
        <label for="note">Note</label>
        <input type="text" id="note" class="form-control" v-model="note" name="note">
      </div>
      <button type="submit" class="btn btn-primary mt-3">Update</button>
    </form>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRoute } from 'vue-router';

const studID = ref('');
const lastName = ref('');
const firstName = ref('');
const middleName = ref('');
const courseYrSec = ref('');
const department = ref('');
const bday = ref('');
const isActive = ref(false);
const image = ref('');
const note = ref('');

const loading = ref(true);
const error = ref('');

const route = useRoute();
studID.value = route.params.studid;

onMounted(() => {    
  axios.get('https://rjprint10.com/entrancemonitoring/backend/studentapi.php?action=get_by_id&studid=' + studID.value)
    .then(response => {
      const student = response.data;
      studID.value = student.studID;
      lastName.value = student.lastName;
      firstName.value = student.firstName;
      middleName.value = student.middleName;
      courseYrSec.value = student.courseYrSec;
      department.value = student.department;
      bday.value = student.bday;
      isActive.value = student.isActive === 1;
      image.value = student.image;
      note.value = student.note;
      loading.value = false;
    })
    .catch(err => {
      error.value = "Error fetching data: " + err.message;
      loading.value = false;
    });
});

function updateRecord() {
  const updatedRecord = {
    action: 'update',
    studID: studID.value,
    lastName: lastName.value,
    firstName: firstName.value,
    middleName: middleName.value,
    courseYrSec: courseYrSec.value,
    department: department.value,
    bday: bday.value,
    isActive: isActive.value ? 1 : 0,
    image: image.value,
    note: note.value,
  };

  axios.post('https://rjprint10.com/entrancemonitoring/backend/studentapi.php', updatedRecord)
    .then(response => {
      alert("Record Updated");
    })
    .catch(err => {
      alert("Error updating record: " + err.message);
    });
}
</script>
