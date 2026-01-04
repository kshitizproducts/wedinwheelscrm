@extends('backend.layouts.main')
@section('main-section')

<section class="edit-profile container py-5">

  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card bg-dark text-white shadow-lg p-4 rounded-4">

        <h3 class="text-warning mb-4">Edit Profile</h3>

        <form id="editProfileForm">
          <!-- Avatar -->
          <div class="mb-3 text-center">
            <img src="https://i.pravatar.cc/150" alt="Profile Picture" class="rounded-circle mb-2" width="120" height="120">
            <input type="file" class="form-control bg-secondary text-white border-0 mt-2">
          </div>

          <!-- Name -->
          <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" class="form-control bg-secondary text-white border-0" value="Kshitiz Kumar">
          </div>

          <!-- Email -->
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control bg-secondary text-white border-0" value="kshitiz.ranchi@gmail.com">
          </div>

          <!-- Phone -->
          <div class="mb-3">
            <label class="form-label">Phone</label>
            <input type="text" class="form-control bg-secondary text-white border-0" value="+91 9006042011">
          </div>

          <!-- Bio -->
          <div class="mb-3">
            <label class="form-label">About Me</label>
            <textarea class="form-control bg-secondary text-white border-0" rows="4">I am a passionate PHP & Laravel developer...</textarea>
          </div>

          <!-- Skills -->
          <div class="mb-3">
            <label class="form-label">Skills</label>
            <input type="text" class="form-control bg-secondary text-white border-0" placeholder="Separate skills with commas" value="PHP,Laravel,MySQL,Golang,Flutter">
          </div>

          <div class="text-end">
            <button type="submit" class="btn btn-warning text-dark">Save Changes</button>
          </div>
        </form>

      </div>
    </div>
  </div>

</section>

@endsection

@section('styles')
<style>
body { background-color: #121212; }
.card { border-radius: 20px; }
.form-control:focus { box-shadow: none; }
</style>
@endsection
