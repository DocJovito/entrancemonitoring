<template>
    <div class="container mt-4">
        <h4>Schedule Details File Maintenance</h4>
        <p>{{ schedID }}</p>
        <RouterLink :to="'/scheduledetails/' + schedID + '/create'" type="button" class="btn btn-success">Add
        </RouterLink>

        <table class="table table-bordered table-hover mt-3">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <!-- <th scope="col">schedID</th> -->
                    <th scope="col">dayOfTheWeek</th>
                    <th scope="col">timeStart</th>
                    <th scope="col">timeEnd</th>
                    <th scope="col">schedDesc</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(data, index) in paginatedArrayData" :key="data.empID">
                    <th scope="row">{{ (currentPage - 1) * pageSize + index + 1 }}</th>
                    <!-- <td>{{ data.schedID }}</td> -->
                    <td>{{ formatDay(data.dayOfTheWeek) }}</td>
                    <td>{{ formatTime(data.timeStart) }}</td>
                    <td>{{ formatTime(data.timeEnd) }}</td>
                    <td>{{ data.schedDesc }}</td>
                    <td>
                        <RouterLink :to="'/scheduledetails/' + data.ID + '/' + data.schedID + '/edit'" type="button"
                            class="btn btn-primary">
                            Edit
                        </RouterLink>
                        <button type="button" class="btn btn-danger" @click="confirmDelete(data.ID)">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>


        <!-- Pagination controls -->
        <nav aria-label=" Pagination" class="d-flex justify-content-center">
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
import { useRoute } from 'vue-router';

//use route to get target id from params
const route = useRoute();
const schedID = ref('');
schedID.value = route.params.schedid;

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
        action: 'searchscheddetails',
        schedID: schedID.value,
    };
    axios.post('https://icpmymis.com/entrancemonitoring/backend/scheduledetailapi.php', data)
        .then((response) => {
            arrayData.value = response.data;
        })
        .catch((error) => {
            console.error('Error fetching data:', error);

        });
};


function confirmDelete(ID) {
    if (confirm("Are you sure you want to delete this record?")) {
        deleteRecord(ID);
    }

}

function deleteRecord(ID) {
    const data = {
        action: 'delete',
        ID: ID,
    };
    axios.post('https://icpmymis.com/entrancemonitoring/backend/scheduledetailapi.php', data)
        .then(() => {
            fetchData();
        })
        .catch((error) => {
            console.error('Error delete data:', error);

        });
}


function formatTime(time) {
    const [hours, minutes] = time.split(':');
    const formattedTime = new Date(0, 0, 0, hours, minutes).toLocaleTimeString('en-US', {
        hour: 'numeric',
        minute: 'numeric',
        hour12: true,
    });
    return formattedTime;
}

const dayNames = [
    'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'
];

function formatDay(dayNumber) {
    // Adjust for 0-based index in array
    return dayNames[dayNumber - 1];
}

</script>