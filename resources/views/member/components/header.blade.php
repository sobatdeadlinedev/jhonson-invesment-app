{{-- ── FIXED HEADER ── --}}
<div style="position:sticky;top:0;z-index:100;background:#080b12;border-bottom:1px solid #1a2235;padding:13px 18px;">
    <div style="display:flex;align-items:center;justify-content:space-between;">

        {{-- Logo --}}
        <div style="display:flex;align-items:center;gap:10px;">
            <img src="{{ $appConfig['app_logo']['value'] }}"
                 alt="Logo"
                 style="height:28px;width:auto;object-fit:contain;">
        </div>

        {{-- Right actions --}}
        <div style="display:flex;align-items:center;gap:8px;">

            {{-- Live pill --}}
            <div style="display:inline-flex;align-items:center;gap:5px;background:rgba(0,212,138,.07);border:1px solid rgba(0,212,138,.18);border-radius:99px;padding:3px 10px;font-size:10px;font-weight:700;color:#00d48a;letter-spacing:.6px;">
                <span style="width:5px;height:5px;border-radius:50%;background:#00d48a;display:inline-block;flex-shrink:0;animation:xi-blink 1.6s infinite;"></span>
                LIVE
            </div>

            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf
                <button type="submit"
                    style="width:36px;height:36px;border-radius:10px;background:rgba(240,79,90,.07);border:1px solid rgba(240,79,90,.2);display:flex;align-items:center;justify-content:center;cursor:pointer;transition:background .2s;"
                    onmouseover="this.style.background='rgba(240,79,90,.15)'"
                    onmouseout="this.style.background='rgba(240,79,90,.07)'"
                    title="Logout">
                    <i class="bi bi-box-arrow-right" style="color:#f04f5a;font-size:15px;"></i>
                </button>
            </form>
        </div>

    </div>
</div>