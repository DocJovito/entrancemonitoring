<template>
    <div class="container">
        <h4>Select Employee</h4>
        <form @submit.prevent="fetchData">
            <div class="container">
                <div class="row">
                    <div class="form-group col">
                        <label for="dateStart">Date Start</label>
                        <input type="date" class="form-control" id="dateStart" v-model="dateStart">
                    </div>
                    <div class="form-group col">
                        <label for="dateEnd">Date End</label>
                        <input type="date" class="form-control" id="dateEnd" v-model="dateEnd">
                    </div>
                </div>

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
                            <option v-for="data in arrayDeptData" :key="data.department" :value="data.department">{{
            data.department }}</option>
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
                    <!-- <th scope="col">middleName</th> -->
                    <th scope="col">position</th>
                    <th scope="col">department</th>
                    <!-- <th scope="col">empType</th> -->
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(data, index) in paginatedArrayData" :key="data.empID">
                    <th scope="row">{{ (currentPage - 1) * pageSize + index + 1 }}</th>
                    <td>{{ data.empID }}</td>
                    <td>{{ data.lastName }}</td>
                    <td>{{ data.firstName }}</td>
                    <!-- <td>{{ data.middleName }}</td> -->
                    <td>{{ data.position }}</td>
                    <td>{{ data.department }}</td>
                    <!-- <td>{{ data.empType }}</td> -->
                    <td>
                        <button @click="showModal(data.empID)" class="btn btn-primary">Select</button>
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

        <TimeCardModal v-if="isModalVisible" :isVisible="isModalVisible" :empID="empID" :dateStart="dateStart"
            :dateEnd="dateEnd" @close="hideModal" />
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
// import { defineEmits } from 'vue';
import TimeCardModal from '@/views/Reports/TimeCardModal.vue';


//modal control
const isModalVisible = ref(false);
// Define emits to declare the toggleNav event
const emits = defineEmits(['toggleNav']);
// Function to show the modal
const showModal = (empIDparam) => {
    isModalVisible.value = true;
    empID.value = empIDparam;
};
// Function to hide the modal
const hideModal = () => {
    isModalVisible.value = false;
};
// Function to toggle navigation (emitting toggleNav event)
const toggleNavigation = () => {
    emits('toggleNav');
};




//search variables
const empID = ref('');
const lastName = ref('');
const department = ref('All');
const empType = ref('All');

//qry variables
const dateStart = ref(new Date().toISOString().slice(0, 10));
const dateEnd = ref(new Date().toISOString().slice(0, 10));

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
    fetchDept();
});

function fetchData() {
    const data = {
        action: 'search_employees',
        empID: empID.value,
        lastName: lastName.value,
        department: department.value,
        empType: empType.value,
    };
    axios.post('https://icpmymis.com/entrancemonitoring/backend/employeeapi.php', data)
        .then((response) => {
            arrayData.value = response.data;
        })
        .catch((error) => {
            console.error('Error fetching data:', error);

        });
};

const arrayDeptData = ref([]);
function fetchDept() {
    const params = {
        action: 'get_distinct_dept',
    };
    axios.get('https://icpmymis.com/entrancemonitoring/backend/employeeapi.php', { params })
        .then((response) => {
            arrayDeptData.value = response.data;
        })
        .catch((error) => {
            console.error('Error fetching data:', error);
        });
};



</script>