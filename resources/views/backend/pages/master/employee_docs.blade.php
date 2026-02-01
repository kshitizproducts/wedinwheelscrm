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
                                <th>Photo</th>
                                <th>Employee Name</th>
                                <th>Email Address</th>
                                <th class="text-center">Status</th>
                                <th class="text-end pe-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($user_docs as $item)
                                <tr>
                                    <td class="ps-3">{{ $loop->iteration }}</td>
                                    <td>


                                        @php
                                            $user_d = DB::table('users')->where('id', $item->user_id)->first();
                                        @endphp
                                        @if ($user_d->profile_photo && $user_d->profile_photo != 'NA')
                                            <img src="{{ asset($user_d->profile_photo) }}" alt="User Photo"
                                                class="rounded-circle border border-warning shadow-sm"
                                                style="width: 40px; height: 40px; object-fit: cover;"
                                                onerror="this.src='https://i.pravatar.cc/40';">
                                        @else
                                            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center border border-warning shadow-sm"
                                                style="width: 40px; height: 40px;">
                                                <i class="fas fa-user text-white-50" style="font-size: 1rem;"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="fw-bold">{{ $item->name }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-success">Documents Verified</span>
                                    </td>
                                    <td class="text-end pe-3">
                                        <a href="{{ url('employee-docs/print/' . $item->id) }}" target="_blank"
                                            class="btn btn-sm btn-outline-warning">
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
