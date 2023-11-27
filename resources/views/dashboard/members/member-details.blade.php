@extends('dashboard.layouts.main')
@section('title', 'Member Details')

@section('container')
    <div class="pagetitle">
        <h1>Member Details</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                <li class="breadcrumb-item">Members</li>
                <li class="breadcrumb-item active">Details</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section profile">
        <div class="row">

            <div class="col-lg-8">

                <div class="card">
                    <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                        @if ($member->photo)
                            <img src="/storage/{{ $member->photo }}" alt="Profile" class="rounded-circle">
                        @else
                            <img src="/storage/photos/blank.jpg" alt="Profile" class="rounded-circle">
                        @endif
                        <h2>{{ $member->name }}</h2>
                        <h3>{{ $member->expertise }}</h3>
                    </div>
                </div>

            </div>

            <div class="col-lg-8">

                <div class="card">
                    <div class="card-body pt-3">
                        <!-- Bordered Tabs -->
                        <ul class="nav nav-tabs nav-tabs-bordered justify-content-center">

                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="tab"
                                    data-bs-target="#profile-overview">Overview</button>
                            </li>

                        </ul>
                        <div class="tab-content pt-2">

                            <div class="tab-pane fade show active profile-overview" id="profile-overview">

                                <h5 class="card-title">Profile Details</h5>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label ">Full Name</div>
                                    <div class="col-lg-9 col-md-8">{{ $member->name }}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Number ID</div>
                                    <div class="col-lg-9 col-md-8">{{ $member->nik }}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Birth Date</div>
                                    <div class="col-lg-9 col-md-8">
                                        {{ \Carbon\Carbon::parse($member->birth_date)->format('F j, Y') }}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Gender</div>
                                    <div class="col-lg-9 col-md-8">{{ $member->gender }}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Address</div>
                                    <div class="col-lg-9 col-md-8">{{ $member->address }}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Expertise</div>
                                    <div class="col-lg-9 col-md-8">{{ $member->expertise }}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Email</div>
                                    <div class="col-lg-9 col-md-8">{{ $member->email }}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Phone</div>
                                    <div class="col-lg-9 col-md-8">{{ $member->phone }}</div>
                                </div>

                                <div class="text-center">
                                    <a href="/dashboard/members" class="btn btn-secondary">Back</a>
                                </div>

                            </div>

                        </div><!-- End Bordered Tabs -->

                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
