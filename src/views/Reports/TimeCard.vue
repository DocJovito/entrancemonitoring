<template>
    <div class="container mt-4">
        <h4>Reports - Time Card</h4>

        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <label for="search1" class="form-label">Search</label>
                    <input type="text" class="form-control" id="search1" placeholder="LastName" v-model="empidname"
                        @input="fetchData">
                </div>
                <div class="col-md-3">
                    <label for="search2" class="form-label">Department</label>
                    <input type="text" class="form-control" id="search2" placeholder="Department" v-model="department"
                        @input="fetchData">
                </div>
                <div class="col-md-3">
                    <label for="dateStart" class="form-label">Date Start</label>
                    <input type="date" class="form-control" id="dateStart" v-model="datestart" @change="fetchData">
                </div>
                <div class="col-md-3">
                    <label for="dateEnd" class="form-label">Date End</label>
                    <input type="date" class="form-control" id="dateEnd" v-model="dateend" @change="fetchData">
                </div>
            </div>
        </div>



        <table class="table table-bordered table-hover mt-3">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">empID</th>
                    <th scope="col">lastName</th>
                    <th scope="col">firstName</th>
                    <th scope="col">position</th>
                    <th scope="col">department</th>
                    <th scope="col">date</th>
                    <th scope="col">timein</th>
                    <th scope="col">timeout</th>
                    <th scope="col">totalhours</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(employee, index) in paginatedEmployees" :key="employee.empID">
                    <th scope="row">{{ (currentPage - 1) * itemsPerPage + index + 1 }}</th>
                    <td>{{ employee.empid }}</td>
                    <td>{{ employee.lastname }}</td>
                    <td>{{ employee.firstname }}</td>
                    <td>{{ employee.position }}</td>
                    <td>{{ employee.department }}</td>
                    <td>{{ employee.date }}</td>
                    <td>{{ employee.time_in }}</td>
                    <td>{{ employee.time_out }}</td>
                    <td>{{ employee.total_hours }}</td>

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

    <div class="container mt-3 text-center">
        <button id="exportButton" type="button" class="btn btn-success" @click="exportCSV">Export to CSV</button>
    </div>

</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';

const employees = ref([]);
const currentPage = ref(1);
const itemsPerPage = 20;


const empidname = ref("");
const department = ref("");
const datestart = ref(new Date().toISOString().substr(0, 10));
const dateend = ref(new Date().toISOString().substr(0, 10));

onMounted(() => {
    fetchData();
});


function fetchData() {

    console.log(empidname.value);

    const searchKey = {
        action: 'alllogs',
        empid_pattern: empidname.value,
        lastname_pattern: empidname.value,
        department: department.value,
        start_date: datestart.value,
        end_date: dateend.value,
    }
    axios.post('https://rjprint10.com/entrancemonitoring/backend/reportsapi.php', searchKey)
        .then(response => {
            employees.value = response.data;
        })
        .catch(error => {
            console.error("Error getting record: ", error)
        });
}

//csv 2 functions
function dataToCSV(data) {
    const header = ['empID', 'lastName', 'firstName', 'position', 'department', 'date', 'time_in', 'time_out', 'total_hours'];
    let csv = header.join(',') + '\n';
    data.forEach(employee => {
        const row = Object.values(employee).join(',');
        csv += row + '\n';
    });
    return csv;
}

function exportCSV() {
    const csvData = dataToCSV(employees.value);
    const encodedUri = encodeURI('data:text/csv;charset=utf-8,' + csvData);
    const link = document.createElement('a');
    link.setAttribute('href', encodedUri);
    link.setAttribute('download', 'employees.csv');
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link); // Cleanup after download
}


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