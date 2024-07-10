<template>
  <div class="container mt-5">
    <h2 class="mb-4 text-primary">Dashboard</h2>

    <!-- Overview of Key Metrics -->
    <div class="row mb-4">
      <div class="col-md-4">
        <div class="card text-white bg-primary mb-3">
          <div class="card-body">
            <h5 class="card-title">Total Owners</h5>
            <p class="card-text display-4">{{ owners.length }}</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card text-white bg-success mb-3">
          <div class="card-body">
            <h5 class="card-title">Total Patients</h5>
            <p class="card-text display-4">{{ patients.length }}</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card text-white bg-info mb-3">
          <div class="card-body">
            <h5 class="card-title">Total Appointments</h5>
            <p class="card-text display-4">{{ appointments.length }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Upcoming Appointments -->
    <div class="card mb-4">
      <div class="card-header bg-warning text-dark">Upcoming Appointments</div>
      <div class="card-body">
        <table class="table table-hover">
          <thead class="thead-dark">
            <tr>
              <th>Patient Name</th>
              <th>Date</th>
              <th>Time</th>
              <th>Reason</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="appointment in upcomingAppointments" :key="appointment.id">
              <td>{{ appointment.patientName }}</td>
              <td>{{ appointment.date }}</td>
              <td>{{ appointment.time }}</td>
              <td>{{ appointment.reason }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Recent Activities -->
    <div class="card mb-4">
      <div class="card-header bg-secondary text-white">Recent Activities</div>
      <div class="card-body">
        <ul class="list-group">
          <li class="list-group-item" v-for="activity in recentActivities" :key="activity.id">
            {{ activity.description }}
          </li>
        </ul>
      </div>
    </div>

    <!-- Graphs -->
    <div class="row">
      <div class="col-md-6 mb-4">
        <div class="card">
          <div class="card-header bg-primary text-white">Appointments by Date</div>
          <div class="card-body">
            <line-chart :chart-data="appointmentsByDateData"></line-chart>
          </div>
        </div>
      </div>
      <div class="col-md-6 mb-4">
        <div class="card">
          <div class="card-header bg-success text-white">Patients by Species</div>
          <div class="card-body">
            <pie-chart :chart-data="patientsBySpeciesData"></pie-chart>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Line, Pie } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, LineElement, PointElement, LineController, CategoryScale, LinearScale, PieController, ArcElement } from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, LineElement, PointElement, LineController, CategoryScale, LinearScale, PieController, ArcElement);

export default {
  components: {
    LineChart: Line,
    PieChart: Pie
  },
  data() {
    return {
      owners: [
        { id: 1, name: 'John Doe', contact: '1234567890' },
        { id: 2, name: 'Jane Smith', contact: '0987654321' }
      ],
      patients: [
        { id: 1, petName: 'Buddy', species: 'Dog', breed: 'Labrador', age: 3, ownerId: 1, medicalHistory: 'Healthy' },
        { id: 2, petName: 'Mittens', species: 'Cat', breed: 'Siamese', age: 2, ownerId: 2, medicalHistory: 'Asthma' }
      ],
      appointments: [
        { id: 1, patientName: 'Buddy', date: '2024-07-11', time: '10:00', reason: 'Vaccination' },
        { id: 2, patientName: 'Mittens', date: '2024-07-12', time: '11:00', reason: 'Check-up' },
        { id: 3, patientName: 'Charlie', date: '2024-07-13', time: '12:00', reason: 'Dental Cleaning' }
      ],
      recentActivities: [
        { id: 1, description: 'Added new owner: John Doe' },
        { id: 2, description: 'Scheduled appointment for Mittens' }
      ]
    };
  },
  computed: {
    upcomingAppointments() {
      const today = new Date().toISOString().split('T')[0];
      return this.appointments.filter(appointment => appointment.date >= today);
    },
    appointmentsByDateData() {
      const labels = [];
      const data = [];

      this.appointments.forEach(appointment => {
        labels.push(appointment.date);
        data.push(1);
      });

      return {
        labels,
        datasets: [
          {
            label: 'Appointments',
            backgroundColor: '#42A5F5',
            borderColor: '#42A5F5',
            data,
            fill: false
          }
        ]
      };
    },
    patientsBySpeciesData() {
      const speciesCount = this.patients.reduce((acc, patient) => {
        acc[patient.species] = (acc[patient.species] || 0) + 1;
        return acc;
      }, {});

      return {
        labels: Object.keys(speciesCount),
        datasets: [
          {
            label: 'Patients by Species',
            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'],
            data: Object.values(speciesCount)
          }
        ]
      };
    }
  }
};
</script>

<style scoped>
.container {
  max-width: 1200px;
}
.card {
  margin-bottom: 1rem;
}
.card-title {
  font-size: 1.25rem;
}
.card-text {
  font-size: 2.5rem;
}
.table-hover tbody tr:hover {
  background-color: #f1f1f1;
}
</style>
