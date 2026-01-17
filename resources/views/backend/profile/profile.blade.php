@extends('backend.layouts.main')
@section('main-section')

<section class="profile-page container py-4 py-lg-5">

  <div class="row justify-content-center">
    <div class="col-lg-9 col-xl-8">

      <div class="card bg-dark text-white shadow-lg p-3 p-md-4 rounded-4 border border-secondary">

        <!-- Profile Header -->
        <div class="d-flex flex-column flex-md-row align-items-center align-items-md-start gap-3 mb-4">

          @php
            $profileImg = $userData->profile_photo ?? null; // field should exist in users table
            $profileUrl = $profileImg ? asset($profileImg) : 'https://i.pravatar.cc/150';
          @endphp

          <div class="position-relative">
            <img src="{{ $profileUrl }}" id="profileImgPreview"
                 alt="Profile Picture"
                 class="rounded-circle border border-warning shadow"
                 width="120" height="120"
                 style="object-fit:cover;">

            <!-- small camera button -->
            <button type="button"
                    class="btn btn-warning text-dark btn-sm rounded-circle position-absolute"
                    style="bottom:0; right:0; width:38px; height:38px;"
                    onclick="openPhotoModal()">
              <i class="fa-solid fa-camera"></i>
            </button>
          </div>

          <div class="flex-grow-1 text-center text-md-start">
            <h3 class="text-warning mb-1">{{ $userData->name ?? 'N/A' }}</h3>
            <p class="text-white-50 mb-0">{{ $userData->role ?? 'N/A' }}</p>

            <div class="mt-3 d-flex flex-wrap gap-2 justify-content-center justify-content-md-start">
              {{-- <a href="{{ url('edit_profile') }}" class="btn btn-warning text-dark btn-sm px-3">Edit Profile</a> --}}
              <a href="{{ url('change_password') }}" class="btn btn-outline-warning btn-sm px-3">Change Password</a>
              <button type="button" onclick="openPhotoModal()" class="btn btn-outline-secondary btn-sm px-3">
                Change Photo
              </button>
            </div>
          </div>
        </div>

        <!-- Contact Info -->
        <div class="mb-4">
          <h5 class="text-warning mb-3">Contact Information</h5>
          <div class="row g-3">
            <div class="col-md-6">
              <div class="p-3 rounded-3 bg-black border border-secondary">
                <div class="small text-white-50 mb-1">Email</div>
                <a href="mailto:{{ $userData->email ?? '#' }}" class="text-white text-decoration-none">
                  {{ $userData->email ?? 'N/A' }}
                </a>
              </div>
            </div>

            <div class="col-md-6">
              <div class="p-3 rounded-3 bg-black border border-secondary">
                <div class="small text-white-50 mb-1">Phone</div>
                <a href="tel:{{ $userData->phone ?? '#' }}" class="text-white text-decoration-none">
                  {{ $userData->phone ?? 'N/A' }}
                </a>
              </div>
            </div>

            <div class="col-12">
              <div class="p-3 rounded-3 bg-black border border-secondary">
                <div class="small text-white-50 mb-1">Address</div>
                <div class="text-white">{{ $userData->address ?? 'N/A' }}</div>
              </div>
            </div>
          </div>
        </div>

      

        <!-- DOCUMENTS SECTION -->
        <div class="mb-2">
          <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
            <h5 class="text-warning mb-0">KYC & Employee Documents</h5>
            @if(!$userDocs)
              <span class="badge bg-danger">Documents Not Uploaded</span>
            @else
              <span class="badge bg-success">Documents Available</span>
            @endif
          </div>

          <div class="row g-3">
            @php
              $docs = [
                'aadhar' => 'Aadhar Card',
                'pan' => 'PAN Card',
                'resume' => 'Resume',
                'offer_letter' => 'Offer Letter',
                'appointment_letter' => 'Appointment Letter',
                'agreement_letter' => 'Agreement Letter',
                'bank_passbook' => 'Bank Passbook',
                'education_certificates' => 'Education Certificates',
              ];
            @endphp

            @foreach($docs as $key => $label)
              <div class="col-12 col-md-6">
                <div class="doc-card p-3 rounded-3 bg-black border border-secondary h-100">
                  @php
                    $file = $userDocs->$key ?? null;
                  @endphp

                  <div class="d-flex align-items-start justify-content-between gap-3">
                    <div class="flex-grow-1">
                      <div class="fw-semibold">{{ $label }}</div>
                      <div class="small mt-1">
                        @if($file)
                          <span class="text-success">Uploaded</span>
                        @else
                          <span class="text-white-50">Not Uploaded</span>
                        @endif
                      </div>
                    </div>

                    <div class="d-flex flex-column gap-2">
                      @if($file)
                        <a href="{{ asset($file) }}" target="_blank" class="btn btn-outline-warning btn-sm">
                          View
                        </a>
                      @else
                        <button type="button" class="btn btn-outline-secondary btn-sm" disabled>
                          View
                        </button>
                      @endif

                      <button type="button"
                              class="btn btn-warning text-dark btn-sm"
                              onclick="openUploadModal('{{ $key }}','{{ $label }}')">
                        {{ $file ? 'Update' : 'Upload' }}
                      </button>
                    </div>
                  </div>

                </div>
              </div>
            @endforeach
          </div>
        </div>

      </div>
      <!-- /Card -->

    </div>
  </div>
</section>


<!-- ✅ Profile Photo Modal -->
<div class="modal fade" id="photoModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-white border border-warning">

      <div class="modal-header border-warning">
        <h5 class="modal-title text-warning">Update Profile Photo</h5>
        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <form method="POST" action="{{ url('profile/photo/update') }}" enctype="multipart/form-data">
        @csrf

        <div class="modal-body">

          <div class="text-center mb-3">
            <img id="newPhotoPreview"
                 src="{{ $profileUrl }}"
                 width="130" height="130"
                 class="rounded-circle border border-warning shadow"
                 style="object-fit:cover;">
          </div>

          <label class="text-warning">Choose Photo</label>
          <input type="file"
                 name="profile_photo"
                 class="form-control bg-secondary text-white"
                 id="profile_photo_input"
                 accept="image/png,image/jpeg,image/jpg"
                 required>

          <div class="small text-white-50 mt-2">
            Supported: JPG / PNG (max 2MB recommended)
          </div>

        </div>

        <div class="modal-footer border-warning">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-warning text-dark fw-semibold">Update</button>
        </div>

      </form>

    </div>
  </div>
</div>


<!-- ✅ Upload Document Modal -->
<div class="modal fade" id="uploadDocModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-white border border-warning">

      <div class="modal-header border-warning">
        <h5 class="modal-title text-warning" id="docModalTitle">Upload Document</h5>
        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <form id="docUploadForm" enctype="multipart/form-data" method="POST" action="{{ url('user-documents/update') }}">
        @csrf
        <input type="hidden" name="doc_key" id="doc_key">

        <div class="modal-body">
          <label class="text-warning">Choose File</label>
          <input type="file" name="document_file" class="form-control bg-secondary text-white" required>
          <div class="small text-white-50 mt-2">
            Supported: PDF/JPG/PNG (Recommended PDF)
          </div>
        </div>

        <div class="modal-footer border-warning">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-warning text-dark fw-semibold">Save</button>
        </div>
      </form>

    </div>
  </div>
</div>


@section('styles')
<style>
  body { background-color: #121212; }
  .card { border-radius: 18px; }
  .text-white-50 { color: rgba(255,255,255,.6) !important; }
  .badge { font-size: 0.85rem; padding: 0.55em 0.75em; border-radius: 10px; }
  .btn-outline-warning { border-color: #ffc107; color: #ffc107; }
  .btn-outline-warning:hover { background-color: #ffc107; color: #121212; }

  .doc-card{ transition: 0.2s ease; }
  .doc-card:hover{ border-color: rgba(255,193,7,0.55) !important; transform: translateY(-1px); }
</style>
@endsection


<script>
let uploadModal, photoModal;

document.addEventListener('DOMContentLoaded', function () {
  uploadModal = new bootstrap.Modal(document.getElementById('uploadDocModal'));
  photoModal  = new bootstrap.Modal(document.getElementById('photoModal'));

  // photo preview
  document.getElementById('profile_photo_input')?.addEventListener('change', function(){
    const f = this.files[0];
    if(f){
      document.getElementById('newPhotoPreview').src = URL.createObjectURL(f);
    }
  });
});

// open modals
function openUploadModal(key, label){
  document.getElementById('doc_key').value = key;
  document.getElementById('docModalTitle').innerText = (label ? label : 'Document') + " - Upload/Update";
  document.querySelector('#docUploadForm input[type="file"]').value = "";
  uploadModal.show();
}

function openPhotoModal(){
  document.getElementById('profile_photo_input').value = "";
  photoModal.show();
}
</script>

@endsection
