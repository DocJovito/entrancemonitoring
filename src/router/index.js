import { createRouter, createWebHistory } from "vue-router";
import HomeView from "../views/HomeView.vue";
import store from '../store/store';

const routes = [
  { path: "/", name: "home", component: HomeView },
  { path: "/about", name: "about", component: () => import("../views/AboutView.vue") },
  { path: "/employees/view", name: "employeesView", component: () => import("../views/Employees/View.vue") },
  { path: "/employees/create", name: "empcreate", component: () => import("../views/Employees/Create.vue") },
  { path: "/employees/:empid/edit", name: "empedit", component: () => import("../views/Employees/Edit.vue") },
  { path: "/students/view", name: "studView", component: () => import("../views/Students/View.vue") },
  { path: "/students/create", name: "studcreate", component: () => import("../views/Students/Create.vue") },
  { path: "/students/:studid/edit", name: "studedit", component: () => import("../views/Students/Edit.vue") },
  { path: "/rfid/view", name: "rfidView", component: () => import("../views/Rfid/View.vue") },
  { path: "/schedules/view", name: "schedulesView", component: () => import("../views/Schedules/View.vue") },
  { path: "/schedules/create", name: "schedulesCreate", component: () => import("../views/Schedules/Create.vue") },
  { path: "/schedules/:schedid/update", name: "schedulesUpdate", component: () => import("../views/Schedules/Update.vue") },
  { path: "/schedules/Assign", name: "schedulesAssign", component: () => import("../views/Schedules/Assign.vue") },
  { path: "/schedules/:schedid/viewdetails", name: "schedulesViewDetails", component: () => import("../views/ScheduleDetails/View.vue") },
  { path: "/scheduledetails/:schedid/create", name: "scheduleDetailsCreate", component: () => import("../views/ScheduleDetails/Create.vue") },
  { path: "/scheduledetails/:id/:schedid/edit", name: "scheduleDetailsEdit", component: () => import("../views/ScheduleDetails/Edit.vue") },
  { path: "/reports/alllogs", name: "alllogs", component: () => import("../views/Reports/AllLogs.vue") },
  { path: "/reports/timecardbasic", name: "timecardbasic", component: () => import("../views/Reports/TimeCardBasic.vue") },
  { path: "/reports/timecard", name: "timecard", component: () => import("../views/Reports/TimeCard.vue") },
  { path: "/reports/summary", name: "summary", component: () => import("../views/Reports/Summary.vue") },
  { path: "/tools/uploadimage", name: "uploadimage", component: () => import("../views/Tools/UploadImage.vue") },
  { path: "/tools/fmtimekeep", name: "fmtimekeep", component: () => import("../views/Tools/FMTimeKeep.vue") },
  { path: "/login", name: "login", component: () => import("../views/LogIn.vue") },
  { path: "/timekeeping", name: "timekeeping", component: () => import("../views/EntranceMode.vue") },
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
});

router.beforeEach((to, from, next) => {
  const isAuthenticated = store.getters.isAuthenticated; // Check if user is authenticated

  if (to.path === '/timekeeping') {
    next(); // Allow access to timekeeping without authentication
  } else if (isAuthenticated || to.path === '/login') {
    next(); // Allow access if authenticated or if accessing the login page
  } else {
    next('/login'); // Redirect to login if not authenticated
  }
});

export default router;
