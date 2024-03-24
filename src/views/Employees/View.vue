<template>
    <div class="container mt-4">
        <h4>Employee File Maintenance</h4>
        <RouterLink to="/employees/create" type="button" class="btn btn-primary">Add Employee</RouterLink>
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">empID</th>
                    <th scope="col">lastName</th>
                    <th scope="col">firstName</th>
                    <th scope="col">middleName</th>
                    <th scope="col">position</th>
                    <th scope="col">department</th>
                    <th scope="col">bday</th>
                    <th scope="col">isActive</th>
                    <th scope="col">empType</th>
                    <th scope="col">image</th>
                    <th scope="col">note</th>
                    <th scope="col">schedID</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(employee, index) in paginatedEmployees" :key="employee.empID">
                    <th scope="row">{{ (currentPage - 1) * itemsPerPage + index + 1 }}</th>
                    <td>{{ employee.empID }}</td>
                    <td>{{ employee.lastName }}</td>
                    <td>{{ employee.firstName }}</td>
                    <td>{{ employee.middleName }}</td>
                    <td>{{ employee.position }}</td>
                    <td>{{ employee.department }}</td>
                    <td>{{ employee.bday }}</td>
                    <td>{{ employee.isActive }}</td>
                    <td>{{ employee.empType }}</td>
                    <td>{{ employee.image }}</td>
                    <td>{{ employee.note }}</td>
                    <td>{{ employee.schedID }}</td>
                    <td>
                        <RouterLink :to="'/employees/' + employee.empID + '/edit'" type="button"
                            class="btn btn-primary">Edit
                        </RouterLink>
                        <button type="button" class="btn btn-danger">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Pagination controls -->
        <nav aria-label="Page navigation" class="d-flex justify-content-center">
            <ul class="pagination">
                <li class="page-item" :class="{ disabled: currentPage === 1 }">
                    <a class="page-link" href="#" @click.prevent="previousPage">Previous</a>
                </li>
                <li class="page-item" v-for="page in totalPages" :key="page" :class="{ active: currentPage === page }">
                    <a class="page-link" href="#" @click.prevent="changePage(page)">{{ page }}</a>
                </li>
                <li class="page-item" :class="{ disabled: currentPage === totalPages }">
                    <a class="page-link" href="#" @click.prevent="nextPage">Next</a>
                </li>
            </ul>
        </nav>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';

const employees = ref([]);
const currentPage = ref(1);
const itemsPerPage = 5;

onMounted(() => {
    fetchData();
});

const fetchData = async () => {
    try {
        const response = await axios.get('https://rjprint10.com/entrancemonitoring/backend/employeeapi.php?action=get_all');
        employees.value = response.data;
    } catch (error) {
        console.error("Error fetching data", error);
    }
};

const totalPages = computed(() => Math.ceil(employees.value.length / itemsPerPage));

const paginatedEmployees = computed(() => {
    const startIndex = (currentPage.value - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    return employees.value.slice(startIndex, endIndex);
});

const changePage = (page) => {
    currentPage.value = page;
};

const previousPage = () => {
    if (currentPage.value > 1) {
        currentPage.value--;
    }
};

const nextPage = () => {
    if (currentPage.value < totalPages.value) {
        currentPage.value++;
    }
};
</script>