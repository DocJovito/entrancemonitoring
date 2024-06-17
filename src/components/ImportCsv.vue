<template>
    <div>
        <h3>CSV File Content</h3>
        <input type="file" @change="handleFileUpload" accept=".csv" />
        <button @click="parseCSV">Parse CSV</button>
        <button @click="importCSV" v-if="csvData.length > 0">Import</button>

        <table v-if="csvData.length > 0">
            <thead>
                <tr>
                    <th v-for="(header, index) in csvHeaders" :key="index">{{ header }}</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(row, rowIndex) in csvData" :key="rowIndex">
                    <td v-for="(column, colIndex) in row" :key="colIndex">{{ column }}</td>
                </tr>
            </tbody>
        </table>

        <p v-if="errorMessage" style="color: red;">{{ errorMessage }}</p>
    </div>
</template>

<script>
import axios from 'axios';


export default {
    data() {
        return {
            file: null,
            csvHeaders: [],
            csvData: [],
            errorMessage: '',
        };
    },
    methods: {
        handleFileUpload(event) {
            this.file = event.target.files[0];
        },
        parseCSV() {
            if (!this.file) {
                this.errorMessage = 'Please select a CSV file to parse.';
                return;
            }

            const reader = new FileReader();
            reader.onload = () => {
                const csv = reader.result;
                const { headers, data } = this.parseCSVData(csv);
                this.csvHeaders = headers;
                this.csvData = data;
                this.errorMessage = '';
            };
            reader.onerror = () => {
                this.errorMessage = 'An error occurred while reading the CSV file.';
            };
            reader.readAsText(this.file);
        },
        parseCSVData(csv) {
            const rows = csv.trim().split('\n');
            const headers = rows[0].split(',').map(header => header.trim()); // Read first line as headers
            const data = [];
            for (let i = 1; i < rows.length; i++) {
                const values = rows[i].split(',');
                const rowData = {};
                for (let j = 0; j < headers.length; j++) {
                    rowData[headers[j]] = values[j].trim();
                }
                data.push(rowData);
            }
            return { headers, data };
        },
        importCSV() {
            axios.post('https://rjprint10.com/entrancemonitoring/backend/employeeapi.php', {
                action: 'import',
                records: this.csvData,
            })
                .then(response => {
                    console.log('Import successful:', response.data.message);
                })
                .catch(error => {
                    console.error('Error importing CSV:', error);
                });
        },
    },
};



</script>

<style scoped>
/* Add styles for the component here */
</style>