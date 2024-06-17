<template>
    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <h4>Add Employee</h4>
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
                    <input type="date" id="bday" class="form-control" v-model="bday">
                </div>
                <div class="form-group">
                    <label for="isActive">isActive:</label><br>
                    <select id="isActive" class="form-control" v-model="isActive">
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="empType">empType:</label><br>
                    <input type="text" id="empType" class="form-control" v-model="empType">
                </div>
                <div class="form-group">
                    <label for="image">image:</label><br>
                    <input type="url" id="image" class="form-control" v-model="image">
                </div>
                <div class="form-group">
                    <label for="note">note:</label><br>
                    <textarea id="note" class="form-control" v-model="note"></textarea>
                </div>
                <div class="form-group">
                    <label for="schedID">schedID:</label><br>
                    <input type="number" id="schedID" class="form-control" v-model="schedID">
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-primary" @click="saveRecord">Save</button>
    </div>
</template>


<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

const router = useRouter();

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

function saveRecord() {
    const data = {
        action: 'create',
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
        schedID: schedID.value,
    }

    axios.post('https://rjprint10.com/entrancemonitoring/backend/employeeapi.php', data)
        .then(response => {
            // alert("Record Saved", response);
            router.push('/employees/view');
        })
        .catch(error => {
            console.error("Error saving record: ", error)
        });
}



</script>