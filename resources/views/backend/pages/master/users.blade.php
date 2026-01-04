@extends('backend.layouts.main')
@section('main-section')
    <!-- Page Content -->
    <section class="dashboard row g-4">

        <!-- Topbar -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="text-warning fw-bold">User Management</h2>
            <button class="btn btn-warning text-dark fw-semibold" data-bs-toggle="modal" data-bs-target="#userModal">
                + Add New User
            </button>
        </div>

        <!-- Users Table -->
        <div class="col-12">
            <div class="card bg-dark text-white p-3">
                <h5 class="mb-3 text-warning">Users List</h5>
                <div class="table-responsive">
                    <table id="userTable" class="table table-dark table-striped table-hover align-middle">
                        <thead class="text-warning">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->role_name }}</td>
                                    {{-- ✅ Status --}}
                                    <td>
                                        <span class="badge {{ $user->status == 'Active' ? 'bg-success' : 'bg-danger' }}">
                                            {{ $user->status ?? 'NA' }}
                                        </span>
                                    </td>

                                    <td>



                                        <button class="btn btn-sm btn-warning text-dark" data-bs-toggle="modal"
                                            data-bs-target="#editmodal{{ $user->id }}">Edit</button>
                                        <form action="{{ url('delete_user', $user->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Are you sure you want to delete this user?')">
                                                Delete
                                            </button>
                                        </form>

                                    </td>
                                </tr>





                                <!-- #edit modal-->
                                <!-- #edit modal-->
                                <div class="modal fade" id="editmodal{{ $user->id }}" tabindex="-1"
                                    aria-labelledby="editmodal{{ $user->id }}Label" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content bg-dark text-white border border-warning">
                                            <div class="modal-header border-warning">
                                                <h5 class="modal-title text-warning"
                                                    id="editmodal{{ $user->id }}Label">Edit User</h5>
                                                <button type="button" class="btn-close btn-close-white"
                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>

                                            <form id="edituserform{{ $user->id }}" class="p-3" method="post"
                                                action="{{ url('update_user', $user->id) }}">
                                                @csrf
                                                @method('PUT')

                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label text-warning">Name</label>
                                                        <input type="text"
                                                            class="form-control bg-secondary text-white border-0"
                                                            value="{{ $user->name }}" name="name" required>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label text-warning">Email</label>
                                                        <input type="email"
                                                            class="form-control bg-secondary text-white border-0"
                                                            value="{{ $user->email }}" name="email" required>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label text-warning">Role</label>
                                                        <select name="role"
                                                            class="form-select bg-secondary text-white border-0" required>
                                                            @foreach ($roles as $role)
                                                                <option value="{{ $role->id }}"
                                                                    {{ $user->role_name == $role->name ? 'selected' : '' }}>
                                                                    {{ $role->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label text-warning">Status</label>
                                                        <select name="status"
                                                            class="form-select bg-secondary text-white border-0">
                                                            <option value="Active"
                                                                {{ $user->status == 'Active' ? 'selected' : '' }}>Active
                                                            </option>
                                                            <option value="Inactive"
                                                                {{ $user->status == 'Inactive' ? 'selected' : '' }}>
                                                                Inactive</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="modal-footer border-warning">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit"
                                                        class="btn btn-warning text-dark fw-semibold">Update User</button>
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>





                                <!-- #delete modal-->
                            @endforeach


                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </section>


    <!-- Add User Modal -->
    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-white border border-warning">
                <div class="modal-header border-warning">
                    <h5 class="modal-title text-warning" id="userModalLabel">Add New User</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
<form id="add_new_user_form" class="p-3" method="post" action="{{ url('addnew_user') }}">
    @csrf
    <div class="modal-body">
        <div class="mb-3">
            <label class="form-label text-warning">Name</label>
            <input type="text" class="form-control bg-secondary text-white border-0" name="name"
                placeholder="Enter User Name" required>
        </div>

        <div class="mb-3">
            <label class="form-label text-warning">Email</label>
            <input type="email" class="form-control bg-secondary text-white border-0" name="email"
                placeholder="Enter Email" required>
        </div>

        <div class="mb-3">
            <label class="form-label text-warning">Password</label>
            <input type="password" class="form-control bg-secondary text-white border-0" name="password"
                placeholder="Enter Password" required>
        </div>

        <div class="mb-3">
            <label class="form-label text-warning">Role</label>
            <select name="role" class="form-select bg-secondary text-white border-0" required>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label text-warning">Status</label>
            <select name="status" class="form-select bg-secondary text-white border-0">
                <option>Active</option>
                <option>Inactive</option>
            </select>
        </div>
    </div>

    <div class="modal-footer border-warning">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" onclick="add_new_user_func()" class="btn btn-warning text-dark fw-semibold">Save User</button>
    </div>
</form>


<script src="{{ asset('backend/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('backend/js/sweetalert2.min.js') }}"></script>
<script>
function add_new_user_func() {
    const form = document.getElementById('add_new_user_form');
    const formData = new FormData(form);

    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log('Response:', data);

        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'User Added Successfully!',
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                location.reload();
            });
        } else {
            // ✅ Show actual error (validation or exception)
            let errorMessage = data.message || 'Something went wrong!';

            // 🔹 If Laravel validation errors exist
            if (data.errors) {
                errorMessage = Object.values(data.errors)
                    .flat()
                    .join('<br>');
            }

            Swal.fire({
                icon: 'error',
                title: 'Add Failed',
                html: errorMessage,
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Request Error',
            text: error.message || 'Something went wrong while submitting the form!',
        });
    });
}
</script>



            </div>
        </div>
    </div>
@endsection

@section('scripts')
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

        .modal-content {
            border-radius: 10px;
        }

        table.dataTable tbody tr:hover {
            background-color: #2b2b2b;
        }
    </style>
@endsection
