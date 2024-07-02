<template>
    <div class="container mt-4">
        <h4>Student Maintenance</h4>
        <RouterLink to="/students/create" type="button" class="btn btn-success">Add Student</RouterLink>

        <form @submit.prevent="fetchData">
            <div class="container">
                <div class="row">
                    <div class="form-group col">
                        <label for="studID">Student ID</label>
                        <input type="text" id="studID" class="form-control" v-model="studID" placeholder="STU01-0001">
                    </div>
                    <div class="form-group col">
                        <label for="lastName">Last Name</label>
                        <input type="text" id="lastName" class="form-control" v-model="lastName" placeholder="Dela Cruz">
                    </div>
                    <div class="form-group col">
                        <label for="department">Department</label>
                        <select id="department" class="form-control" v-model="department">
                            <option value="All">All</option>
                            <option value="CSIT">CSIT</option>
                            <option value="CBEA">CBEA</option>
                            <option value="ENGG">ENGG</option>
                        </select>
                    </div>
                    <div class="form-group col">
                        <button type="submit" class="btn btn-primary mt-4">Submit</button>
                    </div>
                </div>
            </div>
        </form>

        <table class="table table-bordered table-hover mt-3">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Student ID</th>
                    <th scope="col">Last Name</th>
                    <th scope="col">First Name</th>
                    <th scope="col">Middle Name</th>
                    <th scope="col">Course/Year/Sec</th>
                    <th scope="col">Department</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(data, index) in paginatedArrayData" :key="data.studID">
                    <th scope="row">{{ (currentPage - 1) * pageSize + index + 1 }}</th>
                    <td>{{ data.studID }}</td>
                    <td>{{ data.lastName }}</td>
                    <td>{{ data.firstName }}</td>
                    <td>{{ data.middleName }}</td>
                    <td>{{ data.courseYrSec }}</td>
                    <td>{{ data.department }}</td>
                    <td>
                        <RouterLink :to="'/students/' + data.studID + '/edit'" type="button" class="btn btn-primary">
                            Edit
                        </RouterLink>
                        <button type="button" class="btn btn-danger" @click="deleteStudent(data.studID)">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Pagination controls -->
        <nav aria-label="Pagination" class="d-flex justify-content-center">
            <ul class="pagination">
                <li class="page-item" :class="{ disabled: currentPage === 1 }">
                    <button class="page-link" @click="currentPage = 1">First Page</button>
                </li>
                <li class="page-item" :class="{ disabled: currentPage === 1 }">
                    <button class="page-link" @click="currentPage -= 1">Previous</button>
                </li>
                <li class="page-item" :class="{ disabled: currentPage === totalPages }">
                    <button class="page-link" @click="currentPage += 1">Next</button>
                </li>
                <li class="page-item" :class="{ disabled: currentPage === totalPages }">
                    <button class="page-link" @click="currentPage = totalPages">Last Page</button>
                </li>
            </ul>
        </nav>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';

// search variables
const studID = ref('');
const lastName = ref('');
const department = ref('All');

const arrayData = ref([]);

const pageSize = 10;
const currentPage = ref(1);
const paginatedArrayData = computed(() => {
    const startIndex = (currentPage.value - 1) * pageSize;
    return arrayData.value.slice(startIndex, startIndex + pageSize);
});
const totalPages = computed(() => Math.ceil(arrayData.value.length / pageSize));

onMounted(() => {
    fetchData();
});

function fetchData() {
    const data = {
        action: 'search_students',
        studID: studID.value,
        lastName: lastName.value,
        department: department.value,
    };
    axios.post('https://rjprint10.com/entrancemonitoring/backend/studentapi.php', data)
        .then((response) => {
            arrayData.value = response.data;
        })
        .catch((error) => {
            console.error('Error fetching data:', error);
        });
};

function deleteStudent(studID) {
    const data = {
        action: 'delete',
        id: studID
    };
    axios.delete('https://rjprint10.com/entrancemonitoring/backend/studentapi.php', { data })
        .then((response) => {
            console.log(response.data.message);
            fetchData();
        })
        .catch((error) => {
            console.error('Error deleting student:', error);
        });
}
</script>

<style scoped>
.router-link-active {
    font-weight: bold;
    color: #007bff; /* Customize the color as needed */
}
</style>
