<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
      integrity="sha512-6IcTku…"
      crossorigin="anonymous" referrerpolicy="no-referrer" />
<aside class="sidebar" id="sidebar">
  <div class="text-center mb-4">
    <img src="https://wedinwheels.com/wp-content/uploads/2024/01/wedinwheels-logo.png"
         class="img-fluid" style="max-width:150px;" alt="Logo">
  </div>

  <nav>

    <a href="{{ url('dashboard') }}" class="active">
      <i class="fa-solid fa-gauge me-2"></i> Dashboard
    </a>


    <!-- COMPANY PROFILE -->
    <h4 class="accordion-title">
      <i class="fa-solid fa-building me-2"></i> Company Profile
      <i class="fa fa-chevron-down float-end"></i>
    </h4>

    <ul class="submenu">
      <li><a href="{{ url('basic-information') }}"><i class="fa-solid fa-circle-info me-2"></i> Basic Information</a></li>
      <li><a href="{{ url('legal-compliance') }}"><i class="fa-solid fa-scale-balanced me-2"></i> Legal & Compliance</a></li>
      <li><a href="{{ url('address_contacts') }}"><i class="fa-solid fa-location-dot me-2"></i> Address & Contacts</a></li>
      <li><a href="{{ url('financial_banking') }}"><i class="fa-solid fa-building-columns me-2"></i> Financial & Banking</a></li>
    </ul>


    <!-- HR MANAGER -->
    <h4 class="accordion-title">
      <i class="fa-solid fa-people-group me-2"></i> HR Manager
      <i class="fa fa-chevron-down float-end"></i>
    </h4>

    <ul class="submenu">
      <li><a href="{{ url('employees') }}"><i class="fa-solid fa-users me-2"></i> Employees</a></li>
      <li><a href="{{ url('employee_docs') }}"><i class="fa-solid fa-id-card me-2"></i> Employee Documents</a></li>
      <li><a href="{{ url('driver_schedule') }}"><i class="fa-solid fa-calendar-check me-2"></i> Driver Schedules</a></li>
    </ul>


    <!-- MASTER -->
    <h4 class="accordion-title">
      <i class="fa-solid fa-database me-2"></i> Master
      <i class="fa fa-chevron-down float-end"></i>
    </h4>

    <ul class="submenu">
      <li><a href="{{ url('cars_master') }}"><i class="fa-solid fa-car-side me-2"></i> Car Master</a></li>
      <li><a href="{{ url('garage') }}"><i class="fa-solid fa-screwdriver-wrench me-2"></i> Garage</a></li>
      <li><a href="{{ url('service') }}"><i class="fa-solid fa-gear me-2"></i> Service Category</a></li>
      <li><a href="{{ url('my_leads') }}"><i class="fa-solid fa-lightbulb me-2"></i> Leads / Enquiry</a></li>
      <li><a href="{{ url('lead_generation') }}"><i class="fa-solid fa-plus me-2"></i> Lead Generation</a></li>
      <li><a href="{{ url('customer_enquiries') }}"><i class="fa-solid fa-address-book me-2"></i> Customer Enquiries</a></li>
      <li><a href="{{ url('bookings') }}"><i class="fa-solid fa-calendar-days me-2"></i> Booking Management</a></li>
      <li><a href="{{ url('calender_and_schedule') }}"><i class="fa-solid fa-clock me-2"></i> Calendar & Schedule</a></li>
      <li><a href="{{ url('add_notification') }}"><i class="fa-solid fa-bell me-2"></i> Notifications</a></li>
    </ul>


    <!-- SETTINGS -->
    <h4>Settings</h4>

    <a href="{{ url('roles') }}"><i class="fa-solid fa-user-shield me-2"></i> Roles</a>
    <a href="{{ url('permission') }}"><i class="fa-solid fa-lock me-2"></i> Permissions</a>
    <a href="{{ url('logout') }}"><i class="fa-solid fa-right-from-bracket me-2"></i> Logout</a>

  </nav>
</aside>
<style>.submenu {
  list-style: none;
  padding-left: 10px;
  margin: 0 0 8px 0;
  display: none;
}

.submenu li a {
  display: block;
  padding: 6px 6px;
  font-size: 13px;
  color: #ddd;
}

.accordion-title {
  cursor: pointer;
  margin-top: 12px;
  font-size: 14px;
}

.accordion-title i {
  transition: 0.2s;
}

.submenu.open {
  display: block;
}
</style>


<script>
  document.querySelectorAll(".accordion-title").forEach(title => {
    title.addEventListener("click", () => {

      document.querySelectorAll(".submenu").forEach(m => m.classList.remove("open"));
      document.querySelectorAll(".accordion-title i.fa-chevron-down")
              .forEach(i => i.style.transform = "rotate(0deg)");

      const nextMenu = title.nextElementSibling;
      nextMenu.classList.add("open");

      title.querySelector(".fa-chevron-down").style.transform = "rotate(180deg)";
    });
  });
</script>
