<template>
    <div class="container mt-4">
      <div class="card">
        <div class="card-header">
          <h4>Edit Employee</h4>
        </div>
        <div class="card-body" v-if="employeeLoaded">
          <div class="row">
            <!-- Image column -->
            <div class="col-md-3">
              <div class="form-group">
                <label for="image">Image:</label><br />
                <img :src="getImageUrl(image)" class="img-fluid rounded" alt="Employee Image" />
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
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="firstName">First Name:</label>
                <div class="col-sm-9">
                    <input type="text" id="firstName" class="form-control" v-model="firstName">
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
  import { ref, computed, onMounted } from 'vue';
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
  
  const employeeLoaded = ref(false);
  
  const route = useRoute();
  const router = useRouter();
  empID.value = route.params.empid;
  
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
      .get(`https://rjprint10.com/entrancemonitoring/backend/employeeapi.php?action=get_by_id&empid=${empID.value}`)
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
      })
      .catch((error) => {
        console.error("Error fetching data: ", error);
      });
  }
  
  function updateRecord() {
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
      image: image.value,
      note: note.value,
    };
  
    axios
      .post('https://rjprint10.com/entrancemonitoring/backend/employeeapi.php', updatedRecord)
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
      return 'https://example.com/default-image.jpg';
    } else {
      // Construct the full image URL based on server folder path and filename
      return `https://rjprint10.com/images/${imageFilename}`;
    }
  }
  </script>
  