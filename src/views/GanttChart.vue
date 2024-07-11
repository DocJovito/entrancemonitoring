<template>
    <div id="app">
      <GanttChart :rows="rows" :startDate="startDate" :endDate="endDate" />
    </div>
  </template>
  
  <script>
  import { GanttChart } from 'vue-ganttastic'
  
  export default {
    name: 'GanttChartComponent',
    components: {
      GanttChart
    },
    data() {
      return {
        startDate: new Date('2024-07-01'),
        endDate: new Date('2024-12-31'),
        rows: [
          {
            id: 1,
            label: 'Project Initiation',
            tasks: [
              { id: 1, label: 'Kick-off meeting', startDate: new Date('2024-07-01'), endDate: new Date('2024-07-05'), progress: 100, color: 'green' },
              { id: 2, label: 'Requirements gathering', startDate: new Date('2024-07-06'), endDate: new Date('2024-07-10'), progress: 100, color: 'blue' },
              { id: 3, label: 'Initial project plan', startDate: new Date('2024-07-11'), endDate: new Date('2024-07-14'), progress: 100, color: 'red' }
            ]
          },
          {
            id: 2,
            label: 'System Design',
            tasks: [
              { id: 4, label: 'Database design', startDate: new Date('2024-07-15'), endDate: new Date('2024-07-21'), progress: 0, color: 'yellow' },
              { id: 5, label: 'System architecture', startDate: new Date('2024-07-22'), endDate: new Date('2024-07-28'), progress: 0, color: 'orange' },
              { id: 6, label: 'UI/UX design', startDate: new Date('2024-07-29'), endDate: new Date('2024-08-11'), progress: 0, color: 'purple' }
            ]
          },
          {
            id: 3,
            label: 'Development Phase 1',
            tasks: [
              { id: 7, label: 'Backend development', startDate: new Date('2024-08-12'), endDate: new Date('2024-09-08'), progress: 0, color: 'pink' },
              { id: 8, label: 'Frontend development', startDate: new Date('2024-09-09'), endDate: new Date('2024-10-06'), progress: 0, color: 'cyan' },
              { id: 9, label: 'API development', startDate: new Date('2024-10-07'), endDate: new Date('2024-10-19'), progress: 0, color: 'brown' }
            ]
          },
          {
            id: 4,
            label: 'Development Phase 2',
            tasks: [
              { id: 10, label: 'Integration of modules', startDate: new Date('2024-10-20'), endDate: new Date('2024-11-02'), progress: 0, color: 'lime' },
              { id: 11, label: 'Implementing security features', startDate: new Date('2024-11-03'), endDate: new Date('2024-11-16'), progress: 0, color: 'teal' },
              { id: 12, label: 'Email notifications', startDate: new Date('2024-11-17'), endDate: new Date('2024-11-30'), progress: 0, color: 'navy' }
            ]
          },
          {
            id: 5,
            label: 'Testing',
            tasks: [
              { id: 13, label: 'Unit testing', startDate: new Date('2024-12-01'), endDate: new Date('2024-12-07'), progress: 0, color: 'grey' },
              { id: 14, label: 'Integration testing', startDate: new Date('2024-12-08'), endDate: new Date('2024-12-14'), progress: 0, color: 'coral' },
              { id: 15, label: 'User acceptance testing (UAT)', startDate: new Date('2024-12-15'), endDate: new Date('2024-12-21'), progress: 0, color: 'magenta' }
            ]
          },
          {
            id: 6,
            label: 'Deployment and Training',
            tasks: [
              { id: 16, label: 'Deployment to production', startDate: new Date('2024-12-22'), endDate: new Date('2024-12-23'), progress: 0, color: 'olive' },
              { id: 17, label: 'Staff training', startDate: new Date('2024-12-24'), endDate: new Date('2024-12-26'), progress: 0, color: 'maroon' },
              { id: 18, label: 'Final adjustments', startDate: new Date('2024-12-27'), endDate: new Date('2024-12-28'), progress: 0, color: 'silver' }
            ]
          },
          {
            id: 7,
            label: 'Project Closure',
            tasks: [
              { id: 19, label: 'Documentation', startDate: new Date('2024-12-29'), endDate: new Date('2024-12-30'), progress: 0, color: 'gold' },
              { id: 20, label: 'Project review', startDate: new Date('2024-12-31'), endDate: new Date('2024-12-31'), progress: 0, color: 'indigo' },
              { id: 21, label: 'Handover', startDate: new Date('2025-01-01'), endDate: new Date('2025-01-02'), progress: 0, color: 'crimson' }
            ]
          }
        ]
      }
    }
  }
  </script>
  
  <style>
  #app {
    font-family: Arial, sans-serif;
    text-align: center;
    margin: 20px;
  }
  
  .gantt-task {
    color: white;
    padding: 5px;
    border-radius: 4px;
  }
  </style>
  