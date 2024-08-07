<template>
  <div class="container mt-4">
    <div class="card">
      <div class="card-header text-white" style="background-color: #00AA9E;">
        <h4>Edit Student</h4>
      </div>
      <div class="card-body" v-if="!loading && !error">
        <div class="row">
          <!-- Image column -->
          <div class="col-md-3">
            <div class="form-group">
              <label for="image">Current Image:</label><br />
              <img :src="getImageUrl(image)" class="img-fluid rounded mt-2" alt="Student Image"
                @error="handleImageError" />
            </div>
            <div class="form-group">
              <label for="newImage">Upload New Image:</label><br />
              <input type="file" id="newImage" ref="fileInput" accept=".jpg" @change="handleFileUpload"
                class="form-control-file">
            </div>
          </div>

          <!-- Space column -->
          <div class="col-md-1"></div>

          <!-- Data column -->
          <div class="col-md-8">
            <div class="form-group row">
              <label class="col-sm-3 col-form-label" for="studID">Student ID:</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" v-model="studID" id="studID" readonly>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-3 col-form-label" for="RFID">RFID:</label>
              <div class="col-sm-9">
                <input type="text" id="RFID" class="form-control" v-model="RFID">
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-3 col-form-label" for="lastName">Last Name:</label>
              <div class="col-sm-9">
                <input type="text" id="lastName" class="form-control" v-model="lastName">
                <div v-if="!lastName && formSubmitted" class="text-danger">Required Last Name</div>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-3 col-form-label" for="firstName">First Name:</label>
              <div class="col-sm-9">
                <input type="text" id="firstName" class="form-control" v-model="firstName">
                <div v-if="!firstName && formSubmitted" class="text-danger">Required First Name</div>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-3 col-form-label" for="middleName">Middle Name:</label>
              <div class="col-sm-9">
                <input type="text" id="middleName" class="form-control" v-model="middleName">
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-3 col-form-label" for="courseYrSec">Course, Year & Section:</label>
              <div class="col-sm-9">
                <input type="text" id="courseYrSec" class="form-control" v-model="courseYrSec">
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-3 col-form-label" for="department">Department:</label>
              <div class="col-sm-9">
                <input type="text" id="department" class="form-control" v-model="department">
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-3 col-form-label" for="bday">Birthday:</label>
              <div class="col-sm-9">
                <input type="date" id="bday" class="form-control" v-model="bday">
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-3 col-form-label" for="isActive">Active:</label>
              <div class="col-sm-9">
                <select id="isActive" class="form-control" v-model="isActiveText">
                  <option value="Yes">Yes</option>
                  <option value="No">No</option>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-3 col-form-label" for="note">Note:</label>
              <div class="col-sm-9">
                <input type="text" id="note" class="form-control" v-model="note">
              </div>
            </div>
          </div>
        </div>
      </div>
      <div v-if="loading" class="alert alert-info">Loading...</div>
      <div v-if="error" class="alert alert-danger">{{ error }}</div>
    </div>
    <div class="mt-3">
      <button type="button" class="btn btn-primary btn-lg mr-3" @click="updateRecord">Update</button>
      <router-link to="/students/view" class="btn btn-secondary btn-lg">Cancel</router-link>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import axios from 'axios';
import { useRoute } from 'vue-router';

const studID = ref('');
const RFID = ref('');
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

watch(lastName, (newVal) => {
  lastName.value = newVal.toUpperCase();
});
watch(firstName, (newVal) => {
  firstName.value = newVal.toUpperCase();
});
watch(middleName, (newVal) => {
  middleName.value = newVal.toUpperCase();
});
watch(courseYrSec, (newVal) => {
  courseYrSec.value = newVal.toUpperCase();
});
watch(department, (newVal) => {
  department.value = newVal.toUpperCase();
});

const isActiveText = computed({
  get() {
    return isActive.value ? 'Yes' : 'No';
  },
  set(value) {
    isActive.value = value === 'Yes';
  }
});

onMounted(() => {
  axios.get('https://icpmymis.com/entrancemonitoring/backend/studentapi.php?action=get_by_id&studid=' + studID.value)
    .then(response => {
      const student = response.data;
      studID.value = student.studID;
      RFID.value = student.RFID;
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
    RFID: RFID.value,
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

  axios.post('https://icpmymis.com/entrancemonitoring/backend/studentapi.php', updatedRecord)
    .then(response => {
      // alert("Record Updated");
      router.push('/students/view');
    })
    .catch(err => {
      alert("Error updating record: " + err.message);
    });
}


function getImageUrl(imageFilename) {
  if (!imageFilename || imageFilename === "") {
    // If no image is available, you can return a placeholder or default image URL
    return 'https://icpmymis.com/images/ICPLogo.jpg';
  } else {
    // Construct the full image URL based on server folder path and filename
    const lowerCaseFilename = imageFilename.toLowerCase();
    return `https://icpmymis.com/images/${imageFilename}`;
  }
}

function handleImageError(event) {
  const img = event.target;
  const originalSrc = img.src;
  const upperCaseSrc = originalSrc.replace('.jpg', '.JPG');

  // Check if it's already in uppercase format
  if (originalSrc !== upperCaseSrc) {
    // Try to load the uppercase version
    img.src = upperCaseSrc;
  } else {
    // If both attempts fail, set to default image
    img.src = 'https://icpmymis.com/images/ICPLogo.jpg';
  }
}
</script>
