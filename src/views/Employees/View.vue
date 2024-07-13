<template>
    <div class="container mt-4">
        <h4>Employee File Maintenance</h4>
        <router-link to="/employees/create" type="button" class="btn btn-success">Add Employee</router-link>

        <form @submit.prevent="fetchData">
            <div class="container">
                <div class="row">
                    <div class="form-group col">
                        <label for="empID">empID</label>
                        <input type="text" id="empID" class="form-control" v-model="empID" placeholder="ICI08-0001">
                    </div>
                    <div class="form-group col">
                        <label for="lastName">lastName</label>
                        <input type="text" id="lastName" class="form-control" v-model="lastName"
                            placeholder="Dela Cruz">
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
                    <th scope="col">ID</th>
                    <th scope="col">Image</th> <!-- New image column -->
                    <th scope="col">RFID</th>
                    <th scope="col">Employee ID</th>
                    <th scope="col">LastName</th>
                    <th scope="col">FirstName</th>
                    <th scope="col">MiddleName</th>
                    <th scope="col">Position</th>
                    <th scope="col">Department</th>
                    <th scope="col">EmpType</th>
                    <th scope="col">Option</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(data, index) in paginatedArrayData" :key="data.ID">
                    <th scope="row">{{ (currentPage - 1) * pageSize + index + 1 }}</th>
                    <td>{{ data.ID }}</td>
                    <td>
                        <img :src="getImageUrl(data.image)" style="max-width: 40px; max-height: 40px;"
                            alt="Employee Image">
                    </td>
                    <td>{{ data.RFID }}</td>
                    <td>{{ data.empID }}</td>
                    <td>{{ data.lastName }}</td>
                    <td>{{ data.firstName }}</td>
                    <td>{{ data.middleName }}</td>
                    <td>{{ data.position }}</td>
                    <td>{{ data.department }}</td>
                    <td>{{ data.empType }}</td>
                    <td>
                        <router-link :to="'/employees/' + data.empID + '/edit'" type="button" class="btn btn-primary">
                            Edit
                        </router-link>
                        <button type="button" class="btn btn-danger" @click="deleteEmployee(data.empID)">Delete</button>
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

const empID = ref('');
const lastName = ref('');
const department = ref('All');
const empType = ref('All');
const image = ref('');

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

async function fetchData() {
    try {
        const response = await axios.post('https://icpmymis.com/entrancemonitoring/backend/employeeapi.php', {
            action: 'search_employees',
            empID: empID.value,
            lastName: lastName.value,
            department: department.value,
            empType: empType.value,
            image: empID.value
        });
        arrayData.value = response.data;
    } catch (error) {
        console.error('Error fetching data:', error);
    }
}

function getImageUrl(imageFilename) {
    if (!imageFilename || imageFilename === "") {
        // If no image is available, you can return a placeholder or default image URL
        return 'https://example.com/default-image.jpg';
    } else {
        // Construct the full image URL based on server folder path and filename
        return `https://icpmymis.com/images/${imageFilename}`;
    }
}

async function deleteEmployee(empIDToDelete) {
    // Confirm deletion with user
    if (confirm('Are you sure you want to delete this employee?')) {
        try {
            const response = await axios.delete('https://rjprint10.com/entrancemonitoring/backend/employeeapi.php', {
                data: {
                    action: 'delete',
                    empID: empIDToDelete
                }
            });

            // Handle success message
            alert(response.data.message); // Assuming backend sends a message on success

            // Optionally, update UI after deletion
            // Example: Fetch data again to refresh the list
            fetchData();
        } catch (error) {
            console.error('Error deleting employee:', error);
            alert('Error deleting employee. Please try again.');
        }
    } else {
        // User canceled deletion
        alert('Deletion canceled.');
    }
}
</script>
