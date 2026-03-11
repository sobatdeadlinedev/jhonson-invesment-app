<!DOCTYPE html>
<html lang="id">

<head>
    <base href="/" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ $appConfig['app_logo']['value'] }}" />
    <title>STARS INVESTMENT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* ═══════════════════════════════════════════
           COLOR SYSTEM — Midnight Green + Gold
           Base:   #091410  (hijau hitam dalam)
           Card:   #0f1f19  (hijau gelap, lebih terang dari base)
           Border: #1a3028  (border hijau terlihat jelas)
           Gold:   #f5a623  (aksen utama — kontras kuat di atas hijau)
           Green:  #00d48a  (emerald — aksen sekunder & positif)
           Red:    #f04f5a  (negatif)
           Text:   #d8f0e8  (putih kehijauan, enak di mata)
           Muted:  #5e8a76  (teks sekunder)
           Dim:    #1e3d30  (teks tersier / label kecil)
        ═══════════════════════════════════════════ */
        :root {
            --primary-dark:  #091410;
            --secondary-dark:#0f1f19;
            --card-dark:     #0f1f19;
            --gold-color:    #f5a623;
            --gold-hover:    #e8940f;
            --blue-color:    #00d48a;
            --blue-dark:     #00b374;
            --green-color:   #00d48a;
            --red-color:     #f04f5a;
            --text-bright:   #d8f0e8;
            --text-muted:    #5e8a76;
            --border-color:  #1a3028;
            --dim-color:     #1e3d30;
        }

        * { box-sizing: border-box; }

        body {
            background-color: #d4d8de;
            background-image:
                /* Vignette — pojok gelap */
                radial-gradient(ellipse at 50% 50%, transparent 40%, rgba(0,0,0,0.18) 100%),
                /* Grain halus — baseFrequency lebih kecil = lebih smooth */
                url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3CfeColorMatrix type='saturate' values='0'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E"),
                /* Subtle gradient mesh */
                radial-gradient(ellipse at 20% 10%, rgba(225,229,235,0.8) 0%, transparent 50%),
                radial-gradient(ellipse at 82% 88%, rgba(205,210,218,0.7) 0%, transparent 48%),
                linear-gradient(145deg, #ced3d9 0%, #d6dadf 50%, #d0d5db 100%);
            background-size: cover, 256px 256px, cover, cover, cover;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            margin: 0; padding: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            color: var(--text-bright);
        }

        /* Mobile Container */
        .mobile-container {
            width: 425px;
            height: 100vh;
            background-color: var(--primary-dark);
            position: relative;
            box-shadow:
                0 0 0 1px rgba(0,0,0,0.18),
                0 2px 4px rgba(0,0,0,0.15),
                0 8px 20px rgba(0,0,0,0.28),
                0 20px 48px rgba(0,0,0,0.38),
                0 48px 96px rgba(0,0,0,0.28),
                0 80px 120px rgba(0,0,0,0.15);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* Fixed Header */
        .fixed-header {
            position: sticky;
            top: 0;
            z-index: 100;
            background: var(--secondary-dark);
            border-bottom: 1px solid var(--border-color);
            padding: 13px 18px;
        }

        /* Header Layout */
        .header-grid {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 8px;
            justify-content: flex-end;
        }

        .user-avatar { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; }
        .user-info   { display: flex; flex-direction: column; }
        .app-logo    { height: 28px; width: auto; object-fit: contain; }
        .user-email  { color: var(--text-muted); font-size: 11px; }

        .btn-logout {
            background: rgba(240, 79, 90, 0.1);
            border: 1px solid rgba(240, 79, 90, 0.3);
            border-radius: 10px;
            width: 36px; height: 36px;
            display: flex; align-items: center; justify-content: center;
            transition: all 0.2s ease; cursor: pointer;
        }

        .btn-logout:hover {
            background: rgba(240, 79, 90, 0.22);
            border-color: rgba(240, 79, 90, 0.5);
        }

        .btn-logout i { color: var(--red-color); font-size: 16px; }

        /* Scrollable Content */
        .scrollable-content { flex: 1; overflow-y: auto; overflow-x: hidden; }
        .scrollable-content::-webkit-scrollbar { width: 0px; }

        /* Content Sections */
        .content-section { padding: 20px; }

        /* Cards */
        .card-dark {
            background-color: var(--card-dark);
            border: 1px solid var(--border-color);
            border-radius: 18px;
            transition: box-shadow 0.2s ease;
        }

        .card-dark:hover {
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.35);
        }

        /* Gold Theme */
        .text-gold  { color: var(--gold-color) !important; }
        .bg-gold    { background-color: var(--gold-color) !important; }

        .btn-gold {
            background: linear-gradient(135deg, var(--gold-color), var(--gold-hover));
            border: none; color: #0e1628;
            font-weight: 700; transition: all 0.2s ease; border-radius: 14px;
        }

        .btn-gold:hover { opacity: 0.9; transform: translateY(-1px); color: #0e1628; }

        /* Text */
        .text-muted  { color: var(--text-muted) !important; }
        .text-white  { color: var(--text-bright) !important; }

        /* Bottom Nav */
        .bottom-nav {
            position: sticky; bottom: 0; width: 100%;
            background: var(--secondary-dark);
            border-top: 1px solid var(--border-color);
            display: flex; justify-content: space-around;
            padding: 10px 0 14px 0;
            backdrop-filter: blur(10px); z-index: 100;
        }

        .nav-item {
            text-decoration: none;
            color: var(--dim-color);
            font-size: 10px; text-align: center; flex: 1;
            transition: all 0.2s ease; padding: 4px 2px; border-radius: 4px;
            display: flex; flex-direction: column; align-items: center; gap: 3px;
        }

        .nav-item i { font-size: 18px; display: block; }
        .nav-item:hover { color: var(--text-muted); }

        .nav-item.active { color: var(--gold-color); font-weight: 700; }
        .nav-item.active i { color: var(--gold-color); }

        .nav-item span { display: block; margin-top: 2px; letter-spacing: .3px; }

        @media (min-width: 768px) {
            body {
                background-color: #cacfd6;
                background-image:
                    /* Vignette */
                    radial-gradient(ellipse at 50% 50%, transparent 35%, rgba(0,0,0,0.22) 100%),
                    /* Fine grain */
                    url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3CfeColorMatrix type='saturate' values='0'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E"),
                    radial-gradient(ellipse at 20% 10%, rgba(215,220,228,0.8) 0%, transparent 50%),
                    radial-gradient(ellipse at 80% 85%, rgba(196,202,212,0.7) 0%, transparent 48%),
                    linear-gradient(145deg, #c6cbd2 0%, #ced3d9 50%, #c8cdd4 100%);
                background-size: cover, 256px 256px, cover, cover, cover;
            }
            .mobile-container {
                border-radius: 26px;
                overflow: hidden;
                box-shadow:
                    0 0 0 1px rgba(0,0,0,0.14),
                    0 2px 4px rgba(0,0,0,0.12),
                    0 8px 20px rgba(0,0,0,0.22),
                    0 24px 56px rgba(0,0,0,0.35),
                    0 56px 96px rgba(0,0,0,0.28),
                    0 80px 130px rgba(0,0,0,0.15);
                margin: 20px 0;
                height: calc(100vh - 40px);
            }
        }

        /* ════════════════════════════════
           TEAM PAGE
        ════════════════════════════════ */
        .team-icon-wrapper {
            width: 56px; height: 56px;
            background: rgba(245, 166, 35, 0.1);
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            border: 1px solid rgba(245, 166, 35, 0.25);
        }

        .team-icon-wrapper i { font-size: 26px; color: var(--gold-color); }

        .team-member-item {
            padding: 14px 16px;
            border-bottom: 1px solid var(--border-color);
            transition: background-color 0.2s ease;
        }

        .team-member-item:hover { background-color: rgba(0, 212, 138, 0.05); }
        .team-member-item:last-child { border-bottom: none; }

        .team-avatar {
            width: 42px; height: 42px;
            background: rgba(0, 212, 138, 0.08);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            border: 1px solid rgba(0, 212, 138, 0.2);
            flex-shrink: 0;
        }

        .team-avatar i { font-size: 26px; color: var(--green-color); }

        .team-status { width: 12px; height: 12px; flex-shrink: 0; }
        .team-status i { font-size: 12px; }
        .team-status.active   i { color: var(--green-color); }
        .team-status.inactive i { color: var(--dim-color); }

        @media (max-width: 375px) {
            .team-member-item { padding: 12px 14px; }
            .team-avatar { width: 38px; height: 38px; }
            .team-avatar i { font-size: 22px; }
        }

        /* ════════════════════════════════
           PROFILE PAGE
        ════════════════════════════════ */
        .profile-avatar-large {
            width: 58px; height: 58px;
            background: rgba(245, 166, 35, 0.08);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            border: 2px solid rgba(245, 166, 35, 0.35);
            flex-shrink: 0;
        }

        .profile-avatar-large i { font-size: 34px; color: var(--gold-color); }

        .balance-icon-wrapper {
            width: 54px; height: 54px;
            background: rgba(245, 166, 35, 0.08);
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            border: 1px solid rgba(245, 166, 35, 0.25);
        }

        .balance-icon-wrapper i { font-size: 24px; color: var(--gold-color); }

        .btn-outline-gold {
            background-color: transparent;
            border: 1px solid rgba(245, 166, 35, 0.45);
            color: var(--gold-color); font-weight: 600;
            transition: all 0.2s ease; border-radius: 10px;
        }

        .btn-outline-gold:hover {
            background-color: rgba(245, 166, 35, 0.1);
            border-color: var(--gold-color); color: var(--gold-color);
        }

        .badge-count {
            background: rgba(245, 166, 35, 0.12);
            border: 1px solid rgba(245, 166, 35, 0.3);
            color: var(--gold-color);
            padding: 3px 10px; border-radius: 99px;
            font-size: 11px; font-weight: 700;
        }

        .bank-list-item {
            padding: 14px 16px;
            border-bottom: 1px solid var(--border-color);
            transition: background-color 0.2s ease;
        }

        .bank-list-item:hover { background-color: rgba(0, 212, 138, 0.04); }
        .bank-list-item:last-of-type { border-bottom: 1px solid var(--border-color); }

        .bank-icon-circle {
            width: 42px; height: 42px;
            background: rgba(245, 166, 35, 0.08);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            border: 1px solid rgba(245, 166, 35, 0.22);
            flex-shrink: 0;
        }

        .bank-icon-circle i { font-size: 18px; color: var(--gold-color); }

        .btn-bank-action {
            width: 32px; height: 32px; border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            border: 1px solid; transition: all 0.2s ease;
            cursor: pointer; padding: 0; flex-shrink: 0;
        }

        .btn-bank-edit  { background: rgba(0, 212, 138, 0.08);  border-color: rgba(0, 212, 138, 0.25); }
        .btn-bank-edit:hover { background: rgba(0, 212, 138, 0.18); border-color: rgba(0, 212, 138, 0.5); }
        .btn-bank-edit i { color: var(--green-color); font-size: 13px; }

        .btn-bank-delete { background: rgba(240, 79, 90, 0.08); border-color: rgba(240, 79, 90, 0.25); }
        .btn-bank-delete:hover { background: rgba(240, 79, 90, 0.18); border-color: rgba(240, 79, 90, 0.5); }
        .btn-bank-delete i { color: var(--red-color); font-size: 13px; }

        @media (max-width: 375px) {
            .profile-avatar-large { width: 48px; height: 48px; }
            .profile-avatar-large i { font-size: 30px; }
            .balance-icon-wrapper { width: 46px; height: 46px; }
            .balance-icon-wrapper i { font-size: 20px; }
            .bank-icon-circle { width: 38px; height: 38px; }
            .bank-icon-circle i { font-size: 16px; }
            .btn-bank-action { width: 28px; height: 28px; }
        }

        /* ════════════════════════════════
           DASHBOARD
        ════════════════════════════════ */
        .referral-icon-small {
            width: 24px; height: 24px;
            background: rgba(245, 166, 35, 0.1);
            border-radius: 6px;
            display: flex; align-items: center; justify-content: center;
            border: 1px solid rgba(245, 166, 35, 0.25);
            flex-shrink: 0;
        }

        .referral-icon-small i { font-size: 13px; color: var(--gold-color); }

        .referral-code-display {
            font-size: 18px; font-weight: 700;
            color: var(--gold-color);
            letter-spacing: 1.5px;
            font-family: 'Courier New', monospace;
        }

        .btn-copy-small {
            background: rgba(245, 166, 35, 0.1);
            border: 1px solid rgba(245, 166, 35, 0.25);
            border-radius: 8px; width: 32px; height: 32px;
            display: flex; align-items: center; justify-content: center;
            transition: all 0.2s ease; cursor: pointer; padding: 0; flex-shrink: 0;
        }

        .btn-copy-small:hover { background: rgba(245, 166, 35, 0.2); border-color: rgba(245, 166, 35, 0.5); }
        .btn-copy-small i { color: var(--gold-color); font-size: 13px; }

        .market-status-badge {
            padding: 3px 10px; border-radius: 99px;
            font-size: 10px; font-weight: 700;
            display: inline-flex; align-items: center; letter-spacing: .5px;
        }

        .market-status-badge.active {
            background: rgba(0, 212, 138, 0.1);
            border: 1px solid rgba(0, 212, 138, 0.3);
            color: var(--green-color);
        }

        .market-status-badge.active i { font-size: 7px; }

        .market-coin-item {
            padding: 13px 16px;
            border-bottom: 1px solid var(--border-color);
            transition: background-color 0.15s ease; cursor: pointer;
        }

        .market-coin-item:hover { background-color: rgba(0, 212, 138, 0.04); }
        .market-coin-item:last-child { border-bottom: none; }

        /* Coin Icons */
        .coin-icon {
            width: 38px; height: 38px; border-radius: 11px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; font-size: 17px; font-weight: 700;
        }

        .coin-icon.btc  { background: rgba(247, 147, 26, 0.15); color: #f7931a; }
        .coin-icon.eth  { background: rgba(98, 126, 234, 0.15); color: #8fa4ff; }
        .coin-icon.doge { background: rgba(195, 167, 78, 0.15); color: #d4b850; }
        .coin-icon.bnb  { background: rgba(243, 186, 47, 0.15); color: #f3ba2f; }
        .coin-icon.sol  { background: rgba(153, 69, 255, 0.15); color: #b87aff; }
        .coin-icon.xrp  { background: rgba(56, 201, 240, 0.15); color: #38c9f0; }
        .coin-icon.link { background: rgba(56, 130, 243, 0.15); color: #5b9af5; }
        .coin-icon.dot  { background: rgba(230, 0, 122, 0.15);  color: #f0559a; }

        /* Price Change */
        .price-change {
            font-size: 11px; font-weight: 700;
            padding: 2px 8px; border-radius: 6px;
            display: inline-block; font-family: monospace;
        }

        .price-change.positive { color: var(--green-color); background: rgba(0, 212, 138, 0.12); }
        .price-change.negative { color: var(--red-color);   background: rgba(240, 79, 90, 0.12); }

        @media (max-width: 375px) {
            .balance-icon-wrapper { width: 46px; height: 46px; }
            .balance-icon-wrapper i { font-size: 20px; }
            .referral-code-display { font-size: 14px; }
            .btn-copy-small { width: 28px; height: 28px; }
            .btn-copy-small i { font-size: 12px; }
            .coin-icon { width: 34px; height: 34px; font-size: 15px; }
        }

        /* ════════════════════════════════
           INVEST / TRADE PAGE
        ════════════════════════════════ */
        .coin-list-item {
            display: block; padding: 13px 16px;
            border-bottom: 1px solid var(--border-color);
            transition: background-color 0.15s ease;
            text-decoration: none; color: inherit;
        }

        .coin-list-item:hover { background-color: rgba(0, 212, 138, 0.04); }
        .coin-list-item:last-child { border-bottom: none; }

        .btn-back {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 8px 14px;
            background: rgba(245, 166, 35, 0.08);
            border: 1px solid rgba(245, 166, 35, 0.25);
            border-radius: 10px; color: var(--gold-color);
            text-decoration: none; font-size: 13px; font-weight: 600;
            transition: all 0.2s ease;
        }

        .btn-back:hover { background: rgba(245, 166, 35, 0.16); border-color: rgba(245, 166, 35, 0.5); color: var(--gold-color); }

        .coin-icon-large {
            width: 56px; height: 56px; border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; font-size: 26px; font-weight: 700;
        }

        .coin-icon-large.btc { background: rgba(247, 147, 26, 0.15); color: #f7931a; }
        .coin-icon-large.eth { background: rgba(98, 126, 234, 0.15); color: #8fa4ff; }
        .coin-icon-large.sol { background: rgba(153, 69, 255, 0.15); color: #b87aff; }
        .coin-icon-large.bnb { background: rgba(243, 186, 47, 0.15); color: #f3ba2f; }

        .price-change-large {
            font-size: 16px; font-weight: 700;
            padding: 4px 12px; border-radius: 8px;
            display: inline-block; font-family: monospace;
        }

        .price-change-large.positive { color: var(--green-color); background: rgba(0, 212, 138, 0.12); }
        .price-change-large.negative { color: var(--red-color);   background: rgba(240, 79, 90, 0.12); }

        .chart-timeframe-pills { display: flex; gap: 6px; }

        .timeframe-pill {
            padding: 4px 12px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border-color);
            border-radius: 7px; color: var(--text-muted);
            font-size: 11px; font-weight: 700; cursor: pointer;
            transition: all 0.2s ease;
        }

        .timeframe-pill:hover { background: rgba(0, 212, 138, 0.1); color: var(--green-color); border-color: rgba(0, 212, 138, 0.3); }
        .timeframe-pill.active { background: var(--gold-color); border-color: var(--gold-color); color: #0e1628; }

        .chart-container { height: 250px; position: relative; }

        .form-control-dark {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border-color);
            border-radius: 12px; padding: 11px 14px;
            color: var(--text-bright);
            font-size: 14px; font-weight: 600; width: 100%;
            transition: all 0.2s ease; outline: none;
            -webkit-appearance: none; appearance: none;
        }

        .form-control-dark:focus {
            border-color: rgba(0, 212, 138, 0.5);
            background: rgba(0, 212, 138, 0.05);
        }

        .form-control-dark::placeholder { color: var(--dim-color); }

        .amount-quick-select { display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px; }

        .quick-amount-btn {
            padding: 10px;
            background: rgba(245, 166, 35, 0.08);
            border: 1px solid rgba(245, 166, 35, 0.22);
            border-radius: 9px; color: var(--gold-color);
            font-size: 13px; font-weight: 700; cursor: pointer;
            transition: all 0.2s ease;
        }

        .quick-amount-btn:hover { background: rgba(245, 166, 35, 0.18); border-color: var(--gold-color); }

        .duration-options { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; }

        .duration-btn {
            padding: 12px;
            background: rgba(255, 255, 255, 0.04);
            border: 1.5px solid var(--border-color);
            border-radius: 12px; cursor: pointer;
            transition: all 0.2s ease; text-align: center;
        }

        .duration-btn:hover  { border-color: rgba(0, 212, 138, 0.4); background: rgba(0, 212, 138, 0.06); }
        .duration-btn.active { background: rgba(245, 166, 35, 0.1); border-color: var(--gold-color); }

        .duration-time   { color: var(--text-bright); font-size: 14px; font-weight: 700; margin-bottom: 4px; }
        .duration-payout { color: var(--gold-color); font-size: 12px; font-weight: 600; }

        .btn-call {
            background: linear-gradient(135deg, var(--gold-color) 0%, var(--gold-hover) 100%);
            border: none; color: #0e1628;
            font-weight: 700; font-size: 15px;
            padding: 13px 20px; border-radius: 12px;
            transition: all 0.2s ease; cursor: pointer;
        }

        .btn-call:hover { opacity: 0.9; transform: translateY(-1px); box-shadow: 0 4px 16px rgba(245,166,35,0.35); }

        .btn-put {
            background: linear-gradient(135deg, #2ebd85 0%, #1a9966 100%);
            border: none; color: #091410;
            font-weight: 700; font-size: 15px;
            padding: 13px 20px; border-radius: 12px;
            transition: all 0.2s ease; cursor: pointer;
        }

        .btn-put:hover { opacity: 0.9; transform: translateY(-1px); box-shadow: 0 4px 16px rgba(0,212,138,0.35); }

        @media (max-width: 375px) {
            .coin-icon-large { width: 48px; height: 48px; font-size: 22px; }
            .chart-container { height: 200px; }
            .quick-amount-btn { padding: 8px; font-size: 12px; }
        }

        /* ════════════════════════════════
           DEPOSIT PAGE
        ════════════════════════════════ */
        .step-indicator { display: flex; align-items: center; justify-content: center; gap: 0; }
        .step-item { display: flex; flex-direction: column; align-items: center; gap: 6px; }

        .step-circle {
            width: 38px; height: 38px; border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
            border: 1.5px solid var(--border-color);
            display: flex; align-items: center; justify-content: center;
            color: var(--dim-color); font-weight: 700; font-size: 15px;
            transition: all 0.3s ease;
        }

        .step-item.active .step-circle    { background: var(--gold-color); border-color: var(--gold-color); color: #0e1628; }
        .step-item.completed .step-circle { background: rgba(0,212,138,0.12); border-color: var(--green-color); color: var(--green-color); }

        .step-label { font-size: 11px; color: var(--dim-color); font-weight: 600; }
        .step-item.active .step-label { color: var(--gold-color); }

        .step-line { width: 70px; height: 1.5px; background: var(--border-color); margin: 0 -8px; margin-bottom: 26px; }

        .step-content { display: none; }
        .step-content.active { display: block; animation: fadeIn 0.3s ease; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .input-with-icon { position: relative; }

        .input-icon {
            position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
            color: var(--gold-color); font-size: 16px; font-weight: 700; pointer-events: none;
        }

        .form-control-dark.with-icon { padding-left: 38px; }

        .payment-method-option { position: relative; margin-bottom: 10px; cursor: pointer; }
        .payment-radio { position: absolute; opacity: 0; cursor: pointer; }

        .payment-label {
            display: block; padding: 14px 16px;
            background: rgba(255, 255, 255, 0.04);
            border: 1.5px solid var(--border-color);
            border-radius: 14px; cursor: pointer; transition: all 0.2s ease;
        }

        .payment-radio:checked + .payment-label { background: rgba(245,166,35,0.08); border-color: rgba(245,166,35,0.5); }
        .payment-label:hover { background: rgba(0, 212, 138, 0.05); border-color: rgba(0, 212, 138, 0.2); }

        .payment-details { display: none; }
        .payment-details.active { display: block; animation: fadeIn 0.3s ease; }

        .payment-icon {
            width: 42px; height: 42px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; border: 1px solid;
        }

        .payment-icon i { font-size: 20px; }
        .payment-icon.ewallet { background: rgba(138,43,226,0.12); border-color: rgba(138,43,226,0.3); }
        .payment-icon.ewallet i { color: #c07af0; }
        .payment-icon.qrcode  { background: rgba(0,212,138,0.1); border-color: rgba(0,212,138,0.25); }
        .payment-icon.qrcode  i { color: var(--green-color); }

        .payment-info-item { padding: 12px 0; border-bottom: 1px solid var(--border-color); }
        .payment-info-item:last-child { border-bottom: none; padding-bottom: 0; }

        .btn-copy-mini {
            background: rgba(245,166,35,0.1); border: 1px solid rgba(245,166,35,0.25);
            border-radius: 6px; width: 26px; height: 26px;
            display: flex; align-items: center; justify-content: center;
            transition: all 0.2s ease; cursor: pointer; padding: 0;
        }

        .btn-copy-mini:hover { background: rgba(245,166,35,0.2); }
        .btn-copy-mini i { color: var(--gold-color); font-size: 12px; }

        .alert-info-box {
            background: rgba(0,212,138,0.07); border: 1px solid rgba(0,212,138,0.2);
            border-radius: 12px; padding: 10px 14px;
            color: var(--green-color); font-size: 12px;
            display: flex; align-items: center; gap: 8px;
        }

        .alert-info-box i { flex-shrink: 0; }

        .qr-code-container { display: flex; justify-content: center; align-items: center; padding: 20px; }
        .qr-code-image { width: 190px; height: 190px; border-radius: 14px; border: 1px solid var(--border-color); }

        .upload-area {
            border: 1.5px dashed var(--border-color);
            border-radius: 14px; padding: 28px 20px;
            text-align: center; cursor: pointer; transition: all 0.2s ease;
        }

        .upload-area:hover { border-color: rgba(0,212,138,0.4); background: rgba(0,212,138,0.04); }
        .upload-icon { font-size: 32px; color: var(--dim-color); margin-bottom: 8px; display: block; }

        .preview-image { max-width: 100%; max-height: 200px; border-radius: 10px; object-fit: cover; }

        @media (max-width: 375px) {
            .step-circle { width: 34px; height: 34px; font-size: 13px; }
            .step-line { width: 56px; }
            .qr-code-image { width: 170px; height: 170px; }
            .payment-icon { width: 38px; height: 38px; }
            .payment-icon i { font-size: 18px; }
        }

        /* ════════════════════════════════
           WITHDRAW PAGE
        ════════════════════════════════ */
        .withdraw-bank-option { position: relative; cursor: pointer; border-bottom: 1px solid var(--border-color); }
        .withdraw-bank-option:last-child { border-bottom: none; }
        .bank-radio { position: absolute; opacity: 0; cursor: pointer; }

        .bank-option-label {
            display: block; padding: 14px 16px; cursor: pointer;
            transition: all 0.2s ease; background: transparent; margin: 0;
        }

        .bank-option-label:hover { background: rgba(0, 212, 138, 0.04); }
        .bank-radio:checked + .bank-option-label { background: rgba(245,166,35,0.07); }

        .radio-check { width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .radio-check i { font-size: 22px; color: var(--dim-color); transition: all 0.2s ease; }
        .bank-radio:checked + .bank-option-label .radio-check i { color: var(--gold-color); }

        @media (max-width: 375px) {
            .bank-icon-circle { width: 38px; height: 38px; }
            .bank-icon-circle i { font-size: 16px; }
            .radio-check i { font-size: 18px; }
            .quick-amount-btn { padding: 8px; font-size: 12px; }
        }

        /* ════════════════════════════════
           VERIFICATION PAGE
        ════════════════════════════════ */
        .verification-icon-small {
            width: 30px; height: 30px;
            background: rgba(245,166,35,0.1); border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            border: 1px solid rgba(245,166,35,0.25); flex-shrink: 0;
        }

        .verification-icon-small i { font-size: 13px; color: var(--gold-color); }

        .form-control-dark.is-invalid { border-color: rgba(240,79,90,0.6); }

        /* Dropdown */
        .form-control-dark-select {
            background: rgba(255,255,255,0.05);
            border: 1px solid var(--border-color);
            border-radius: 12px; padding: 11px 36px 11px 14px;
            color: var(--text-bright);
            font-size: 14px; font-weight: 600; width: 100%;
            transition: all 0.2s ease;
            appearance: none; -webkit-appearance: none; -moz-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' fill='%236e87ab' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat; background-position: right 12px center;
            outline: none; cursor: pointer;
        }

        .form-control-dark-select:focus { border-color: rgba(0,212,138,0.5); background-color: rgba(0,212,138,0.05); }
        .form-control-dark-select.is-invalid { border-color: rgba(240,79,90,0.6); }

        .form-control-dark-select option {
            background-color: #0f1f19 !important;
            color: var(--text-bright) !important;
            padding: 10px; font-size: 14px;
        }

        .form-control-dark-select option[value=""] { color: var(--dim-color) !important; }
        .form-control-dark-select option:disabled   { color: var(--dim-color) !important; opacity: 0.5; }

        @media (max-width: 480px) { .form-control-dark-select { font-size: 16px; } }

        .preview-container {
            min-height: 150px;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
        }

        .preview-container img { max-width: 100%; max-height: 300px; border-radius: 10px; object-fit: contain; }

        .verification-data-item { padding: 12px 0; border-bottom: 1px solid var(--border-color); }
        .verification-data-item:last-child { border-bottom: none; padding-bottom: 0; }

        .btn-verification-link {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 6px 12px;
            background: rgba(245,166,35,0.08); border: 1px solid rgba(245,166,35,0.25);
            border-radius: 8px; color: var(--gold-color);
            font-size: 12px; font-weight: 600; text-decoration: none; transition: all 0.2s ease;
        }

        .btn-verification-link:hover { background: rgba(245,166,35,0.18); border-color: var(--gold-color); color: var(--gold-color); text-decoration: none; }
        .btn-verification-link i { font-size: 13px; }

        /* ════════════════════════════════
           ANIMATIONS
        ════════════════════════════════ */
        @keyframes xi-blink   { 0%,100%{opacity:1} 50%{opacity:.2} }
        @keyframes xi-pulse-g { 0%,100%{opacity:1;box-shadow:0 0 0 0 rgba(0,212,138,.4)} 50%{opacity:.7;box-shadow:0 0 0 6px rgba(0,212,138,0)} }
    </style>
</head>

<body>
    <div class="mobile-container">
        @include('member.components.header')

        @yield('content')

        @include('member.components.bottombar')

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>