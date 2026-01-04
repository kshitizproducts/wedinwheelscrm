@extends('backend.layouts.main')
@section('main-section')

<section class="profile-page container py-5">

  <div class="row justify-content-center">

    <!-- Profile Card -->
    <div class="col-lg-8">
      <div class="card bg-dark text-white shadow-lg p-4 rounded-4">

        <!-- Profile Header -->
        <div class="d-flex align-items-center mb-4">
          <img src="https://i.pravatar.cc/150" alt="Profile Picture" class="rounded-circle me-3" width="120" height="120">
          <div>
            <h3 class="text-warning mb-1">Kshitiz Kumar</h3>
            <p class="text-white-50 mb-0">Full Stack PHP & Laravel Developer</p>
          </div>
        </div>

        <!-- Contact Info -->
        <div class="mb-4">
          <h5 class="text-warning">Contact Information</h5>
          <ul class="list-unstyled mb-0">
            <li><strong>Email:</strong> <a href="mailto:kshitiz.ranchi@gmail.com" class="text-white">kshitiz.ranchi@gmail.com</a></li>
            <li><strong>Phone:</strong> <a href="tel:+919006042011" class="text-white">+91 9006042011</a></li>
            <li><strong>Website:</strong> <a href="https://kshitizkumar.com" target="_blank" class="text-white">kshitizkumar.com</a></li>
          </ul>
        </div>

        <!-- About Section -->
        <div class="mb-4">
          <h5 class="text-warning">About Me</h5>
          <p class="text-white-50">I am a passionate PHP & Laravel developer with over 4 years of experience building web applications, performance optimization, and custom CRM systems. I love coding, exploring new technologies, and creating efficient solutions.</p>
        </div>

        <!-- Skills / Interests -->
        <div class="mb-4">
          <h5 class="text-warning">Skills & Interests</h5>
          <span class="badge bg-secondary me-2 mb-1">PHP</span>
          <span class="badge bg-secondary me-2 mb-1">Laravel</span>
          <span class="badge bg-secondary me-2 mb-1">MySQL</span>
          <span class="badge bg-secondary me-2 mb-1">Golang</span>
          <span class="badge bg-secondary me-2 mb-1">Ethical Hacking</span>
          <span class="badge bg-secondary me-2 mb-1">Flutter</span>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex justify-content-end">
          <a href="{{ url('edit_profile') }}"><button class="btn btn-warning text-dark me-2">Edit Profile</button></a>
         <a href="{{ url('change_password') }}"><button class="btn btn-outline-warning text-white">Change Password</button></a> 
        </div>

      </div>
    </div>

  </div>
</section>

@endsection

@section('styles')
<style>
body { background-color: #121212; }
.card { border-radius: 20px; }
.text-white-50 { color: rgba(255,255,255,.6) !important; }
.badge { font-size: 0.9rem; padding: 0.5em 0.75em; border-radius: 10px; }
.btn-outline-warning { border-color: #ffc107; }
.btn-outline-warning:hover { background-color: #ffc107; color: #121212; }
</style>
@endsection
