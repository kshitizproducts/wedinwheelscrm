@extends('backend.layouts.main')
@section('main-section')

<section class="change-password container py-5">

  <div class="row justify-content-center">
    <div class="col-lg-6">
      <div class="card bg-dark text-white shadow-lg p-4 rounded-4">

        <h3 class="text-warning mb-4">Change Password</h3>

        <form id="changePasswordForm">
          <!-- Current Password -->
          <div class="mb-3">
            <label class="form-label">Current Password</label>
            <input type="password" class="form-control bg-secondary text-white border-0" placeholder="Enter current password">
          </div>

          <!-- New Password -->
          <div class="mb-3">
            <label class="form-label">New Password</label>
            <input type="password" class="form-control bg-secondary text-white border-0" placeholder="Enter new password">
          </div>

          <!-- Confirm New Password -->
          <div class="mb-3">
            <label class="form-label">Confirm New Password</label>
            <input type="password" class="form-control bg-secondary text-white border-0" placeholder="Confirm new password">
          </div>

          <div class="text-end">
            <button type="submit" class="btn btn-warning text-dark">Update Password</button>
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
