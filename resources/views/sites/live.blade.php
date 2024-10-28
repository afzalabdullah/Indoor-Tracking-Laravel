@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">Sites</li>
        </ol>
    </nav>
    <h1 class="text-center">Live Sites</h1>
</div><!-- End Page Title -->

<section class="section">
    <div class="container">
        <div class="row">
            @foreach ($sites as $site)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card shadow border-0 rounded overflow-hidden">
                    <img src="{{ $site->image_url }}" class="card-img-top" alt="{{ $site->name }}">
                    <div class="card-body text-center">
                        <h5 class="card-title">{{ $site->name }}</h5>
                        <a href="{{ route('sites.show', $site->id) }}" class="btn btn-primary">View Site</a> <!-- Updated button text -->
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection

@section('styles')
<style>
    .pagetitle h1 {
        font-size: 2.5rem;
        color: #343a40;
        margin-bottom: 20px;
        font-weight: 700;
    }

    .card {
        border: none;
        border-radius: 10px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        background: #ffffff; /* Add background color */
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); /* Subtle shadow */
    }

    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
    }

    .card-img-top {
        height: 200px;
        object-fit: cover;
        border-bottom: 4px solid #007bff; /* Adding a border at the bottom */
    }

    .card-title {
        font-size: 1.5rem; /* Increased font size */
        font-weight: 600;
        color: #007bff;
    }

    .card-text {
        color: #6c757d; /* Muted text color */
        margin-bottom: 1.5rem;
        font-size: 1rem; /* Font size for description */
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        transition: background-color 0.3s, border-color 0.3s;
        padding: 10px 20px; /* Padding adjustments */
        font-weight: 500; /* Font weight for button */
        text-transform: uppercase; /* Uppercase text */
        letter-spacing: 0.5px; /* Letter spacing */
        border-radius: 5px; /* Rounded corners for button */
        box-shadow: 0 4px 10px rgba(0, 123, 255, 0.2); /* Subtle shadow */
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #0056b3;
        box-shadow: 0 6px 15px rgba(0, 86, 179, 0.2); /* Enhanced shadow on hover */
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .card-img-top {
            height: 150px; /* Smaller image height on mobile */
        }

        .pagetitle h1 {
            font-size: 2rem; /* Responsive title size */
        }
    }

    @media (max-width: 576px) {
        .btn-primary {
            padding: 8px 16px; /* Smaller button padding on mobile */
        }
    }
</style>
@endsection
