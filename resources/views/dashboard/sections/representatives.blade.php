@php($representatives = $payload['collections']['representatives'])
<section class="page-heading compact-heading">
    <div><p class="eyebrow">MONITOR / FIELD TEAM</p><h1>People in the field.</h1><p class="page-subtitle">Live representative status and territory coverage across Makati and nearby areas.</p></div>
    <button class="primary-button" data-action="notify">+ <span>Send update</span></button>
</section>
<section class="team-summary-grid">
    <div class="mini-stat panel"><span class="mini-stat-icon teal">●</span><div><strong>{{ $payload['stats']['activeRepresentatives'] }}</strong><small>Active now</small></div></div>
    <div class="mini-stat panel"><span class="mini-stat-icon indigo">↗</span><div><strong>81%</strong><small>Average coverage</small></div></div>
    <div class="mini-stat panel"><span class="mini-stat-icon amber">◷</span><div><strong>3.8h</strong><small>Avg. field time</small></div></div>
</section>
<section class="panel table-panel">
    <div class="panel-header"><div><p class="eyebrow">REPRESENTATIVE DIRECTORY</p><h2>Field team status</h2></div><div class="search-field compact-search"><span>⌕</span><input type="search" placeholder="Find a representative" data-table-search="#reps-table"></div></div>
    <div class="table-scroll"><table id="reps-table"><thead><tr><th>Representative</th><th>Territory</th><th>Today’s visits</th><th>Coverage</th><th>Status</th><th>Last seen</th></tr></thead><tbody>
    @foreach($representatives as $rep)
        <tr><td><div class="table-person"><div class="avatar avatar-{{ $loop->index + 1 }}">{{ strtoupper(substr($rep['name'] ?? 'R', 0, 1)) }}</div><div><strong>{{ $rep['name'] ?? '—' }}</strong><small>Medical representative</small></div></div></td><td>{{ $rep['territory'] ?? 'Unassigned' }}</td><td><span class="visit-number">{{ $rep['visits'] ?? 0 }}</span></td><td><div class="table-progress"><span style="width: {{ $rep['coverage'] ?? 0 }}%"></span></div><strong class="progress-value">{{ $rep['coverage'] ?? 0 }}%</strong></td><td><span class="presence-status {{ strtolower($rep['status'] ?? '') === 'active' ? 'presence-active' : 'presence-away' }}"><i></i>{{ $rep['status'] ?? 'Unknown' }}</span></td><td class="muted">{{ $rep['lastSeen'] ?? '—' }}</td></tr>
    @endforeach
    </tbody></table></div>
</section>