<template>
    <form @submit.prevent="saveRecord">
        <div class="container mt-4">
            <div class="card">
                <div class="card-header">
                    <h4>Add Schedule</h4>
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
import { ref } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import { useRoute } from 'vue-router';

const router = useRouter();

//use route to get target id from params
const route = useRoute();
const schedID = ref('');
schedID.value = route.params.schedid;

const dayOfTheWeek = ref('');

const timeStart = ref('');
const timeEnd = ref('');

function saveRecord() {
    const data = {
        action: 'create',
        schedID: schedID.value,
        dayOfTheWeek: dayOfTheWeek.value,
        timeStart: timeStart.value,
        timeEnd: timeEnd.value,
        schedDesc: schedDesc.value,
    }

    axios.post('https://icpmymis.com/entrancemonitoring/backend/scheduledetailapi.php', data)
        .then(response => {
            // alert("Record Saved", response);
            router.push('/schedules/' + schedID.value + '/viewdetails');
        })
        .catch(error => {
            console.error("Error saving record: ", error)
        });
}



</script>