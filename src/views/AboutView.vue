<template>
  <div class="container mt-5">
    <h2 class="mb-4 text-primary">Dashboard</h2>

    <!-- Overview of Key Metrics -->
    <div class="row mb-4">
      <div class="col-md-4">
        <div class="card text-white bg-primary mb-3">
          <div class="card-body">
            <h5 class="card-title">Total Employees</h5>
            <p class="card-text display-4">{{ totalEmployees }}</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card text-white bg-success mb-3">
          <div class="card-body">
            <h5 class="card-title">Total Students</h5>
            <p class="card-text display-4">{{ totalStudents }}</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card text-white bg-info mb-3">
          <div class="card-body">
            <h5 class="card-title">Departments</h5>
            <p class="card-text display-4">{{ totalDepartments }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Employee Time Tracking -->
    <div class="card mb-4">
      <div class="card-header bg-warning text-dark">Employee Time Tracking</div>
      <div class="card-body">
        <table class="table table-hover">
          <thead class="thead-dark">
            <tr>
              <th>Employee Name</th>
              <th>Department</th>
              <th>Time In</th>
              <th>Time Out</th>
              <th>Total Hours</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="employee in employeesTimeTracking" :key="employee.id">
              <td>{{ employee.name }}</td>
              <td>{{ employee.department }}</td>
              <td>{{ employee.timeIn }}</td>
              <td>{{ employee.timeOut }}</td>
              <td>{{ calculateHours(employee.timeIn, employee.timeOut) }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Student Time Tracking -->
    <div class="card mb-4">
      <div class="card-header bg-secondary text-white">Student Time Tracking</div>
      <div class="card-body">
        <table class="table table-hover">
          <thead class="thead-dark">
            <tr>
              <th>Student Name</th>
              <th>Course</th>
              <th>Department</th>
              <th>Time In</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="student in studentsTimeTracking" :key="student.id">
              <td>{{ student.name }}</td>
              <td>{{ student.course }}</td>
              <td>{{ student.department }}</td>
              <td>{{ student.timeIn }}</td>
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
          <li class="list-group-item" v-for="activity in recentActivities" :key="activity.time">
            {{ activity.name }} ({{ activity.type }}) - {{ activity.activity }} at {{ activity.time }}
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      totalEmployees: 0,
      totalStudents: 0,
      totalDepartments: 0,
      employeesTimeTracking: [],
      studentsTimeTracking: [],
      recentActivities: []
    };
  },
  created() {
    this.fetchDashboardData();
  },
  methods: {
    async fetchDashboardData() {
      try {
        const response = await fetch('https://icpmymis.com/entrancemonitoring/backend/dashboardapi.php'); // Update with your API URL
        const data = await response.json();

        this.totalEmployees = data.totalEmployees;
        this.totalStudents = data.totalStudents;
        this.totalDepartments = data.totalDepartments;
        this.employeesTimeTracking = data.employeesTimeTracking;
        this.studentsTimeTracking = data.studentsTimeTracking;
        this.recentActivities = data.recentActivities;
      } catch (error) {
        console.error('Error fetching dashboard data:', error);
      }
    },
    calculateHours(timeIn, timeOut) {
      if (!timeOut) return 'N/A'; // Handle cases where timeOut is not provided

      const [inHours, inMinutes] = timeIn.split(':').map(Number);
      const [outHours, outMinutes] = timeOut.split(':').map(Number);

      const start = new Date();
      const end = new Date();

      start.setHours(inHours, inMinutes, 0);
      end.setHours(outHours, outMinutes, 0);

      const totalHours = (end - start) / 1000 / 60 / 60;

      return totalHours.toFixed(2);
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
