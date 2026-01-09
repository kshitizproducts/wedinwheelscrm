@extends('backend.layouts.main')
@section('main-section')
    <section class="dashboard row g-4">

        <div class="container my-4">
            <div class="card">

                <div class="row g-4">

                    <!-- Left Column -->
                    <div style="border-style: solid;border-color:#ffff00;border-width:2px; border-radius: 10px; padding: 10px;"
                        class="col-lg-5 col-md-6">
                        <form id="profilepart1" method="post" action="{{ url('update_profile_part1') }}"
                            enctype="multipart/form-data">
                            @csrf

                            <input type="hidden" value="{{ $id }}" name="car_id">
                            <div class="text-center mb-4">

                                <!-- img profile start -->


                                <!-- Profile Photo -->
                                @php
                                    $profilepublicUrl = $car_data->profile_pic;
                                @endphp
                                <div class="mb-3">

                                    <img id="profilePreview" src="{{ asset($profilepublicUrl) }}"
                                        class="img-fluid rounded mb-2 shadow-sm" alt="Car Photo"
                                        style="width:180px; height:180px; object-fit:cover;">
                                    <div>
                                        <label class="form-label text-warning fw-bold mt-2">Change Profile Photo</label>
                                        <input type="file" name="profile_photo" id="profile_photo"
                                            class="form-control w-50 mx-auto">
                                    </div>
                                </div>

                                <!-- end of profil image -->

                                <h4 class="fw-bold text-warning mb-1">
                                    <label for="">Brand Name</label>
                                    <input type="text" class="form-control" value="{{ $car_data->brand ?? '' }}"
                                        name="brand_name">
                                </h4>
                                <p class=" text-white mb-0">
                                    <label for="">Car Description</label>
                                    <input type="text" class="form-control" value="{{ $car_data->car_desc ?? '' }}"
                                        name="car_info">
                                </p>
                                <p class=" text-white mb-0">
                                    <label for="">Model</label>
                                    <input type="text" class="form-control" value="{{ $car_data->model ?? '' }}"
                                        name="car_model">
                                </p>
                            </div>

                            <div class="info-tag text-warning-emphasis">
                                <i class="bi bi-info-circle-fill me-1 text-warning"></i> Latest update:
                                {{ $car_data->updated_at }}
                            </div>

                            <h6>360° View URL</h6>
                            <div class="mb-4 text-center">
                                <input type="text" class="form-control" value="{{ $car_data->view_360 }}"
                                    placeholder="URL for 360" name="360_url">
                            </div>

                            <h6>Media Feed</h6>
                            <div class="media-feed mb-3">
                                <!-- Images -->
                                <div class="mb-3">
                                    <label class="form-label text-warning fw-bold">Upload Images</label>
                                    <input type="file" multiple class="form-control" name="images[]" id="images">
                                    <div id="imagePreviewContainer" class="d-flex flex-wrap gap-2 mt-2"></div>
                                </div>

                                <!-- Videos -->
                                <div class="mb-3">
                                    <label class="form-label text-warning fw-bold">Upload Videos</label>
                                    <input type="file" multiple class="form-control" name="videos[]" id="videos">
                                    <div id="videoPreviewContainer" class="d-flex flex-wrap gap-2 mt-2"></div>
                                </div>
                                <hr>

                                <button onclick="udpate_profile_pic()" type="button"
                                    class="btn btn-success">Update</button>
                            </div>
                    </div>
                    <!-- <button> Update it</button> -->
                    </form>

                    <script>
                        function udpate_profile_pic() {
                            const form = document.getElementById('profilepart1');
                            const formData = new FormData(form);

                            fetch(form.action, {
                                    method: 'POST',
                                    body: formData,
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                                    }
                                })
                                .then(res => res.json())
                                .then(data => {
                                    if (data.success) {
                                        // alert('aa gya ');
                                        // code for sweat alert start
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Updated Successfully!',
                                            showConfirmButton: false,
                                            timer: 1500
                                        }).then(() => {
                                            location.reload();
                                        });
                                        // code for sweat alert end
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Update Failed',
                                            // showConfirmButton:false,
                                            text: data.message || 'Something went wrong!',
                                        });
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    alert('Error occurred while submitting the form.');
                                })
                        }
                    </script>
                    <!-- ========== Scripts ========== -->
                    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

                    <script>
                        // ✅ Profile photo preview
                        $('#profile_photo').on('change', function(e) {
                            const file = e.target.files[0];
                            if (file) {
                                const reader = new FileReader();
                                reader.onload = (event) => $('#profilePreview').attr('src', event.target.result);
                                reader.readAsDataURL(file);
                            }
                        });

                        // ✅ Image preview with remove button
                        $('#images').on('change', function(e) {
                            $('#imagePreviewContainer').empty(); // clear old previews
                            Array.from(e.target.files).forEach((file, index) => {
                                if (!file.type.startsWith('image/')) return;
                                const reader = new FileReader();
                                reader.onload = function(event) {
                                    const html = `
                                      <div class="position-relative d-inline-block">
                                        <img src="${event.target.result}" class="rounded shadow-sm" 
                                             style="width:120px; height:120px; object-fit:cover;">
                                        <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 remove-btn" data-index="${index}">&times;</button>
                                      </div>`;
                                    $('#imagePreviewContainer').append(html);
                                };
                                reader.readAsDataURL(file);
                            });
                        });

                        // ✅ Video preview with remove button
                        $('#videos').on('change', function(e) {
                            $('#videoPreviewContainer').empty();
                            Array.from(e.target.files).forEach((file, index) => {
                                if (!file.type.startsWith('video/')) return;
                                const url = URL.createObjectURL(file);
                                const html = `
                                    <div class="position-relative d-inline-block">
                                      <video src="${url}" class="rounded shadow-sm" controls
                                             style="width:160px; height:120px; object-fit:cover;"></video>
                                      <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 remove-btn" data-index="${index}">&times;</button>
                                    </div>`;
                                $('#videoPreviewContainer').append(html);
                            });
                        });

                        // ✅ Remove preview on click
                        $(document).on('click', '.remove-btn', function() {
                            $(this).parent().remove();
                        });
                    </script>



                    <!-- profilepart1 -->

                    <!-- Right Column -->
                    <div style="border-style: solid;border-color:#ffff00;border-width:2px; border-radius: 10px; padding: 10px;"
                        class="col-lg-7 col-md-6">
                        <h5 class="fw-bold mb-3">Car Details</h5>
                        <form id="profileformpart2" method="post" action="{{ url('update_profile_part_2') }}">
                            @csrf

                            <input type="hidden" value="{{ $car_data->unique_id }}" name="car_id">
                            <div class="row mb-2">
                                <div class="col-sm-6 text-white"><strong class="text-warning">Mileage:</strong>
                                    <input type="text" value="{{ $car_data->mileage }}"
                                        class="form-control  bg-dark text-white " name="mileage">
                                </div>
                                {{--  <div class="col-sm-6 text-white"><strong class="text-warning">Fuel Type:</strong>
                                    <input type="text" value="{{ $car_data->fuel_type }}" class="form-control  bg-dark text-white"
								name="fuel_type">  </div> --}}






                                <div class="col-sm-6 text-white">
                                    <strong class="text-warning">Fuel Type:</strong>
                                    <select name="fuel_type" class="form-select bg-dark text-white border-0 mt-1">
                                        <option selected disabled>Please Select</option>
                                        {{-- Single Fuel Options --}}
                                        <option value="Petrol" {{ $car_data->fuel_type == 'Petrol' ? 'selected' : '' }}>
                                            Petrol</option>
                                        <option value="Diesel" {{ $car_data->fuel_type == 'Diesel' ? 'selected' : '' }}>
                                            Diesel</option>
                                        <option value="Electric"
                                            {{ $car_data->fuel_type == 'Electric' ? 'selected' : '' }}>Electric (EV)
                                        </option>

                                        {{-- Combination Options --}}
                                        <option value="Petrol + CNG"
                                            {{ $car_data->fuel_type == 'Petrol + CNG' ? 'selected' : '' }}>Petrol + CNG
                                        </option>
                                        <option value="Petrol + Hybrid"
                                            {{ $car_data->fuel_type == 'Petrol + Hybrid' ? 'selected' : '' }}>Petrol +
                                            Hybrid</option>
                                        <option value="Diesel + Hybrid"
                                            {{ $car_data->fuel_type == 'Diesel + Hybrid' ? 'selected' : '' }}>Diesel +
                                            Hybrid</option>
                                        <option value="CNG Only"
                                            {{ $car_data->fuel_type == 'CNG Only' ? 'selected' : '' }}>CNG Only</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-6 text-white"><strong class="text-warning">Seats:</strong>
                                    <input type="text" value="{{ $car_data->seat_capacity }}"
                                        class="form-control  bg-dark text-white" name="seats">
                                </div>
                                <div class="col-sm-6 text-white"><strong class="text-warning">Engine:</strong>
                                    <input type="text" value="{{ $car_data->engine }}"
                                        class="form-control  bg-dark text-white" name="engine">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-6 text-white"><strong class="text-warning">Registration
                                        Number:</strong>
                                    <input type="text" value="{{ $car_data->registration_no }}"
                                        class="form-control  bg-dark text-white" name="registration_no">
                                </div>

                                <div class="col-sm-6 text-white"><strong class="text-warning">Owner Name:</strong>
                                    <input type="text" value="{{ $car_data->owner_name }}"
                                        class="form-control  bg-dark text-white" name="owner_name">
                                </div>
                                <div class="col-sm-6 text-white"><strong class="text-warning">Status:</strong>

                                    <select name="status" class="form-control">
                                        <option @if ($car_data->status == 1) selected @endif value="1">Running
                                        </option>
                                        <option @if ($car_data->status == 2) selected @endif value="2">
                                            Blacklisted</option>
                                        <option @if ($car_data->status == 3) selected @endif value="3">Sold
                                        </option>
                                        <option @if ($car_data->status == 4) selected @endif value="4">Booked
                                        </option>
                                        <option @if ($car_data->status == 5) selected @endif value="5">Break-down
                                        </option>

                                    </select>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-6 text-white"><strong class="text-warning">Location:</strong>
                                    <input type="text" value="{{ $car_data->location }}"
                                        class="form-control  bg-dark text-white" name="location">
                                </div>
                                <div class="col-sm-6 text-white"><strong class="text-warning">Next Availability:</strong>
                                    <input type="date" value="{{ $car_data->next_availability }}"
                                        class="form-control bg-dark text-white" name="next_availability">
                                </div>
                                <div class="col-sm-6 bg-dark text-white"><strong class="text-warning">Duplicate
                                        Keys:</strong>
                                    <select class="form-control" name="duplicate_keys" id="">
                                        <option selected disabled>Please Select</option>
                                        <option @if ($car_data->duplicate_keys == 'Yes') selected @endif value="Yes">Yes
                                        </option>
                                        <option @if ($car_data->duplicate_keys == 'No') selected @endif value="No">No
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <button type="button" onclick="update_form_part_2_fun()" class="btn btn-success">Update
                                IT</button>
                        </form>


                        <script>
                            function update_form_part_2_fun() {
                                const form = document.getElementById('profileformpart2');
                                const formData = new FormData(form);

                                fetch(form.action, {
                                        method: 'POST',
                                        body: formData,
                                        headers: {
                                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                                        }
                                    })
                                    .then(res => res.json())
                                    .then(data => {
                                        if (data.success) {
                                            // alert('aa gya ');
                                            // code for sweat alert start
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Updated Successfully!',
                                                showConfirmButton: false,
                                                timer: 1500
                                            }).then(() => {
                                                location.reload();
                                            });
                                            // code for sweat alert end
                                        } else {
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Update Failed',
                                                // showConfirmButton:false,
                                                text: data.message || 'Something went wrong!',
                                            });
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                        alert('Error occurred while submitting the form.');
                                    })
                            }
                        </script>
                        <hr class="border-warning">

                        <!--  ------------------------------------------------ Documents Section Starts Here ------------------------------------------------ -->
                        <div class="container">
                            <h2>Upload required documents!</h2>

                            <form id="update_main_doc_form" method="post" enctype="multipart/form-data"
                                action="{{ url('save_imp_docs') }}">
                                @csrf
                                <input type="hidden" value="{{ $car_data->unique_id }}" name="car_id">

                                <div class="row">
                                    {{-- RC BOOK --}}
                                    <div class="col-4 mb-3">
                                        <label><strong class="text-warning">RC book</strong></label>
                                        <input type="file" name="rc_book" class="form-control">
                                         <label>RC Expiry Date:<input type="date" name="rc_expiry_date" class="form-control">

                                        @if (!empty($car_data->rc_book))
                                            <a href="{{ asset($car_data->rc_book) }}" target="_blank"
                                                class="btn btn-sm btn-primary mt-2">View File</a>
                                        @else
                                            <p class="text-muted mt-2">No file uploaded</p>
                                        @endif
                                    </div>

                                    {{-- POLLUTION --}}
                                    <div class="col-4 mb-3">
                                        <label><strong class="text-warning">Pollution</strong></label>
                                        <input type="file" name="pollution" class="form-control">
                                        <label>Pollution Expiry Date:<input type="date" name="pollution_expiry_date" class="form-control">
                                        @if (!empty($car_data->pollution))
                                            <a href="{{ asset($car_data->pollution) }}" target="_blank"
                                                class="btn btn-sm btn-primary mt-2">View File</a>
                                        @else
                                            <p class="text-muted mt-2">No file uploaded</p>
                                        @endif
                                    </div>

                                    {{-- INSURANCE --}}
                                    <div class="col-4 mb-3">
                                        <label><strong class="text-warning">Insurance</strong></label>
                                        <input type="file" name="insurance" class="form-control">
                                        <label>Insurance Expiry Date:<input type="date" name="insurance_expiry_date" class="form-control">
                                        @if (!empty($car_data->insurance))
                                            <a href="{{ asset($car_data->insurance) }}" target="_blank"
                                                class="btn btn-sm btn-primary mt-2">View File</a>
                                        @else
                                            <p class="text-muted mt-2">No file uploaded</p>
                                        @endif
                                    </div>

                                    {{-- Submit --}}
                                    <div class="col-12 mt-3">
                                        <button class="btn btn-success" onclick="update_main_doc()" type="button">Update
                                            It</button>
                                    </div>
                                </div>
                            </form>

                            <script src="{{ asset('backend/js/sweetalert2.min.js') }}"></script>
                            <script>
                                function update_main_doc() {
                                    const form = document.getElementById('update_main_doc_form');
                                    const formData = new FormData(form);

                                    fetch(form.action, {
                                            method: 'POST',
                                            body: formData,
                                            headers: {
                                                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                                            }
                                        })
                                        .then(res => res.json())
                                        .then(data => {
                                            if (data.success) {
                                                // alert('aa gya ');
                                                // code for sweat alert start
                                                Swal.fire({
                                                    icon: 'success',
                                                    title: 'Added Successfully!',
                                                    showConfirmButton: false,
                                                    timer: 1500
                                                }).then(() => {
                                                    location.reload();
                                                });
                                                // code for sweat alert end
                                            } else {
                                                Swal.fire({
                                                    icon: 'error',
                                                    title: 'Update Failed',
                                                    // showConfirmButton:false,
                                                    text: data.message || 'Something went wrong!',
                                                });
                                            }
                                        })
                                        .catch(error => {
                                            console.error('Error:', error);
                                            alert('Error occurred while submitting the form.');
                                        })
                                }
                            </script>

                        </div>


                        <!--  ------------------------------------------------ Documents Section Ends Here ------------------------------------------------ -->



                        {{--
            <h6>Documents</h6>
            <div class="table-responsive">
              <table class="table table-dark table-striped table-bordered align-middle">
                <thead>
               <form id="addcar_document_form" method="post" action="{{ url('add_document') }}">
                    @csrf

                    <input type="hidden" value="{{ $car_data->unique_id }}" name="car_id">
                    <tr>
                      <td>
                        <label for="">Name of Document</label>
                        <select class="form-control" name="document_name" id="">
                          <option disable selected>Please Select</option>
                          @foreach ($document_master as $doc)
                            <option value="{{ $doc->id }}">{{$doc->doc_name}}</option>
                          @endforeach
                        </select>
                      </td>

                      <td>
                        <label for="">Issued Date</label>
                        <input type="date" name="issue_date" class="form-control">
                      </td>

                      <td>
                        <label for="">Expiry Date</label>
                        <input type="date" name="expiry_date" class="form-control">
                      </td>

                      <td>
                        <label for="">Reminder before days(count)</label>
                        <input type="number" min="1" name="reminder_days" class="form-control">
                      </td>

                      <td>
                        <button type="button" onclick="add_car_document_function()" class="btn btn-success">Add</button>
                      </td>
                      <td></td>
                    </tr>
                  </form>
                  <script src="{{ asset('backend/js/sweetalert2.min.js') }}"></script>
                  <script>
                    function add_car_document_function() {
                      const form = document.getElementById('addcar_document_form');
                      const formData = new FormData(form);

                      fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                          'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        }
                      })
                        .then(res => res.json())
                        .then(data => {
                          if (data.success) {
                            // alert('aa gya ');
                            // code for sweat alert start
                            Swal.fire({
                              icon: 'success',
                              title: 'Added Successfully!',
                              showConfirmButton: false,
                              timer: 1500
                            }).then(() => {
                              location.reload();
                            });
                            // code for sweat alert end
                          }
                          else {
                            Swal.fire({
                              icon: 'error',
                              title: 'Update Failed',
                              // showConfirmButton:false,
                              text: data.message || 'Something went wrong!',
                            });
                          }
                        })
                        .catch(error => {
                          console.error('Error:', error);
                          alert('Error occurred while submitting the form.');
                        })
                    }
                  </script>

                </thead>

                <thead>
                  <tr>
                    <th>Document Name</th>
                    <th>Issued</th>
                    <th>Expiry</th>
                    <th>Days</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @php
                  @endphp
                  @foreach ($doc_data as $docs)
                    <tr>
                      <td>
                        @php
                          $doc_id = $docs->document_id;
                          $doc_data = DB::table('document_master')->where('id', $doc_id)->first();
                        @endphp
                        {{$doc_data->doc_name}}
                      </td>
                      <td>{{ $docs->issued_date }}</td>
                      <td>{{ $docs->expiry_date }}</td>
                      <td>{{ $docs->reminder_within }}</td>
                      <td>

                        @if ($docs->status == 0)
                          <span class="text-danger fw-semibold">Expired</span>
                        @elseif($docs->status == 1)
                          <span class="text-success fw-semibold">Valid</span>
                        @endif
                      </td>
                      <td>

                        <!-- edit modal starts here -->
                        <button class="btn btn-warning text-dark fw-semibold" data-bs-toggle="modal"
                          data-bs-target="#carModalprofileupdate{{ $docs->id }}">
                          Edit
                        </button>


                        <div class="modal fade" id="carModalprofileupdate{{ $docs->id }}" tabindex="-1"
                          aria-labelledby="carModalprofileupdate{{ $docs->id }}Label" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content bg-dark text-white border border-warning">
                              <div class="modal-header border-warning">
                                <h5 class="modal-title text-warning" id="carModalLabel">Update Document Informations</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                  aria-label="Close"></button>
                              </div>

                              <form id="updateinfoforprofile" class="p-3" method="post"
                                action="{{ url('update_document') }}">
                                @csrf
                                <input type="hidden" value="{{$docs->id}}" name="table_id">
                                <input type="hidden" value="{{$docs->car_id}}" name="car_id">
                                <div class="modal-body">
                                  <div class="mb-3">
                                    <label class="form-label text-warning">Document</label>
                                    <select class="form-control" name="document_name" id="">
                                      <option disable selected>Please Select</option>
                                      @foreach ($document_master as $doc)
                                        <option @if ($docs->document_id == $doc->id) selected @endif value="{{ $doc->id }}">
                                          {{$doc->doc_name}}
                                        </option>
                                      @endforeach
                                    </select>
                                  </div>
                                  <div class="mb-3">
                                    <label class="form-label text-warning">Issued Date</label>
                                    <input type="date" class="form-control bg-secondary text-white border-0"
                                      name="issue_date" value="{{ $docs->issued_date }}" name="issue_date" placeholder="">
                                  </div>
                                  <div class="mb-3">
                                    <label class="form-label text-warning">Expiry date</label>
                                    <input type="date" class="form-control bg-secondary text-white border-0"
                                      name="expiry_date" value="{{ $docs->expiry_date }}" name="expiry_date"
                                      placeholder="Enter Expiry date">
                                  </div>
                                  <div class="mb-3">
                                    <label class="form-label text-warning">Reminder</label>
                                    <input type="number" class="form-control bg-secondary text-white border-0"
                                      name="reminder_within" value="{{ $docs->reminder_within }}" placeholder="">
                                  </div>
                                  <div class="mb-3">
                                    <label class="form-label text-warning">Status</label>
                                    <select class="form-select bg-secondary text-white border-0" name="status">
                                      <option @if ($docs->status == 0) selected @endif value="0">Expired</option>
                                      <option @if ($docs->status == 1) selected @endif value="1">Vallid</option>
                                    </select>
                                  </div>
                                </div>
                                <div class="modal-footer border-warning">
                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                  <button type="button" class="btn btn-warning text-dark fw-semibold"
                                    onclick="update_document()">Update now</button>
                                </div>
                              </form>  


                              <script src="{{ asset('backend/js/sweetalert2.min.js') }}"></script>
                              <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                              <script>
                                function update_document() {
                                  const form = document.getElementById('updateinfoforprofile');
                                  const formData = new FormData(form);

                                  fetch(form.action, {
                                    method: 'POST',
                                    body: formData,
                                    headers: {
                                      'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                                    }
                                  })
                                    .then(res => res.json())
                                    .then(data => {
                                      if (data.success) {
                                        // alert('aa gya ');
                                        // code for sweat alert start
                                        Swal.fire({
                                          icon: 'success',
                                          title: 'Updated Successfully!',
                                          showConfirmButton: false,
                                          timer: 1500
                                        }).then(() => {
                                          location.reload();
                                        });
                                        // code for sweat alert end
                                      }
                                      else {
                                        Swal.fire({
                                          icon: 'error',
                                          title: 'Update Failed',
                                          // showConfirmButton:false,
                                          text: data.message || 'Something went wrong!',
                                        });
                                      }
                                    })
                                    .catch(error => {
                                      console.error('Error:', error);
                                      alert('Error occurred while submitting the form.');
                                    })
                                }
                              </script>



                            </div>
                          </div>
                        </div>


                        <!-- end of edit modal -->


                        <!-- delete button starts here -->
                        <form id="remove_document_car_profile" action="{{ url('delete_document_car') }}">
                          @csrf
                          <input type="hidden" value="{{ $docs->id }}" name="doc_id">
                          <button onclick="remove_car_docs()" type="button" class="btn btn-danger">Delete</button>
                        </form>

                        <script>
                          function remove_car_docs() {
                            const form = document.getElementById('remove_document_car_profile');
                            const formData = new FormData(form);

                            fetch(form.action, {
                              method: 'POST',
                              body: formData,
                              headers: {
                                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                              }
                            })
                              .then(res => res.json())
                              .then(data => {
                                if (data.success) {
                                  // alert('aa gya ');
                                  // code for sweat alert start
                                  Swal.fire({
                                    icon: 'success',
                                    title: 'Removed Successfully!',
                                    showConfirmButton: false,
                                    timer: 1500
                                  }).then(() => {
                                    location.reload();
                                  });
                                  // code for sweat alert end
                                }
                                else {
                                  Swal.fire({
                                    icon: 'error',
                                    title: 'Update Failed',
                                    // showConfirmButton:false,
                                    text: data.message || 'Something went wrong!',
                                  });
                                }
                              })
                              .catch(error => {
                                console.error('Error:', error);
                                alert('Error occurred while submitting the form.');
                              })
                          }
                        </script>

                        <!-- end of delete button code -->
                      </td>
                    </tr>
                  @endforeach


                </tbody>
              </table>
            </div>
                   --}}

                        <hr class="border-warning">
                    </div>
                </div>

                {{-- Manage service records start   --}}

                <div style="border-style: solid;border-color:#ffff00;border-width:2px; border-radius: 10px; padding: 10px;" class="row">
                    <div class="col-12">
                        <h6 class="mb-3">Last Servicing Reports</h6>
                        <div class="table-responsive">
                            <table class="table table-dark table-striped table-bordered align-middle">
                                <thead>
                                    {{-- Form for Adding New Record --}}
                                    <form id="newservicing_form" method="post" action="{{ url('add_servicing') }}"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" value="{{ $id }}" name="car_id">
                                        <tr>
                                            <td>
                                                <label class="small">Service type</label>
                                                <select name="service_master" class="form-control form-control-sm"
                                                    required>
                                                    <option selected disabled>Please Select</option>
                                                    @foreach ($service_master as $sm)
                                                        <option value="{{ $sm->id }}">{{ $sm->service_type }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <label class="small">Service Date</label>
                                                <input type="date" class="form-control form-control-sm"
                                                    name="service_date" required>
                                            </td>
                                            <td>
                                                <label class="small">Garage Name</label>
                                                <select name="garage" class="form-control form-control-sm" required>
                                                    <option selected disabled>Please Select</option>
                                                    @foreach ($garage_master as $gm)
                                                        <option value="{{ $gm->id }}">{{ $gm->name }}
                                                            ({{ $gm->location }})</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <label class="small">Total Cost</label>
                                                <input type="number" class="form-control form-control-sm"
                                                    name="total_amount" id="add_total">
                                            </td>
                                            <td>
                                                <label class="small">Amount Paid</label>
                                                <input type="number" class="form-control form-control-sm"
                                                    name="amount_paid" id="add_paid">
                                            </td>
                                            <td>
                                                <label class="small">Due Amount</label>
                                                <input type="number"
                                                    class="form-control form-control-sm bg-secondary text-white"
                                                    name="due_amount" id="add_due" readonly>
                                            </td>
                                            <td>
                                                <label class="small">Payment Status</label>
                                                <select name="payment_status" class="form-control form-control-sm">
                                                    <option selected disabled>Select</option>
                                                    <option value="0">Paid</option>
                                                    <option value="1">Pending</option>
                                                </select>
                                            </td>
                                            <td>
                                                <label class="small">Invoice</label>
                                                <input type="file" class="form-control form-control-sm"
                                                    name="invoice[]" multiple>
                                            </td>
                                            <td>
                                                <button id="add_btn" onclick="uploadnew_servicing()" type="button"
                                                    class="btn btn-success btn-sm mt-3 w-100 fw-bold">Add</button>
                                            </td>
                                        </tr>
                                    </form>
                                </thead>
                                <thead class="bg-secondary text-white">
                                    <tr>
                                        <th>Service</th>
                                        <th>Date</th>
                                        <th>Garage</th>
                                        <th>Cost (₹)</th>
                                        <th>Paid</th>
                                        <th>Due</th>
                                        <th>Status</th>
                                        <th>Invoices</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($car_service_data as $service)
                                        <tr>
                                            <td>
                                                @php
                                                    $s_data = DB::table('service_master')
                                                        ->where('id', $service->service_type_id)
                                                        ->first();
                                                    $g_data = DB::table('garage_master')
                                                        ->where('id', $service->garage_id)
                                                        ->first();
                                                @endphp
                                                {{ $s_data->service_type ?? 'N/A' }}
                                            </td>
                                            <td>{{ $service->billed_on_date }}</td>
                                            <td>{{ $g_data->name ?? 'N/A' }} ({{ $g_data->location ?? '' }})</td>
                                            <td>₹{{ $service->cost }}</td>
                                            <td>₹{{ $service->bill_paid }}</td>
                                            <td>₹{{ $service->due }}</td>
                                            <td>
                                                @if ($service->status == 0)
                                                    <span class="badge bg-success">Paid</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $images = json_decode($service->invoice, true);
                                                    $sl = 1;
                                                @endphp
                                                @if (!empty($images))
                                                    @foreach ($images as $img)
                                                        <a target="_blank" href="{{ asset($img) }}"
                                                            class="d-inline-block mb-1">
                                                            <span class="badge bg-info text-dark">Bill
                                                                {{ $sl++ }}</span>
                                                        </a>
                                                    @endforeach
                                                @else
                                                    <span class="text-muted small">No File</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <button class="btn btn-xs btn-warning text-dark"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editModal{{ $service->id }}">Edit</button>
                                                    <button id="del_btn{{ $service->id }}"
                                                        onclick="delete_servicing({{ $service->id }})"
                                                        class="btn btn-xs btn-danger">Del</button>

                                                    <form id="deleteForm{{ $service->id }}"
                                                        action="{{ url('delete_btn_servicing') }}" method="POST"
                                                        style="display:none;">
                                                        @csrf
                                                        <input type="hidden" name="table_id"
                                                            value="{{ $service->id }}">
                                                    </form>
                                                </div>

                                                {{-- Edit Modal --}}
                                                <div class="modal fade" id="editModal{{ $service->id }}" tabindex="-1"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div
                                                            class="modal-content bg-dark text-white border border-warning">
                                                            <div class="modal-header border-warning">
                                                                <h5 class="modal-title text-warning">Update Service Record
                                                                </h5>
                                                                <button type="button" class="btn-close btn-close-white"
                                                                    data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <form id="updateForm{{ $service->id }}"
                                                                action="{{ url('update_servicing') }}" method="POST"
                                                                enctype="multipart/form-data">
                                                                @csrf
                                                                <input type="hidden" name="table_id"
                                                                    value="{{ $service->id }}">
                                                                <input type="hidden" name="car_id"
                                                                    value="{{ $id }}">
                                                                <div class="modal-body">
                                                                    <div class="row g-3">
                                                                        <div class="col-md-4">
                                                                            <label>Service Type</label>
                                                                            <select name="service_master"
                                                                                class="form-control">
                                                                                @foreach ($service_master as $st)
                                                                                    <option value="{{ $st->id }}"
                                                                                        {{ $service->service_type_id == $st->id ? 'selected' : '' }}>
                                                                                        {{ $st->service_type }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label>Date</label>
                                                                            <input type="date" class="form-control"
                                                                                name="service_date"
                                                                                value="{{ $service->billed_on_date }}">
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label>Garage</label>
                                                                            <select name="garage" class="form-control">
                                                                                @foreach ($garage_master as $g)
                                                                                    <option value="{{ $g->id }}"
                                                                                        {{ $service->garage_id == $g->id ? 'selected' : '' }}>
                                                                                        {{ $g->name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label>Total Cost</label>
                                                                            <input type="number"
                                                                                class="form-control edit-total"
                                                                                name="total_amount"
                                                                                value="{{ $service->cost }}">
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label>Paid</label>
                                                                            <input type="number"
                                                                                class="form-control edit-paid"
                                                                                name="amount_paid"
                                                                                value="{{ $service->bill_paid }}">
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label>Due</label>
                                                                            <input type="number"
                                                                                class="form-control edit-due bg-secondary text-white"
                                                                                name="due_amount"
                                                                                value="{{ $service->due }}" readonly>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label>Status</label>
                                                                            <select name="payment_status"
                                                                                class="form-control">
                                                                                <option value="0"
                                                                                    {{ $service->status == 0 ? 'selected' : '' }}>
                                                                                    Paid</option>
                                                                                <option value="1"
                                                                                    {{ $service->status == 1 ? 'selected' : '' }}>
                                                                                    Pending</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-8">
                                                                            <label>Invoice <small
                                                                                    class="text-warning">(Uploading new
                                                                                    files will delete old
                                                                                    ones)</small></label>
                                                                            <input type="file" class="form-control"
                                                                                name="invoice[]" multiple>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer border-warning">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Close</button>
                                                                    <button id="upd_btn{{ $service->id }}"
                                                                        onclick="update_servicing({{ $service->id }})"
                                                                        type="button"
                                                                        class="btn btn-warning text-dark fw-bold">Save
                                                                        Changes</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <script>
                    /** 1. AUTO CALCULATION LOGIC **/
                    function handleCalculation(totalInput, paidInput, dueInput) {
                        const calc = () => {
                            const total = parseFloat(totalInput.value) || 0;
                            const paid = parseFloat(paidInput.value) || 0;
                            dueInput.value = total - paid;
                        };
                        totalInput.addEventListener('input', calc);
                        paidInput.addEventListener('input', calc);
                    }

                    // For Add Form
                    handleCalculation(document.getElementById('add_total'), document.getElementById('add_paid'), document
                        .getElementById('add_due'));

                    // For All Edit Modals
                    document.querySelectorAll('.modal').forEach(modal => {
                        handleCalculation(modal.querySelector('.edit-total'), modal.querySelector('.edit-paid'), modal
                            .querySelector('.edit-due'));
                    });

                    /** 2. LOADER HELPER **/
                    function setBtnState(btn, state) {
                        if (state === 'loading') {
                            btn.disabled = true;
                            btn.oldHTML = btn.innerHTML;
                            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Please Wait...';
                        } else {
                            btn.disabled = false;
                            btn.innerHTML = btn.oldHTML || 'Submit';
                        }
                    }

                    /** 3. AJAX ADD **/
                    function uploadnew_servicing() {
                        const btn = document.getElementById('add_btn');
                        setBtnState(btn, 'loading');

                        const form = document.getElementById('newservicing_form');
                        const formData = new FormData(form);

                        fetch(form.action, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Added!',
                                        text: data.message,
                                        timer: 1500
                                    }).then(() => location.reload());
                                } else {
                                    Swal.fire('Error', data.message, 'error');
                                    setBtnState(btn, 'reset');
                                }
                            })
                            .catch(() => setBtnState(btn, 'reset'));
                    }

                    /** 4. AJAX UPDATE **/
                    function update_servicing(id) {
                        const btn = document.getElementById('upd_btn' + id);
                        setBtnState(btn, 'loading');

                        const form = document.getElementById('updateForm' + id);
                        const formData = new FormData(form);

                        fetch(form.action, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Updated!',
                                        text: data.message,
                                        timer: 1500
                                    }).then(() => location.reload());
                                } else {
                                    Swal.fire('Error', data.message, 'error');
                                    setBtnState(btn, 'reset');
                                }
                            })
                            .catch(() => setBtnState(btn, 'reset'));
                    }

                    /** 5. AJAX DELETE WITH SWEETALERT CONFIRMATION **/
                    function delete_servicing(id) {
                        Swal.fire({
                            title: 'Delete this record?',
                            text: "Invoices will also be permanently removed from server!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Yes, Delete!',
                            cancelButtonText: 'No'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                const btn = document.getElementById('del_btn' + id);
                                setBtnState(btn, 'loading');

                                const form = document.getElementById('deleteForm' + id);
                                const formData = new FormData(form);

                                fetch(form.action, {
                                        method: 'POST',
                                        body: formData,
                                        headers: {
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                        }
                                    })
                                    .then(res => res.json())
                                    .then(data => {
                                        if (data.success) {
                                            Swal.fire('Deleted!', data.message, 'success').then(() => location.reload());
                                        } else {
                                            Swal.fire('Failed', data.message, 'error');
                                            setBtnState(btn, 'reset');
                                        }
                                    })
                                    .catch(() => setBtnState(btn, 'reset'));
                            }
                        });
                    }
                </script>

                {{--  End of manage service recoreds --}}





                <hr class="border-warning">
<section style="border-style: solid;border-color:#ffff00;border-width:2px; border-radius: 10px; padding: 10px;">
                <h6>Additional Info</h6>
                <ul class="mb-0">

                    <!-- textara start here -->
                    <!-- Include Quill styles in your <head> -->
                    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
                    <!-- Include SweetAlert -->
                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

                    <!-- FORM START -->
                    <form method="POST" id="additional_detail_form" action="{{ url('additional_info_car') }}">
                        @csrf
                        <input type="hidden" name="car_id" value="{{ $id }}">
                        <input type="hidden" name="car_features" id="car_features">

                        <!-- Quill Editor -->
                        <div id="editor-container" style="height: 200px; background-color: black;">
                            {!! $car_data->additional_details ?? '' !!}
                        </div>

                        <!-- Submit Button -->
                        <button type="button" onclick="update_additional_details()" class="btn btn-success mt-3">Update
                            it</button>
                    </form>

                    <!-- Include Quill script (should be before init) -->
                    <!-- <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script> -->
                    <script src="{{ asset('backend/js/quill.min.js') }}"></script>

                    <!-- Init Quill Editor -->
                    <script>
                        var quill = new Quill('#editor-container', {
                            theme: 'snow',
                            placeholder: 'Write car features here...',
                            modules: {
                                toolbar: [
                                    [{
                                        'header': [1, 2, false]
                                    }],
                                    ['bold', 'italic', 'underline'],
                                    [{
                                        'list': 'ordered'
                                    }, {
                                        'list': 'bullet'
                                    }],
                                    ['clean']
                                ]
                            }
                        });
                    </script>

                    <!-- Submit via JS + Sync hidden input -->
                    <script>
                        function update_additional_details() {
                            // 🔁 Sync Quill data to hidden input
                            document.getElementById('car_features').value = quill.root.innerHTML;

                            const form = document.getElementById('additional_detail_form');
                            const formData = new FormData(form);

                            fetch(form.action, {
                                    method: 'POST',
                                    body: formData,
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                                    }
                                })
                                .then(res => res.json())
                                .then(data => {
                                    if (data.success) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Updated Successfully!',
                                            showConfirmButton: false,
                                            timer: 1500
                                        }).then(() => {
                                            location.reload();
                                        });
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Update Failed',
                                            text: data.message || 'Something went wrong!',
                                        });
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Request Failed',
                                        text: 'Something went wrong while sending data.',
                                    });
                                });
                        }
                    </script>


                    <!-- end of textarea -->
                </ul>
</section>
            </div>
        </div>
        </div>
        </div>

    </section>

    @push('styles')
        <style>
            body {
                background-color: #0e0e0e;
                color: #e0e0e0;
                font-family: 'Poppins', sans-serif;
            }

            .card {
                border-radius: 15px;
                background-color: #181818;
                border: 1px solid #ffc107;
                padding: 25px;
                box-shadow: 0 0 20px rgba(255, 193, 7, 0.1);
            }

            h4,
            h5,
            h6 {
                color: #ffc107;
                font-weight: 600;
            }

            table {
                color: #e0e0e0;
                font-size: 0.95rem;
            }

            table thead {
                color: #ffc107;
            }

            .feed-item {
                width: 120px;
                height: 80px;
                object-fit: cover;
                border-radius: 8px;
                cursor: pointer;
                transition: 0.25s;
            }

            .feed-item:hover {
                transform: scale(1.05);
                box-shadow: 0 0 8px rgba(255, 193, 7, 0.4);
            }

            .media-feed {
                display: flex;
                flex-wrap: wrap;
                gap: 8px;
                overflow-x: auto;
                max-height: 450px;
            }

            .btn-share {
                font-weight: 600;
                border-radius: 8px;
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 10px 16px;
            }

            .btn-whatsapp {
                background-color: #25D366;
                color: white;
                border: none;
            }

            .btn-email {
                background-color: #ffc107;
                color: #000;
                border: none;
            }

            hr.border-warning {
                opacity: 0.4;
            }

            .info-tag {
                background: rgba(255, 193, 7, 0.15);
                border-left: 4px solid #ffc107;
                padding: 8px 12px;
                border-radius: 6px;
                margin-bottom: 12px;
                font-size: 0.9rem;
            }

            @media (max-width: 768px) {
                .feed-item {
                    width: 100px;
                    height: 70px;
                }

                .card {
                    padding: 15px;
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.querySelectorAll('.feed-item').forEach(item => {
                item.addEventListener('click', () => {
                    if (item.tagName === 'IMG') {
                        let modalHtml = `
                                                                            <div class="modal fade" id="lightboxModal" tabindex="-1">
                                                                              <div class="modal-dialog modal-dialog-centered modal-lg">
                                                                                <div class="modal-content bg-dark text-center p-3">
                                                                                  <img src="${item.src}" class="img-fluid rounded" alt="Car Image">
                                                                                  <button type="button" class="btn btn-warning mt-3" data-bs-dismiss="modal">Close</button>
                                                                                </div>
                                                                              </div>
                                                                            </div>`;
                        document.body.insertAdjacentHTML('beforeend', modalHtml);
                        let lightbox = new bootstrap.Modal(document.getElementById('lightboxModal'));
                        lightbox.show();
                        document.getElementById('lightboxModal').addEventListener('hidden.bs.modal', () => {
                            document.getElementById('lightboxModal').remove();
                        });
                    }
                });
            });
        </script>
    @endpush
@endsection
