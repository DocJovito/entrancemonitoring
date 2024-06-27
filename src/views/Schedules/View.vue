<template>
    <div class="container mt-4">
        <h4>Schedule File Maintenance</h4>
        <RouterLink to="/schedules/create" type="button" class="btn btn-success">Create Schedule</RouterLink>

        <form @submit.prevent="fetchData">
            <div class="container">
                <div class="row">
                    <div class="form-group col">
                        <label for="schedID">schedID</label>
                        <input type="text" id="schedID" class="form-control" v-model="schedID" placeholder="1">
                    </div>
                    <div class="form-group col">
                        <label for="schedName">schedName</label>
                        <input type="text" id="schedName" class="form-control" v-model="schedName"
                            placeholder="Dela Cruz Sched">
                    </div>
                    <div class="form-group col">
                        <label for="schoolYear">schoolYear</label>
                        <select id="schoolYear" class="form-control" v-model="schoolYear">
                            <option value="All">All</option>
                            <option value="2021-2022">2021-2022</option>
                            <option value="2022-2023">2022-2023</option>
                            <option value="2023-2024">2023-2024</option>
                        </select>
                    </div>
                    <div class="form-group col">
                        <label for="semester">semester</label>
                        <select id="semester" class="form-control" v-model="semester">
                            <option value="All">All</option>
                            <option value="1st">1st Sem</option>
                            <option value="2nd">2nd Sem</option>
                            <option value="Summer">Summer</option>
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
                    <th scope="col">schedID</th>
                    <th scope="col">schedName</th>
                    <th scope="col">schoolYear</th>
                    <th scope="col">semester</th>
                    <th scope="col">effDateStart</th>
                    <th scope="col">effDateEnd</th>
                    <th scope="col">description</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(data, index) in paginatedArrayData" :key="data.schedID">
                    <th scope="row">{{ (currentPage - 1) * pageSize + index + 1 }}</th>
                    <td>{{ data.schedID }}</td>
                    <td>{{ data.schedName }}</td>
                    <td>{{ data.schoolYear }}</td>
                    <td>{{ data.semester }}</td>
                    <td>{{ data.effDateStart }}</td>
                    <td>{{ data.effDateEnd }}</td>
                    <td>{{ data.description }}</td>
                    <td>
                        <RouterLink :to="'/schedules/' + data.schedID + '/viewdetails'" type="button"
                            class="btn btn-success">
                            View
                        </RouterLink>
                        <RouterLink :to="'/schedules/' + data.schedID + '/update'" type="button"
                            class="btn btn-primary">
                            Edit
                        </RouterLink>
                        <button type="button" class="btn btn-danger"
                            @click="confirmDelete(data.schedID)">Delete</button>
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
const schedID = ref('');
const schedName = ref('');
const schoolYear = ref('All');
const semester = ref('All');

//delete
const deletedBy = ref('1'); //replace with current user

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
        action: 'searchsched',
        schedID: schedID.value,
        schedName: schedName.value,
        schoolYear: schoolYear.value,
        semester: semester.value,
    };
    axios.post('https://rjprint10.com/entrancemonitoring/backend/scheduleapi.php', data)
        .then((response) => {
            arrayData.value = response.data;
        })
        .catch((error) => {
            console.error('Error fetching data:', error);

        });
};

function confirmDelete(schedID) {
    if (confirm("Are you sure you want to delete this record?")) {
        deleteRecord(schedID);
        fetchData();
    }

}

function deleteRecord(schedID) {
    const data = {
        action: 'delete',
        schedID: schedID,
        deletedBy: deletedBy.value,
    };
    axios.post('https://rjprint10.com/entrancemonitoring/backend/scheduleapi.php', data)
        .then(() => {
            fetchData();
        })
        .catch((error) => {
            console.error('Error delete data:', error);

        });
}


</script>