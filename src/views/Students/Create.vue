<template>
  <div class="container mt-4">
    <div class="card">
      <div class="card-header text-white" style="background-color: #00AA9E;">
        <h4>Add New Student</h4>
      </div>
      <div class="card-body">
        <div class="row">
          <!-- Image column -->
          <div class="col-md-3">
            <div class="form-group">
              <label for="image">Image:</label><br />
              <input type="file" ref="fileInput" accept=".jpg" @change="handleFileUpload" class="form-control-file">
              <div v-if="imageUrl">
                <img :src="imageUrl" class="img-fluid rounded" alt="Student Image">
                <p>Dimensions: {{ imageDimensions.width }} x {{ imageDimensions.height }}</p>
                <p>File Size: {{ imageSize }} KB</p>
              </div>
            </div>
          </div>
          <!-- Space column -->
          <div class="col-md-1">
            <!-- Empty space -->
          </div>
          <!-- Data column -->
          <div class="col-md-8">
            <div class="form-group row">
              <label class="col-sm-3 col-form-label" for="studID">Student ID:</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" v-model="studID" id="studID" placeholder="Student ID">
                <div v-if="!studID && formSubmitted" class="text-danger">Required Student ID</div>
              </div>
            </div>

            <!-- RFID -->
            <div class="form-group row">
              <label class="col-sm-3 col-form-label" for="RFID">RFID:</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" v-model="RFID" id="RFID" placeholder="00000000">
              </div>
            </div>

            <!-- Last Name -->
            <div class="form-group row">
              <label class="col-sm-3 col-form-label" for="lastName">Last Name:</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" v-model="lastName" id="lastName" placeholder="Last Name">
                <div v-if="!lastName && formSubmitted" class="text-danger">Required Last Name</div>
              </div>
            </div>

            <!-- First Name -->
            <div class="form-group row">
              <label class="col-sm-3 col-form-label" for="firstName">First Name:</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" v-model="firstName" id="firstName" placeholder="First Name">
                <div v-if="!firstName && formSubmitted" class="text-danger">Required First Name</div>
              </div>
            </div>

            <!-- Middle Name -->
            <div class="form-group row">
              <label class="col-sm-3 col-form-label" for="middleName">Middle Name:</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" v-model="middleName" id="middleName" placeholder="Middle Name">
              </div>
            </div>

            <!-- Course/Year/Sec -->
            <div class="form-group row">
              <label class="col-sm-3 col-form-label" for="courseYrSec">Strand:</label>
              <div class="col-sm-9">
                <select class="form-control" v-model="courseYrSec" id="courseYrSec">
                  <option value="ABM">ABM</option>
                  <option value="GAS">GAS</option>
                  <option value="HRCTO">HRCTO</option>
                  <option value="HUMSS">HUMSS</option>
                  <option value="ICT">ICT</option>
                  <option value="STEM">STEM</option>
                  <option value="TESDA">TESDA</option>
                </select>
              </div>
            </div>

            <!-- Department -->
            <div class="form-group row">
              <label class="col-sm-3 col-form-label" for="department">Department:</label>
              <div class="col-sm-9">
                <select class="form-control" v-model="department" id="department">
                  <option value="ABM">ABM</option>
                  <option value="GAS">GAS</option>
                  <option value="HRCTO">HRCTO</option>
                  <option value="HUMSS">HUMSS</option>
                  <option value="ICT">ICT</option>
                  <option value="STEM">STEM</option>
                  <option value="TESDA">TESDA</option>
                </select>
              </div>
            </div>

            <!-- Birthday -->
            <div class="form-group row">
              <label class="col-sm-3 col-form-label" for="bday">Birthday:</label>
              <div class="col-sm-9">
                <input type="date" class="form-control" v-model="bday" id="bday">
              </div>
            </div>

            <!-- isActive -->
            <div class="form-group row">
              <label class="col-sm-3 col-form-label" for="isActive">Is Active:</label>
              <div class="col-sm-9">
                <select class="form-control" v-model="isActive" id="isActive">
                  <option value="1">Yes</option>
                  <option value="0">No</option>
                </select>
              </div>
            </div>

            <!-- Note -->
            <div class="form-group row">
              <label class="col-sm-3 col-form-label" for="note">Note:</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" v-model="note" id="note" placeholder="Note">
              </div>
            </div>

            <!-- Parent ID -->
            <div class="form-group row">
              <label class="col-sm-3 col-form-label" for="parentID">Parent ID:</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" v-model="parentID" id="parentID" placeholder="Parent ID">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="mt-3">
      <button type="button" class="btn btn-primary btn-lg mr-3" @click="createStudent">Add Student</button>
      <router-link to="/students/view" class="btn btn-secondary btn-lg">Cancel</router-link>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

const studID = ref('');
const RFID = ref('');
const lastName = ref('');
const firstName = ref('');
const middleName = ref('');
const courseYrSec = ref('');
const department = ref('');
const bday = ref('');
const isActive = ref('1'); // Assuming isActive defaults to Yes (1)
const note = ref('');
const email = ref('');  // for future.. variable for email
const parentID = ref('');
const imageUrl = ref(null);
const imageDimensions = ref({ width: 0, height: 0 });
const imageSize = ref(0);
const fileInput = ref(null);
const router = useRouter();

// Automatically convert Last Name and First Name to uppercase
watch(studID, (newVal) => {
  studID.value = newVal.toUpperCase();
});
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

function handleFileUpload() {
  const file = fileInput.value.files[0];
  const validExtensions = ['jpg', 'JPG'];

  if (file) {
    const fileExtension = file.name.split('.').pop();

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
  formData.append('studID', studID.value);

  try {
    const response = await axios.post('https://icpmymis.com/entrancemonitoring/backend/upload-1image.php', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });
    alert('Image uploaded successfully');
    console.log(response.data);
    return response.data.file; // Return uploaded image filename
  } catch (error) {
    alert('Failed to upload image');
    console.error(error);
    return null;
  }
}

function validateEmail(email) {
  const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailPattern.test(email);
}

const formSubmitted = ref(false);
const emailError = ref('');


async function createStudent() {
  let uploadedImage = null;
  formSubmitted.value = true;

  if (!studID.value || !lastName.value || !firstName.value) {
    alert('Student ID, Last Name, and First Name are required.');
    return;
  }

  // for future ,, add email  -----------------------------------------------------------------------------------------
  if (!validateEmail(email.value)) {
    emailError.value = 'Invalid email format';
    return;
  }
  emailError.value = '';


  if (fileInput.value.files[0]) {
    uploadedImage = await uploadImage();
    if (!uploadedImage) {
      return; // Stop further execution if image upload fails
    }
  } else {
    const confirmNoImage = confirm('There is no image uploaded. Are you sure you don\'t want to upload an image for the employee?');
    if (!confirmNoImage) {
      return;
    } else {
      // Set image value to empID + ".JPG"
      uploadedImage = empID.value + '.JPG';
    }
  }

  const newStudent = {
    action: 'create',
    studID: studID.value,
    RFID: RFID.value,
    lastName: lastName.value,
    firstName: firstName.value,
    middleName: middleName.value,
    courseYrSec: courseYrSec.value,
    department: department.value,
    bday: bday.value,
    isActive: isActive.value,
    note: note.value,
    parentID: parentID.value    
  };

  try {
    const response = await axios.post('https://icpmymis.com/entrancemonitoring/backend/studentapi.php', newEmployee);
    alert("Student created successfully");
    router.push('/students/view');
  } catch (error) {
    console.error("Error creating Student: ", error);
    alert("Failed to create Student. Please check the input fields and try again.");
  }
}
</script>

<style scoped>
.container {
  max-width: 800px;
  margin: 0 auto;
}
.img-fluid {
  max-width: 100%;
  height: auto;
}
</style>
