import { createRouter, createWebHistory } from "vue-router";
import HomeView from "../views/HomeView.vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/",
      name: "home",
      component: HomeView,
    },
    {
      path: "/about",
      name: "about",
      component: () => import("../views/AboutView.vue"),
    },
    {
      path: "/employees/view",
      name: "employeesView",
      component: () => import("../views/Employees/View.vue"),
    },
    {
      path: "/employees/create",
      name: "create",
      component: () => import("../views/Employees/Create.vue"),
    },
    {
      path: "/employees/:empid/edit",
      name: "edit",
      component: () => import("../views/Employees/Edit.vue"),
    },
    {
      path: "/rfid/view",
      name: "rfidView",
      component: () => import("../views/Rfid/View.vue"),
    },
    {
      path: "/schedules/view",
      name: "schedulesView",
      component: () => import("../views/Schedules/View.vue"),
    },
    {
      path: "/schedules/create",
      name: "schedulesCreate",
      component: () => import("../views/Schedules/Create.vue"),
    },
    {
      path: "/schedules/:schedid/update",
      name: "schedulesUpdate",
      component: () => import("../views/Schedules/Update.vue"),
    },
    {
      path: "/schedules/:schedid/viewdetails",
      name: "schedulesViewDetails",
      component: () => import("../views/ScheduleDetails/View.vue"),
    },
    //trans
    {
      path: "/timekeeping",
      name: "timekeeping",
      component: () => import("../views/EntranceMode.vue"),
    },
    //reports
    {
      path: "/reports/alllogs",
      name: "alllogs",
      component: () => import("../views/Reports/AllLogs.vue"),
    },
  ],
});

export default router;
