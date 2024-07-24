<template>
    <div v-if="isVisible" class="modal-overlay" @click="closeModal">
        <div class="modalbg " @click.stop>
            <div class="modal-header">
                <h4>Employee Time Card</h4>
            </div>
            <div class="modal-body">
                <div class="row headers">
                    <div class="col">
                        <p>Employee ID: {{ empID }}</p>
                    </div>
                    <div class="col">
                        <p>Employee Name: {{ employee.lastName + ", " + employee.firstName }}</p>
                    </div>
                    <div class="col">
                        <p>Start Date: {{ dateStart }}</p>
                    </div>
                    <div class="col">
                        <p>End Date: {{ dateEnd }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <p class="good">Total Work Time: {{ formatTotalTime(arraySummaryData.total_time) }}</p>
                    </div>
                    <div class="col">
                        <p class="bad">Total Late: {{ formatTotalTime(arraySummaryData.total_late) }}</p>
                    </div>
                    <div class="col">
                        <p class="bad">Total Undertime: {{ formatTotalTime(arraySummaryData.total_undertime) }}
                        </p>
                    </div>
                    <div class="col">
                        <p class="bad">Total Absent: {{ arraySummaryData.total_absent }}
                        </p>
                    </div>


                </div>
                <table class="table table-bordered table-hover mt-3">
                    <thead>
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Day</th>
                            <th scope="col">Schedule Start</th>
                            <th scope="col">Schedule End</th>
                            <th scope="col">Log In</th>
                            <th scope="col">Log Out</th>
                            <th scope="col">Total Time</th>
                            <th scope="col">late</th>
                            <th scope="col">undertime</th>
                            <th scope="col">Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(data, index) in arrayData" :key="data.logID">
                            <td>{{ data.date }}</td>
                            <td>{{ data.day_of_week }}</td>
                            <td>{{ data.timeStart }}</td>
                            <td>{{ data.timeEnd }}</td>
                            <td>{{ data.log_in }}</td>
                            <td>{{ data.log_out }}</td>
                            <td>{{ formatTotalTime(data.total_time) }}</td>
                            <td>{{ formatTotalTime(data.late) }}</td>
                            <td>{{ formatTotalTime(data.undertime) }}</td>
                            <td :class="data.absent == 1 ? 'bad' : 'good'">
                                {{ data.absent == 1 ? 'ABSENT' : 'PRESENT' }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer mt-2">
                <!-- <button class="btn btn-success" @click="printID">Print</button> -->
                <button class="btn btn-danger" @click="closeModal">Close</button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { defineProps, defineEmits, onMounted, ref } from 'vue';
import axios from 'axios';

const employee = ref([]);
const arrayData = ref([]);
const arraySummaryData = ref([]);


const props = defineProps({
    isVisible: {
        type: Boolean,
        required: true,
    },
    empID: {
        type: String,
        required: true,
    },
    dateStart: {
        required: true,
        // type: Date
    },
    dateEnd: {
        required: true,
        // type: Date
    }
});



const emit = defineEmits(['close']);

const closeModal = () => {
    emit('close');
};

function printID() {
    window.print();
}

function formatTotalTime(totalTime) {
    if (!totalTime) return ""; // Handle missing total_time
    // if (!totalTime) return "0h 0m"; // Handle missing total_time
    const parts = totalTime.split(':');
    const hours = parseInt(parts[0], 10);
    const minutes = parseInt(parts[1], 10);
    // const seconds = parseInt(parts[2], 10);
    return `${hours}h ${minutes}m`;
    // return `${hours}h ${minutes}m ${seconds}s`;
}

onMounted(() => {
    fetchEmployeeData();
    getTimeCard();
    getTimeCardSummary();
});

function fetchEmployeeData() {
    axios
        .get(`https://icpmymis.com/entrancemonitoring/backend/employeeapi.php?action=get_emp&empid=${props.empID}`)
        .then((response) => {
            employee.value = response.data;
        })
        .catch((error) => {
            console.error("Error fetching data: ", error);
        });
}

function getTimeCard() {
    const data = {
        action: 'timecard',
        empID: props.empID,
        start_date: dateStart.value,
        end_date: dateEnd.value,
    };
    axios
        .post('https://icpmymis.com/entrancemonitoring/backend/reportsapi.php', data)
        .then((response) => {
            arrayData.value = response.data;

        })
        .catch((error) => {
            console.error("Error fetching data: ", error);
        });
}

function getTimeCardSummary() {
    const data = {
        action: 'timecardsummary',
        empID: props.empID,
        start_date: dateStart.value,
        end_date: dateEnd.value,
    };
    axios
        .post('https://icpmymis.com/entrancemonitoring/backend/reportsapi.php', data)
        .then((response) => {
            arraySummaryData.value = response.data;
        })
        .catch((error) => {
            console.error("Error fetching data: ", error);
        });
}


</script>


<style scoped>
.headers {
    font-weight: bolder;
}

.bad {
    color: rgba(255, 0, 0, 1);
    font-weight: bold;
}


.good {
    color: darkgreen;
    font-weight: bold;
}



.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.3);
    display: flex;
    align-items: center;
    justify-content: center;
}

.modalbg {
    padding: 1%;
    background: white;
    border-radius: 0;
    width: 90%;
    max-height: 90vh;
    overflow: auto;
}

.modal-header {
    display: flex;
    justify-content: space-between;
}

.modal-body {
    display: block;
    margin: 0;
}

.table-container {
    max-height: 60vh;
    /* Adjust this value as needed */
    overflow-y: auto;
}

.modal-footer {
    display: flex;
    justify-content: center;
    gap: 10px;
}

@media print {
    body * {
        visibility: hidden;
    }

    .modal-overlay,
    .modalbg {
        visibility: visible;
        position: static !important;
        /* Ensure it's not fixed for printing */
        width: auto !important;
        /* Allow natural width */
    }

    .modal-body {
        display: block;
        /* Ensure body content flows */
    }

    .print-hide {
        display: none;
    }
}
</style>