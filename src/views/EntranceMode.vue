<template>
    <div class="container mt-4">

        <!-- Title and Address -->
        <div class="text-center">
            <p class="h1">Immaculate Conception I-College of Arts and Technology</p>
            <p class="h1">#47 A. Bonifacio St. Poblacion Santa Maria Bulacan</p>
        </div>

        <!-- Date and Time Display -->
        <div class="text-center mt-4">
            <p class="h4">Current Date and Time: {{ currentDate }}</p>
        </div>

        <!-- Search Input -->
        <div class="card mt-4">
            <div class="card-header">
                <h4>Timekeeping Mode</h4>
            </div>
            <div class="card-body">
                <input @keydown.enter="searchRFID" type="text" id="searchID" class="form-control" v-model="searchID">
            </div>
        </div>

        <!-- Employee Details -->
        <div class="card mt-4">
            <div class="card-body">
                <div class="form-group">
                    <label for="empID">Employee ID:</label><br>
                    <input type="text" id="empID" class="form-control" v-model="empID">
                </div>
                <div class="form-group">
                    <label for="lastName">Last Name:</label><br>
                    <input type="text" id="lastName" class="form-control" v-model="lastName">
                </div>
                <div class="form-group">
                    <label for="firstName">First Name:</label><br>
                    <input type="text" id="firstName" class="form-control" v-model="firstName">
                </div>
                <div class="form-group">
                    <label for="middleName">Middle Name:</label><br>
                    <input type="text" id="middleName" class="form-control" v-model="middleName">
                </div>
                <div class="form-group">
                    <label for="position">Position:</label><br>
                    <input type="text" id="position" class="form-control" v-model="position">
                </div>
                <div class="form-group">
                    <label for="department">Department:</label><br>
                    <input type="text" id="department" class="form-control" v-model="department">
                </div>
                <div class="form-group">
                    <label for="bday">Birthday:</label><br>
                    <input type="text" id="bday" class="form-control" v-model="bday">
                </div>
                <div class="form-group">
                    <label for="isActive">Is Active:</label><br>
                    <input type="text" id="isActive" class="form-control" v-model="isActive">
                </div>
                <div class="form-group">
                    <label for="empType">Employee Type:</label><br>
                    <input type="text" id="empType" class="form-control" v-model="empType">
                </div>
                <div class="form-group">
                    <label for="image">Image:</label><br>
                    <input type="text" id="image" class="form-control" v-model="image">
                </div>
                <div class="form-group">
                    <label for="note">Note:</label><br>
                    <input type="text" id="note" class="form-control" v-model="note">
                </div>
                <div class="form-group">
                    <label for="schedID">Schedule ID:</label><br>
                    <input type="text" id="schedID" class="form-control" v-model="schedID">
                </div>
            </div>
        </div>

    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const searchID = ref('');
const empID = ref('');
const lastName = ref('');
const firstName = ref('');
const middleName = ref('');
const position = ref('');
const department = ref('');
const bday = ref('');
const isActive = ref('');
const empType = ref('');
const image = ref('');
const note = ref('');
const schedID = ref('');

const currentDate = ref('');

// Function to fetch RFID data
function searchRFID() {
    axios.get('https://rjprint10.com/entrancemonitoring/backend/rfidapi.php?action=get_by_id&RFID=' + searchID.value)
        .then((response) => {
            const data = response.data;
            if (data) {
                empID.value = data.empID;
                lastName.value = data.lastName;
                firstName.value = data.firstName;
                middleName.value = data.middleName;
                position.value = data.position;
                department.value = data.department;
                bday.value = data.bday;
                isActive.value = data.isActive;
                empType.value = data.empType;
                image.value = data.image;
                note.value = data.note;
                schedID.value = data.schedID;
                insertLogEmp(data.userID);
            } else {
                clearFields();
            }
        })
        .catch((error) => {
            console.error("Error fetching RFID data: ", error);
        });
}

// Function to insert log for employee
function insertLogEmp(userID) {
    const newRecord = {
        action: 'create',
        empID: userID,
        logType: "TimeIn or PC Name or Param", // Update with appropriate log type
    };

    axios.post('https://rjprint10.com/entrancemonitoring/backend/timekeepingapi.php', newRecord)
        .then(response => {
            // Handle success
        })
        .catch(error => {
            console.error("Error saving record: ", error);
        });
}

// Function to clear form fields
function clearFields() {
    empID.value = '';
    lastName.value = '';
    firstName.value = '';
    middleName.value = '';
    position.value = '';
    department.value = '';
    bday.value = '';
    isActive.value = '';
    empType.value = '';
    image.value = '';
    note.value = '';
    schedID.value = '';
}

// Function to update current date and time
function updateTime() {
    const now = new Date();
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric', second: 'numeric', hour12: true };
    currentDate.value = now.toLocaleDateString('en-US', options);
}

// Update time on component mount
onMounted(() => {
    updateTime();
    // Refresh time every second
    setInterval(updateTime, 1000);
});
</script>

<style>
/* Add custom styles here */
</style>