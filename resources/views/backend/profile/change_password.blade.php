@extends('backend.layouts.main')
@section('main-section')

<section class="change-password container py-4 py-lg-5">

  <div class="row justify-content-center">
    <div class="col-lg-6 col-md-8">

      <div class="card bg-dark text-white shadow-lg p-3 p-md-4 rounded-4 border border-secondary">

        <div class="d-flex justify-content-between align-items-center mb-4">
          <h3 class="text-warning mb-0">Change Password</h3>
          <a href="{{ url('profile') }}" class="btn btn-outline-warning btn-sm">← Back</a>
        </div>

        {{-- ✅ SUCCESS / ERROR MESSAGE --}}
        @if(session('success'))
          <div class="alert alert-success py-2">
            {{ session('success') }}
          </div>
        @endif

        @if(session('error'))
          <div class="alert alert-danger py-2">
            {{ session('error') }}
          </div>
        @endif

        @if($errors->any())
          <div class="alert alert-danger py-2">
            <ul class="mb-0">
              @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        {{-- ✅ Change Password FORM --}}
        <form method="POST" action="{{ url('change-password/update') }}">
          @csrf

          <!-- Current Password -->
          <div class="mb-3">
            <label class="form-label text-warning">Current Password</label>
            <div class="input-group">
              <input type="password" name="current_password"
                     class="form-control bg-secondary text-white border-0"
                     placeholder="Enter current password" required>
              <button type="button" class="btn btn-outline-warning" onclick="togglePass(this)">
                <i class="fa-solid fa-eye"></i>
              </button>
            </div>
          </div>

          <!-- New Password -->
          <div class="mb-3">
            <label class="form-label text-warning">New Password</label>
            <div class="input-group">
              <input type="password" name="new_password"
                     class="form-control bg-secondary text-white border-0"
                     placeholder="Enter new password" required>
              <button type="button" class="btn btn-outline-warning" onclick="togglePass(this)">
                <i class="fa-solid fa-eye"></i>
              </button>
            </div>
            <div class="small text-white-50 mt-2">
              Password should be minimum 6 characters.
            </div>
          </div>

          <!-- Confirm New Password -->
          <div class="mb-4">
            <label class="form-label text-warning">Confirm New Password</label>
            <div class="input-group">
              <input type="password" name="new_password_confirmation"
                     class="form-control bg-secondary text-white border-0"
                     placeholder="Confirm new password" required>
              <button type="button" class="btn btn-outline-warning" onclick="togglePass(this)">
                <i class="fa-solid fa-eye"></i>
              </button>
            </div>
          </div>

          <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-warning text-dark fw-semibold px-4">
              Update Password
            </button>
          </div>

        </form>

      </div>

    </div>
  </div>

</section>

@section('styles')
<style>
  body { background-color: #121212; }
  .card { border-radius: 20px; }
  .form-control:focus { box-shadow: none; }
</style>
@endsection

<script>
function togglePass(btn){
  let input = btn.parentElement.querySelector("input");
  if(input.type === "password"){
    input.type = "text";
    btn.innerHTML = `<i class="fa-solid fa-eye-slash"></i>`;
  }else{
    input.type = "password";
    btn.innerHTML = `<i class="fa-solid fa-eye"></i>`;
  }
}
</script>

@endsection
