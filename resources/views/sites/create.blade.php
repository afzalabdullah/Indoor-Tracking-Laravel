@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <h1>Create New Site</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('sites.index') }}">Sites</a></li>
            <li class="breadcrumb-item active">Create Site</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Site Information</h5>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('sites.store') }}" method="POST" enctype="multipart/form-data" class="row g-3">
                        @csrf
                        <div class="col-12">
                            <label for="name" class="form-label">Site Name</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Enter site name" required>
                        </div>
                        <div class="col-12">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control" placeholder="Enter site description" required></textarea>
                        </div>

                        <div id="anchors-container" class="col-12">
                            <h5 class="my-3">Anchors</h5>
                            <div class="anchor mb-3 p-3 border rounded bg-light">
                                <div class="form-group">
                                    <label for="anchors[0][uid]" class="form-label">UID</label>
                                    <input type="text" name="anchors[0][uid]" class="form-control" placeholder="Enter UID" required>
                                </div>
                                <div class="form-group">
                                    <label for="anchors[0][x]" class="form-label">X Value</label>
                                    <input type="number" name="anchors[0][x]" class="form-control" step="0.0001" placeholder="Enter X value" required>
                                </div>
                                <div class="form-group">
                                    <label for="anchors[0][y]" class="form-label">Y Value</label>
                                    <input type="number" name="anchors[0][y]" class="form-control" step="0.0001" placeholder="Enter Y value" required>
                                </div>
                                <button type="button" class="btn btn-danger remove-anchor">Remove Anchor</button>
                            </div>
                            <button type="button" id="add-anchor" class="btn btn-outline-primary mb-3">Add Another Anchor</button>
                        </div>

                        <div id="assets-container" class="col-12 my-4">
                            <h5>Devices (Assets)</h5>
                            <div class="asset mb-3 p-3 border rounded bg-light">
                                <div class="form-group">
                                    <label for="assets[0][device_uid]" class="form-label">Device UID</label>
                                    <input type="text" name="assets[0][device_uid]" class="form-control" placeholder="Enter Device UID" required>
                                </div>
                                <div class="form-group">
                                    <label for="assets[0][device_icon]" class="form-label">Device Icon Class <small>(FontAwesome class, e.g., "fas fa-lightbulb")</small></label>
                                    <input type="text" name="assets[0][device_icon]" class="form-control" placeholder="Enter Device Icon Class" required>
                                </div>
                                <div class="form-group">
                                    <label for="assets[0][device_name]" class="form-label">Device Name</label>
                                    <input type="text" name="assets[0][device_name]" class="form-control" placeholder="Enter Device Name" required>
                                </div>
                                <button type="button" class="btn btn-danger remove-asset">Remove Device</button>
                            </div>
                            <button type="button" id="add-asset" class="btn btn-outline-primary mb-3">Add Another Device</button>
                        </div>

                        <div class="col-12">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" name="image" id="image" class="form-control" required>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-success">Create Site</button>
                            <button type="reset" class="btn btn-secondary">Reset</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    let anchorIndex = 1;
    let assetIndex = 1;

    document.getElementById('add-anchor').addEventListener('click', function() {
        const newAnchor = `
            <div class="anchor mb-3 p-3 border rounded bg-light" style="margin-top: 20px;">
                <div class="form-group">
                    <label for="anchors[${anchorIndex}][uid]" class="form-label">UID</label>
                    <input type="text" name="anchors[${anchorIndex}][uid]" class="form-control" placeholder="Enter UID" required>
                </div>
                <div class="form-group">
                    <label for="anchors[${anchorIndex}][x]" class="form-label">X Value</label>
                    <input type="number" name="anchors[${anchorIndex}][x]" class="form-control" step="0.0001" placeholder="Enter X value" required>
                </div>
                <div class="form-group">
                    <label for="anchors[${anchorIndex}][y]" class="form-label">Y Value</label>
                    <input type="number" name="anchors[${anchorIndex}][y]" class="form-control" step="0.0001" placeholder="Enter Y value" required>
                </div>
                <button type="button" class="btn btn-danger remove-anchor">Remove Anchor</button>
            </div>
        `;
        document.getElementById('anchors-container').insertAdjacentHTML('beforeend', newAnchor);
        anchorIndex++;
    });

    document.getElementById('add-asset').addEventListener('click', function() {
        const newAsset = `
            <div class="asset mb-3 p-3 border rounded bg-light" style="margin-top: 20px;">
                <div class="form-group">
                    <label for="assets[${assetIndex}][device_uid]" class="form-label">Device UID</label>
                    <input type="text" name="assets[${assetIndex}][device_uid]" class="form-control" placeholder="Enter Device UID" required>
                </div>
                <div class="form-group">
                    <label for="assets[${assetIndex}][device_icon]" class="form-label">Device Icon Class <small>(FontAwesome class, e.g., "fas fa-lightbulb")</small></label>
                    <input type="text" name="assets[${assetIndex}][device_icon]" class="form-control" placeholder="Enter Device Icon Class" required>
                </div>
                <div class="form-group">
                    <label for="assets[${assetIndex}][device_name]" class="form-label">Device Name</label>
                    <input type="text" name="assets[${assetIndex}][device_name]" class="form-control" placeholder="Enter Device Name" required>
                </div>
                <button type="button" class="btn btn-danger remove-asset">Remove Device</button>
            </div>
        `;
        document.getElementById('assets-container').insertAdjacentHTML('beforeend', newAsset);
        assetIndex++;
    });

    // Event delegation for removing anchors and assets
    document.getElementById('anchors-container').addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-anchor')) {
            e.target.closest('.anchor').remove();
        }
    });

    document.getElementById('assets-container').addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-asset')) {
            e.target.closest('.asset').remove();
        }
    });
</script>

<style>
    .card {
        border-radius: 10px;
        border: none;
    }

    .btn-outline-primary {
        width: 100%;
    }

    .alert {
        margin-top: 20px;
    }
</style>

@endsection
