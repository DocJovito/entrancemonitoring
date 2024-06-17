<template>
    <div class="container mt-4">

        <div class="card">
            <div class="card-header">
                <h4>Edit Employee</h4>
            </div>
            <div class="card-body">

                <div class="form-group">
                    <label for="empID">empID:</label><br>
                    <input type="text" id="empID" class="form-control" v-model="empID">
                </div>
                <div class="form-group">
                    <label for="lastName">lastName:</label><br>
                    <input type="text" id="lastName" class="form-control" v-model="lastName">
                </div>
                <div class="form-group">
                    <label for="firstName">firstName:</label><br>
                    <input type="text" id="firstName" class="form-control" v-model="firstName">
                </div>
                <div class="form-group">
                    <label for="middleName">middleName:</label><br>
                    <input type="text" id="middleName" class="form-control" v-model="middleName">
                </div>
                <div class="form-group">
                    <label for="position">position:</label><br>
                    <input type="text" id="position" class="form-control" v-model="position">
                </div>
                <div class="form-group">
                    <label for="department">department:</label><br>
                    <input type="text" id="department" class="form-control" v-model="department">
                </div>
                <div class="form-group">
                    <label for="bday">bday:</label><br>
                    <input type="text" id="bday" class="form-control" v-model="bday">
                </div>
                <div class="form-group">
                    <label for="isActive">isActive:</label><br>
                    <input type="text" id="isActive" class="form-control" v-model="isActive">
                </div>
                <div class="form-group">
                    <label for="empType">empType:</label><br>
                    <input type="text" id="empType" class="form-control" v-model="empType">
                </div>
                <div class="form-group">
                    <label for="image">image:</label><br>
                    <input type="text" id="image" class="form-control" v-model="image">
                </div>
                <div class="form-group">
                    <label for="note">note:</label><br>
                    <input type="text" id="note" class="form-control" v-model="note">
                </div>
            </div>
        </div>

        <button type="button" class="btn btn-primary" @click="updateRecord">Update</button>

    </div>

</template>


<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRoute } from 'vue-router';

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

const employees = ref([]);

//use route to get target id from params
const route = useRoute();
empID.value = route.params.empid;

onMounted(() => {
    axios.get('https://rjprint10.com/entrancemonitoring/backend/employeeapi.php?action=get_by_id&empid=' + empID.value)
        .then((response) => {
            employees.value = response.data;
            empID.value = employees.value.empID;
            lastName.value = employees.value.lastName;
            firstName.value = employees.value.firstName;
            middleName.value = employees.value.middleName;
            position.value = employees.value.position;
            department.value = employees.value.department;
            bday.value = employees.value.bday;
            isActive.value = employees.value.isActive;
            empType.value = employees.value.empType;
            image.value = employees.value.image;
            note.value = employees.value.note;
        })
        .catch((error) => {
            console.error("Error fetching data: ", error)
        });
});



function updateRecord() {
    const newRecord = {
        action: 'update',
        empID: empID.value,
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
    }

    axios.post('https://rjprint10.com/entrancemonitoring/backend/employeeapi.php', newRecord)
        .then(response => {
            alert("Record Updated", response);
        })
        .catch(error => {
            console.error("Error updating record: ", error)
        });
}



</script>