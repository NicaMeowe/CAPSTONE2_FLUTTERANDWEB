@php($facilities = $payload['collections']['facilities'])
<section class="page-heading compact-heading">
    <div><p class="eyebrow">MONITOR / CARE NETWORK</p><h1>Know every territory.</h1><p class="page-subtitle">Facility coverage, doctor relationships, and stock signals in one place.</p></div>
    <button class="secondary-button" data-action="download">↓ <span>Export facilities</span></button>
</section>
<section class="facility-grid">
@foreach($facilities as $facility)
    <article class="facility-card panel">
        <div class="facility-card-top"><span class="facility-type">{{ $facility['type'] ?? 'Facility' }}</span><span class="facility-more">···</span></div>
        <h2>{{ $facility['name'] ?? 'Unnamed facility' }}</h2>
        <p>{{ $facility['territory'] ?? 'Unassigned territory' }}</p>
        <div class="facility-metrics"><div><strong>{{ $facility['doctors'] ?? 0 }}</strong><small>doctors</small></div><div><strong>{{ $facility['coverage'] ?? 0 }}%</strong><small>coverage</small></div><div><strong class="stock-{{ strtolower(str_replace(' ', '-', $facility['stock'] ?? 'healthy')) }}">{{ $facility['stock'] ?? 'Healthy' }}</strong><small>stock</small></div></div>
        <div class="facility-progress"><span style="width: {{ $facility['coverage'] ?? 0 }}%"></span></div>
    </article>
@endforeach
</section>
<section class="panel map-placeholder"><div class="map-grid"></div><div class="map-copy"><span class="map-pin">+</span><p class="eyebrow">TERRITORY MAP</p><h2>GPS coverage map</h2><p>Live geofence activity will appear here when representative locations sync with Firebase.</p><a href="{{ route('analytics') }}" class="text-link">Explore territory insights <span>→</span></a></div><div class="map-label label-one">Makati Central</div><div class="map-label label-two">QC South</div><div class="map-label label-three">Pasig East</div></section>