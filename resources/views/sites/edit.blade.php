@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <h1>Edit Site: {{ $site->name }}</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('sites.index') }}">Sites</a></li>
            <li class="breadcrumb-item active">Edit Site</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
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

                    <form action="{{ route('sites.update', $site->id) }}" method="POST" enctype="multipart/form-data" class="row g-3">
                        @csrf
                        @method('PUT')
                        <div class="col-12">
                            <label for="name" class="form-label">Site Name</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $site->name) }}" placeholder="Enter site name" required>
                        </div>
                        <div class="col-12">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control" placeholder="Enter site description" required>{{ old('description', $site->description) }}</textarea>
                        </div>

                        <div id="anchors-container" class="col-12">
                            <h5 class="my-3">Anchors</h5>
                            @foreach ($site->anchors as $index => $anchor)
                                <div class="anchor mb-3 p-3 border rounded bg-light">
                                    <div class="form-group">
                                        <label for="anchors[{{ $index }}][uid]" class="form-label">UID</label>
                                        <input type="text" name="anchors[{{ $index }}][uid]" class="form-control" value="{{ old("anchors.$index.uid", $anchor->uid) }}" placeholder="Enter UID" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="anchors[{{ $index }}][x]" class="form-label">X Value</label>
                                        <input type="number" name="anchors[{{ $index }}][x]" class="form-control" step="0.0001" value="{{ old("anchors.$index.x", $anchor->x) }}" placeholder="Enter X value" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="anchors[{{ $index }}][y]" class="form-label">Y Value</label>
                                        <input type="number" name="anchors[{{ $index }}][y]" class="form-control" step="0.0001" value="{{ old("anchors.$index.y", $anchor->y) }}" placeholder="Enter Y value" required>
                                    </div>
                                    <button type="button" class="btn btn-danger remove-anchor">Remove Anchor</button>
                                </div>
                            @endforeach
                            <button type="button" id="add-anchor" class="btn btn-outline-primary mb-3">Add Another Anchor</button>
                        </div>

                        <div id="assets-container" class="col-12 my-4">
                            <h5>Devices (Assets)</h5>
                            @foreach ($site->assets as $index => $asset)
                                <div class="asset mb-3 p-3 border rounded bg-light">
                                    <div class="form-group">
                                        <label for="assets[{{ $index }}][device_uid]" class="form-label">Device UID</label>
                                        <input type="text" name="assets[{{ $index }}][device_uid]" class="form-control" value="{{ old("assets.$index.device_uid", $asset->device_uid) }}" placeholder="Enter Device UID" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="assets[{{ $index }}][device_icon]" class="form-label">Device Icon Class <small>(FontAwesome class, e.g., "fas fa-lightbulb")</small></label>
                                        <input type="text" name="assets[{{ $index }}][device_icon]" class="form-control" value="{{ old("assets.$index.device_icon", $asset->device_icon) }}" placeholder="Enter Device Icon Class" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="assets[{{ $index }}][device_name]" class="form-label">Device Name</label>
                                        <input type="text" name="assets[{{ $index }}][device_name]" class="form-control" value="{{ old("assets.$index.device_name", $asset->device_name) }}" placeholder="Enter Device Name" required>
                                    </div>
                                    <button type="button" class="btn btn-danger remove-asset">Remove Device</button>
                                </div>
                            @endforeach
                            <button type="button" id="add-asset" class="btn btn-outline-primary mb-3">Add Another Device</button>
                        </div>

                        <div class="col-12">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" name="image" id="image" class="form-control">
                            <small>Leave empty to keep the current image.</small>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-success">Update Site</button>
                            <button type="reset" class="btn btn-secondary">Reset</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    let anchorIndex = {{ count($site->anchors) }};
    let assetIndex = {{ count($site->assets) }};

    document.getElementById('add-anchor').addEventListener('click', function() {
        const container = document.getElementById('anchors-container');
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
        container.insertAdjacentHTML('beforeend', newAnchor);
        anchorIndex++;
    });

    document.getElementById('add-asset').addEventListener('click', function() {
        const container = document.getElementById('assets-container');
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
        container.insertAdjacentHTML('beforeend', newAsset);
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
    }
    .btn-outline-primary {
        width: 100%;
    }
</style>

@endsection
