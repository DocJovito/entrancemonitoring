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
      path: "/employees",
      name: "employees",
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
