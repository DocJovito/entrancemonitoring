<template>
    <div class="container mt-4">
        <h4>Student File Maintenance</h4>
        <router-link to="/students/create" type="button" class="btn btn-success">Add Student</router-link>

        <form @submit.prevent="fetchData">
            <div class="container">
                <div class="row">
                    <div class="form-group col">
                        <label for="studID">Student ID</label>
                        <input type="text" id="studID" class="form-control" v-model="studID" placeholder="S1234">
                    </div>
                    <div class="form-group col">
                        <label for="lastName">Last Name</label>
                        <input type="text" id="lastName" class="form-control" v-model="lastName"
                            placeholder="Dela Cruz">
                    </div>
                    <div class="form-group col">
                        <label for="department">Department</label>
                        <select id="department" class="form-control" v-model="department">
                            <option value="All">All</option>
                            <option value="ABM">ABM</option>
                            <option value="GAS">GAS</option>
                            <option value="HRCTO">HRCTO</option>
                            <option value="HUMSS">HUMSS</option>
                            <option value="ICT">ICT</option>
                            <option value="STEM">STEM</option>
                            <option value="TESDA">TESDA</option>
                        </select>
                    </div>
                    <div class="form-group col">
                        <label for="courseYrSec">Strand</label>
                        <select id="courseYrSec" class="form-control" v-model="courseYrSec">
                            <option value="All">All</option>
                            <option value="ABM">ABM</option>
                            <option value="GAS">GAS</option>
                            <option value="HRCTO">HRCTO</option>
                            <option value="HUMSS">HUMSS</option>
                            <option value="ICT">ICT</option>
                            <option value="STEM">STEM</option>
                            <option value="TESDA">TESDA</option>
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
                    <th scope="col">ID</th>
                    <th scope="col">Image</th> <!-- New image column -->
                    <th scope="col">RFID</th>
                    <th scope="col">Student ID</th>
                    <th scope="col">Last Name</th>
                    <th scope="col">First Name</th>
                    <th scope="col">Middle Name</th>
                    <th scope="col">Course</th>
                    <th scope="col">Department</th>
                    <th scope="col">Birthday</th>
                    <th scope="col">Active</th>
                    <th scope="col">Option</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(data, index) in paginatedArrayData" :key="data.studID">
                    <th scope="row">{{ (currentPage - 1) * pageSize + index + 1 }}</th>
                    <td>{{ data.ID }}</td>
                    <td>
                        <img :src="getImageUrl(data.image)" @error="handleImageError"
                            style="max-width: 40px; max-height: 40px;" alt="Student Image">
                    </td>
                    <td>{{ data.RFID }}</td>
                    <td>{{ data.studID }}</td>
                    <td>{{ data.lastName }}</td>
                    <td>{{ data.firstName }}</td>
                    <td>{{ data.middleName }}</td>
                    <td>{{ data.courseYrSec }}</td>
                    <td>{{ data.department }}</td>
                    <td>{{ data.bday }}</td>
                    <td>{{ data.isActive ? 'Yes' : 'No' }}</td>
                    <td>
                        <router-link :to="'/students/' + data.studID + '/edit'" type="button" class="btn btn-primary">
                            Edit
                        </router-link>
                        <button type="button" class="btn btn-danger" @click="deleteStudent(data.studID)">Delete</button>
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

        <div class="form-group d-flex justify-content-center">
            <button class="btn btn-primary" @click="downloadTemplate">Download Template</button>
            <button class="btn btn-success" @click="exportexcel">Export to Excel</button>
            <button class="btn btn-danger" @click="readexcel">Import From Excel</button>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import * as XLSX from 'xlsx';
import { saveAs } from 'file-saver';

const studID = ref('');
const lastName = ref('');
const department = ref('All');
const courseYrSec = ref('All');

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

async function fetchData() {
    try {
        const response = await axios.post('https://icpmymis.com/entrancemonitoring/backend/studentapi.php', {
            action: 'search_students',
            studID: studID.value,
            lastName: lastName.value,
            department: department.value,
            courseYrSec: courseYrSec.value
        });
        arrayData.value = response.data;
    } catch (error) {
        console.error('Error fetching data:', error);
    }
}

function getImageUrl(imageFilename) {
    if (!imageFilename || imageFilename === "") {
        return 'https://icpmymis.com/images/ICPLogo.JPG';
    } else {
        return `https://icpmymis.com/images/${imageFilename}`;
    }
}

function handleImageError(event) {
    const img = event.target;
    const originalSrc = img.src;
    const upperCaseSrc = originalSrc.replace('.jpg', '.JPG');

    if (originalSrc !== upperCaseSrc) {
        img.src = upperCaseSrc;
    } else {
        img.src = 'https://icpmymis.com/images/ICPLogo.JPG';
    }
}

async function deleteStudent(studIDToDelete) {
    if (confirm('Are you sure you want to delete this student?')) {
        try {
            const response = await axios.delete('https://icpmymis.com/entrancemonitoring/backend/studentapi.php', {
                data: {
                    action: 'delete',
                    studID: studIDToDelete
                }
            });

            alert(response.data.message);
            fetchData();
        } catch (error) {
            console.error('Error deleting student:', error);
            alert('Error deleting student. Please try again.');
        }
    } else {
        alert('Deletion canceled.');
    }
}

function downloadTemplate() {
  const headers = [
    'studID',
    'lastName',
    'firstName',
    'middleName',
    'strand',
    'department',
    'bday',
    'isActive (Yes / No)',
    'RFID'
  ];

  const ws = XLSX.utils.aoa_to_sheet([headers]);

  // Data validation for department column (index 5)
  const departmentRange = { s: { r: 1, c: 5 }, e: { r: 1048576, c: 5 } };
  const departmentValidation = {
    type: 'list',
    allowBlank: 1,
    showDropDown: 1,
    formula1: '"ADMIN,CSIT,CBEA"',
    error: 'Please select a valid value from the dropdown (ADMIN, CSIT, or CBEA).',
    errorTitle: 'Invalid Value',
    errorStyle: 'stop',
    sqref: XLSX.utils.encode_range(departmentRange),
  };

  // Adding validations to the worksheet
  ws['!dataValidations'] = {
    validation: [departmentValidation]
  };

  const wb = XLSX.utils.book_new();
  XLSX.utils.book_append_sheet(wb, ws, 'Students');

  const wbout = XLSX.write(wb, { bookType: 'xlsx', type: 'binary' });

  function s2ab(s) {
    const buf = new ArrayBuffer(s.length);
    const view = new Uint8Array(buf);
    for (let i = 0; i < s.length; i++) view[i] = s.charCodeAt(i) & 0xFF;
    return buf;
  }

  saveAs(new Blob([s2ab(wbout)], { type: 'application/octet-stream' }), 'Student_Template.xlsx');
}

async function exportexcel() {
  try {
    const response = await axios.get('https://icpmymis.com/entrancemonitoring/backend/studentapi.php', {
      params: { action: 'get_students' }
    });

    const data = response.data;

    const ws = XLSX.utils.json_to_sheet(data);
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, 'Students');

    const wbout = XLSX.write(wb, { bookType: 'xlsx', type: 'binary' });

    saveAs(new Blob([s2ab(wbout)], { type: 'application/octet-stream' }), 'Students_Data.xlsx');
  } catch (error) {
    console.error('Error exporting data to Excel:', error);
  }
}

async function readexcel(event) {
  const file = event.target.files[0];
  const reader = new FileReader();
  
  reader.onload = async function(e) {
    const data = new Uint8Array(e.target.result);
    const workbook = XLSX.read(data, { type: 'array' });
    const sheetName = workbook.SheetNames[0];
    const sheet = workbook.Sheets[sheetName];
    const json = XLSX.utils.sheet_to_json(sheet);

    try {
      const response = await axios.post('https://icpmymis.com/entrancemonitoring/backend/studentapi.php', {
        action: 'import_students',
        data: json
      });
      alert(response.data.message);
      fetchData();
    } catch (error) {
      console.error('Error importing data from Excel:', error);
    }
  };

  reader.readAsArrayBuffer(file);
}
</script>
