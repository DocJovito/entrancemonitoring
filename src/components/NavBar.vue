<template>
    <nav class="navbar navbar-expand-lg bg-primary" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Time Keeping System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <RouterLink to="/" class="nav-link" active-class="active">Home</RouterLink>
                    </li>
                    <li class="nav-item">
                        <RouterLink to="/about" class="nav-link" active-class="active">About</RouterLink>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownFileMaintenance" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            File Maintenance
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownFileMaintenance">
                            <li>
                                <RouterLink to="/employees/view" class="dropdown-item" active-class="active">
                                    Employee Maintenance</RouterLink>
                            </li>
                            <li>
                                <RouterLink to="/schedules/view" class="dropdown-item" active-class="active">
                                    Schedules</RouterLink>
                            </li>
                            <li>
                                <RouterLink to="/schedules/assign" class="dropdown-item" active-class="active">
                                    Assign Schedules</RouterLink>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li> <!-- Divider -->
                            <li>
                                <RouterLink to="/tools/fmtimekeep" class="dropdown-item" active-class="active">
                                    Time Keeping</RouterLink>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li> <!-- Divider -->
                            <li>
                                <RouterLink to="/students/view" class="dropdown-item" active-class="active">
                                    Student Maintenance</RouterLink>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li> <!-- Divider -->

                            <li>
                                <RouterLink to="/tools/uploadimage" class="dropdown-item" active-class="active">
                                    Upload Image</RouterLink>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <RouterLink to="/timekeeping" class="nav-link" active-class="active">Time Keeping</RouterLink>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownReports" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Reports
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownReports">
                            <li>
                                <RouterLink to="/reports/alllogs" class="dropdown-item" active-class="active">Time
                                    Keeping</RouterLink>
                            </li>
                            <li>
                                <RouterLink to="/reports/timecardbasic" class="dropdown-item" active-class="active">Time
                                    Card Basic</RouterLink>
                            </li>
                            <li>
                                <RouterLink to="/reports/timecard" class="dropdown-item" active-class="active">Time Card
                                    Scheduled</RouterLink>
                            </li>
                            <li>
                                <RouterLink to="/reports/summary" class="dropdown-item" active-class="active">Summary
                                </RouterLink>
                            </li>
                        </ul>
                    </li>
                </ul>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <span class="nav-link text-light" v-if="isAuthenticated">
                            Welcome, {{ user.firstName }}!
                        </span>
                    </li>
                    <li class="nav-item">
                        <button class="btn btn-outline-light" v-if="isAuthenticated" @click="logout">Log Out</button>
                        <RouterLink to="/login" class="btn btn-outline-light" v-else>Log In</RouterLink>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>


<style scoped>
.active {
    font-weight: 700;
}

.dropdown-submenu {
    position: relative;
}

.dropdown-submenu .dropdown-menu {
    top: 0;
    left: 100%;
    margin-top: -1px;
}
</style>

<script>
import { mapGetters, mapActions } from 'vuex';
import { RouterLink } from 'vue-router';

export default {
    components: {
        RouterLink
    },
    computed: {
        ...mapGetters(['isAuthenticated', 'getUser']),
        user() {
            return this.getUser;
        }
    },
    methods: {
        ...mapActions(['logOut']),
        logout() {
            this.logOut();
            this.$router.push('/login');
        },
        initDropdowns() {
            const dropdownSubmenus = document.querySelectorAll('.dropdown-submenu');
            dropdownSubmenus.forEach(submenu => {
                submenu.addEventListener('mouseenter', this.showSubmenu);
                submenu.addEventListener('mouseleave', this.hideSubmenu);
            });
        },
        showSubmenu(event) {
            const submenu = event.currentTarget.querySelector('.dropdown-menu');
            submenu.classList.add('show');
        },
        hideSubmenu(event) {
            const submenu = event.currentTarget.querySelector('.dropdown-menu');
            submenu.classList.remove('show');
        }
    },
    mounted() {
        this.initDropdowns();
    }
};
</script>
