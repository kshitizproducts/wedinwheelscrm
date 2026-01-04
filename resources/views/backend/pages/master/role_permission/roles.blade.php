@extends('backend.layouts.main')
@section('main-section')

  <section class="dashboard row g-4">

    <!-- Topbar -->
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2 class="text-warning fw-bold">Roles & Permissions</h2>
      <button class="btn btn-warning text-dark" data-bs-toggle="modal" data-bs-target="#addRoleModal">+ Add Role</button>
    </div>

    <!-- Roles Table -->
    <div class="col-12">
      <div class="card bg-dark text-white p-3 shadow-lg">
        <h5 class="mb-3 text-warning">All Roles</h5>
        <div class="table-responsive">
          <table id="rolesTable" class="table table-dark table-striped table-hover align-middle">
            <thead class="text-warning">
              <tr>
                <th>#</th>
                <th>Role Name</th>
                <th>Permissions</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @php
                $sl = 1;
              @endphp
              @foreach($roles as $item)
                @php
                  $permisssions = DB::table('role_has_permissions')->where('role_id', $item->id)->get();
                @endphp
                <tr>
                  <td>{{ $sl++ }}</td>
                  <td>{{$item->name}}</td>
                  <td>
                    @foreach($permisssions as $items)
                      @php
                        $permission_name = DB::table('permissions')->where('id', $items->permission_id)->first();
                      @endphp
                      <span class="badge bg-primary">{{ $permission_name->name }}</span>
                    @endforeach
                  </td>
                  <td>
                    <!-- <button class="btn btn-sm btn-info edit-role-btn" data-role="Admin"
                          data-permissions="all_permissions">Edit</button> -->

                        <!-- Edit button -->
                        <button class="btn btn-warning text-dark" data-bs-toggle="modal"
                                  data-bs-target="#EditModal{{ $item->id }}">
                            Edit
                          </button>

                          <!-- Edit Modal -->
                          <div class="modal fade" id="EditModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                              <div class="modal-content bg-dark text-white">
                                <div class="modal-header border-0">
                                  <h5 class="modal-title text-warning">Edit Role</h5>
                                  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                          aria-label="Close"></button>
                                </div>

                                <div class="modal-body">
                                  <form id="editRoleForm{{ $item->id }}" method="POST"
                                        action="{{ url('update_role/'.$item->id) }}">
                                    @csrf
                                    @method('PUT')

                                    <!-- Role Name -->
                                    <div class="mb-3">
                                      <label class="form-label">Role Name</label>
                                      <input type="text" class="form-control bg-secondary text-white border-0"
                                            name="role_name" value="{{ $item->name }}" placeholder="Enter role name">
                                    </div>

                                    <!-- Permissions -->
                                    <div class="mb-3">
                                      <label class="form-label">Permissions</label>
                                      <div class="row g-4">
                                        @foreach($groupedPermissions as $groupName => $group)
                                          <div class="col-12 col-md-6 col-lg-4">
                                            <div class="card shadow-sm border-0 h-100 bg-secondary">
                                              <div class="card-body">
                                                <h5 class="card-title text-capitalize mb-3 text-warning border-bottom pb-2">
                                                  {{ $groupName }} Permissions
                                                </h5>

                                                @foreach($group as $permission)
                                                  <div class="form-check mb-2">
                                                    <input class="form-check-input" type="checkbox"
                                                          name="permissions[]" value="{{ $permission->id }}"
                                                          id="perm{{ $item->id }}_{{ $permission->id }}"
                                                          {{ in_array($permission->id, $rolePermissions[$item->id] ?? []) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="perm{{ $item->id }}_{{ $permission->id }}">
                                                      {{ ucfirst(explode(' ', $permission->name)[0]) }}
                                                    </label>
                                                  </div>
                                                @endforeach

                                              </div>
                                            </div>
                                          </div>
                                        @endforeach
                                      </div>
                                    </div>

                                    <div class="text-end">
                                      <button type="button" class="btn btn-warning text-dark"
                                              onclick="updateRole({{ $item->id }})">
                                        Save Changes
                                      </button>
                                    </div>
                                  </form>
                                </div>
                              </div>
                            </div>
                          </div>


                          <script>
function updateRole(roleId) {
    let form = $('#editRoleForm' + roleId);
    let url = form.attr('action');
    let data = form.serialize();

    $.ajax({
        url: url,
        type: 'POST',
        data: data,
        beforeSend: function() {
            form.find('button').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
        },
        success: function(response) {
            form.find('button').prop('disabled', false).text('Save Changes');
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: response.message,
                    confirmButtonColor: '#ffc107'
                }).then(() => location.reload());
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: response.message });
            }
        },
        error: function(xhr) {
            form.find('button').prop('disabled', false).text('Save Changes');
            if (xhr.status === 422) {
                let errors = xhr.responseJSON.errors;
                let msg = Object.values(errors).flat().join('\n');
                Swal.fire({ icon: 'error', title: 'Validation Error', text: msg });
            } else {
                Swal.fire({ icon: 'error', title: 'Server Error', text: 'Something went wrong!' });
            }
        }
    });
}
</script>


                            <!-- end of edit modal -->
                          <!-- delete modal starts here -->
                                      <form id="deleteform{{ $item->id }}" method="POST"
                                        action="{{ url('delete_role/'.$item->id) }}">
                                    @csrf
                                    @method('DELETE')

                                    <!-- Role Name -->
                                   
                                      <button type="button" class="btn btn-danger text-dark"
                                              onclick="deleterole({{ $item->id }})">
                                        Delete
                                      </button>
                                  </form>

                                <script>
function deleterole(roleId) {
    let form = $('#deleteform' + roleId);
    let url = form.attr('action');
    let data = form.serialize();

    $.ajax({
        url: url,
        type: 'POST',
        data: data,
        beforeSend: function() {
            form.find('button').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
        },
        success: function(response) {
            form.find('button').prop('disabled', false).text('Save Changes');
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: response.message,
                    confirmButtonColor: '#ffc107'
                }).then(() => location.reload());
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: response.message });
            }
        },
        error: function(xhr) {
            form.find('button').prop('disabled', false).text('Save Changes');
            if (xhr.status === 422) {
                let errors = xhr.responseJSON.errors;
                let msg = Object.values(errors).flat().join('\n');
                Swal.fire({ icon: 'error', title: 'Validation Error', text: msg });
            } else {
                Swal.fire({ icon: 'error', title: 'Server Error', text: 'Something went wrong!' });
            }
        }
    });
}
</script>

                          <!-- end of delete modal -->
                  </td>
                </tr>

              @endforeach

            </tbody>
          </table>
        </div>
      </div>
    </div>

  </section>

  <!-- Add Role Modal -->
  <div class="modal fade" id="addRoleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content bg-dark text-white">
        <div class="modal-header border-0">
          <h5 class="modal-title text-warning">Add Role</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <meta name="csrf-token" content="{{ csrf_token() }}">

          <form id="addRoleForm" method="POST" action="{{ url('add_new_role') }}">
            @csrf
            <div class="mb-3">
              <label class="form-label">Role Name</label>
              <input type="text" class="form-control bg-secondary text-white border-0" name="role_name" id="roleName"
                placeholder="Enter role name">
            </div>

            <div class="mb-3">
              <label class="form-label">Permissions</label>
              <div class="row">
                @php
                  $groupedPermissions = $permissions->groupBy(function ($perm) {
                    return explode(' ', $perm->name)[1] ?? 'Other';
                  });
                @endphp

                <div class="row g-4">
                  @foreach($groupedPermissions as $groupName => $group)
                    <div class="col-12 col-md-6 col-lg-4">
                      <div class="card shadow-sm border-0 h-100 bg-secondary">
                        <div class="card-body">
                          <h5 class="card-title text-capitalize mb-3 text-warning border-bottom pb-2">
                            {{ $groupName }} Permissions
                          </h5>
                          @foreach($group as $permission)
                            <div class="form-check mb-2">
                              <input class="form-check-input permission-checkbox" type="checkbox"
                                value="{{ $permission->id }}" name="permissions[]" id="perm{{ $permission->id }}">
                              <label class="form-check-label" for="perm{{ $permission->id }}">
                                {{ ucfirst(explode(' ', $permission->name)[0]) }}
                              </label>
                            </div>
                          @endforeach
                        </div>
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>
            </div>

            <div class="text-end">
              <button type="button" class="btn btn-warning text-dark" onclick="addnew_role()">
                Save Role
              </button>
            </div>
          </form>

          <script src="{{ asset('backend/js/sweetalert2.min.js') }}"></script>
          <script>
            function addnew_role() {
              const form = document.getElementById('addRoleForm');
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
  </div>

@endsection

@section('scripts')
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


  <script src="{{ asset('backend/js/bootstrap.bundle.min.js') }}"></script>
  <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script> -->


  <style>
    body {
      background-color: #121212;
    }

    .card {
      border-radius: 15px;
      background-color: #1e1e1e;
      border: 1px solid #333;
    }

    .btn-warning {
      background-color: #ffc107;
      border: none;
    }

    .btn-warning:hover {
      background-color: #ffca2c;
    }

    .table-hover tbody tr:hover {
      background-color: #2b2b2b;
    }

    .form-control:focus {
      box-shadow: none;
    }
  </style>
@endsection