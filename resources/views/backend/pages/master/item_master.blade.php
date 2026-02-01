@extends('backend.layouts.main')
@section('main-section')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <section class="dashboard container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4 bg-dark p-3 rounded-3 shadow-sm border-start border-warning border-4">
            <div>
                <h4 class="text-warning fw-bold mb-0">Product Item Master</h4>
                <p class="text-white-50 small mb-0">Manage specific products and their codes</p>
            </div>
            <button class="btn btn-warning btn-md text-dark fw-bold rounded-pill shadow-sm px-4" data-bs-toggle="modal"
                data-bs-target="#itemModal" onclick="resetItemForm()">
                <i class="fas fa-plus-circle me-2"></i>New Item
            </button>
        </div>

        <div class="card bg-dark border-0 shadow-lg">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table id="itemTable" class="table table-dark table-hover align-middle mb-0 custom-table w-100">
                        <thead>
                            <tr class="text-warning border-secondary">
                                <th class="ps-3">#</th>
                                <th>Category</th>
                                <th>Item Name</th>
                                <th class="text-center">Item Code</th>
                                <th>Created Date</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-white">
                            @foreach ($items as $key => $item)
                                <tr>
                                    <td class="text-white-50 small ps-3">{{ $key + 1 }}</td>
                                    <td><span class="badge bg-secondary">{{ $item->category_name }}</span></td>
                                    <td class="fw-bold">{{ $item->item_name }}</td>
                                    <td class="text-center text-warning">{{ $item->item_code }}</td>
                                    <td class="text-white-50 small">{{ date('d-m-Y', strtotime($item->created_at)) }}</td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <button class="btn btn-sm btn-action edit-btn"
                                                onclick="editItem('{{ $item->id }}', '{{ $item->category_id }}', '{{ $item->item_name }}', '{{ $item->item_code }}')">
                                                <i class="fas fa-pen"></i>
                                            </button>
                                            <button class="btn btn-sm btn-action delete-btn text-danger"
                                                onclick="deleteItem('{{ $item->id }}')">
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

    <div class="modal fade" id="itemModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div style="background-color:#1e1e1e" class="modal-content text-white border-0 shadow-lg">
                <div class="modal-header border-secondary py-3">
                    <h5 class="modal-title text-warning fw-bold" id="itemModalTitle">Add New Item</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <form id="itemForm" method="post" action="{{ url('store-item') }}">
                    @csrf
                    <input type="hidden" name="id" id="item_id">

                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Select Category <span class="text-warning">*</span></label>
                            <select required name="category_id" id="category_id" class="form-select bg-dark text-white border-secondary shadow-none">
                                <option value="">-- Choose Category --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Item Name <span class="text-warning">*</span></label>
                            <input required type="text" name="item_name" id="item_name"
                                class="form-control bg-dark text-white border-secondary shadow-none" placeholder="e.g. Sony Bravia 55 inch">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Item Code <span class="text-warning">*</span></label>
                            <input required type="text" name="item_code" id="item_code"
                                class="form-control bg-dark text-white border-secondary shadow-none" placeholder="e.g. SNY-TV-001">
                        </div>

                        <div id="item-progress" class="d-none mt-3">
                            <div class="progress" style="height: 5px; background: #333;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-warning" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-outline-secondary text-white" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" id="item-save-btn" onclick="save_item()" class="btn btn-warning fw-bold px-4">SAVE ITEM</button>
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
            $('#itemTable').DataTable();
        });

        function save_item() {
            const form = document.getElementById('itemForm');
            const saveBtn = document.getElementById('item-save-btn');
            
            if(!document.getElementById('item_name').value || !document.getElementById('category_id').value) {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Please fill all required fields!', background: '#1e1e1e', color: '#fff' });
                return;
            }

            saveBtn.disabled = true;
            document.getElementById('item-progress').classList.remove('d-none');

            fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({ icon: 'success', title: 'Success', text: data.message, background: '#1e1e1e', color: '#fff', timer: 1500 }).then(() => location.reload());
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: data.message, background: '#1e1e1e', color: '#fff' });
                    saveBtn.disabled = false;
                }
            });
        }

        function resetItemForm() {
            document.getElementById('itemForm').reset();
            document.getElementById('item_id').value = '';
            document.getElementById('itemModalTitle').innerText = 'Add New Item';
        }

        function editItem(id, cat_id, name, code) {
            resetItemForm();
            document.getElementById('item_id').value = id;
            document.getElementById('category_id').value = cat_id;
            document.getElementById('item_name').value = name;
            document.getElementById('item_code').value = code;
            document.getElementById('itemModalTitle').innerText = 'Edit Item';
            new bootstrap.Modal(document.getElementById('itemModal')).show();
        }

        function deleteItem(id) {
            Swal.fire({
                title: 'Are you sure?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ffc107',
                background: '#1e1e1e', color: '#fff'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.get("{{ url('delete-item') }}/" + id, function(res) {
                        location.reload();
                    });
                }
            });
        }
    </script>

    <style>
        .btn-action { background: #2b2b2b; color: #ffc107; border: 1px solid #444; width: 35px; height: 35px; border-radius: 8px; transition: 0.3s; }
        .edit-btn:hover { background: #ffc107; color: #000; }
        .delete-btn:hover { background: #dc3545; color: #fff; }
        .form-control:focus, .form-select:focus { background-color: #1e1e1e; border-color: #ffc107; color: white; }
    </style>
@endsection