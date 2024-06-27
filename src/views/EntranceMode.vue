<template>
    <div class="container mt-4">
        <!-- Title and Address -->
        <div class="text-center mb-4">
            <p class="h4">Immaculate Conception I-College of Arts and Technology</p>
            <p class="h4">#47 A. Bonifacio St. Poblacion Santa Maria Bulacan</p>
        </div>

        <!-- Date and Time Display -->
        <div class="text-center mb-4">
            <p class="h5">Current Date and Time: {{ currentDate }}</p>
        </div>

        <!-- Main Content Grid -->
        <div class="row">
            <!-- Left Column: Image and Details -->
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img class="card-img-top" :src="image || 'https://via.placeholder.com/150'" alt="Image">
                    <div class="card-body">
                        <p class="card-text text-center">{{ lastName }}, {{ firstName }}</p>
                    </div>
                </div>
            </div>

            <!-- Right Column: Data and Timekeeping -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>{{ userType === 'EMPLOYEE' ? 'Employee Details' : 'Student Details' }}</h4>
                    </div>
                    <div class="card-body">
                        <div v-if="userType === 'EMPLOYEE'">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Employee ID:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" v-model="empID" disabled>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Last Name:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" v-model="lastName" disabled>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">First Name:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" v-model="firstName" disabled>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Middle Name:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" v-model="middleName" disabled>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Position:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" v-model="position" disabled>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Department:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" v-model="department" disabled>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Birthday:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" v-model="bday" disabled>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Is Active:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" v-model="isActive" disabled>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Employee Type:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" v-model="empType" disabled>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Note:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" v-model="note" disabled>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Schedule ID:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" v-model="schedID" disabled>
                                </div>
                            </div>
                        </div>

                        <div v-else-if="userType === 'STUDENT'">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Student ID:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" v-model="studID" disabled>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Last Name:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" v-model="lastName" disabled>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">First Name:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" v-model="firstName" disabled>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Middle Name:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" v-model="middleName" disabled>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Course:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" v-model="course" disabled>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Year Level:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" v-model="yearLevel" disabled>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Birthday:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" v-model="bday" disabled>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Is Active:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" v-model="isActive" disabled>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Note:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" v-model="note" disabled>
                                </div>
                            </div>
                        </div>

                        <!-- RFID Search Input -->
                        <div class="mt-4">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Timekeeping Mode</h4>
                                </div>
                                <div class="card-body">
                                    <input @keydown.enter="searchRFID" type="text" class="form-control"
                                        v-model="searchID" placeholder="Scan RFID here">
                                </div>
                            </div>
                        </div>
                    </div>
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
const studID = ref('');
const lastName = ref('');
const firstName = ref('');
const middleName = ref('');
const position = ref('');
const department = ref('');
const course = ref('');
const yearLevel = ref('');
const bday = ref('');
const isActive = ref('');
const empType = ref('');
const image = ref('');
const note = ref('');
const schedID = ref('');
const userType = ref('');
const currentDate = ref('');

// Function to fetch RFID data
function searchRFID() {
    axios.get('https://rjprint10.com/entrancemonitoring/backend/timekeepingapi.php?action=get_by_id&RFID=' + searchID.value)
        .then(response => {
            const data = response.data;
            if (data) {
                userType.value = data.userType;
                if (userType.value === 'EMPLOYEE') {
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
                    insertLog(data.userID);
                } else if (userType.value === 'STUDENT') {
                    studID.value = data.studID;
                    lastName.value = data.lastName;
                    firstName.value = data.firstName;
                    middleName.value = data.middleName;
                    course.value = data.course;
                    yearLevel.value = data.yearLevel;
                    bday.value = data.bday;
                    isActive.value = data.isActive;
                    note.value = data.note;
                    insertLog(data.userID);
                }
            } else {
                clearFields();
            }
        })
        .catch(error => {
            console.error("Error fetching RFID data: ", error);
        });
}

// Function to insert log
function insertLog(userID) {
    const newLog = {
        action: 'create',
        userID: userID,
        logType: "TimeIn or PC Name or Param", // Update with appropriate log type
    };

    axios.post('https://rjprint10.com/entrancemonitoring/backend/timekeepingapi.php', newLog)
        .then(response => {
            // Handle success
        })
        .catch(error => {
            console.error("Error saving log: ", error);
        });
}

// Function to clear form fields
function clearFields() {
    empID.value = '';
    studID.value = '';
    lastName.value = '';
    firstName.value = '';
    middleName.value = '';
    position.value = '';
    department.value = '';
    course.value = '';
    yearLevel.value = '';
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

<style scoped>
/* Custom CSS styles */
.card {
    height: 100%;
}

.card-img-top {
    max-height: 300px;
    object-fit: cover;
}
</style>
