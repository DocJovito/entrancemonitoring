<template>
  <div class="container mt-4">
    <div class="card">
      <div class="card-header">
        <h4>Create New Employee</h4>
      </div>
      <div class="card-body">
        <div class="row">
          <!-- Image column -->
          <div class="col-md-3">
            <div class="form-group">
              <label for="image">Image:</label><br />
              <input type="file" ref="fileInput" accept=".jpg" @change="handleFileUpload" class="form-control-file">
              <div v-if="imageUrl">
                <img :src="imageUrl" class="img-fluid rounded" alt="Employee Image">
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
              <label class="col-sm-3 col-form-label" for="empID">Employee ID:</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" v-model="empID" id="empID" placeholder="ICI08-0001">
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
              </div>
            </div>

            <!-- First Name -->
            <div class="form-group row">
              <label class="col-sm-3 col-form-label" for="firstName">First Name:</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" v-model="firstName" id="firstName" placeholder="First Name">
              </div>
            </div>

            <!-- Middle Name -->
            <div class="form-group row">
              <label class="col-sm-3 col-form-label" for="middleName">Middle Name:</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" v-model="middleName" id="middleName" placeholder="Middle Name">
              </div>
            </div>

            <!-- Position -->
            <div class="form-group row">
              <label class="col-sm-3 col-form-label" for="position">Position:</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" v-model="position" id="position" placeholder="Position">
              </div>
            </div>

            <!-- Department -->
            <div class="form-group row">
              <label class="col-sm-3 col-form-label" for="department">Department:</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" v-model="department" id="department" placeholder="Department">
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

            <!-- empType -->
            <div class="form-group row">
              <label class="col-sm-3 col-form-label" for="empType">Employee Type:</label>
              <div class="col-sm-9">
                <select class="form-control" v-model="empType" id="empType">
                  <option value="Full-Time">Full-Time</option>
                  <option value="Part-Time">Part-Time</option>
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

            <!-- Email -->
            <div class="form-group row">
              <label class="col-sm-3 col-form-label" for="email">Email:</label>
              <div class="col-sm-9">
                <input type="email" class="form-control" v-model="email" id="email" placeholder="Email">
                <div v-if="emailError" class="text-danger">{{ emailError }}</div>
              </div>
            </div>

            <!-- Password -->
            <div class="form-group row">
              <label class="col-sm-3 col-form-label" for="Password">Password:</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" v-model="note" id="note" placeholder="Password" disabled>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="mt-3">
      <button type="button" class="btn btn-primary btn-lg mr-3" @click="createEmployee">Create</button>
      <router-link to="/employees/view" class="btn btn-secondary btn-lg">Cancel</router-link>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

const empID = ref('');
const RFID = ref('');
const lastName = ref('');
const firstName = ref('');
const middleName = ref('');
const position = ref('');
const department = ref('');
const bday = ref('');
const isActive = ref('1'); // Assuming isActive defaults to Yes (1)
const empType = ref('Full-Time'); // Assuming empType defaults to Full-Time
const note = ref('');
const imageUrl = ref(null);
const imageDimensions = ref({ width: 0, height: 0 });
const imageSize = ref(0);
const fileInput = ref(null);
const router = useRouter();

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

async function createEmployee() {
  let uploadedImage = null;

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

  const newEmployee = {
    action: 'create',
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
    image: uploadedImage,
    note: note.value,
  };

  try {
    const response = await axios.post('https://icpmymis.com/entrancemonitoring/backend/employeeapi.php', newEmployee);
    alert("Employee created successfully");
    router.push('/employees/view');
  } catch (error) {
    console.error("Error creating employee: ", error);
    alert("Failed to create employee. Please check the input fields and try again.");
  }
}
</script>
