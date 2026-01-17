@extends('backend.layouts.main')

@section('main-section')

<section class="dashboard row g-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="text-warning fw-bold">User Documents</h2>
    </div>

    <div class="card bg-dark text-white p-3 shadow-lg">
        <table class="table table-dark table-striped align-middle">
            <thead class="text-warning">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Aadhar</th>
                    <th>PAN</th>
                    <th>Resume</th>
                    <th>Offer Letter</th>
                    <th>Appointment Letter</th>
                    <th>Agreement Letter</th>
                    <th>Bank Passbook</th>
                    <th>Education Certificates</th>
                </tr>
            </thead>

            <tbody>
                @foreach($user_docs as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->email }}</td>

                        @php
                            function docBtn($path){
                                if($path && $path !== "NA"){
                                    return '<a href="'.asset($path).'" target="_blank" class="btn btn-sm btn-success">View</a>';
                                }
                                return '<span class="badge bg-danger">Not Uploaded</span>';
                            }
                        @endphp

                        <td>{!! docBtn($item->aadhar) !!}</td>
                        <td>{!! docBtn($item->pan) !!}</td>
                        <td>{!! docBtn($item->resume) !!}</td>
                        <td>{!! docBtn($item->offer_letter) !!}</td>
                        <td>{!! docBtn($item->appointment_letter) !!}</td>
                        <td>{!! docBtn($item->agreement_letter) !!}</td>
                        <td>{!! docBtn($item->bank_passbook) !!}</td>
                        <td>{!! docBtn($item->education_certificates) !!}</td>

                      
                    </tr>
                @endforeach
            </tbody>

        </table>
    </div>

</section>
@endsection
