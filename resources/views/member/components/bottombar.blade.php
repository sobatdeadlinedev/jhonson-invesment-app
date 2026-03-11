{{-- ── BOTTOM NAVIGATION ── --}}
<div style="position:sticky;bottom:0;z-index:100;background:#080b12;border-top:1px solid #1a2235;display:flex;justify-content:space-around;padding:10px 0 14px;">

    {{-- Home --}}
    <a href="{{ route('member.dashboard.index') }}"
       style="text-decoration:none;flex:1;display:flex;flex-direction:column;align-items:center;gap:4px;padding:4px 2px;transition:opacity .15s;
              {{ request()->routeIs('member.dashboard.*') ? 'opacity:1;' : 'opacity:1;' }}">
        @php $home = request()->routeIs('member.dashboard.*'); @endphp
        <div style="width:38px;height:32px;border-radius:10px;display:flex;align-items:center;justify-content:center;
                    background:{{ $home ? 'rgba(0,212,138,.12)' : 'transparent' }};
                    transition:background .2s;">
            <i class="bi bi-house-door-fill"
               style="font-size:17px;color:{{ $home ? '#00d48a' : '#3a4d66' }};"></i>
        </div>
        <span style="font-size:10px;font-weight:{{ $home ? '700' : '500' }};
                     color:{{ $home ? '#00d48a' : '#3a4d66' }};letter-spacing:.3px;">Home</span>
    </a>

    {{-- Trade --}}
    <a href="{{ route('member.invest.index') }}"
       style="text-decoration:none;flex:1;display:flex;flex-direction:column;align-items:center;gap:4px;padding:4px 2px;">
        @php $trade = request()->routeIs('member.invest.*'); @endphp
        <div style="width:38px;height:32px;border-radius:10px;display:flex;align-items:center;justify-content:center;
                    background:{{ $trade ? 'rgba(245,166,35,.12)' : 'transparent' }};
                    transition:background .2s;">
            <i class="bi bi-graph-up-arrow"
               style="font-size:17px;color:{{ $trade ? '#f5a623' : '#3a4d66' }};"></i>
        </div>
        <span style="font-size:10px;font-weight:{{ $trade ? '700' : '500' }};
                     color:{{ $trade ? '#f5a623' : '#3a4d66' }};letter-spacing:.3px;">Trade</span>
    </a>

    {{-- Team --}}
    <a href="{{ route('member.team.index') }}"
       style="text-decoration:none;flex:1;display:flex;flex-direction:column;align-items:center;gap:4px;padding:4px 2px;">
        @php $team = request()->routeIs('member.team.*'); @endphp
        <div style="width:38px;height:32px;border-radius:10px;display:flex;align-items:center;justify-content:center;
                    background:{{ $team ? 'rgba(98,126,234,.12)' : 'transparent' }};
                    transition:background .2s;">
            <i class="bi bi-people-fill"
               style="font-size:17px;color:{{ $team ? '#627eea' : '#3a4d66' }};"></i>
        </div>
        <span style="font-size:10px;font-weight:{{ $team ? '700' : '500' }};
                     color:{{ $team ? '#627eea' : '#3a4d66' }};letter-spacing:.3px;">Team</span>
    </a>

    {{-- My Assets --}}
    <a href="{{ route('member.profile.index') }}"
       style="text-decoration:none;flex:1;display:flex;flex-direction:column;align-items:center;gap:4px;padding:4px 2px;">
        @php $profile = request()->routeIs('member.profile.*'); @endphp
        <div style="width:38px;height:32px;border-radius:10px;display:flex;align-items:center;justify-content:center;
                    background:{{ $profile ? 'rgba(245,166,35,.12)' : 'transparent' }};
                    transition:background .2s;">
            <i class="bi bi-wallet2"
               style="font-size:17px;color:{{ $profile ? '#f5a623' : '#3a4d66' }};"></i>
        </div>
        <span style="font-size:10px;font-weight:{{ $profile ? '700' : '500' }};
                     color:{{ $profile ? '#f5a623' : '#3a4d66' }};letter-spacing:.3px;">My Assets</span>
    </a>

</div>