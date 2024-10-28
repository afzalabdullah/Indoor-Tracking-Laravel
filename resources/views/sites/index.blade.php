@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <h1>Sites</h1>
    <nav>
        <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Sites</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Site</h5>
                    <a href="{{ route('sites.create') }}" class="btn btn-primary btn-custom">Create Site</a>
                </div>
                <!-- Table with stripped rows -->
                <table class="table datatable" id="sites_Table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sites as $site)
                            <tr>
                                <td>{{ $site->name }}</td>
                                <td>{{ $site->description }}</td>
                                <td>
                                    <a href="{{ route('sites.edit', $site->id) }}" class="action-btn btn btn-warning mr-2 text-white">
                                        <i class="bi bi-pencil-square"></i> <span>Edit</span>
                                    </a>
                                    <form action="{{ route('sites.destroy', $site->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn btn btn-danger" onclick="return confirm('Are you sure you want to delete this site?')">
                                            <i class="bi bi-trash"></i> <span>Delete</span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

@push('script')
    @if(session()->has('success'))
        <script>
            swal("Success!", "{{ session('success') }}", "success");
        </script>
    @endif
@endpush

@push('styles')
<style>
    body {
        background-color: #f0f4f8; /* Light background color for the page */
    }

    .table {
        margin-top: 20px;
    }

    .btn {
        font-weight: bold; /* Make button text bold */
        padding: 12px 15px; /* Add padding for larger buttons */
        border-radius: 0.5rem; /* Rounded button corners */
    }

    .btn-info {
        background-color: #17a2b8; /* Bootstrap info color */
        border-color: #17a2b8; /* Same as background */
    }

    .btn-warning {
        background-color: #ffc107; /* Bootstrap warning color */
        border-color: #ffc107; /* Same as background */
    }

    .btn-danger {
        background-color: #dc3545; /* Bootstrap danger color */
        border-color: #dc3545; /* Same as background */
    }

    .btn-info:hover,
    .btn-warning:hover,
    .btn-danger:hover {
        opacity: 0.9; /* Slight opacity change on hover */
    }
</style>
@endpush

@endsection
