<template>
    <form @submit.prevent="updateRecord">
        <div class="container mt-4">
            <div class="card">
                <div class="card-header">
                    <h4>Update Schedule</h4>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="schedID ">schedID :</label><br>
                        <input type="text" id="schedID " class="form-control" v-model="schedID"
                            placeholder="System Generated" readonly>
                    </div>
                    <div class="form-group">
                        <label for="schedName">schedName:</label><br>
                        <input type="text" id="schedName" class="form-control" v-model="schedName">
                    </div>
                    <div class="form-group col">
                        <label for="schoolYear">schoolYear</label>
                        <select id="schoolYear" class="form-control" v-model="schoolYear">
                            <option value="2021-2022">2021-2022</option>
                            <option value="2022-2023">2022-2023</option>
                            <option value="2023-2024">2023-2024</option>
                        </select>
                    </div>
                    <div class="form-group col">
                        <label for="semester">semester</label>
                        <select id="semester" class="form-control" v-model="semester">
                            <option value="1st">1st Sem</option>
                            <option value="2nd">2nd Sem</option>
                            <option value="Summer">Summer</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="effDateStart">effDateStart:</label><br>
                        <input type="date" id="effDateStart" class="form-control" v-model="effDateStart">
                    </div>
                    <div class="form-group">
                        <label for="effDateEnd">effDateEnd:</label><br>
                        <input type="date" id="effDateEnd" class="form-control" v-model="effDateEnd">
                    </div>
                    <div class="form-group">
                        <label for="description">description:</label><br>
                        <input type="text" id="description" class="form-control" v-model="description">
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">Submit</button>
                </div>
            </div>
        </div>
    </form>
</template>


<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import { useRoute } from 'vue-router';

const route = useRoute();
const router = useRouter();

const schedID = ref('');
const schedName = ref('');
const schoolYear = ref('');
const semester = ref('');
const effDateStart = ref('');
const effDateEnd = ref('');
const description = ref('');
const isDeleted = ref('');
const createdBy = ref('');
const createdAt = ref('');
const updatedBy = ref('');
const updatedAt = ref('');
const deletedBy = ref('');
const deletedAt = ref('');

schedID.value = route.params.schedid;

const arrayData = ref([]);


function updateRecord() {
    const data = {
        action: 'update',
        schedID: schedID.value,
        schedName: schedName.value,
        schoolYear: schoolYear.value,
        semester: semester.value,
        effDateStart: effDateStart.value,
        effDateEnd: effDateEnd.value,
        description: description.value,
        isDeleted: isDeleted.value,
        createdBy: createdBy.value,
        createdAt: createdAt.value,
        updatedBy: updatedBy.value,
        updatedAt: updatedAt.value,
        deletedBy: deletedBy.value,
        deletedAt: deletedAt.value,
    }

    axios.post('https://rjprint10.com/entrancemonitoring/backend/scheduleapi.php', data)
        .then(response => {
            router.push('/schedules/view');
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
        action: 'searchsched',
        schedID: schedID.value,
        schedName: '',
        schoolYear: 'All',
        semester: 'All',
    };

    axios.post('https://rjprint10.com/entrancemonitoring/backend/scheduleapi.php', data)
        .then((response) => {
            arrayData.value = response.data[0];
            schedID.value = arrayData.value.schedID;
            schedName.value = arrayData.value.schedName;
            schoolYear.value = arrayData.value.schoolYear;
            semester.value = arrayData.value.semester;
            effDateStart.value = arrayData.value.effDateStart;
            effDateEnd.value = arrayData.value.effDateEnd;
            description.value = arrayData.value.description;
        })
        .catch((error) => {
            console.error('Error fetching data:', error);
        });
};


</script>