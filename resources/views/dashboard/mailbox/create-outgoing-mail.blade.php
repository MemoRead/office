@extends('dashboard.layouts.main')
@section('title', 'Outgoing Mail Create')

@section('container')
    <div class="pagetitle">
        <h1>Create New Outgoing Mails</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                <li class="breadcrumb-item">Mails</li>
                <li class="breadcrumb-item">Outbox</li>
                <li class="breadcrumb-item active">Create</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="sections">
        <div class="row">
            <div class="col-xxl-4 col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-center">Make an Invitation Letter</h5>

                        <p class="card-text text-center"><button type="button" class="btn btn-primary"
                                data-bs-toggle="modal" data-bs-target="#modalDialogInvitation">Make</button></p>

                    </div>
                </div>
            </div>

            <div class="col-xxl-4 col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-center">Make a Circular Letter</h5>

                        <p class="card-text text-center"><button type="button" class="btn btn-primary"
                                data-bs-toggle="modal" data-bs-target="#modalDialogEdaran">Make</button></p>

                    </div>
                </div>
            </div>

            <div class="col-xxl-4 col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-center">Make Another Letter</h5>

                        <p class="card-text text-center"><button type="button" class="btn btn-primary"
                                data-bs-toggle="modal" data-bs-target="#modalDialogAnother">Make</button></p>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Include Modal -->
    @include('dashboard.partials.modal')
@endsection
