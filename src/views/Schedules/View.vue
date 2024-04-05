<template>
    <div class="container mt-4">
        <h4>Schedule Maintenance</h4>
        <RouterLink to="/employees/create" type="button" class="btn btn-success">Create Schedule</RouterLink>
        <table class="table table-bordered table-hover mt-3">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">schedID</th>
                    <th scope="col">schedName</th>
                    <th scope="col">schoolYear</th>
                    <th scope="col">Semester</th>
                    <th scope="col">effDateStart</th>
                    <th scope="col">effDateEnd</th>
                    <th scope="col">description</th>
                    <th scope="col">dateCreated</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(schedule, index) in paginatedschedules" :key="schedule.schedID">
                    <th scope="row">{{ (currentPage - 1) * itemsPerPage + index + 1 }}</th>
                    <td>{{ schedule.schedID }}</td>
                    <td>{{ schedule.schedName }}</td>
                    <td>{{ schedule.schoolYear }}</td>
                    <td>{{ schedule.Semester }}</td>
                    <td>{{ schedule.effDateStart }}</td>
                    <td>{{ schedule.effDateEnd }}</td>
                    <td>{{ schedule.description }}</td>
                    <td>{{ schedule.dateCreated }}</td>
                    <td>
                        <!-- <RouterLink :to="'/employees/' + employee.empID + '/edit'" type="button"
                            class="btn btn-primary">Edit
                        </RouterLink> -->
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

    <div class="container mt-3 text-center">
        <button id="exportButton" type="button" class="btn btn-success" @click="exportCSV">Export to CSV</button>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';

const employees = ref([]);
const schedules = ref([]);
const currentPage = ref(1);
const itemsPerPage = 10;

onMounted(() => {
    fetchData();
});

const fetchData = async () => {
    try {
        const response = await axios.get('https://rjprint10.com/entrancemonitoring/backend/schedulesapi.php?action=get_all');
        schedules.value = response.data;
    } catch (error) {
        console.error("Error fetching data", error);
    }
};


// Function to export data to CSV
const exportCSV = () => {
    // Create the CSV content
    let csvContent = 'data:text/csv;charset=utf-8,';

    // Add CSV headers
    csvContent += 'No,schedID,schedName,schoolYear,Semester,effDateStart,effDateEnd,description,dateCreated\n';

    // Add data rows to the CSV content
    schedules.value.forEach((schedule, index) => {
        csvContent += `${index + 1},${schedule.schedID},${schedule.schedName},${schedule.schoolYear},${schedule.Semester},${schedule.effDateStart},${schedule.effDateEnd},${schedule.description},${schedule.dateCreated}\n`;
    });

    // Create a Blob object containing the CSV data
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });

    // Create a temporary anchor element to trigger the download
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    link.setAttribute('href', url);
    link.setAttribute('download', 'schedules.csv');

    // Append the anchor to the document body and trigger the download
    document.body.appendChild(link);
    link.click();

    // Clean up by removing the anchor and revoking the URL
    document.body.removeChild(link);
    URL.revokeObjectURL(url);
};





const totalPages = computed(() => Math.ceil(schedules.value.length / itemsPerPage));

const paginatedschedules = computed(() => {
    const startIndex = (currentPage.value - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    return schedules.value.slice(startIndex, endIndex);
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