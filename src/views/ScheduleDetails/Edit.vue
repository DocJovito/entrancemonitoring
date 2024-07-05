<template>
    <form @submit.prevent="updateRecord">
        <div class="container mt-4">
            <div class="card">
                <div class="card-header">
                    <h4>Edit Schedule</h4>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="dayOfTheWeek">Day of the week:</label><br>
                        <select id="dayOfTheWeek" class="form-control" v-model="dayOfTheWeek" required>
                            <option value="1">Monday</option>
                            <option value="2">Tuesday</option>
                            <option value="3">Wednesday</option>
                            <option value="4">Thursday</option>
                            <option value="5">Friday</option>
                            <option value="6">Saturday</option>
                            <option value="7">Sunday</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="timeStart">timeStart:</label><br>
                        <input type="time" id="timeStart" class="form-control" v-model="timeStart" required>
                    </div>
                    <div class="form-group">
                        <label for="timeEnd">timeEnd:</label><br>
                        <input type="time" id="timeEnd" class="form-control" v-model="timeEnd" required>
                    </div>
                    <div class="form-group">
                        <label for="schedDesc">schedDesc:</label><br>
                        <input type="text" id="schedDesc" class="form-control" v-model="schedDesc">
                    </div>


                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-2">Submit</button>
        </div>
    </form>
</template>


<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import { useRoute } from 'vue-router';

const router = useRouter();

//use route to get target id from params
const route = useRoute();
const schedID = ref('');
schedID.value = route.params.schedid;
const ID = ref('');
ID.value = route.params.id;

const dayOfTheWeek = ref('');
const timeStart = ref('');
const timeEnd = ref('');
const schedDesc = ref('');

const arrayData = ref([]);

function updateRecord() {
    const data = {
        action: 'update',
        ID: ID.value,
        dayOfTheWeek: dayOfTheWeek.value,
        timeStart: timeStart.value,
        timeEnd: timeEnd.value,
        schedDesc: schedDesc.value,
    }

    axios.post('https://rjprint10.com/entrancemonitoring/backend/scheduledetailapi.php', data)
        .then(response => {
            // alert("Record Saved", response);
            router.push('/schedules/' + schedID.value + '/viewdetails');
        })
        .catch(error => {
            console.error("Error saving record: ", error)
        });
}


onMounted(() => {
    fetchData();
});

function fetchData() {
    const data = {
        action: 'getscheddetail',
        ID: ID.value,
    };
    axios.post('https://rjprint10.com/entrancemonitoring/backend/scheduledetailapi.php', data)
        .then((response) => {
            arrayData.value = response.data;
            dayOfTheWeek.value = arrayData.value[0].dayOfTheWeek;
            timeStart.value = arrayData.value[0].timeStart;
            timeEnd.value = arrayData.value[0].timeEnd;
            schedDesc.value = arrayData.value[0].schedDesc;

        })
        .catch((error) => {
            console.error('Error fetching data:', error);

        });
};


</script>