<template>
    <div class="container mt-4">
        <h4>Schedule Details File Maintenance</h4>
        <p>{{ schedID }}</p>
        <RouterLink to="/employees/create" type="button" class="btn btn-success">Add Schedule</RouterLink>
        <table class="table table-bordered table-hover mt-3">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">schedID</th>
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
                    <td>{{ data.schedID }}</td>
                    <td>{{ data.dayOfTheWeek }}</td>
                    <td>{{ data.timeStart }}</td>
                    <td>{{ data.timeEnd }}</td>
                    <td>{{ data.schedDesc }}</td>
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
        action: 'searchscheddetail',
        schedID: schedID.value,
    };
    axios.post('https://rjprint10.com/entrancemonitoring/backend/scheduleapi.php', data)
        .then((response) => {
            arrayData.value = response.data;
        })
        .catch((error) => {
            console.error('Error fetching data:', error);

        });
};



</script>