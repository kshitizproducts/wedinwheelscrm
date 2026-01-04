  <!-- Main Content -->
  <div class="main-content flex-grow-1">
   <!-- Topbar -->
<div class="topbar mb-4 d-flex align-items-center justify-content-between">
  <button class="btn btn-sm text-white d-lg-none" id="menu-btn">☰</button>

  <input type="text" class="form-control me-auto ms-3" placeholder="Search Campaign...">

  <!-- Profile Dropdown -->
  <div class="dropdown ms-3">
    <div class="profile d-flex align-items-center dropdown-toggle" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="cursor:pointer;">
      <img src="https://i.pravatar.cc/40" alt="Avatar" class="rounded-circle me-2" width="35" height="35">
      <span>Hossein</span>
    </div>

    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="profileDropdown">
      <li>
        <a class="dropdown-item d-flex align-items-center" href="{{ url('profile') }}">
          <i class="bi bi-person-circle me-2"></i> My Profile
        </a>
      </li>
      <li>
        <a class="dropdown-item d-flex align-items-center" href="{{ url('logout') }}">
          <i class="bi bi-box-arrow-right me-2"></i> Logout
        </a>
      </li>
    </ul>
  </div>
</div>

<style>
  .topbar {
  background: rgba(40, 40, 40, 0.9);
  padding: 10px 15px;
  border-radius: 10px;
}

.profile span {
  color: #fff;
  font-weight: 500;
}

.dropdown-menu {
  background: #2c2c2c;
  border: none;
  border-radius: 10px;
  min-width: 180px;
}

.dropdown-item {
  color: #ddd;
  transition: 0.3s;
}

.dropdown-item:hover {
  background: rgba(255, 230, 0, 0.9); /* yellow highlight */
  color: #000;
  font-weight: 600;
}

.dropdown-menu i {
  font-size: 1rem;
}

</style>