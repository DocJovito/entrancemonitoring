<template>
    <div class="container mt-4">
        <h4>RFID File Maintenance</h4>
        <RouterLink to="/employees/create" type="button" class="btn btn-success">Add Employee</RouterLink>

        <form @submit.prevent="fetchData">
            <div class="container">
                <div class="row">
                    <div class="form-group col">
                        <label for="empID">empID</label>
                        <input type="text" id="empID" class="form-control" v-model="empID" placeholder="ICI08-0001">
                    </div>
                    <div class="form-group col">
                        <label for="lastName">lastName</label>
                        <input type="text" id="empID" class="form-control" v-model="lastName" placeholder="Dela Cruz">
                    </div>
                    <div class="form-group col">
                        <label for="department">department</label>
                        <select id="department" class="form-control" v-model="department">
                            <option value="All">All</option>
                            <option value="ADMIN">ADMIN</option>
                            <option value="CSIT">CSIT</option>
                            <option value="CBEA">CBEA</option>
                        </select>
                    </div>
                    <div class="form-group col">
                        <label for="empType">empType</label>
                        <select id="empType" class="form-control" v-model="empType">
                            <option value="All">All</option>
                            <option value="Full-Time">Full-Time</option>
                            <option value="Part-Time">Part-Time</option>
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
                    <th scope="col">empID</th>
                    <th scope="col">lastName</th>
                    <th scope="col">firstName</th>
                    <th scope="col">middleName</th>
                    <th scope="col">position</th>
                    <th scope="col">department</th>
                    <th scope="col">empType</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(data, index) in paginatedArrayData" :key="data.empID">
                    <th scope="row">{{ (currentPage - 1) * pageSize + index + 1 }}</th>
                    <td>{{ data.empID }}</td>
                    <td>{{ data.lastName }}</td>
                    <td>{{ data.firstName }}</td>
                    <td>{{ data.middleName }}</td>
                    <td>{{ data.position }}</td>
                    <td>{{ data.department }}</td>
                    <td>{{ data.empType }}</td>
                    <td>
                        <RouterLink :to="'/employees/' + data.empID + '/edit'" type="button" class="btn btn-primary">
                            Edit
                        </RouterLink>
                        <button type="button" class="btn btn-danger">Delete</button>
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

//search variables
const empID = ref('');
const lastName = ref('');
const department = ref('All');
const empType = ref('All');

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
        action: 'search_employees',
        empID: empID.value,
        lastName: lastName.value,
        department: department.value,
        empType: empType.value,
    };
    axios.post('https://rjprint10.com/entrancemonitoring/backend/employeeapi.php', data)
        .then((response) => {
            arrayData.value = response.data;
        })
        .catch((error) => {
            console.error('Error fetching data:', error);

        });
};



</script>