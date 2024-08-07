<template>
    <div class="container bgMain">
        <div class="row">
            <div class="text-center title">
                <p class="h1">Immaculate Conception Polytechnic</p>
                <p class="h4">Santa Maria | Marilao | Meycauayan | Balagtas | City of San Jose Del Monte</p>
            </div>
            <div class="text-center address">
                <p class="h2" style="background-color: #FF8C00; color: white;">
                    Date: {{ currentDate }} &nbsp;&nbsp;|&nbsp;&nbsp; Time: {{ currentTime }}
                </p>
            </div>
        </div>

        <div class="row mt-3">
            <!-- Left Column: Image and Details -->
            <div class="col-md-4 ">
                <div class="card">
                    <img class="user-image" :src="getImageUrl(image)" alt="Image">
                </div>
            </div>

            <!-- Right Column: Data and Timekeeping -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <!-- modify the display here. if the scan RFID has no record. provide the instruction how to register it. to HR or OSAS -->
                        <h4 v-if="userType && (userType === 'EMPLOYEE' || userType === 'STUDENT')">
                            {{ userType === 'EMPLOYEE' ? 'Employee Details' : 'Student Details' }}
                        </h4>
                        <h4 v-else>
                            No RFID Record Found
                        </h4>
                    </div>
                    <div class="card-body">
                        <template v-if="userType === 'EMPLOYEE'">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="empID">Employee ID:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" v-model="empID" id="empID" disabled>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="empLastName">Last Name:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" v-model="lastName" id="empLastName"
                                        disabled>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="empFirstName">First Name:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" v-model="firstName" id="empFirstName"
                                        disabled>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="empMiddleName">Middle Name:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" v-model="middleName" id="empMiddleName"
                                        disabled>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="empPosition">Position:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" v-model="position" id="empPosition"
                                        disabled>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="empDepartment">Department:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" v-model="department" id="empDepartment"
                                        disabled>
                                </div>
                            </div>
                            <!-- <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="empBday">Birthday:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" v-model="bday" id="empBday" disabled>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="empIsActive">Is Active:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" v-model="isActive" id="empIsActive" disabled>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="empType">Employee Type:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" v-model="empType" id="empType" disabled>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="empNote">Note:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" v-model="note" id="empNote" disabled>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="empSchedID">Schedule ID:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" v-model="schedID" id="empSchedID" disabled>
                                </div>
                            </div> -->
                        </template>

                        <template v-else-if="userType === 'STUDENT'">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="studID">Student ID:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" v-model="studID" id="studID" disabled>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="studLastName">Last Name:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" v-model="lastName" id="studLastName"
                                        disabled>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="studFirstName">First Name:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" v-model="firstName" id="studFirstName"
                                        disabled>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="studMiddleName">Middle Name:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" v-model="middleName" id="studMiddleName"
                                        disabled>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="studCourse">Course:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" v-model="course" id="studCourse" disabled>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="studYearLevel">Year Level:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" v-model="yearLevel" id="studYearLevel"
                                        disabled>
                                </div>
                            </div>
                            <!-- Do not remove -->
                            <!-- <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="studBday">Birthday:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" v-model="bday" id="studBday" disabled>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="studIsActive">Is Active:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" v-model="isActive" id="studIsActive" disabled>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="studNote">Note:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" v-model="note" id="studNote" disabled>
                                </div>
                            </div> -->
                        </template>

                        <!-- Instruction when RFID record is not found -->
                        <template v-if="!userType">
                            <div class="alert alert-warning mt-4" role="alert">
                                No RFID record found. Please contact HR or OSAS to register your RFID.
                            </div>
                        </template>
                    </div>
                </div>
            </div>
            <!-- RFID Search Input -->
            <!-- <div class="">
                    <input ref="rfidInput" @keydown.enter="searchRFID" type="text" class="form-control"
                        v-model="searchID" placeholder="Scan RFID here">
                </div> -->
        </div>

        <!-- <div style="text-align: center;">
            <RouterLink to="/">www.timekeeping.com</RouterLink>
        </div> -->
    </div>

</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';
import axios from 'axios';
import { RouterView, useRoute } from 'vue-router'

const searchID = ref('');
const empID = ref('');
const studID = ref('');
const lastName = ref('');
const firstName = ref('');
const middleName = ref('');
const position = ref('');
const department = ref('');
const bday = ref('');
const isActive = ref('');
const empType = ref('');
const course = ref('');
const yearLevel = ref('');
const note = ref('');
const schedID = ref('');
const image = ref('');
const userType = ref('');

const currentDate = ref('');
const currentTime = ref('');
const clientIPAddress = ref('');
const rfidInput = ref(null);

function clearFields() {
    empID.value = '';
    studID.value = '';
    lastName.value = '';
    firstName.value = '';
    middleName.value = '';
    position.value = '';
    department.value = '';
    bday.value = '';
    isActive.value = '';
    empType.value = '';
    course.value = '';
    yearLevel.value = '';
    note.value = '';
    schedID.value = '';
    image.value = '';
    userType.value = '';
    rfidInput.value = '';
    searchID.value = '';
}

function searchRFID() {
    axios.get(`https://icpmymis.com/entrancemonitoring/backend/timekeepingapi.php?action=get_by_id&RFID=${searchID.value}`)
        .then(response => {
            const data = response.data;
            // console.log('Received data:', data);

            if (data) {
                userType.value = data.empType || data.userType;

                if (userType.value === 'EMPLOYEE') {
                    empID.value = data.userID;
                    lastName.value = data.lastName;
                    firstName.value = data.firstName;
                    middleName.value = data.middleName;
                    position.value = data.position;
                    department.value = data.department;
                    bday.value = data.bday;
                    isActive.value = data.isActive;
                    empType.value = data.empType;
                    schedID.value = data.schedID;

                    // Update image and other fields
                    image.value = data.image;
                    // console.log('Image filename:', getImageUrl(image.value));
                    note.value = data.note;

                    // Insert log
                    getIPAddress();
                    insertLog(data.userID, clientIPAddress.value);
                } else if (userType.value === 'STUDENT') {
                    studID.value = data.userID;
                    lastName.value = data.lastName;
                    firstName.value = data.firstName;
                    middleName.value = data.middleName;
                    course.value = data.course;
                    yearLevel.value = data.yearLevel;
                    bday.value = data.bday;
                    isActive.value = data.isActive;
                    empType.value = data.empType || data.userType; // Adjust according to API response structure

                    // Update image and other fields
                    image.value = data.image;
                    // console.log('Image filename:', getImageUrl(image.value));
                    note.value = data.note;

                    // Insert log
                    getIPAddress();
                    insertLog(data.userID, clientIPAddress.value);

                } else {
                    clearFields();
                    // console.error("Error 404: Resource not found");
                }

                searchID.value = '';
            } else {
                clearFields();
            }
        })
        .catch(error => {
            if (error.response && error.response.status === 404) {

                // Handle the 404 error here
                clearFields();

                // alert("Resource not found. Please check the RFID.");
            } else {
                console.error("Error fetching RFID data: ", error);
                clearFields();
                alert("An error occurred while fetching RFID data.");
            }
        });
}

function insertLog(userID, logType) {
    const now = new Date();
    const formattedDate = now.toISOString().slice(0, 10); // Format as YYYY-MM-DD
    const formattedTime = now.toLocaleTimeString('en-US', { hour12: false }); // Format as HH:MM:SS in 24-hour format

    const newLog = {
        action: userType.value === 'EMPLOYEE' ? 'create_employee_log' : 'create_student_log',
        userID: userID,
        logType: logType,
        currentDate: formattedDate,
        currentTime: formattedTime,
        clientIP: clientIPAddress.value
    };

    // console.log("New log payload:", newLog);

    axios.post('https://icpmymis.com/entrancemonitoring/backend/timekeepingapi.php', newLog)
        .then(response => {
            // console.log('Log created successfully:', response.data);
        })
        .catch(error => {
            console.error("Error saving log: ", error);
        });
}


function getIPAddress() {
    fetch('https://api.ipify.org?format=json')
        .then(response => response.json())
        .then(data => {
            // console.log('IP Address:', data.ip);
            clientIPAddress.value = data.ip; // Store the client's IP address in a reactive variable
        })
        .catch(error => {
            console.error('Error fetching IP address:', error);
        });
}

function updateTime() {
    const now = new Date();

    // Options for formatting the date and time
    const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    const timeOptions = { hour: 'numeric', minute: 'numeric', second: 'numeric', hour12: true };

    // Format the date and time separately
    const formattedDate = now.toLocaleDateString('en-US', dateOptions);
    const formattedTime = now.toLocaleTimeString('en-US', timeOptions);

    // Update the values
    currentDate.value = formattedDate;
    currentTime.value = formattedTime;
}

function getImageUrl(imageFilename) {
    if (imageFilename == "") {
        // if there is no RFID record. this is the default LOGO Display.

        return 'https://icpmymis.com/images/ICPLogo.jpg';

    }
    else {

        return `https://icpmymis.com/images/${imageFilename}`;

    }
    // Construct the full image URL based on server folder path and filename

}

function handleKeyPress(event) {
    if (event.key === 'Enter') {
        searchRFID();
        // alert(searchID.value);
    } else {
        searchID.value += event.key;
    }
}

onMounted(() => {
    updateTime();
    setInterval(updateTime, 1000);
    window.addEventListener('keypress', handleKeyPress);
});

onBeforeUnmount(() => {
    window.removeEventListener('keypress', handleKeyPress);
});

</script>

<style scoped>
.card {
    height: 100%;
}



.form-control[disabled] {
    font-size: 30px;
    /* Adjust the font size as needed */
    font-weight: bolder;
    color: #333;
    /* Optional: Adjust text color if needed */
}

.bgMain {
    background-color: powderblue;
    height: 90vh;
    width: 90vw;
    margin: 5vh auto;
    border-radius: 1%;
    /* padding: 10px;
    box-sizing: border-box; */
}

.user-image {
    width: 43vh;
    height: 43vh;
    border: 10px solid white;
    border-radius: 10%;
    /* max-height: 67vh; */
}
</style>
