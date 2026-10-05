@php($visits = $payload['collections']['visits'])
<section class="page-heading compact-heading">
    <div><p class="eyebrow">MONITOR / VISIT HISTORY</p><h1>Every visit, verified.</h1><p class="page-subtitle">Review location, attendance, and field notes from the medical representative team.</p></div>
    <button class="secondary-button" data-action="download">↓ <span>Export report</span></button>
</section>
<section class="filter-bar panel">
    <div class="search-field"><span>⌕</span><input type="search" placeholder="Search representative, facility, or doctor" data-table-search="#visits-table"></div>
    <select data-table-filter="#visits-table" data-filter-column="4"><option value="">All statuses</option><option>Completed</option><option>Checked-in</option><option>Review</option><option>Missed call</option></select>
    <select><option>Last 7 days</option><option>Last 30 days</option><option>This quarter</option></select>
</section>
<section class="panel table-panel">
    <div class="panel-header"><div><p class="eyebrow">SYNCHRONIZED RECORDS</p><h2>Visit activity</h2></div><span class="record-count">{{ count($visits) }} records</span></div>
    <div class="table-scroll">
        <table id="visits-table">
            <thead><tr><th>Representative</th><th>Facility / doctor</th><th>Time</th><th>Status</th><th>Verification</th><th></th></tr></thead>
            <tbody>
            @foreach($visits as $visit)
                <tr data-detail="{{ e(json_encode($visit)) }}">
                    <td><div class="table-person"><div class="avatar avatar-{{ $loop->index + 1 }}">{{ strtoupper(substr($visit['representative'] ?? 'R', 0, 1)) }}</div><div><strong>{{ $visit['representative'] ?? '—' }}</strong><small>{{ $visit['date'] ?? '—' }}</small></div></div></td>
                    <td><strong>{{ $visit['facility'] ?? '—' }}</strong><small>{{ $visit['doctor'] ?? 'Doctor not specified' }}</small></td>
                    <td><span class="mono-value">{{ $visit['time'] ?? '—' }}</span></td>
                    <td><span class="table-status status-label-{{ strtolower(str_replace(' ', '-', $visit['status'] ?? 'review')) }}">{{ $visit['status'] ?? 'Review' }}</span></td>
                    <td><span class="verification {{ !empty($visit['verified']) ? 'verified' : 'pending' }}">{{ !empty($visit['verified']) ? '● GPS verified' : '○ Needs review' }}</span></td>
                    <td><button class="row-action" data-row-details>View</button></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</section>
<div class="modal-backdrop" id="detail-modal" hidden><div class="detail-modal" role="dialog" aria-modal="true"><button class="modal-close" data-modal-close>×</button><p class="eyebrow">VISIT RECORD</p><h2 data-modal-title>Visit details</h2><div class="detail-grid" data-modal-body></div><div class="modal-actions"><button class="secondary-button" type="button" data-visit-action="verified">Mark verified</button><button class="secondary-button" type="button" data-visit-action="reviewed">Mark reviewed</button><button class="secondary-button" type="button" data-visit-action="resolved">Mark resolved</button></div><p class="modal-feedback" data-modal-feedback aria-live="polite"></p></div></div>