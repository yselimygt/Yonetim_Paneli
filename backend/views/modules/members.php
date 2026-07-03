<style>
    .members-topbar {
        display: flex; gap: 12px; align-items: center; justify-content: space-between; 
        margin-bottom: 24px; flex-wrap: wrap;
    }
    .topbar-actions {
        display: flex; gap: 10px; flex-wrap: wrap;
    }
    .members-btn {
        padding: 8px 16px; border-radius: 8px; font-size: 14px; font-weight: 500;
        display: inline-flex; align-items: center; gap: 8px; cursor: pointer;
        transition: all 0.2s ease; border: 1px solid transparent; text-decoration: none;
    }
    .members-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .btn-outline {
        background: var(--card); border-color: var(--border); color: var(--text);
    }
    .btn-outline:hover {
        background: var(--bg); border-color: var(--muted);
    }
    .btn-primary {
        background: #3b82f6; color: #fff; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
    }
    .btn-primary:hover {
        background: #2563eb; box-shadow: 0 6px 16px rgba(37, 99, 235, 0.3);
    }
    .btn-danger {
        background: rgba(239, 68, 68, 0.1); color: #ef4444; border-color: rgba(239, 68, 68, 0.2);
    }
    .btn-danger:hover {
        background: rgba(239, 68, 68, 0.15);
    }
    
    .search-wrapper {
        position: relative; width: 280px;
    }
    .search-wrapper input {
        width: 100%; padding: 10px 14px 10px 38px; border-radius: 8px; 
        border: 1px solid var(--border); background: var(--card); color: var(--text);
        transition: border-color 0.2s, box-shadow 0.2s; font-size: 14px;
    }
    .search-wrapper input:focus {
        outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px rgba(0, 188, 212, 0.1);
    }
    .search-wrapper svg {
        position: absolute; left: 14px; top: 50%; transform: translateY(-50%); 
        width: 16px; color: var(--muted); transition: color 0.2s;
    }
    .search-wrapper input:focus + svg { color: var(--accent); }
    
    .members-table-wrapper {
        overflow-x: auto; border-radius: 12px; border: 1px solid var(--border); background: var(--card);
    }
    .members-table {
        width: 100%; border-collapse: collapse; text-align: left; min-width: 800px;
    }
    .members-table th {
        padding: 16px; color: var(--muted); font-size: 12px; font-weight: 600; 
        text-transform: uppercase; border-bottom: 1px solid var(--border); letter-spacing: 0.5px;
    }
    .members-table td {
        padding: 16px; border-bottom: 1px solid var(--border); vertical-align: middle;
    }
    .member-row {
        transition: background-color 0.2s ease;
    }
    .member-row:hover {
        background: var(--bg);
    }
    .member-row:last-child td { border-bottom: none; }
    
    .member-avatar {
        width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; 
        justify-content: center; font-weight: 600; font-size: 14px; flex-shrink: 0;
    }
    .member-info { display: flex; align-items: center; gap: 12px; }
    .member-name { font-weight: 600; color: var(--text); margin-bottom: 2px; font-size: 14px;}
    .member-sub { font-size: 12px; color: var(--muted); }
    
    .action-btn {
        width: 32px; height: 32px; border-radius: 8px; border: 1px solid var(--border); 
        background: var(--card); color: var(--text); cursor: pointer; display: flex; 
        align-items: center; justify-content: center; transition: all 0.2s ease;
    }
    .action-btn:hover { background: var(--bg); transform: translateY(-2px); }
    .action-btn.detail:hover { border-color: #3b82f6; color: #3b82f6; }
    .action-btn.edit:hover { border-color: #f59e0b; color: #f59e0b; }
    .action-btn.delete:hover { border-color: #ef4444; color: #ef4444; }
    .action-btn.suspend:hover { border-color: #ef4444; background: rgba(239, 68, 68, 0.1); }
    
    .modal-overlay {
        display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; 
        background: var(--modal-overlay); z-index: 9999; align-items: center; 
        justify-content: center; backdrop-filter: blur(4px); padding: 20px; overflow-y: auto;
        opacity: 1;
    }
    .modal-content {
        background: var(--card); border: 1px solid var(--border); border-radius: 16px;
        width: 100%; max-width: 500px; box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        animation: modalFadeIn 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    @keyframes modalFadeIn {
        from { opacity: 0; transform: translateY(20px) scale(0.98); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }
    .modal-header {
        display: flex; justify-content: space-between; align-items: center; 
        padding: 20px 24px; border-bottom: 1px solid var(--border);
    }
    .modal-header h3 { margin: 0; font-size: 18px; font-weight: 600; display: flex; align-items: center; gap: 8px; }
    .modal-close {
        background: none; border: none; color: var(--muted); cursor: pointer; 
        padding: 4px; border-radius: 6px; transition: background 0.2s; display:flex;
    }
    .modal-close:hover { background: var(--bg); color: var(--text); }
    .modal-body { padding: 24px; }
    .modal-footer {
        padding: 16px 24px; border-top: 1px solid var(--border); 
        display: flex; justify-content: flex-end; gap: 12px; background: var(--bg);
        border-bottom-left-radius: 16px; border-bottom-right-radius: 16px;
    }
    
    .form-group { margin-bottom: 16px; }
    .form-group label {
        display: block; margin-bottom: 6px; color: var(--text); font-size: 13px; font-weight: 500;
    }
    .form-control {
        width: 100%; padding: 10px 12px; border-radius: 8px; border: 1px solid var(--border); 
        background: var(--input-bg); color: var(--text); font-size: 14px; transition: border-color 0.2s;
    }
    .form-control:focus { outline: none; border-color: var(--accent); }
    .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }
    .members-pagination {
        margin-top: 14px;
        padding: 12px;
        border: 1px solid var(--border);
        border-radius: 12px;
        background: var(--card);
    }
    .members-pagination-info {
        margin-bottom: 10px;
        color: var(--muted);
        font-size: 12px;
        text-align: center;
    }
    .members-pagination-pages {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 8px;
    }
    .members-page-btn {
        min-width: 34px;
        height: 34px;
        padding: 0 10px;
        border: 1px solid var(--border);
        border-radius: 9px;
        background: var(--bg);
        color: var(--text);
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
    }
    .members-page-btn.is-active {
        border-color: rgba(245, 158, 11, 0.45);
        background: rgba(245, 158, 11, 0.16);
        color: #f59e0b;
    }
    .member-detail-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }
    .member-detail-item {
        padding: 10px 12px;
        border: 1px solid var(--border);
        border-radius: 10px;
        background: var(--bg);
    }
    .member-detail-label {
        margin-bottom: 4px;
        color: var(--muted);
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .04em;
        text-transform: uppercase;
    }
    .member-detail-value {
        color: var(--text);
        font-size: 13px;
        font-weight: 600;
        overflow-wrap: anywhere;
    }
    #memberDetailModal .modal-footer {
        grid-template-columns: 1fr;
    }
    .members-no-results {
        display: none;
        margin-top: 12px;
        padding: 28px 16px;
        border: 1px dashed var(--border);
        border-radius: 12px;
        background: var(--card);
        color: var(--muted);
        font-size: 13px;
        text-align: center;
    }
    .members-module .member-row.is-hidden {
        display: none !important;
    }

    @media (max-width: 768px) {
        .members-module {
            padding-bottom: 84px;
        }

        .members-topbar {
            display: grid;
            grid-template-columns: 1fr;
            gap: 10px;
            margin-bottom: 14px;
        }

        .members-topbar > div:first-child {
            display: grid !important;
            grid-template-columns: minmax(0, 1fr) 112px;
            gap: 8px !important;
            align-items: stretch !important;
            width: 100%;
        }

        .search-wrapper {
            width: 100%;
        }

        .search-wrapper input,
        .form-control {
            min-height: 40px;
            border-radius: 10px;
            font-size: 12px;
        }

        #statusFilter {
            width: 100% !important;
            padding: 8px;
            font-size: 11px;
        }

        .topbar-actions {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 8px;
            width: 100%;
        }

        .topbar-actions form {
            display: contents;
        }

        .members-btn {
            min-height: 36px;
            padding: 7px 8px;
            border-radius: 10px;
            font-size: 11px;
            justify-content: center;
            line-height: 1.1;
            text-align: center;
        }

        .members-btn svg {
            width: 14px !important;
            flex-shrink: 0;
        }

        .members-module .members-table-wrapper {
            overflow: visible;
            border: 0;
            border-radius: 0;
            background: transparent;
        }

        .members-module .members-table {
            display: block;
            min-width: 0;
        }

        .members-module .members-table thead {
            display: none;
        }

        .members-module .members-table tbody {
            display: grid;
            grid-template-columns: 1fr;
            gap: 10px;
        }

        .members-module .members-table tr.member-row {
            display: grid;
            grid-template-columns: auto minmax(0, 1fr) auto;
            grid-template-rows: auto auto;
            align-items: center;
            column-gap: 8px;
            row-gap: 3px;
            min-height: 74px;
            padding: 8px 10px;
            border: 1px solid var(--border);
            border-radius: 12px;
            background: var(--card);
            overflow: hidden;
        }

        .members-module .members-table tr:not(.member-row) {
            display: block;
            grid-column: 1 / -1;
            border: 1px dashed var(--border);
            border-radius: 14px;
            background: var(--card);
        }

        .members-module .members-table td {
            display: block;
            padding: 0;
            border: 0;
            min-width: 0;
        }

        .members-module .members-table tr.member-row td:first-child {
            display: flex;
            grid-column: 1;
            grid-row: 1 / 3;
            align-items: center;
            justify-content: center;
            width: 26px;
            height: 26px;
            border-radius: 8px;
            background: rgba(245, 158, 11, 0.12);
            color: #f59e0b !important;
            font-size: 11px !important;
            font-weight: 800;
            line-height: 1;
        }

        .members-module .member-info {
            display: block;
            min-width: 0;
        }

        .members-module .member-avatar,
        .members-module .member-info .member-sub,
        .members-module .member-grade,
        .members-module .members-table td:nth-child(4) {
            display: none;
        }

        .member-name {
            margin-bottom: 0;
            font-size: 12px;
            line-height: 1.25;
            overflow-wrap: anywhere;
        }

        .member-sub {
            font-size: 10px;
            line-height: 1.25;
            overflow-wrap: anywhere;
        }

        .members-module .members-table td:nth-child(3) {
            margin-top: 0;
            grid-column: 2;
            grid-row: 2;
        }

        .members-module .members-table td:nth-child(3)::before {
            display: none;
        }

        .members-module .members-table td:nth-child(3)::before {
            content: "";
        }

        .members-module .members-table td:nth-child(2) {
            grid-column: 2;
            grid-row: 1;
        }

        .members-module .members-table td:last-child {
            grid-column: 3;
            grid-row: 1 / 3;
            margin-top: 0;
            padding-top: 0;
            align-self: center;
        }

        .members-module .members-table td:last-child > div {
            justify-content: flex-end !important;
            gap: 5px !important;
        }

        .action-btn {
            width: 27px;
            height: 27px;
            border-radius: 8px;
        }

        .action-btn svg {
            width: 12px !important;
        }

        .modal-overlay {
            padding: 14px;
            align-items: center;
        }

        .modal-content,
        .modal-content[style] {
            width: 100% !important;
            max-width: 420px !important;
            max-height: calc(100dvh - 28px);
            border-radius: 16px;
            overflow: hidden;
        }

        .modal-header {
            padding: 14px 16px;
        }

        .modal-header h3 {
            font-size: 14px;
        }

        .modal-body {
            max-height: calc(100dvh - 156px);
            overflow-y: auto;
            padding: 16px;
        }

        .modal-footer {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            padding: 12px 16px;
        }

        .grid-2,
        .grid-3 {
            grid-template-columns: 1fr;
            gap: 0;
        }

        .form-group {
            margin-bottom: 12px;
        }

        #redListModal .modal-body {
            padding: 12px !important;
        }

        #redListModal .members-table {
            display: block;
            min-width: 0;
        }

        #redListModal .members-table thead {
            display: none;
        }

        #redListModal .members-table tbody {
            display: grid;
            grid-template-columns: 1fr;
            gap: 10px;
        }

        #redListModal .members-table tr.member-row {
            display: block;
            padding: 12px;
            border: 1px solid rgba(239, 68, 68, 0.22);
            border-radius: 14px;
            background: var(--card);
        }

        #redListModal .members-table td {
            display: block;
            padding: 0;
            border: 0;
            text-align: left !important;
        }

        #redListModal .members-table td + td {
            margin-top: 8px;
        }

        .members-pagination {
            margin-top: 12px;
            padding: 10px;
            border: 1px solid var(--border);
            border-radius: 14px;
            background: var(--card);
        }

        .members-pagination-info {
            margin-bottom: 8px;
            color: var(--muted);
            font-size: 11px;
            text-align: center;
        }

        .members-pagination-pages {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 6px;
        }

        .members-page-btn {
            min-width: 30px;
            height: 30px;
            padding: 0 9px;
            border: 1px solid var(--border);
            border-radius: 9px;
            background: var(--bg);
            color: var(--text);
            font-size: 11px;
            font-weight: 700;
            cursor: pointer;
        }

        .members-page-btn.is-active {
            border-color: rgba(245, 158, 11, 0.45);
            background: rgba(245, 158, 11, 0.16);
            color: #f59e0b;
        }

        .member-detail-grid {
            grid-template-columns: 1fr;
            gap: 8px;
        }

        .member-detail-item {
            padding: 9px 10px;
            border-radius: 10px;
        }

        .member-detail-label {
            font-size: 10px;
        }

        .member-detail-value {
            font-size: 12px;
        }
    }

    @media (max-width: 380px) {
        .members-module .members-table tbody {
            gap: 8px;
        }

        .members-module .members-table tr.member-row {
            min-height: 68px;
            padding: 7px 9px;
        }

        .topbar-actions {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    .members-mobile-hero {
        display: none;
    }

    @media (max-width: 768px) {
        .members-module {
            padding-bottom: 96px;
        }

        .members-mobile-hero {
            display: grid;
            gap: 8px;
            margin-bottom: 14px;
            padding: 0 0 14px;
            border-bottom: 1px solid var(--border);
        }

        .members-mobile-kicker {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--accent);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.3px;
            text-transform: uppercase;
        }

        .members-mobile-kicker::before {
            content: "";
            width: 20px;
            height: 1px;
            background: var(--accent);
        }

        .members-mobile-hero h2 {
            margin: 0;
            color: var(--text);
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.04em;
        }

        .members-mobile-hero p {
            margin: 4px 0 0;
            color: var(--muted);
            font-size: 12px;
        }

        .members-topbar {
            display: grid;
            gap: 10px;
            margin-bottom: 12px;
        }

        .members-topbar > div:first-child {
            display: grid !important;
            grid-template-columns: 1fr;
            gap: 8px !important;
            width: 100%;
        }

        .search-wrapper,
        #statusFilter {
            width: 100% !important;
        }

        .search-wrapper input,
        #statusFilter {
            min-height: 42px;
            border-radius: 8px;
            background: var(--card);
            font-size: 13px;
        }

        .topbar-actions {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 8px;
            width: 100%;
        }

        .topbar-actions form {
            display: contents;
        }

        .members-btn {
            min-height: 34px;
            padding: 6px 8px;
            border-radius: 7px;
            justify-content: center;
            font-size: 11px;
            line-height: 1.15;
            text-align: center;
            box-shadow: none;
            transform: none;
        }

        .members-btn svg {
            width: 13px !important;
        }

        .topbar-actions .btn-primary {
            grid-column: 1 / -1;
            order: -1;
        }

        .members-module .members-table-wrapper {
            overflow: visible;
            border: 0;
            background: transparent;
        }

        .members-module .members-table {
            display: block;
            min-width: 0;
        }

        .members-module .members-table thead {
            display: none;
        }

        .members-module .members-table tbody {
            display: grid;
            gap: 8px;
        }

        .members-module .members-table tbody > tr.member-row {
            display: grid !important;
            grid-template-columns: 28px minmax(0, 1fr) auto;
            grid-template-areas:
                "index person actions";
            align-items: center;
            gap: 8px;
            min-height: 0;
            padding: 8px 9px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: var(--card);
        }

        .members-module .members-table tbody > tr.member-row.is-hidden {
            display: none !important;
        }

        .members-module .members-table tbody > tr:not(.member-row) {
            display: block;
            padding: 24px 14px;
            border: 1px dashed var(--border);
            border-radius: 8px;
            background: var(--card);
        }

        .members-module .members-table td {
            display: block;
            min-width: 0;
            padding: 0;
            border: 0;
        }

        .members-module .members-table tbody > tr.member-row > td:nth-child(1) {
            grid-area: index;
            display: flex;
            width: 28px;
            height: 28px;
            align-items: center;
            justify-content: center;
            border-radius: 7px;
            background: var(--input-bg);
            color: var(--accent) !important;
            font-size: 10px !important;
            font-weight: 800;
        }

        .members-module .members-table tbody > tr.member-row > td:nth-child(2) {
            grid-area: person;
        }

        .members-module .members-table tbody > tr.member-row > td:nth-child(3) {
            display: none;
        }

        .members-module .members-table tbody > tr.member-row > td:nth-child(4) {
            display: none;
        }

        .members-module .members-table tbody > tr.member-row > td:nth-child(4)::before,
        .members-module .members-table tbody > tr.member-row > td:nth-child(4)::after {
            display: none;
        }

        .members-module .members-table tbody > tr.member-row > td:nth-child(5) {
            grid-area: actions;
            padding-top: 0;
            border-top: 0;
        }

        .members-module .member-info {
            display: flex !important;
            gap: 8px;
            align-items: center;
            min-width: 0;
        }

        .members-module .member-avatar {
            display: none !important;
        }

        .members-module .member-name {
            margin: 0;
            font-size: 12px;
            line-height: 1.25;
            overflow-wrap: anywhere;
        }

        .members-module .member-sub,
        .members-module .member-info .member-sub {
            display: none !important;
            margin-top: 2px;
            font-size: 10px;
            line-height: 1.3;
            overflow-wrap: anywhere;
        }

        .members-module .member-grade {
            display: inline !important;
        }

        .members-module .members-table tbody > tr.member-row > td:nth-child(4) .member-name,
        .members-module .members-table tbody > tr.member-row > td:nth-child(4) .member-sub {
            display: block;
            padding: 6px 7px;
            border: 1px solid var(--border);
            border-radius: 7px;
            background: var(--input-bg);
        }

        .members-module .members-table tbody > tr.member-row > td:nth-child(4) .member-sub {
            margin-top: 0;
        }

        .members-module .members-table td:last-child > div {
            display: flex !important;
            gap: 4px !important;
            justify-content: flex-end !important;
            align-items: center;
        }

        .members-module .members-table td:last-child form {
            display: contents;
        }

        .action-btn {
            width: 28px;
            height: 28px;
            border-radius: 6px;
            background: var(--input-bg);
            transform: none;
        }

        .action-btn svg {
            width: 12px !important;
        }

        .modal-overlay {
            align-items: center;
            justify-content: center;
            padding: 14px;
            height: 100dvh;
            overflow: hidden;
        }

        .modal-content,
        .modal-content[style] {
            display: flex;
            flex-direction: column;
            width: 100% !important;
            max-width: 440px !important;
            max-height: calc(100dvh - 28px);
            border: 1px solid var(--border);
            border-radius: 12px !important;
            overflow: hidden;
        }

        #redListModal .modal-content,
        #memberModal .modal-content {
            max-width: 560px !important;
        }

        .modal-header {
            flex: 0 0 auto;
            padding: 14px 16px;
        }

        .modal-body {
            flex: 1 1 auto;
            min-height: 0;
            max-height: none;
            overflow-y: auto;
            padding: 16px;
            -webkit-overflow-scrolling: touch;
        }

        .modal-footer {
            flex: 0 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            padding: 12px 16px;
            border-radius: 0 !important;
        }

        #memberModal form,
        #excelModal form#uploadForm,
        #suspendModal form#suspendForm {
            min-height: 0;
        }

        #memberModal form {
            display: flex;
            flex: 1 1 auto;
            flex-direction: column;
            min-height: 0;
        }

        #memberDetailModal .modal-footer,
        #redListModal .modal-footer {
            grid-template-columns: 1fr;
        }

        .members-pagination {
            padding: 8px;
            border-radius: 8px;
        }

        .members-pagination-info {
            margin-bottom: 8px;
            font-size: 11px;
        }

        .members-pagination-pages {
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            gap: 6px;
            align-items: center;
        }

        .members-page-btn {
            height: 32px;
            min-width: 32px;
            border-radius: 7px;
            font-size: 11px;
        }

        .members-page-btn:not(.members-page-prev):not(.members-page-next):not(.is-active) {
            display: none;
        }

        .members-page-btn.is-active {
            min-width: 44px;
            padding: 0 12px;
        }

        .members-page-btn:disabled {
            opacity: .38;
            cursor: not-allowed;
        }
    }
</style>

<div class="members-module">
    <div class="members-mobile-hero">
        <span class="members-mobile-kicker">Üye Yönetimi</span>
        <div>
            <h2>Üye Listesi</h2>
            <p><?= count($members ?? []) ?> aktif kayıt görüntüleniyor.</p>
        </div>
    </div>

    <!-- Üst Bar -->
    <div class="members-topbar">
        <div style="display:flex; gap:12px; align-items:center;">
            <!-- Arama -->
            <div class="search-wrapper">
                <input type="text" id="memberSearch" placeholder="İsim, e-posta veya no ile ara..." oninput="filterMembers()">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            </div>
            
            <!-- Durumlar Filtresi -->
            <select id="statusFilter" class="form-control" onchange="filterMembers()" style="width: auto;">
                <option value="">Tüm Durumlar</option>
                <option value="active">Sadece Aktifler</option>
            </select>
        </div>
        
        <div class="topbar-actions">
            <!-- Excel Yükle -->
            <button onclick="openMembersModal('excelModal')" class="members-btn btn-outline">
                <svg style="width:16px; color:#3b82f6;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                Yükle
            </button>

            <!-- Dışa Aktar -->
            <form method="POST" style="margin:0;">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                <input type="hidden" name="action" value="export_excel">
                <button type="submit" class="members-btn btn-outline">
                    <svg style="width:16px; color:#10b981;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                    Dışa Aktar
                </button>
            </form>
            
            <!-- Excel Şablon İndir -->
            <form method="POST" style="margin:0;">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                <input type="hidden" name="action" value="download_template">
                <button type="submit" class="members-btn btn-outline" title="Excel Yükleme Şablonu">
                    <svg style="width:16px; color:#8b5cf6;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                    Şablon
                </button>
            </form>
            
            <!-- Kırmızı Liste -->
            <button onclick="openMembersModal('redListModal')" class="members-btn btn-danger">
                <span style="display:inline-block; width:8px; height:8px; border-radius:50%; background:#ef4444; box-shadow: 0 0 6px rgba(239, 68, 68, 0.8);"></span>
                Kırmızı Liste
            </button>

            <!-- Yeni Üye -->
            <button onclick="openMemberModal()" class="members-btn btn-primary">
                + Yeni Üye
            </button>
        </div>
    </div>

    <!-- Üye Tablosu -->
    <div class="members-table-wrapper">
        <table class="members-table">
            <thead>
                <tr>
                    <th style="width: 50px; text-align:center;">#</th>
                    <th>AD SOYAD</th>
                    <th>EĞİTİM BİLGİSİ</th>
                    <th>ÖĞRENCİ NO / İLETİŞİM</th>
                    <th style="text-align: right;">İŞLEM</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($members)): ?>
                    <tr>
                        <td colspan="5" style="text-align:center; padding:40px; color:var(--muted);">
                            <svg style="width:48px; margin:0 auto 16px; opacity:0.5;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                            <p style="margin:0; font-size:15px;">Kayıtlı aktif üye bulunmamaktadır.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php 
                    $colors = ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#ec4899', '#06b6d4'];
                    foreach ($members as $index => $m): 
                        $avatarColor = $colors[$m['id'] % count($colors)];
                    ?>
                    <tr class="member-row" data-search="<?= strtolower(htmlspecialchars($m['first_name'].' '.$m['last_name'].' '.$m['email'].' '.$m['student_id'])) ?>" data-status="<?= htmlspecialchars($m['status'] ?? 'active', ENT_QUOTES, 'UTF-8') ?>">
                        <td style="text-align:center; color: var(--muted); font-size:13px;"><?= $index + 1 ?></td>
                        <td>
                            <div class="member-info">
                                <div class="member-avatar" style="background: <?= $avatarColor ?>20; color: <?= $avatarColor ?>;">
                                    <?= mb_strtoupper(mb_substr($m['first_name'],0,1) . mb_substr($m['last_name'],0,1)) ?>
                                </div>
                                <div>
                                    <div class="member-name"><?= htmlspecialchars($m['first_name'] . ' ' . $m['last_name']) ?></div>
                                    <div class="member-sub"><?= htmlspecialchars($m['email']) ?></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="member-name"><?= htmlspecialchars($m['university'] ?: '-') ?></div>
                            <div class="member-sub"><?= htmlspecialchars($m['department'] ?: '-') ?> <span class="member-grade">(<?= htmlspecialchars($m['grade'] ?: '-') ?>)</span></div>
                        </td>
                        <td>
                            <div class="member-name"><?= htmlspecialchars($m['student_id'] ?: '-') ?></div>
                            <div class="member-sub"><?= htmlspecialchars($m['phone'] ?: '-') ?></div>
                        </td>
                        <td>
                            <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                <button
                                    class="action-btn detail"
                                    type="button"
                                    data-first-name="<?= htmlspecialchars($m['first_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                    data-last-name="<?= htmlspecialchars($m['last_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                    data-email="<?= htmlspecialchars($m['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                    data-phone="<?= htmlspecialchars($m['phone'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                    data-student-id="<?= htmlspecialchars($m['student_id'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                    data-university="<?= htmlspecialchars($m['university'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                    data-faculty="<?= htmlspecialchars($m['faculty'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                    data-department="<?= htmlspecialchars($m['department'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                    data-grade="<?= htmlspecialchars($m['grade'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                    data-status="<?= htmlspecialchars($m['status'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                    data-suspend-reason="<?= htmlspecialchars($m['suspend_reason'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                    data-created-at="<?= htmlspecialchars($m['created_at'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                    title="Detay"
                                >
                                    <svg style="width:14px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                                </button>
                                <button class="action-btn edit" onclick="openMemberModal(<?= htmlspecialchars(json_encode($m)) ?>)" title="Düzenle">
                                    <svg style="width:14px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </button>
                                <form method="POST" style="margin:0;" onsubmit="return confirm('Bu üyeyi tamamen silmek istediğinize emin misiniz? Bu işlem geri alınamaz.');">
                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="member_id" value="<?= $m['id'] ?>">
                                    <button type="submit" class="action-btn delete" title="Sil">
                                        <svg style="width:14px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                    </button>
                                </form>
                                <?php if ($userRole === 'director' || Auth::isSuperAdmin()): ?>
                                <button class="action-btn suspend" onclick="openSuspendModal(<?= $m['id'] ?>)" title="Kırmızı Listeye Ekle">
                                    <svg style="width:14px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                                </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div id="membersNoResults" class="members-no-results">Aramana uygun üye bulunamadı.</div>
    <div id="membersPagination" class="members-pagination" style="display:none;"></div>
</div>

<!-- Excel Yükle Modal -->
<div id="excelModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3>
                <svg style="width:20px; color:#3b82f6;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                Excel İle Üye Yükle
            </h3>
            <button class="modal-close" onclick="closeMembersModal('excelModal')">
                <svg style="width:20px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
        <div class="modal-body">
            <div style="background: var(--bg); padding: 16px; border-radius: 8px; margin-bottom: 24px; font-size: 13px; color: var(--text); border: 1px solid var(--border);">
                <p style="margin-bottom:8px;"><strong>Format:</strong> Sadece .xlsx uzantılı dosyalar kabul edilir.</p>
                <p style="margin-bottom:8px;"><strong>Sütun Sırası:</strong> ID, Ad, Soyad, E-posta, Öğrenci No, Fakülte, Bölüm, Sınıf, Telefon</p>
                <p style="color:var(--muted); font-style:italic; margin:0;">* Öğrenci No ve E-posta benzersiz olmalıdır. Mevcut olanlar aktarılmaz.</p>
                
                <form method="POST" style="margin-top:16px;">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                    <input type="hidden" name="action" value="download_template">
                    <button type="submit" class="members-btn btn-outline" style="font-size:12px; padding:6px 12px;">
                        <svg style="width:14px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                        Örnek Şablonu İndir
                    </button>
                </form>
            </div>
            
            <form method="POST" enctype="multipart/form-data" id="uploadForm">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                <input type="hidden" name="action" value="upload_excel">
                <div class="form-group">
                    <label>Excel Dosyasını Seçin</label>
                    <input type="file" name="excel_file" accept=".xlsx" class="form-control" required style="padding: 8px;">
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="members-btn btn-outline" onclick="closeMembersModal('excelModal')">İptal</button>
            <button class="members-btn btn-primary" onclick="document.getElementById('uploadForm').submit()">Yüklemeyi Başlat</button>
        </div>
    </div>
</div>

<!-- Kırmızı Liste Modal -->
<div id="redListModal" class="modal-overlay">
    <div class="modal-content" style="max-width: 800px;">
        <div class="modal-header">
            <h3 style="color:#ef4444;">
                <span style="display:inline-block; width:12px; height:12px; border-radius:50%; background:#ef4444; box-shadow: 0 0 8px rgba(239, 68, 68, 0.6);"></span>
                Kırmızı Liste (Askıya Alınanlar)
            </h3>
            <button class="modal-close" onclick="closeMembersModal('redListModal')">
                <svg style="width:20px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
        
        <div class="modal-body" style="padding:0; overflow-x:auto; border-bottom-left-radius: 16px; border-bottom-right-radius: 16px;">
            <table class="members-table" style="min-width:100%;">
                <thead>
                    <tr>
                        <th>UZAKLAŞTIRILAN ÜYE</th>
                        <th>ÖĞRENCİ NO / TELEFON</th>
                        <th>SEBEP / DURUM</th>
                        <?php if ($userRole === 'director' || Auth::isSuperAdmin()): ?>
                        <th style="text-align: right;">İŞLEM</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($redList)): ?>
                        <tr><td colspan="4" style="text-align:center; padding:32px; color:var(--muted);">Kırmızı listede üye bulunmamaktadır.</td></tr>
                    <?php else: ?>
                        <?php foreach ($redList as $rm): ?>
                            <tr class="member-row">
                                <td>
                                    <div class="member-info">
                                        <div class="member-avatar" style="background:rgba(239, 68, 68, 0.1); color:#ef4444;">
                                            <?= mb_strtoupper(mb_substr($rm['first_name'],0,1) . mb_substr($rm['last_name'],0,1)) ?>
                                        </div>
                                        <div>
                                            <div class="member-name" style="color:#ef4444;"><?= htmlspecialchars($rm['first_name'] . ' ' . $rm['last_name']) ?></div>
                                            <div class="member-sub"><?= htmlspecialchars($rm['email']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="member-name"><?= htmlspecialchars($rm['student_id'] ?: '-') ?></div>
                                    <div class="member-sub"><?= htmlspecialchars($rm['phone'] ?: '-') ?></div>
                                </td>
                                <td>
                                    <div style="display:inline-block; padding:4px 10px; border-radius:20px; border:1px solid rgba(239,68,68,0.3); background:rgba(239,68,68,0.1); color:#ef4444; font-size:11px; font-weight:600; margin-bottom:6px;">ASKIDA</div>
                                    <div class="member-sub"><?= htmlspecialchars($rm['suspend_reason'] ?: 'Sebep belirtilmedi') ?></div>
                                </td>
                                <?php if ($userRole === 'director' || Auth::isSuperAdmin()): ?>
                                <td style="text-align: right;">
                                    <form method="POST" style="margin:0;">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                                        <input type="hidden" name="action" value="restore">
                                        <input type="hidden" name="member_id" value="<?= $rm['id'] ?>">
                                        <button type="submit" class="members-btn btn-outline" style="font-size:12px; padding:6px 12px;">Geri Yükle</button>
                                    </form>
                                </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Askıya Al Modal -->
<div id="suspendModal" class="modal-overlay">
    <div class="modal-content" style="max-width: 400px;">
        <div class="modal-header" style="border-bottom:none; padding-bottom:10px;">
            <h3 style="color:#ef4444;">Üyeyi Askıya Al</h3>
            <button class="modal-close" onclick="closeMembersModal('suspendModal')">
                <svg style="width:20px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
        <div class="modal-body" style="padding-top:0;">
            <p style="color:var(--muted); font-size:14px; margin-bottom:20px; line-height:1.5;">Bu üyeyi kırmızı listeye eklemek üzeresiniz. Lütfen işlem için bir sebep belirtin.</p>
            <form method="POST" id="suspendForm">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                <input type="hidden" name="action" value="suspend">
                <input type="hidden" name="member_id" id="suspendMemberId" value="">
                <div class="form-group">
                    <label>Askıya Alma Sebebi:</label>
                    <input type="text" name="suspend_reason" class="form-control" placeholder="Örn: Etkinlik kurallarına aykırı davranış" required>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="members-btn btn-outline" onclick="closeMembersModal('suspendModal')">İptal</button>
            <button class="members-btn btn-danger" onclick="document.getElementById('suspendForm').submit()" style="color:#fff; background:#ef4444;">Askıya Al</button>
        </div>
    </div>
</div>

<!-- Üye Detay Modal -->
<div id="memberDetailModal" class="modal-overlay">
    <div class="modal-content" style="max-width: 560px;">
        <div class="modal-header">
            <h3 id="memberDetailTitle">Üye Detayı</h3>
            <button class="modal-close" onclick="closeMembersModal('memberDetailModal')">
                <svg style="width:20px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
        <div class="modal-body">
            <div id="memberDetailContent" class="member-detail-grid"></div>
        </div>
        <div class="modal-footer">
            <button type="button" class="members-btn btn-outline" onclick="closeMembersModal('memberDetailModal')">Kapat</button>
        </div>
    </div>
</div>

<!-- Yeni/Düzenle Üye Modal -->
<div id="memberModal" class="modal-overlay">
    <div class="modal-content" style="max-width: 650px;">
        <div class="modal-header">
            <h3 id="memberModalTitle">Yeni Üye Ekle</h3>
            <button class="modal-close" onclick="closeMembersModal('memberModal')">
                <svg style="width:20px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
        
        <form method="POST" id="memberForm">
            <div class="modal-body">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                <input type="hidden" name="action" id="memberAction" value="create">
                <input type="hidden" name="member_id" id="memberId" value="">
                
                <div class="grid-2">
                    <div class="form-group">
                        <label>Ad <span style="color:#ef4444">*</span></label>
                        <input type="text" name="first_name" id="memberFirstName" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Soyad <span style="color:#ef4444">*</span></label>
                        <input type="text" name="last_name" id="memberLastName" class="form-control" required>
                    </div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label>E-posta Adresi <span style="color:#ef4444">*</span></label>
                        <input type="email" name="email" id="memberEmail" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Telefon Numarası</label>
                        <input type="text" name="phone" id="memberPhone" class="form-control phone-mask" placeholder="(555) 000 00 00" maxlength="15">
                    </div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label>Öğrenci Numarası</label>
                        <input type="text" name="student_id" id="memberStudentId" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Üniversite</label>
                        <input type="text" name="university" id="memberUniversity" class="form-control">
                    </div>
                </div>

                <div class="grid-3">
                    <div class="form-group">
                        <label>Fakülte</label>
                        <input type="text" name="faculty" id="memberFaculty" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Bölüm</label>
                        <input type="text" name="department" id="memberDepartment" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Sınıf</label>
                        <select name="grade" id="memberGrade" class="form-control">
                            <option value="">Seçiniz</option>
                            <option value="Hazırlık">Hazırlık</option>
                            <option value="1. Sınıf">1. Sınıf</option>
                            <option value="2. Sınıf">2. Sınıf</option>
                            <option value="3. Sınıf">3. Sınıf</option>
                            <option value="4. Sınıf">4. Sınıf</option>
                            <option value="Yüksek Lisans">Yüksek Lisans</option>
                            <option value="Mezun">Mezun</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="members-btn btn-outline" onclick="closeMembersModal('memberModal')">İptal</button>
                <button type="submit" class="members-btn btn-primary">Kaydet</button>
            </div>
        </form>
    </div>
</div>

<script>
const membersPerPage = 25;
let currentMembersPage = 1;

function openMembersModal(id) {
    const modal = document.getElementById(id);
    if (!modal) return;
    modal.style.display = 'flex';
    modal.offsetHeight;
    modal.classList.add('show');
    modal.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
}

function closeMembersModal(id) {
    const modal = document.getElementById(id);
    if (!modal) return;
    modal.classList.remove('show');
    modal.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
    modal.style.display = 'none';
}

function getMemberRows() {
    return Array.from(document.querySelectorAll('.members-module .member-row'));
}

function getFilteredMemberRows() {
    const searchInput = document.getElementById('memberSearch');
    const statusFilter = document.getElementById('statusFilter');
    const search = searchInput ? searchInput.value.toLowerCase() : '';
    const status = statusFilter ? statusFilter.value : '';

    return getMemberRows().filter(row => {
        const text = row.getAttribute('data-search') || '';
        const rowStatus = row.getAttribute('data-status') || 'active';
        return text.includes(search) && (!status || rowStatus === status);
    });
}

function filterMembers() {
    currentMembersPage = 1;
    renderMembersPage();
}

function goToMembersPage(page) {
    const totalPages = Math.max(1, Math.ceil(getFilteredMemberRows().length / membersPerPage));
    currentMembersPage = Math.min(Math.max(1, page), totalPages);
    renderMembersPage(true);
}

function renderMembersPage(shouldScroll = false) {
    const allRows = getMemberRows();
    const filteredRows = getFilteredMemberRows();
    const totalPages = Math.max(1, Math.ceil(filteredRows.length / membersPerPage));
    const pagination = document.getElementById('membersPagination');
    const noResults = document.getElementById('membersNoResults');

    if (currentMembersPage > totalPages) {
        currentMembersPage = totalPages;
    }

    const start = (currentMembersPage - 1) * membersPerPage;
    const end = start + membersPerPage;
    const visibleRows = new Set(filteredRows.slice(start, end));

    allRows.forEach(row => {
        row.classList.toggle('is-hidden', !visibleRows.has(row));
    });

    if (noResults) {
        noResults.style.display = filteredRows.length === 0 && allRows.length > 0 ? 'block' : 'none';
    }

    if (!pagination) {
        return;
    }

    if (filteredRows.length <= membersPerPage) {
        pagination.style.display = 'none';
        pagination.innerHTML = '';
        return;
    }

    const startText = start + 1;
    const endText = Math.min(end, filteredRows.length);
    let pageButtons = `
        <button type="button" class="members-page-btn members-page-prev" onclick="goToMembersPage(${currentMembersPage - 1})" ${currentMembersPage === 1 ? 'disabled' : ''}>Önceki</button>
    `;

    for (let page = 1; page <= totalPages; page++) {
        pageButtons += `<button type="button" class="members-page-btn ${page === currentMembersPage ? 'is-active' : ''}" onclick="goToMembersPage(${page})">${page}</button>`;
    }

    pageButtons += `
        <button type="button" class="members-page-btn members-page-next" onclick="goToMembersPage(${currentMembersPage + 1})" ${currentMembersPage === totalPages ? 'disabled' : ''}>Sonraki</button>
    `;

    pagination.style.display = '';
    pagination.innerHTML = `
        <div class="members-pagination-info">${filteredRows.length} kişiden ${startText}-${endText} arası gösteriliyor</div>
        <div class="members-pagination-pages">${pageButtons}</div>
    `;

    if (shouldScroll) {
        requestAnimationFrame(() => {
            document.querySelector('.members-module')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    }
}

function escapeMemberValue(value) {
    return String(value ?? '-')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

function openMemberDetailModalFromButton(button) {
    openMemberDetailModal({
        first_name: button.dataset.firstName || '',
        last_name: button.dataset.lastName || '',
        email: button.dataset.email || '',
        phone: button.dataset.phone || '',
        student_id: button.dataset.studentId || '',
        university: button.dataset.university || '',
        faculty: button.dataset.faculty || '',
        department: button.dataset.department || '',
        grade: button.dataset.grade || '',
        status: button.dataset.status || '',
        suspend_reason: button.dataset.suspendReason || '',
        created_at: button.dataset.createdAt || ''
    });
}

function openMemberDetailModal(data) {
    const modal = document.getElementById('memberDetailModal');
    const title = document.getElementById('memberDetailTitle');
    const content = document.getElementById('memberDetailContent');

    if (!modal || !content) {
        return;
    }

    const fullName = `${data.first_name || ''} ${data.last_name || ''}`.trim() || 'Üye Detayı';
    const fields = [
        ['Ad Soyad', fullName],
        ['E-posta', data.email],
        ['Telefon', data.phone],
        ['Öğrenci No', data.student_id],
        ['Üniversite', data.university],
        ['Fakülte', data.faculty],
        ['Bölüm', data.department],
        ['Sınıf', data.grade],
        ['Durum', data.status],
        ['Askıya Alma Sebebi', data.suspend_reason],
        ['Kayıt Tarihi', data.created_at]
    ];

    if (title) {
        title.textContent = fullName;
    }

    content.innerHTML = fields.map(([label, value]) => `
        <div class="member-detail-item">
            <div class="member-detail-label">${escapeMemberValue(label)}</div>
            <div class="member-detail-value">${escapeMemberValue(value || '-')}</div>
        </div>
    `).join('');

    openMembersModal('memberDetailModal');
}

document.addEventListener('click', function(event) {
    const detailButton = event.target.closest('.action-btn.detail');
    if (!detailButton) {
        return;
    }

    event.preventDefault();
    openMemberDetailModalFromButton(detailButton);
});

function openMemberModal(data = null) {
    const modal = document.getElementById('memberModal');
    const form = document.getElementById('memberForm');
    
    if (data) {
        document.getElementById('memberModalTitle').textContent = 'Üye Bilgilerini Düzenle';
        document.getElementById('memberAction').value = 'update';
        document.getElementById('memberId').value = data.id;
        document.getElementById('memberFirstName').value = data.first_name;
        document.getElementById('memberLastName').value = data.last_name;
        document.getElementById('memberEmail').value = data.email;
        document.getElementById('memberPhone').value = data.phone;
        document.getElementById('memberStudentId').value = data.student_id;
        document.getElementById('memberUniversity').value = data.university;
        document.getElementById('memberFaculty').value = data.faculty;
        document.getElementById('memberDepartment').value = data.department;
        document.getElementById('memberGrade').value = data.grade;
    } else {
        document.getElementById('memberModalTitle').textContent = 'Yeni Üye Ekle';
        document.getElementById('memberAction').value = 'create';
        document.getElementById('memberId').value = '';
        form.reset();
    }
    
    openMembersModal('memberModal');
}

function openSuspendModal(id) {
    document.getElementById('suspendMemberId').value = id;
    openMembersModal('suspendModal');
}

// Close modals when clicking outside
window.onclick = function(event) {
    if (event.target.classList.contains('modal-overlay')) {
        closeMembersModal(event.target.id);
    }
}

// Phone Mask
document.addEventListener("DOMContentLoaded", function() {
    renderMembersPage();

    const phoneInputs = document.querySelectorAll('.phone-mask');
    phoneInputs.forEach(input => {
        input.addEventListener('input', function(e) {
            let x = e.target.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,2})(\d{0,2})/);
            if (!x[1]) {
                e.target.value = '';
                return;
            }
            e.target.value = !x[2] ? '(' + x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? ' ' + x[3] : '') + (x[4] ? ' ' + x[4] : '');
        });
    });
});
</script>
