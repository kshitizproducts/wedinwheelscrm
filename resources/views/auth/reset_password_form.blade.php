<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Set New Password | WedinWheels</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background:#0b0b0b;color:#fff;">
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-5">

      <div class="card shadow" style="background:#141414;border:1px solid #2b2b2b;border-radius:16px;">
        <div class="card-body p-4">

          <h3 class="text-center mb-1" style="color:#fff;">WedinWheels</h3>
          <p class="text-center mb-4" style="color:#bdbdbd;">Set your new password</p>

          @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
          @endif

          @if($errors->any())
            <div class="alert alert-danger">
              @foreach($errors->all() as $err)
                <div>{{ $err }}</div>
              @endforeach
            </div>
          @endif

          <form action="{{ route('password.update') }}" method="POST">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <div class="mb-3">
              <label class="form-label" style="color:#cfcfcf;">New Password</label>
              <input type="password" name="password" class="form-control"
                style="background:#0f0f0f;border:1px solid #2b2b2b;color:#fff;border-radius:12px;"
                placeholder="Enter new password" required>
            </div>

            <div class="mb-3">
              <label class="form-label" style="color:#cfcfcf;">Confirm Password</label>
              <input type="password" name="password_confirmation" class="form-control"
                style="background:#0f0f0f;border:1px solid #2b2b2b;color:#fff;border-radius:12px;"
                placeholder="Confirm password" required>
            </div>

            <button type="submit" class="btn w-100"
              style="background:#2f2f2f;border:1px solid #3d3d3d;color:#fff;border-radius:12px;padding:12px;">
              ✅ Update Password
            </button>
          </form>

        </div>
      </div>

    </div>
  </div>
</div>
</body>
</html>
