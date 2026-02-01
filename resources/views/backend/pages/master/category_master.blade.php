@extends('backend.layouts.main')
@section('main-section')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <section class="dashboard container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4 bg-dark p-3 rounded-3 shadow-sm border-start border-warning border-4">
            <div>
                <h4 class="text-warning fw-bold mb-0">Product Category Master</h4>
                <p class="text-white-50 small mb-0">Manage inventory categories and classifications</p>
            </div>
            <button class="btn btn-warning btn-md text-dark fw-bold rounded-pill shadow-sm px-4" data-bs-toggle="modal"
                data-bs-target="#categoryModal" onclick="resetCategoryForm()">
                <i class="fas fa-plus-circle me-2"></i>New Category
            </button>
        </div>
 
        <div class="card bg-dark border-0 shadow-lg">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table id="categoryTable" class="table table-dark table-hover align-middle mb-0 custom-table w-100">
                        <thead>
                            <tr class="text-warning border-secondary">
                                <th class="ps-3">#</th>
                                <th>Category Name</th>
                                <th class="text-center">Category Code</th>
                                <th>Created Date</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-white">
                            @foreach ($categories as $key => $item)
                                <tr>
                                    <td class="text-white-50 small ps-3">{{ $key + 1 }}</td>
                                    <td class="fw-bold">{{ $item->name }}</td>
                                    <td class="text-center">{{ $item->code }}</td>
                                    <td class="text-white-50 small">{{ date('d-m-Y', strtotime($item->created_at)) }}</td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <button class="btn btn-sm btn-action edit-btn"
                                                onclick="editCategory('{{ $item->id }}', '{{ $item->name }}')">
                                                <i class="fas fa-pen"></i>
                                            </button>
                                            <button class="btn btn-sm btn-action delete-btn text-danger"
                                                onclick="deleteCategory('{{ $item->id }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="categoryModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div style="background-color:#1e1e1e" class="modal-content text-white border-0 shadow-lg">
                <div class="modal-header border-secondary py-3">
                    <h5 class="modal-title text-warning fw-bold" id="catModalTitle">Add New Category</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="categoryForm" method="post" action="{{ url('store-category') }}">
                    @csrf
                    <input type="hidden" name="id" id="cat_id">

                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Category Name <span class="text-warning">*</span></label>
                            <input required type="text" name="name" id="cat_name"
                                class="form-control bg-dark text-white border-secondary shadow-none"
                                placeholder="e.g. Electronics, Furniture">
                        </div>

                          <div class="mb-3">
                            <label class="form-label fw-semibold">Category Code <span class="text-warning">*</span></label>
                            <input required type="text" name="code" id="cat_code"
                                class="form-control bg-dark text-white border-secondary shadow-none"
                                placeholder="e.g. ELEC, FURN">
                        </div>

                        <div id="cat-progress" class="d-none mt-3">
                            <div class="progress" style="height: 5px; background: #333;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-warning" style="width: 100%"></div>
                            </div>
                            <small class="text-warning d-block text-center mt-1">Saving category...</small>
                        </div>
                    </div>

                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-outline-secondary text-white" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" id="cat-save-btn" onclick="save_category()" class="btn btn-warning fw-bold px-4">SAVE CATEGORY</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $('#categoryTable').DataTable({
                "pageLength": 10,
                "language": {
                    "search": "",
                    "searchPlaceholder": "Search categories...",
                    "paginate": { "previous": "<", "next": ">" }
                }
            });
        });

        // Global Save Function
        function save_category() {
            const form = document.getElementById('categoryForm');
            const saveBtn = document.getElementById('cat-save-btn');
            const progress = document.getElementById('cat-progress');
            const catName = document.getElementById('cat_name').value;
            
            if(!catName) {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Category name is required!', background: '#1e1e1e', color: '#fff' });
                return;
            }

            saveBtn.disabled = true;
            progress.classList.remove('d-none');

            const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                saveBtn.disabled = false;
                progress.classList.add('d-none');

                if (data.success) {
                    Swal.fire({
                        icon: 'success', title: 'Success!', text: data.message,
                        background: '#1e1e1e', color: '#fff', showConfirmButton: false, timer: 1500
                    }).then(() => location.reload());
                } else {
                    Swal.fire({ icon: 'error', title: 'Oops...', text: data.message, background: '#1e1e1e', color: '#fff' });
                }
            })
            .catch(() => {
                saveBtn.disabled = false;
                progress.classList.add('d-none');
                Swal.fire('Error', 'Server connection error', 'error');
            });
        }

        function resetCategoryForm() {
            const form = document.getElementById('categoryForm');
            if(form) {
                form.reset();
                document.getElementById('cat_id').value = '';
                document.getElementById('catModalTitle').innerText = 'Add New Category';
                document.getElementById('cat-progress').classList.add('d-none');
                document.getElementById('cat-save-btn').disabled = false;
            }
        }

        function editCategory(id, name) {
            resetCategoryForm();
            document.getElementById('cat_id').value = id;
            document.getElementById('cat_name').value = name;
            document.getElementById('catModalTitle').innerText = 'Edit Category';
            
            var myModal = new bootstrap.Modal(document.getElementById('categoryModal'));
            myModal.show();
        }

        function deleteCategory(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Deleting this category might affect products linked to it!",
                icon: 'warning',
                showCancelButton: true,
                background: '#1e1e1e', color: '#fff',
                confirmButtonColor: '#ffc107', cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('delete-category') }}/" + id,
                        type: "GET",
                        success: function(res) {
                            Swal.fire({
                                icon: 'success', title: 'Deleted!', text: res.message,
                                background: '#1e1e1e', color: '#fff', timer: 1000, showConfirmButton: false
                            });
                            setTimeout(() => location.reload(), 1000);
                        }
                    });
                }
            });
        }
    </script>

    <style>
        .btn-action { background: #2b2b2b; color: #ffc107; border: 1px solid #444; width: 35px; height: 35px; border-radius: 8px; transition: 0.3s; }
        .edit-btn:hover { background: #ffc107; color: #000; border-color: #ffc107; }
        .delete-btn:hover { background: #dc3545; color: #fff; border-color: #dc3545; }
        .dataTables_filter input { background: #1e1e1e !important; color: #fff !important; border: 1px solid #333 !important; border-radius: 20px !important; padding: 5px 15px !important; }
        .form-control:focus { background-color: #1e1e1e; border-color: #ffc107; color: white; }
    </style>
@endsection

