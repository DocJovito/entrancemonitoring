<template>
  <div class="container mt-4">
    <div class="card">
      <div class="card-header text-white" style="background-color: #00AA9E;">
        <h4>Edit Employee</h4>
      </div>
      <div class="card-body" v-if="employeeLoaded">
        <div class="row">
          <!-- Image column -->
          <div class="col-md-3">
            <div class="form-group">
              <label for="image">Current Image:</label><br />
              <img :src="getImageUrl(image)" class="img-fluid rounded mt-2" alt="Employee Image"
                @error="handleImageError" />
            </div>
            <div class="form-group">
              <label for="newImage">Upload New Image:</label><br />
              <input type="file" id="newImage" ref="fileInput" accept=".jpg" @change="handleFileUpload"
                class="form-control-file">
            </div>
          </div>

          <!-- Space column -->
          <div class="col-md-1">
            <div class="form-group">
              <!-- <label for="image">Space lang</label><br />                -->
            </div>
          </div>
          <!-- Data column -->
          <div class="col-md-8">
            <div class="form-group row">
              <label class="col-sm-3 col-form-label" for="empID">Employee ID:</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" v-model="empID" id="empID" placeholder="ICI08-0001" disabled>
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
              <label class="col-sm-3 col-form-label" for="position">Position:</label>
              <div class="col-sm-9">
                <input type="text" id="position" class="form-control" v-model="position">
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
              <label class="col-sm-3 col-form-label" for="empType">Employee Type:</label>
              <div class="col-sm-9">
                <select id="empType" class="form-control" v-model="empType">
                  <option value="Full-Time">Full-Time</option>
                  <option value="Part-Time">Part-Time</option>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-3 col-form-label" for="note">Note:</label>
              <div class="col-sm-9">
                <input type="text" id="note" class="form-control" v-model="note">
              </div>
            </div>
            <!-- Email -->
            <div class="form-group row">
              <label class="col-sm-3 col-form-label" for="email">Email:</label>
              <div class="col-sm-9">
                <input type="email" class="form-control" v-model="email" id="email" placeholder="Email">
                <div v-if="emailError" class="text-danger">{{ emailError }}</div>
                <div v-if="!email && formSubmitted" class="text-danger">Required Email</div>
              </div>
            </div>

            <!-- Password -->
            <div class="form-group row">
              <label class="col-sm-3 col-form-label" for="password">Password:</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" v-model="password" id="password" placeholder="Password">
                <div v-if="!password && formSubmitted" class="text-danger">Required Password</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="mt-3">
      <button type="button" class="btn btn-primary btn-lg mr-3" @click="updateRecord">Update</button>
      <router-link to="/employees/view" class="btn btn-secondary btn-lg">Cancel</router-link>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import axios from 'axios';
import { useRoute, useRouter } from 'vue-router';

const empID = ref('');
const RFID = ref('');
const lastName = ref('');
const firstName = ref('');
const middleName = ref('');
const position = ref('');
const department = ref('');
const bday = ref('');
const isActive = ref(false);
const empType = ref('');
const image = ref('');
const note = ref('');
const email = ref('');
const password = ref(''); // Added password ref

const employeeLoaded = ref(false);
const fileInput = ref(null); // Ref for file input element

const route = useRoute();
const router = useRouter();
empID.value = route.params.empid;

watch(lastName, (newVal) => {
  lastName.value = newVal.toUpperCase();
});
watch(firstName, (newVal) => {
  firstName.value = newVal.toUpperCase();
});
watch(middleName, (newVal) => {
  middleName.value = newVal.toUpperCase();
});
watch(position, (newVal) => {
  position.value = newVal.toUpperCase();
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
  fetchEmployeeData();
});

function fetchEmployeeData() {
  axios
    .get(`https://icpmymis.com/entrancemonitoring/backend/employeeapi.php?action=get_by_id&empid=${empID.value}`)
    .then((response) => {
      const employee = response.data;
      empID.value = employee.empID;
      RFID.value = employee.RFID;
      lastName.value = employee.lastName;
      firstName.value = employee.firstName;
      middleName.value = employee.middleName;
      position.value = employee.position;
      department.value = employee.department;
      bday.value = employee.bday;
      isActive.value = employee.isActive == "1";
      empType.value = employee.empType;
      image.value = employee.image;
      note.value = employee.note;
      employeeLoaded.value = true;
      email.value = employee.email;
      password.value = employee.password;
    })
    .catch((error) => {
      console.error("Error fetching data: ", error);
    });
}

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

    // Load image to check dimensions
    const img = new Image();
    img.onload = () => {
      if (img.width < 300 || img.width > 340 || img.height < 300 || img.height > 340) {
        alert('Image dimensions must be between 300x300 and 340x340 pixels.');
        fileInput.value.value = ''; // Clear the file input
        return;
      }

      const formData = new FormData();
      formData.append('file', file);

      axios.post('https://icpmymis.com/entrancemonitoring/backend/upload-1image.php', formData)
        .then((response) => {
          // Assuming the backend returns the filename or path to the uploaded image
          image.value = response.data.filename; // Update image data URL or filename
        })
        .catch((error) => {
          console.error('Error uploading image: ', error);
          alert('Failed to upload image.');
        });
    };
    img.src = URL.createObjectURL(file);
  }
}

function validateEmail(email) {
  const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailPattern.test(email);
}

const formSubmitted = ref(false);
const emailError = ref('');

function updateRecord() {
  formSubmitted.value = true;

  emailError.value = '';
  if (!validateEmail(email.value)) {
    emailError.value = 'Invalid Email';
    return;
  }

  if (!empID.value || !lastName.value || !firstName.value || !password.value || !email.value) {
    return;
  }

  const updatedRecord = {
    action: 'update',
    empID: empID.value,
    RFID: RFID.value,
    lastName: lastName.value,
    firstName: firstName.value,
    middleName: middleName.value,
    position: position.value,
    department: department.value,
    bday: bday.value,
    isActive: isActive.value,
    empType: empType.value,
    image: empID.value + '.JPG',
    note: note.value,
    email: email.value,
    password: password.value,
  };

  axios
    .post('https://icpmymis.com/entrancemonitoring/backend/employeeapi.php', updatedRecord)
    .then((response) => {
      // alert("Record Updated");
      router.push('/employees/view');
    })
    .catch((error) => {
      console.error("Error updating record: ", error);
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