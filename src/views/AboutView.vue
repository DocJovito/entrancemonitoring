<template>
  <div class="container mt-5">
    <h2 class="mb-4 text-primary">Dashboard</h2>

    <!-- Overview of Key Metrics -->
    <div class="row mb-4">
      <div class="col-md-4">
        <div class="card text-white bg-primary mb-3">
          <div class="card-body">
            <h5 class="card-title">Total Employees</h5>
            <p class="card-text display-4">{{ employees.length }}</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card text-white bg-success mb-3">
          <div class="card-body">
            <h5 class="card-title">Total Students</h5>
            <p class="card-text display-4">{{ students.length }}</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card text-white bg-info mb-3">
          <div class="card-body">
            <h5 class="card-title">Departments</h5>
            <p class="card-text display-4">{{ departments.length }}</p>
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
            <tr v-for="employee in employees" :key="employee.id">
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
            <tr v-for="student in students" :key="student.id">
              <td>{{ student.name }}</td>
              <td>{{ student.course }}</td>
              <td>{{ student.department }}</td>
              <td>{{ student.timeIn }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
<!-- // abay galaw.,. 3days ng bakasyon., walang ngyari ? dabed ano ginagawa mo? -->
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
  </div>
</template>

<script>
export default {
  data() {
    return {
      employees: [
        { id: 1, name: 'John Doe', department: 'HR', timeIn: '08:00', timeOut: '17:00' },
        { id: 2, name: 'Jane Smith', department: 'IT', timeIn: '09:00', timeOut: '18:00' }
      ],
      students: [
        { id: 1, name: 'Alice Johnson', course: 'Computer Science', department: 'IT', timeIn: '09:00' },
        { id: 2, name: 'Bob Brown', course: 'Business Administration', department: 'Business', timeIn: '08:30' }
      ],
      departments: ['HR', 'IT', 'Business'],
      recentActivities: [
        { id: 1, description: 'John Doe clocked in at 08:00' },
        { id: 2, description: 'Alice Johnson clocked in at 09:00' }
      ]
    };
  },
  methods: {
    calculateHours(timeIn, timeOut) {
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
