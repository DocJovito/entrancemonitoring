<template>
    <nav class="navbar  navbar-expand-lg bg-primary " data-bs-theme="dark">
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
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Employees
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <RouterLink to="/employees/view" class="dropdown-item" active-class="active">Employee
                                    Maintenance
                                </RouterLink>
                            </li>
                            <hr class="dropdown-divider">
                            <RouterLink to="/schedules/view" class="dropdown-item" active-class="active">Schedules
                            </RouterLink>
                            <hr class="dropdown-divider">
                            <RouterLink to="/schedules/assign" class="dropdown-item" active-class="active">Assign
                                Schedule
                            </RouterLink>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Students
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <RouterLink to="/students/view" class="dropdown-item" active-class="active">Student
                                    Maintenance
                                </RouterLink>
                            </li>
                            <!-- <hr class="dropdown-divider">
                            <RouterLink to="/courses/view" class="dropdown-item" active-class="active">Courses
                            </RouterLink>
                            <hr class="dropdown-divider">
                            <RouterLink to="/grades/view" class="dropdown-item" active-class="active">Grades
                            </RouterLink> -->
                        </ul>
                    </li>

                    <li>
                        <RouterLink to="/rfid/view" class="nav-link" active-class="active">RFID

                        </RouterLink>
                    </li>
                    <li class="nav-item">
                        <RouterLink to="/timekeeping" class="nav-link" active-class="active">Time Keeping</RouterLink>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Reports
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <RouterLink to="/reports/alllogs" class="dropdown-item" active-class="active">Time
                                    Keeping
                                </RouterLink>
                            </li>
                            <li>
                                <RouterLink to="/reports/timecardbasic" class="dropdown-item" active-class="active">Time
                                    Card
                                    Basic
                                </RouterLink>
                            </li>
                            <li>
                                <RouterLink to="/reports/timecard" class="dropdown-item" active-class="active">Time Card
                                    Scheduled
                                </RouterLink>
                            </li>
                            <li>
                                <RouterLink to="/reports/alllogs" class="dropdown-item" active-class="active">Time Time
                                    Card
                                    (Multiple)
                                </RouterLink>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Tools
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <RouterLink to="/tools/uploadimage" class="dropdown-item" active-class="active">
                                    Upload Image
                                </RouterLink>
                            </li>
                            <!-- <li>
                                <RouterLink to="/reports/timecard" class="dropdown-item" active-class="active">Time Card
                                </RouterLink>
                            </li>
                            <li>
                                <RouterLink to="/reports/alllogs" class="dropdown-item" active-class="active">Time Time
                                    Card
                                    (Multiple)
                                </RouterLink>
                            </li> -->
                        </ul>
                    </li>

                </ul>
                <!-- <form class="d-flex" role="search">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form> -->
            </div>
        </div>
    </nav>
</template>


<style scoped>
.active {
    font-weight: 700;
}
</style>