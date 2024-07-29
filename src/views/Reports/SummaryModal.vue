<template>
    <div v-if="isVisible" class="modal-overlay" @click="closeModal">
        <div class="modalbg " @click.stop>
            <div class="modal-header">
                <h4>Timekeeping Scheduled Summary</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <!-- <p>Employee ID: {{ empID }}</p> -->
                    </div>
                    <div class="col">
                        <!-- <p>Employee Name: {{ employee.lastName + ", " + employee.firstName }}</p> -->
                    </div>
                    <div class="col">
                        <p>Start Date: {{ dateStart }}</p>
                    </div>
                    <div class="col">
                        <p>End Date: {{ dateEnd }}</p>
                    </div>
                </div>
                <table class="table table-bordered table-hover mt-3">
                    <thead>
                        <tr>
                            <th scope="col">empID</th>
                            <th scope="col">fullName</th>
                            <th scope="col">total_time</th>
                            <th scope="col">late</th>
                            <th scope="col">undertime</th>
                            <th scope="col">absent</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(data, index) in arrayData" :key="data.logID">
                            <td>{{ data.empID }}</td>
                            <td>{{ data.fullName }}</td>
                            <td>{{ (data.total_time) }}</td>
                            <td>{{ (data.total_late) }}</td>
                            <td>{{ (data.total_undertime) }}</td>
                            <td>{{ data.total_absent }}</td>

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
import { onMounted, ref } from 'vue';
import axios from 'axios';

const employee = ref([]);
const arrayData = ref([]);

const props = defineProps({
    isVisible: {
        type: Boolean,
        required: true,
    },
    // empID: {
    //     type: String,
    //     required: true,
    // },
    dateStart: {
        required: true,
        // type: Date
    },
    dateEnd: {
        required: true,
        // type: Date
    },
    summaryData: {
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

function formatTotalTime(seconds) {
    const hours = Math.floor(seconds / 3600);
    const minutes = Math.floor((seconds % 3600) / 60);
    const formattedHours = hours < 10 ? `0${hours}` : hours;
    const formattedMinutes = minutes < 10 ? `0${minutes}` : minutes;
    return `${formattedHours}:${formattedMinutes}`;
}

onMounted(() => {
    arrayData.value = props.summaryData;
    // fetchEmployeeData();
    // getTimeCard();
});

function fetchEmployeeData() {
    axios
        .get(`https://icpmymis.com/entrancemonitoring/backend/employeeapi.php?action=get_emp&empid=${props.empID}`)
        .then((response) => {
            employee.value = response.data;
            // console.log(employee.lastName)
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
            // console.log(employee.lastName)
        })
        .catch((error) => {
            console.error("Error fetching data: ", error);
        });
}


</script>


<style scoped>
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