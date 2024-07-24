<template>
    <div class="container mt-4">
        <h4>Employee File Maintenance</h4>
        <router-link to="/employees/create" type="button" class="btn btn-success">Add Employee</router-link>

        <form @submit.prevent="fetchData">
            <div class="container">
                <div class="row">
                    <div class="form-group col">
                        <label for="empID">empID</label>
                        <input type="text" id="empID" class="form-control" v-model="empID" placeholder="ICI08-0001">
                    </div>
                    <div class="form-group col">
                        <label for="lastName">lastName</label>
                        <input type="text" id="lastName" class="form-control" v-model="lastName"
                            placeholder="Dela Cruz">
                    </div>
                    <div class="form-group col">
                        <label for="department">department</label>
                        <select id="department" class="form-control" v-model="department">
                            <option value="All">All</option>
                            <option value="ADMIN">ADMIN</option>
                            <option value="CSIT">CSIT</option>
                            <option value="CBEA">CBEA</option>
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
                    <th scope="col">ID</th>
                    <th scope="col">Image</th> <!-- New image column -->
                    <th scope="col">RFID</th>
                    <th scope="col">Employee ID</th>
                    <th scope="col">LastName</th>
                    <th scope="col">FirstName</th>
                    <th scope="col">MiddleName</th>
                    <th scope="col">Position</th>
                    <th scope="col">Department</th>
                    <th scope="col">EmpType</th>
                    <th scope="col">Option</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(data, index) in paginatedArrayData" :key="data.ID">
                    <th scope="row">{{ (currentPage - 1) * pageSize + index + 1 }}</th>
                    <td>{{ data.ID }}</td>
                    <td>
                        <!-- <img :src="getImageUrl(data.image)" @error="setDefaultImage"
                            style="max-width: 40px; max-height: 40px;" alt="Employee Image"> -->

                        <img :src="getImageUrl(data.image)" @error="handleImageError"
                            style="max-width: 40px; max-height: 40px;" alt="Employee Image">
                    </td>
                    <td>{{ data.RFID }}</td>
                    <td>{{ data.empID }}</td>
                    <td>{{ data.lastName }}</td>
                    <td>{{ data.firstName }}</td>
                    <td>{{ data.middleName }}</td>
                    <td>{{ data.position }}</td>
                    <td>{{ data.department }}</td>
                    <td>{{ data.empType }}</td>
                    <td>
                        <router-link :to="'/employees/' + data.empID + '/edit'" type="button" class="btn btn-primary">
                            Edit
                        </router-link>
                        <button type="button" class="btn btn-danger" @click="deleteEmployee(data.empID)">Delete</button>
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


const empID = ref('');
const lastName = ref('');
const department = ref('All');
const empType = ref('All');
const image = ref('');

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
        const response = await axios.post('https://icpmymis.com/entrancemonitoring/backend/employeeapi.php', {
            action: 'search_employees',
            empID: empID.value,
            lastName: lastName.value,
            department: department.value,
            empType: empType.value,
            image: empID.value
        });
        arrayData.value = response.data;
    } catch (error) {
        console.error('Error fetching data:', error);
    }
}

function getImageUrl(imageFilename) {
    if (!imageFilename || imageFilename === "") {
        // If no image is available, you can return a placeholder or default image URL
        return 'https://icpmymis.com/images/ICPLogo.JPG';
    } else {
        // Construct the full image URL based on server folder path and filename
        const lowerCaseFilename = imageFilename.toLowerCase();
        return `https://icpmymis.com/images/${imageFilename}`;
    }
}

function handleImageError(event) {
    const img = event.target;
    const originalSrc = img.src;
    const upperCaseSrc = originalSrc.replace('.jpg', '.JPG');

    // Check if it's already in uppercase format
    if (originalSrc !== upperCaseSrc) {
        // Try to load the uppercase version
        img.src = upperCaseSrc;
    } else {
        // If both attempts fail, set to default image
        img.src = 'https://icpmymis.com/images/ICPLogo.JPG';
    }
}

function setDefaultImage(event) {
    event.target.src = 'https://icpmymis.com/images/ICPLogo.JPG'; // Default image URL
}

async function deleteEmployee(empIDToDelete) {
    // Confirm deletion with user
    if (confirm('Are you sure you want to delete this employee?')) {
        try {
            const response = await axios.delete('https://icpmymis.com/entrancemonitoring/backend/employeeapi.php', {
                data: {
                    action: 'delete',
                    empID: empIDToDelete
                }
            });

            // Handle success message
            alert(response.data.message); // Assuming backend sends a message on success

            // Optionally, update UI after deletion
            // Example: Fetch data again to refresh the list
            fetchData();
        } catch (error) {
            console.error('Error deleting employee:', error);
            alert('Error deleting employee. Please try again.');
        }
    } else {
        // User canceled deletion
        alert('Deletion canceled.');
    }
}

function downloadTemplate() {
  const headers = [
    'empID',
    'lastName',
    'firstName',
    'middleName',
    'position',
    'department (ADMIN / CSIT / CBEA)',
    'bday',
    'empType (Full-Time / Part-Time)',
    'note',
    'RFID',
    'type'
  ];

  const ws = XLSX.utils.aoa_to_sheet([headers]);

  // Data validation for department column (index 5)
  const departmentRange = { s: { r: 1, c: 5 }, e: { r: 1048576, c: 5 } }; // Up to 1,048,576 rows
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

  // Data validation for empType column (index 8)
  const empTypeRange = { s: { r: 1, c: 7 }, e: { r: 1048576, c: 7 } };
  const empTypeValidation = {
    type: 'list',
    allowBlank: 1,
    showDropDown: 1,
    formula1: '"Full-Time,Part-Time"',
    error: 'Please select a valid value from the dropdown (Full-Time or Part-Time).',
    errorTitle: 'Invalid Value',
    errorStyle: 'stop',
    sqref: XLSX.utils.encode_range(empTypeRange),
  };

  // Adding validations to the worksheet
  ws['!dataValidations'] = {
    validation: [departmentValidation, empTypeValidation]
  };

  const wb = XLSX.utils.book_new();
  XLSX.utils.book_append_sheet(wb, ws, 'Employees');

  const wbout = XLSX.write(wb, { bookType: 'xlsx', type: 'binary' });

  function s2ab(s) {
    const buf = new ArrayBuffer(s.length);
    const view = new Uint8Array(buf);
    for (let i = 0; i < s.length; i++) view[i] = s.charCodeAt(i) & 0xff;
    return buf;
  }

  saveAs(new Blob([s2ab(wbout)], { type: 'application/octet-stream' }), 'employees_template.xlsx');
}

function exportexcel() {
    const data = arrayData.value.map(employee => ({
        'empID': employee.empID,
        'lastName': employee.lastName,
        'firstName': employee.firstName,
        'middleName': employee.middleName,
        'position': employee.position,
        'department': employee.department,
        'empType': employee.empType,
        'RFID': employee.RFID
    }));

    const ws = XLSX.utils.json_to_sheet(data);
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, 'Employees');

    const wbout = XLSX.write(wb, { bookType: 'xlsx', type: 'binary' });

    function s2ab(s) {
        const buf = new ArrayBuffer(s.length);
        const view = new Uint8Array(buf);
        for (let i = 0; i < s.length; i++) view[i] = s.charCodeAt(i) & 0xff;
        return buf;
    }

    saveAs(new Blob([s2ab(wbout)], { type: 'application/octet-stream' }), 'employees.xlsx');
}

function readexcel() {
    const input = document.createElement('input');
    input.type = 'file';
    input.accept = '.xlsx, .xls';
    input.addEventListener('change', handleFile);
    input.click();
}

function handleFile(event) {
    const file = event.target.files[0];
    const reader = new FileReader();
    reader.onload = (e) => {
        const data = new Uint8Array(e.target.result);
        const workbook = XLSX.read(data, { type: 'array' });
        const sheetName = workbook.SheetNames[0];
        const worksheet = workbook.Sheets[sheetName];
        const excelData = XLSX.utils.sheet_to_json(worksheet, { header: 1, range: 'A2:' + worksheet['!ref'].split(':')[1] });

        importExcel(excelData);

    };
    reader.readAsArrayBuffer(file);
}

function importExcel(excelData) {
    axios.post('https://icpmymis.com/entrancemonitoring/backend/employeeapi.php', {
        action: 'import',
        records: excelData,
    })
        .then(response => {
            console.log('Import successful:', response.data.message);
            fetchData();
        })
        .catch(error => {
            console.error('Error importing CSV:', error);
        });

}
</script>
