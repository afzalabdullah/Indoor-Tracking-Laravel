@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('sites.index') }}">Sites</a></li>
            <li class="breadcrumb-item active">{{ $site->name }}</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4 shadow-lg">
                <div class="card-body">
                    <h5 class="card-title text-center">{{ $site->name }}</h5>
                    <p class="text-center text-muted">{{ $site->description }}</p>

                    <div class="position-relative">
                        <img src="{{ $site->image_url }}" alt="Site Image" id="site-image" class="img-fluid rounded">

                        <!-- Anchor points -->
                        @foreach ($site->anchors as $anchor)
                            <div class="point anchor-point hidden" data-x="{{ $anchor->x }}" data-y="{{ $anchor->y }}" data-uid="{{ $anchor->uid }}">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4 shadow-lg">
                <div class="card-body">
                    <h5 class="card-title">Asset Details</h5>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Device UID</th>
                                    <th>Device Icon</th>
                                    <th>Device Name</th>
                                    <th>Device Origin</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($site->assets as $asset)
                                    <tr>
                                        <td>{{ $asset->device_uid }}</td>
                                        <td><i class="{{ $asset->device_icon }}" style="font-size: 24px;"></i></td>
                                        <td>{{ $asset->device_name }}</td>
                                        <td>{{ $site->name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">Point Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> <!-- Updated for Bootstrap 5 -->
                </div>
                <div class="modal-body">
                    <div id="modalDetailContent">
                        <strong>ID:</strong> <span id="deviceId">Device ID</span><br>
                        <strong>Name:</strong> <span id="deviceName">Device Name</span><br>
                        <strong>Device Origin:</strong> <span id="deviceOrigin">{{ $site ? $site->name : 'Unknown' }}</span><br>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> <!-- Updated for Bootstrap 5 -->
                </div>
            </div>
        </div>
    </div>

</section>

<script>
const MAX_WIDTH = @json($maxWidth);
const MAX_HEIGHT = @json($maxHeight);
const POINT_SIZE = 40;
const TRILATERATION_INTERVAL = 1000;

function positionPoints(points, className) {
    const image = document.getElementById('site-image');
    const imageRect = image.getBoundingClientRect();

    if (!Array.isArray(points)) {
        console.error('Invalid points data:', points);
        return; // Early return if points is not valid
    }

    points.forEach(point => {
        const pointElement = document.querySelector(`[data-uid="${point.uid}"]`) || createPointElement(point.uid, className, point.icon, point.name);

        const xRatio = point.x / MAX_WIDTH;
        const yRatio = point.y / MAX_HEIGHT;

        const x = xRatio * imageRect.width;
        const y = yRatio * imageRect.height;

        pointElement.style.left = `${x - POINT_SIZE / 2}px`;
        pointElement.style.top = `${y - POINT_SIZE / 2}px`;
        pointElement.style.display = 'flex';
    });
}

function createPointElement(uid, className, iconClass, deviceName) {
    const container = document.getElementById('site-image').parentElement;
    const pointElement = document.createElement('div');

    pointElement.classList.add('point', className);
    pointElement.setAttribute('data-uid', uid);

    const icon = document.createElement('i');

    // Check if the device name is unknown
    if (!deviceName || deviceName.toLowerCase() === 'unknown') {
        pointElement.classList.add('unknown-device'); // Add class for styling
        icon.className = 'fas fa-question'; // Set to question mark icon
        pointElement.style.backgroundColor = 'red'; // Set background to red for unknown devices
    } else {
        icon.className = iconClass; // Set the provided icon for known devices
        pointElement.style.backgroundColor = ''; // Reset background color for known devices
    }

    pointElement.appendChild(icon);

    pointElement.addEventListener('click', () => {
        document.getElementById('deviceName').textContent = deviceName || 'Unknown Device';
        document.getElementById('deviceId').textContent = uid;
        $('#detailModal').modal('show');
    });

    container.appendChild(pointElement);
    return pointElement;
}

function fetchTrilateration() {
    // Collect anchor points from DOM elements
    const anchors = Array.from(document.querySelectorAll('.anchor-point')).map(anchor =>
        anchor.getAttribute('data-uid')
    );

    const requestData = { anchors };  // Prepare the request payload

    // Log the outgoing request payload
    console.log('Outgoing Request:', JSON.stringify(requestData, null, 2));

    fetch('/trilateration/latest-position', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
        body: JSON.stringify(requestData) // Send the anchors as JSON
    })
    .then(response => {
        // Log raw response object for troubleshooting
        console.log('Raw Response:', response);

        if (!response.ok) {
            // Log the error if response status isn't OK
            console.error('Network response error:', response.statusText);
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        // Log the response JSON for inspection
        console.log('Received Data:', JSON.stringify(data, null, 2));

        const assetPoints = Object.entries(data).map(([uid, position]) => ({
            uid,
            x: position[0],
            y: position[1],
            icon: position.name && position.name.toLowerCase() !== 'unknown'
                ? position.icon || 'default_icon.png'
                : 'fas fa-question', // Use FontAwesome icon for unknowns
            name: position.name || 'Unknown'
        }));

        // Log the transformed asset points
        console.log('Asset Points:', assetPoints);

        // Position the points on the page
        positionPoints(assetPoints, 'asset-point');
    })
    .catch(error => {
        // Log any fetch-related error
        console.error('Error fetching trilateration data:', error);
    });
}

const image = document.getElementById('site-image');
image.addEventListener('load', function () {
    const anchors = @json($site->anchors).map(anchor => ({
        uid: anchor.uid,
        x: anchor.x,
        y: anchor.y
    }));

    positionPoints(anchors, 'anchor-point');
    fetchTrilateration();
    setInterval(fetchTrilateration, TRILATERATION_INTERVAL);
});

window.addEventListener('resize', function () {
    const anchors = @json($site->anchors).map(anchor => ({
        uid: anchor.uid,
        x: anchor.x,
        y: anchor.y
    }));

    positionPoints(anchors, 'anchor-point');
});
</script>

<style>
    body {
        background-color: #f8f9fa;
        font-family: 'Arial', sans-serif;
    }

    .unknown-device {
        background-color: red; /* Red background for unknown devices */
    }

    .unknown-device i {
        color: white; /* Ensure the icon is visible against the red background */
    }

    .pagetitle h1 {
        font-size: 2.5rem;
        color: #343a40;
        text-align: center;
        margin-bottom: 20px;
        font-weight: 700;
    }

    .position-relative {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        overflow: hidden;
        background-color: #ffffff;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        position: relative;
    }

    .point {
        position: absolute;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
        transition: transform 0.2s ease-in-out;
        cursor: pointer;
        z-index: 10; /* Ensure points are on top of the image */
    }

    .anchor-point {
        background-color: rgba(0, 123, 255, 0.5); /* Example color for anchor points */
    }

    .asset-point {
        background-color: rgba(40, 167, 69, 0.5); /* Example color for asset points */
    }

    .point:hover {
        transform: scale(1.1);
    }
</style>
@endsection
