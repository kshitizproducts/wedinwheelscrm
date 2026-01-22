<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
      integrity="sha512-6IcTku…"
      crossorigin="anonymous"
      referrerpolicy="no-referrer" />

<aside class="sidebar" id="sidebar">

    <!-- Logo -->
    <div class="sidebar-logo text-center mb-4">
        <img src="https://wedinwheels.com/wp-content/uploads/2024/01/wedinwheels-logo.png"
             class="img-fluid"
             style="max-width:150px;"
             alt="Logo">
    </div>

    <nav class="sidebar-menu">

        @canany(['view drivertask', 'view garage', 'view role', 'view inquiries', 'view lead', 'view driverschedule', 'view user', 'view cars','view companyprofile','view HrManager'])

            <!-- Dashboard -->
            <a href="{{ url('dashboard') }}" class="menu-link active">
                <i class="fa-solid fa-gauge me-2"></i> Dashboard
            </a>

            <!-- MASTER -->
            <h4 class="accordion-title">
                <span>
                    <i class="fa-solid fa-database me-2"></i> Master
                </span>
                <i class="fa fa-chevron-down accordion-icon"></i>
            </h4>

            <ul class="submenu">
                @can('view cars')
                    <li>
                        <a href="{{ url('car_status') }}">
                            <i class="fa-solid fa-car-side me-2"></i> Car Status
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('cars_master') }}">
                            <i class="fa-solid fa-car-side me-2"></i> Car Master
                        </a>
                    </li>
                @endcan

                @can('view garage')
                    <li>
                        <a href="{{ url('garage') }}">
                            <i class="fa-solid fa-screwdriver-wrench me-2"></i> Garage
                        </a>
                    </li>
                @endcan

                @can('view carservice')
                    <li>
                        <a href="{{ url('service') }}">
                            <i class="fa-solid fa-gear me-2"></i> Service Category
                        </a>
                    </li>
                @endcan

                @can('view HrManager')
                    <li>
                        <a href="{{ url('bookings') }}">
                            <i class="fa-solid fa-calendar-days me-2"></i> Booking Management
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('add_notification') }}">
                            <i class="fa-solid fa-bell me-2"></i> Notifications
                        </a>
                    </li>
                @endcan
            </ul>


            <!-- Lead Generation -->
            <h4 class="accordion-title">
                <span>
                    <i class="fa-solid fa-chart-line me-2"></i> Lead Generation
                </span>
                <i class="fa fa-chevron-down accordion-icon"></i>
            </h4>

            <ul class="submenu">
                @can('view HrManager')
                    <li>
                        <a href="{{ url('lead_generation') }}">
                            <i class="fa-solid fa-plus me-2"></i> Lead Generation
                        </a>
                    </li>
                @endcan
            </ul>


            <!-- Company Profile -->
            @can('view companyprofile')
                <h4 class="accordion-title">
                    <span>
                        <i class="fa-solid fa-building me-2"></i> Company Profile
                    </span>
                    <i class="fa fa-chevron-down accordion-icon"></i>
                </h4>

                <ul class="submenu">
                    <li><a href="{{ url('basic-information') }}"><i class="fa-solid fa-circle-info me-2"></i> Basic Information</a></li>
                    <li><a href="{{ url('legal-compliance') }}"><i class="fa-solid fa-scale-balanced me-2"></i> Legal & Compliance</a></li>
                    <li><a href="{{ url('address_contacts') }}"><i class="fa-solid fa-location-dot me-2"></i> Address & Contacts</a></li>
                    <li><a href="{{ url('financial_banking') }}"><i class="fa-solid fa-building-columns me-2"></i> Financial & Banking</a></li>
                </ul>
            @endcan


            <!-- HR Manager -->
            @can('view HrManager')
                <h4 class="accordion-title">
                    <span>
                        <i class="fa-solid fa-people-group me-2"></i> HR Manager
                    </span>
                    <i class="fa fa-chevron-down accordion-icon"></i>
                </h4>

                <ul class="submenu">
                    <li><a href="{{ url('users') }}"><i class="fa-solid fa-users me-2"></i> Users</a></li>
                    <li><a href="{{ url('employee_docs') }}"><i class="fa-solid fa-id-card me-2"></i> Employee Documents</a></li>
                </ul>
            @endcan


            <!-- Maintainance -->
            @can('view HrManager')
                <h4 class="accordion-title">
                    <span>
                        <i class="fa-solid fa-screwdriver-wrench me-2"></i> MAINTAINANCE
                    </span>
                    <i class="fa fa-chevron-down accordion-icon"></i>
                </h4>

                <ul class="submenu">
                    <li><a href="{{ url('car_servicing') }}"><i class="fa-solid fa-wrench me-2"></i> Car Servicing</a></li>
                </ul>
            @endcan


            <!-- My Tasks -->
            <h4 class="accordion-title">
                <span>
                    <i class="fa-solid fa-list-check me-2"></i> My Tasks
                </span>
                <i class="fa fa-chevron-down accordion-icon"></i>
            </h4>

            <ul class="submenu">
                @can('view cars')
                    <li><a href="{{ url('driver_tasks') }}"><i class="fa-solid fa-user-gear me-2"></i> Drivers Task</a></li>
                @endcan

                @can('view lead')
                    <li><a href="{{ url('my_leads') }}"><i class="fa-solid fa-id-card me-2"></i> Lead Managers</a></li>
                @endcan

                @can('view HrManager')
                    <li><a href="{{ url('driver_schedule') }}"><i class="fa-solid fa-calendar-check me-2"></i> Driver Schedules</a></li>
                @endcan
            </ul>


            <!-- Website Enquiries -->
            <h4 class="accordion-title">
                <span>
                    <i class="fa-solid fa-address-book me-2"></i> Website Enquiries
                </span>
                <i class="fa fa-chevron-down accordion-icon"></i>
            </h4>

            <ul class="submenu">
                @can('view HrManager')
                    <li><a href="{{ url('customer_enquiries') }}"><i class="fa-solid fa-address-book me-2"></i> Customer Enquiries</a></li>
                @endcan
            </ul>


            <!-- Calendar & Schedule -->
            <h4 class="accordion-title">
                <span>
                    <i class="fa-solid fa-calendar-days me-2"></i> Calendar & Schedule
                </span>
                <i class="fa fa-chevron-down accordion-icon"></i>
            </h4>

            <ul class="submenu">
                @can('view HrManager')
                    <li><a href="{{ url('calender_and_schedule') }}"><i class="fa-solid fa-clock me-2"></i> Calendar & Schedule</a></li>
                @endcan
            </ul>


            <!-- Settings -->
            <h4 class="accordion-title">
                <span>
                    <i class="fa-solid fa-gear me-2"></i> Settings
                </span>
                <i class="fa fa-chevron-down accordion-icon"></i>
            </h4>

            <ul class="submenu">
                <li><a href="{{ url('roles') }}"><i class="fa-solid fa-user-shield me-2"></i> Roles</a></li>
                <li><a href="{{ url('permission') }}"><i class="fa-solid fa-lock me-2"></i> Permissions</a></li>
            </ul>

            <!-- Logout -->
            <a href="{{ url('logout') }}" class="menu-link logout">
                <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
            </a>

        @endcanany
    </nav>
</aside>
<style>
 .sidebar {
    width: 260px;
    height: 100vh;              /* ✅ full screen height */
    background: #111827;
    padding: 18px 14px;
    overflow-y: auto;           /* ✅ scroll when content is long */
    color: #fff;

               /* ✅ left side */
}


    .sidebar-menu .menu-link {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 12px;
        border-radius: 10px;
        font-size: 14px;
        color: #e5e7eb;
        text-decoration: none;
        margin-bottom: 6px;
        transition: 0.25s;
    }

    .sidebar-menu .menu-link:hover {
        background: rgba(255, 255, 255, 0.08);
        color: #fff;
    }

    .sidebar-menu .menu-link.active {
        background: rgba(59, 130, 246, 0.2);
        color: #fff;
    }

    /* Accordion Title */
    .accordion-title {
        margin-top: 14px;
        padding: 10px 12px;
        border-radius: 10px;
        font-size: 14px;
        color: #f3f4f6;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: 0.25s;
        background: rgba(255, 255, 255, 0.03);
    }

    .accordion-title:hover {
        background: rgba(255, 255, 255, 0.08);
    }

    .accordion-icon {
        transition: 0.25s;
    }

    /* Submenu */
    .submenu {
        list-style: none;
        padding: 8px 0 0 0;
        margin: 0;
        display: none;
    }

    .submenu li a {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 18px;
        font-size: 13px;
        border-radius: 8px;
        color: #cbd5e1;
        text-decoration: none;
        transition: 0.2s;
    }

    .submenu li a:hover {
        background: rgba(255, 255, 255, 0.06);
        color: #fff;
    }

    .submenu.open {
        display: block;
    }

    .logout {
        margin-top: 20px;
        background: rgba(239, 68, 68, 0.12);
    }

    .logout:hover {
        background: rgba(239, 68, 68, 0.25);
    }
</style>
<script>
    document.querySelectorAll(".accordion-title").forEach((title) => {
        title.addEventListener("click", () => {
            const submenu = title.nextElementSibling;
            const icon = title.querySelector(".accordion-icon");

            // Toggle open/close
            submenu.classList.toggle("open");

            // Rotate icon
            if (submenu.classList.contains("open")) {
                icon.style.transform = "rotate(180deg)";
            } else {
                icon.style.transform = "rotate(0deg)";
            }
        });
    });
</script>
