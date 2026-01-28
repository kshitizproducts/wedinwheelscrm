@extends('backend.layouts.main')

@section('main-section')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-warning fw-bold">Employee Documentation</h2>
    </div>

    <div class="card bg-dark text-white border-0 shadow-lg">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0 align-middle">
                    <thead class="bg-secondary text-warning">
                        <tr>
                            <th class="ps-3">#</th>
                            <th>Employee Name</th>
                            <th>Email Address</th>
                            <th class="text-center">Status</th>
                            <th class="text-end pe-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($user_docs as $item)
                        <tr>
                            <td class="ps-3">{{ $loop->iteration }}</td>
                            <td class="fw-bold">{{ $item->name }}</td>
                            <td>{{ $item->email }}</td>
                            <td class="text-center">
                                <span class="badge bg-success">Documents Verified</span>
                            </td>
                            <td class="text-end pe-3">
                                <a href="{{ url('employee-docs/print/'.$item->id) }}" target="_blank" class="btn btn-sm btn-outline-warning">
                                    <i class="fas fa-print me-1"></i> Print Dossier
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection