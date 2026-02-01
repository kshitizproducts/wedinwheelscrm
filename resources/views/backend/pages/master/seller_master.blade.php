@extends('backend.layouts.main')
@section('main-section')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <section class="dashboard container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4 bg-dark p-3 rounded-3 shadow-sm border-start border-warning border-4">
            <div>
                <h4 class="text-warning fw-bold mb-0">Seller Master</h4>
                <p class="text-white-50 small mb-0">Manage all product sellers and their contact info</p>
            </div>
            <button class="btn btn-warning btn-md text-dark fw-bold rounded-pill shadow-sm px-4" data-bs-toggle="modal"
                data-bs-target="#sellerModal" onclick="resetSellerForm()">
                <i class="fas fa-plus-circle me-2"></i>New Seller
            </button>
        </div>

        <div class="card bg-dark border-0 shadow-lg">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table id="sellerTable" class="table table-dark table-hover align-middle mb-0 custom-table w-100">
                        <thead>
                            <tr class="text-warning border-secondary">
                                <th class="ps-3">#</th>
                                <th>Seller Name</th>
                                <th>Contact No</th>
                                <th>Email</th>
                                <th>Address</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-white">
                            @foreach ($sellers as $key => $seller)
                                <tr>
                                    <td class="text-white-50 small ps-3">{{ $key + 1 }}</td>
                                    <td class="fw-bold text-white">{{ $seller->seller_name }}</td>
                                    <td class="text-warning">{{ $seller->seller_contact }}</td>
                                    <td class="text-white-50">{{ $seller->seller_email ?? 'N/A' }}</td>
                                    <td class="small">{{ Str::limit($seller->seller_address, 30) }}</td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <button class="btn btn-sm btn-action edit-btn"
                                                onclick="editSeller('{{ $seller->id }}', '{{ $seller->seller_name }}', '{{ $seller->seller_contact }}', '{{ $seller->seller_email }}', '{{ $seller->seller_address }}')">
                                                <i class="fas fa-pen"></i>
                                            </button>
                                            <button class="btn btn-sm btn-action delete-btn text-danger"
                                                onclick="deleteSeller('{{ $seller->id }}')">
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

    <div class="modal fade" id="sellerModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div style="background-color:#1e1e1e" class="modal-content text-white border-0 shadow-lg">
                <div class="modal-header border-secondary py-3">
                    <h5 class="modal-title text-warning fw-bold" id="sellerModalTitle">Add New Seller</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <form id="sellerForm" method="post" action="{{ url('store-seller') }}">
                    @csrf
                    <input type="hidden" name="id" id="seller_id">

                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Seller Name <span class="text-warning">*</span></label>
                            <input required type="text" name="seller_name" id="seller_name"
                                class="form-control bg-dark text-white border-secondary shadow-none" placeholder="Enter Full Name">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Contact Number <span class="text-warning">*</span></label>
                            <input required type="text" name="seller_contact" id="seller_contact"
                                class="form-control bg-dark text-white border-secondary shadow-none" placeholder="e.g. +91 9000000000">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email Address</label>
                            <input type="email" name="seller_email" id="seller_email"
                                class="form-control bg-dark text-white border-secondary shadow-none" placeholder="example@mail.com">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Address</label>
                            <textarea name="seller_address" id="seller_address" rows="2"
                                class="form-control bg-dark text-white border-secondary shadow-none" placeholder="Shop address..."></textarea>
                        </div>

                        <div id="seller-progress" class="d-none mt-3">
                            <div class="progress" style="height: 5px; background: #333;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-warning" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-outline-secondary text-white" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" id="seller-save-btn" onclick="save_seller()" class="btn btn-warning fw-bold px-4">SAVE SELLER</button>
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
            $('#sellerTable').DataTable({
                "language": { "search": "", "searchPlaceholder": "Search sellers..." }
            });
        });

        function save_seller() {
            const form = document.getElementById('sellerForm');
            const saveBtn = document.getElementById('seller-save-btn');
            
            if(!document.getElementById('seller_name').value || !document.getElementById('seller_contact').value) {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Please fill all required fields!', background: '#1e1e1e', color: '#fff' });
                return;
            }

            saveBtn.disabled = true;
            document.getElementById('seller-progress').classList.remove('d-none');

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

        function resetSellerForm() {
            document.getElementById('sellerForm').reset();
            document.getElementById('seller_id').value = '';
            document.getElementById('sellerModalTitle').innerText = 'Add New Seller';
        }

        function editSeller(id, name, contact, email, address) {
            resetSellerForm();
            document.getElementById('seller_id').value = id;
            document.getElementById('seller_name').value = name;
            document.getElementById('seller_contact').value = contact;
            document.getElementById('seller_email').value = email;
            document.getElementById('seller_address').value = address;
            document.getElementById('sellerModalTitle').innerText = 'Edit Seller';
            new bootstrap.Modal(document.getElementById('sellerModal')).show();
        }

        function deleteSeller(id) {
            Swal.fire({ 
                title: 'Are you sure?',
                text: "Seller will be permanently removed!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ffc107',
                background: '#1e1e1e', color: '#fff'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.get("{{ url('delete-seller') }}/" + id, function(res) {
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
        .form-control:focus { background-color: #1e1e1e; border-color: #ffc107; color: white; }
    </style>
@endsection