@extends('layouts.user')
@section('title', 'Notifikasi')
@section('content')

<style>
    /* VARIABLES CONSISTENT WITH BERANDA */
    :root {
        --primary-color: #6293c4;
        --primary-hover: #4a7cb0;
        --primary-light: #E8F4FD;
        --text-dark: #1A202C;
        --text-light: #64748b;
        --bg-light: #f8fafc;
        --card-bg: #FFFFFF;
        --success: #1AC42E;
        --warning: #F59E0B;
        --danger: #FE2222;
        --info: #6293c4;
        --card-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        --card-radius: 12px;
        --chip-radius: 16px;
        --transition: all 0.3s ease;
    }

    .notification-page {
        background: white !important;
        min-height: 100vh;
        padding: 16px 0;
    }

    /* PAGE CONTENT - DIKECILKAN */
    .page-content {
        width: 100%;
        max-width: 700px;
        margin: 0 auto;
        padding: 0 16px;
    }

    @media (min-width: 768px) {
        .page-content {
            padding: 0 20px;
        }
    }

    /* BACK BUTTON - DIKECILKAN */
    .back-button-header {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: white;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        transition: var(--transition);
        padding: 8px 16px;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
        border-radius: var(--chip-radius);
        box-shadow: 0 2px 8px rgba(98, 147, 196, 0.3);
        position: absolute;
        left: 16px;
        top: 16px;
        z-index: 100;
        border: none;
        cursor: pointer;
    }

    .back-button-header:hover {
        transform: translateX(-2px);
        box-shadow: 0 4px 12px rgba(98, 147, 196, 0.4);
    }

    /* FILTER SECTION - DIKECILKAN */
    .filter-section {
        background: transparent !important;
        padding: 18px;
        margin-bottom: 20px;
    }

    .filter-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 1px solid var(--primary-light);
    }

    .filter-header h2 {
        color: var(--text-dark);
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        
    }

    .notification-count {
        background: var(--primary-color);
        color: white;
        padding: 4px 10px;
        border-radius: var(--chip-radius);
        font-size: 12px;
        font-weight: 600;
        box-shadow: 0 2px 6px rgba(98, 147, 196, 0.3);
    }

    .filter-notifikasi {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        justify-content: flex-start;
        align-items: center;
    }

    .filter-notifikasi button {
        padding: 8px 14px;
        border: none;
        border-radius: var(--chip-radius);
        background: #ffff;
        color: var(--primary-color);
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        box-shadow: 0 1px 4px rgba(0,0,0,0.05);
        border: 1px solid transparent;
    }

    .filter-notifikasi button:hover {
        background: rgba(98, 147, 196, 0.15);
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(98, 147, 196, 0.2);
    }

    .filter-notifikasi button.active {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(98, 147, 196, 0.3);
    }

    .mark-all-btn {
        margin-left: auto;
        padding: 8px 16px;
        background: linear-gradient(135deg, var(--success) 0%, #1ac42ecc 100%);
        color: white;
        border: none;
        border-radius: var(--chip-radius);
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        display: flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 2px 6px rgba(26, 196, 46, 0.3);
    }

    .mark-all-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(26, 196, 46, 0.4);
    }

    /* NOTIFICATION CARD - DIKECILKAN */
    .notifikasi-container {
        background: transparent;
        border-radius: var(--card-radius);
        padding: 0;
        box-shadow: none;
        overflow: hidden;
        margin-bottom: 30px;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .notifikasi-card {
        background: white;
        border-radius: var(--card-radius);
        padding: 16px;
        box-shadow: var(--card-shadow);
        border: 1px solid rgba(98, 147, 196, 0.1);
        transition: var(--transition);
        position: relative;
        overflow: hidden;
        animation: slideIn 0.4s ease-out;
        animation-fill-mode: both;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(-10px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .notifikasi-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 3px;
        height: 100%;
        background: var(--primary-color);
        transition: var(--transition);
    }

    .notifikasi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .notifikasi-card.unread {
        background: #f8fbff;
        border: 1px solid var(--primary-light);
    }

    .notifikasi-card.unread::before {
        background: linear-gradient(180deg, var(--primary-color) 0%, var(--primary-hover) 100%);
        width: 4px;
    }

    /* CARD HEADER - DIKECILKAN */
    .card-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 12px;
        gap: 12px;
    }

    .card-title-section {
        display: flex;
        align-items: center;
        gap: 12px;
        flex: 1;
    }

    /* NOTIFICATION ICON - DIKECILKAN */
    .notifikasi-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 16px;
        position: relative;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    .notifikasi-icon.booking_success,
    .notifikasi-icon.payment_success {
        background: linear-gradient(135deg, var(--success) 0%, #1ac42ecc 100%);
        color: white;
    }

    .notifikasi-icon.review {
        background: linear-gradient(135deg, var(--warning) 0%, #f59e0bcc 100%);
        color: white;
    }

    .notifikasi-icon.reminder {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
        color: white;
    }

    .notifikasi-icon.promo {
        background: linear-gradient(135deg, #9F7AEA 0%, #9F7AEA 100%);
        color: white;
    }

    .notifikasi-icon.booking_cancelled {
        background: linear-gradient(135deg, var(--danger) 0%, #fe2222cc 100%);
        color: white;
    }

    /* NOTIFICATION TITLE - DIKECILKAN */
    .notifikasi-title-section {
        flex: 1;
    }

    .notifikasi-title-section h4 {
        font-size: 15px;
        margin-bottom: 4px;
        color: var(--text-dark);
        font-weight: 700;
        line-height: 1.3;
    }

    .notification-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
        margin-bottom: 6px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .badge-booking {
        background: rgba(26, 196, 46, 0.1);
        color: var(--success);
        border: 1px solid rgba(26, 196, 46, 0.2);
    }

    .badge-payment {
        background: rgba(98, 147, 196, 0.1);
        color: var(--primary-color);
        border: 1px solid rgba(98, 147, 196, 0.2);
    }

    .badge-reminder {
        background: rgba(245, 158, 11, 0.1);
        color: var(--warning);
        border: 1px solid rgba(245, 158, 11, 0.2);
    }

    .badge-promo {
        background: rgba(159, 122, 234, 0.1);
        color: #9F7AEA;
        border: 1px solid rgba(159, 122, 234, 0.2);
    }

    .badge-review {
        background: rgba(254, 34, 34, 0.1);
        color: var(--danger);
        border: 1px solid rgba(254, 34, 34, 0.2);
    }

    /* NOTIFICATION CONTENT - DIKECILKAN */
    .notifikasi-content {
        margin-bottom: 12px;
        padding-left: 52px; /* Icon width + gap */
    }

    .notifikasi-content p {
        font-size: 13px;
        color: var(--text-light);
        margin-bottom: 6px;
        line-height: 1.5;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .notifikasi-meta {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 8px;
    }

    .notifikasi-time {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 12px;
        color: var(--text-light);
        font-weight: 500;
        padding: 2px 8px;
        background: rgba(100, 116, 139, 0.08);
        border-radius: 12px;
    }

    .notifikasi-time i {
        font-size: 10px;
    }

    .venue-info {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 12px;
        color: var(--primary-color);
        font-weight: 500;
        padding: 2px 8px;
        background: rgba(98, 147, 196, 0.08);
        border-radius: 12px;
    }

    /* NOTIFICATION ACTIONS - DIKECILKAN */
    .notifikasi-actions {
        display: flex;
        gap: 8px;
        align-items: center;
        justify-content: flex-end;
        padding-top: 12px;
        border-top: 1px solid rgba(98, 147, 196, 0.1);
        margin-top: 12px;
    }

    .notifikasi-actions button {
        padding: 6px 12px;
        border: none;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 4px;
        min-width: 100px;
        justify-content: center;
    }

    .btn-tandai {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
        color: white;
        box-shadow: 0 2px 6px rgba(98, 147, 196, 0.3);
    }

    .btn-tandai:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(98, 147, 196, 0.4);
    }

    .btn-tandai:disabled {
        background: linear-gradient(135deg, #cbd5e0 0%, #94a3b8 100%);
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    .btn-hapus {
        background: white;
        color: var(--text-light);
        border: 1px solid rgba(100, 116, 139, 0.2);
    }

    .btn-hapus:hover {
        background: linear-gradient(135deg, var(--danger) 0%, #fe2222cc 100%);
        color: white;
        border-color: var(--danger);
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(254, 34, 34, 0.3);
    }

    /* UNREAD INDICATOR - DIKECILKAN */
    .unread-indicator {
        position: absolute;
        top: 16px;
        right: 16px;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--primary-color);
        box-shadow: 0 0 0 2px rgba(98, 147, 196, 0.2);
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(98, 147, 196, 0.7);
        }
        70% {
            box-shadow: 0 0 0 6px rgba(98, 147, 196, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(98, 147, 196, 0);
        }
    }

    /* EMPTY STATE - DIKECILKAN */
    .empty-notification {
        text-align: center;
        padding: 40px 16px;
        background: white;
        border-radius: var(--card-radius);
        box-shadow: var(--card-shadow);
        border: 2px dashed rgba(98, 147, 196, 0.2);
    }

    .empty-icon {
        font-size: 40px;
        color: rgba(98, 147, 196, 0.3);
        margin-bottom: 16px;
        animation: float 3s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-5px);
        }
    }

    .empty-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 8px;
    }

    .empty-description {
        font-size: 13px;
        color: var(--text-light);
        line-height: 1.5;
        max-width: 400px;
        margin: 0 auto 20px;
    }

    .empty-action {
        padding: 8px 20px;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
        color: white;
        border: none;
        border-radius: var(--chip-radius);
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 2px 6px rgba(98, 147, 196, 0.3);
    }

    .empty-action:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(98, 147, 196, 0.4);
    }

    /* MODAL - DIKECILKAN */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
        opacity: 0;
        visibility: hidden;
        transition: var(--transition);
    }

    .modal-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    .modal-content {
        background: white;
        border-radius: var(--card-radius);
        width: 90%;
        max-width: 400px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        transform: translateY(-20px) scale(0.95);
        transition: var(--transition);
        overflow: hidden;
        border: 1px solid rgba(98, 147, 196, 0.1);
    }

    .modal-overlay.active .modal-content {
        transform: translateY(0) scale(1);
    }

    .modal-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
        padding: 16px;
        text-align: center;
    }

    .modal-header h2 {
        color: white;
        margin: 0;
        font-size: 16px;
        font-weight: 700;
    }

    .modal-body {
        padding: 20px;
        border-bottom: 1px solid rgba(226, 232, 240, 0.5);
    }

    .modal-body p {
        margin-bottom: 12px;
        color: var(--text-dark);
        line-height: 1.5;
        font-size: 13px;
    }

    .modal-body .notifikasi-detail {
        background: var(--primary-light);
        border-radius: 8px;
        padding: 12px;
        margin: 16px 0;
        border-left: 3px solid var(--primary-color);
    }

    .modal-body .notifikasi-detail h4 {
        margin-top: 0;
        margin-bottom: 8px;
        color: var(--text-dark);
        font-size: 14px;
        font-weight: 700;
    }

    .modal-body .notifikasi-detail p {
        margin-bottom: 6px;
        color: var(--text-light);
        font-size: 12px;
    }

    .modal-footer {
        padding: 16px 20px;
        display: flex;
        justify-content: flex-end;
        gap: 8px;
    }

    .modal-footer button {
        padding: 8px 16px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        font-size: 12px;
        min-width: 80px;
    }

    .btn-batal {
        background: white;
        color: var(--text-light);
        border: 1px solid rgba(100, 116, 139, 0.2);
    }

    .btn-batal:hover {
        background: rgba(100, 116, 139, 0.1);
        border-color: rgba(100, 116, 139, 0.3);
    }

    .btn-hapus-modal {
        background: linear-gradient(135deg, var(--danger) 0%, #fe2222cc 100%);
        color: white;
        box-shadow: 0 2px 6px rgba(254, 34, 34, 0.3);
    }

    .btn-hapus-modal:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(254, 34, 34, 0.4);
    }

    /* PAGINATION - DIKECILKAN */
    .pagination-container {
        display: flex;
        justify-content: center;
        margin-top: 30px;
    }

    .pagination {
        display: flex;
        gap: 6px;
        background: white;
        padding: 6px;
        border-radius: var(--chip-radius);
        box-shadow: var(--card-shadow);
    }

    .pagination button {
        width: 32px;
        height: 32px;
        border: none;
        border-radius: 8px;
        background: white;
        color: var(--text-dark);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: var(--transition);
        font-weight: 600;
        font-size: 12px;
    }

    .pagination button:hover {
        background: var(--primary-light);
        color: var(--primary-color);
        transform: translateY(-1px);
    }

    .pagination button.active {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
        color: white;
        box-shadow: 0 2px 6px rgba(98, 147, 196, 0.3);
    }

    .pagination button:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }

    /* TOAST NOTIFICATION - DIKECILKAN */
    .toast-message {
        position: fixed;
        top: 16px;
        right: 16px;
        padding: 12px 16px;
        border-radius: 8px;
        font-weight: 600;
        z-index: 1001;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        display: flex;
        align-items: center;
        gap: 8px;
        animation: slideInRight 0.3s ease-out;
        max-width: 300px;
        font-size: 13px;
    }

    .toast-message::before {
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        font-size: 14px;
    }

    .toast-message.success {
        background: linear-gradient(135deg, var(--success) 0%, #1ac42ecc 100%);
        color: white;
    }

    .toast-message.success::before {
        content: '\f00c';
    }

    .toast-message.error {
        background: linear-gradient(135deg, var(--danger) 0%, #fe2222cc 100%);
        color: white;
    }

    .toast-message.error::before {
        content: '\f00d';
    }

    .toast-message.info {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
        color: white;
    }

    .toast-message.info::before {
        content: '\f05a';
    }

    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    /* RESPONSIVE DESIGN */
    @media (max-width: 768px) {
        .notification-page {
            padding: 12px 0;
        }
        
        .page-content {
            max-width: 100%;
            padding: 0 12px;
        }
        
        .back-button-header {
            left: 12px;
            top: 12px;
            padding: 6px 12px;
            font-size: 13px;
        }
        
        .filter-section {
            padding: 14px;
            margin-bottom: 16px;
        }
        
        .filter-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
            margin-bottom: 12px;
        }
        
        .filter-header h2 {
            font-size: 16px;
        }
        
        .filter-notifikasi {
            gap: 6px;
            justify-content: flex-start;
            overflow-x: auto;
            padding-bottom: 6px;
            margin-bottom: 12px;
        }
        
        .filter-notifikasi button {
            padding: 6px 12px;
            font-size: 12px;
            white-space: nowrap;
            flex-shrink: 0;
        }
        
        .mark-all-btn {
            width: 100%;
            justify-content: center;
            margin-left: 0;
            font-size: 12px;
            padding: 6px 12px;
        }
        
        .notifikasi-card {
            padding: 14px;
        }
        
        .card-header {
            flex-direction: column;
            gap: 10px;
        }
        
        .card-title-section {
            width: 100%;
        }
        
        .notifikasi-icon {
            width: 36px;
            height: 36px;
            font-size: 14px;
        }
        
        .notifikasi-content {
            padding-left: 0;
            margin-top: 12px;
        }
        
        .notifikasi-title-section h4 {
            font-size: 14px;
        }
        
        .notification-badge {
            font-size: 10px;
            padding: 2px 6px;
        }
        
        .notifikasi-content p {
            font-size: 12px;
        }
        
        .notifikasi-meta {
            gap: 8px;
        }
        
        .notifikasi-time, .venue-info {
            font-size: 11px;
            padding: 2px 6px;
        }
        
        .notifikasi-actions {
            flex-direction: column;
            width: 100%;
            gap: 6px;
        }
        
        .notifikasi-actions button {
            width: 100%;
            font-size: 11px;
            padding: 5px 10px;
        }
        
        .unread-indicator {
            top: 14px;
            right: 14px;
            width: 6px;
            height: 6px;
        }
        
        .empty-notification {
            padding: 30px 12px;
        }
        
        .empty-icon {
            font-size: 32px;
        }
        
        .empty-title {
            font-size: 16px;
        }
        
        .empty-description {
            font-size: 12px;
        }
        
        .empty-action {
            padding: 6px 16px;
            font-size: 12px;
        }
        
        .modal-content {
            max-width: 95%;
        }
        
        .modal-body {
            padding: 16px;
        }
        
        .modal-footer {
            padding: 12px 16px;
            flex-direction: column;
        }
        
        .modal-footer button {
            width: 100%;
            padding: 6px 12px;
        }
        
        .toast-message {
            max-width: 250px;
            font-size: 12px;
            padding: 10px 14px;
        }
    }

    @media (max-width: 480px) {
        .page-content {
            padding: 0 8px;
        }
        
        .back-button-header {
            left: 8px;
            top: 8px;
            padding: 5px 10px;
            font-size: 12px;
        }
        
        .filter-section {
            padding: 12px;
        }
        
        .filter-header h2 {
            font-size: 15px;
        }
        
        .filter-notifikasi button {
            padding: 5px 10px;
            font-size: 11px;
        }
        
        .mark-all-btn {
            font-size: 11px;
            padding: 5px 10px;
        }
        
        .notifikasi-card {
            padding: 12px;
        }
        
        .notifikasi-icon {
            width: 32px;
            height: 32px;
            font-size: 13px;
        }
        
        .notifikasi-title-section h4 {
            font-size: 13px;
        }
        
        .notification-badge {
            font-size: 9px;
        }
        
        .notifikasi-content p {
            font-size: 11px;
        }
        
        .notifikasi-time, .venue-info {
            font-size: 10px;
        }
        
        .empty-notification {
            padding: 24px 8px;
        }
        
        .empty-icon {
            font-size: 28px;
        }
        
        .empty-title {
            font-size: 15px;
        }
        
        .empty-description {
            font-size: 11px;
        }
        
        .empty-action {
            padding: 5px 14px;
            font-size: 11px;
        }
    }
</style>

<div class="notification-desktop">
    <div class="desktop-content">
        <!-- Tombol Kembali -->
        <a href="{{ route('beranda') }}" class="back-button-header fade-in-up">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>

        <!-- Filter Section -->
        <div class="filter-section fade-in-up">
            <div class="filter-header">
                <h2>Filter Notifikasi</h2>
                <div class="notification-count">
                    {{ count($notifications) }} Notifikasi
                </div>
            </div>
            <div class="filter-notifikasi">
                <button class="active" data-filter="semua">Semua</button>
                <button data-filter="belum-dibaca">Belum Dibaca</button>
                <button data-filter="booking_success">Booking</button>
                <button data-filter="payment_success">Pembayaran</button>
                <button data-filter="review">Ulasan</button>
                <button data-filter="promo">Promo</button>
                <button data-filter="reminder">Reminder</button>
            </div>
            <button class="mark-all-btn" onclick="markAllAsRead()">
                <i class="fas fa-check-double"></i>
                Tandai Semua Dibaca
            </button>
        </div>

        <!-- Notification Container -->
        <div class="notifikasi-container">
            @if(count($notifications) > 0)
                @foreach($notifications as $index => $notification)
                <div class="notifikasi-card {{ !$notification['read'] ? 'unread' : '' }} {{ $notification['type'] }}"
                     data-id="{{ $notification['id'] }}" 
                     data-type="{{ $notification['type'] }}"
                     style="animation-delay: {{ $index * 0.05 }}s">
                    
                    <!-- Unread Indicator -->
                    @if(!$notification['read'])
                    <div class="unread-indicator"></div>
                    @endif
                    
                    <div class="card-header">
                        <div class="card-title-section">
                            <div class="notifikasi-icon {{ $notification['type'] }}">
                                <i class="fas 
                                    @if($notification['type'] === 'booking_success') fa-calendar-check
                                    @elseif($notification['type'] === 'payment_success') fa-money-bill-wave
                                    @elseif($notification['type'] === 'review') fa-star
                                    @elseif($notification['type'] === 'promo') fa-tag
                                    @elseif($notification['type'] === 'reminder') fa-bell
                                    @elseif($notification['type'] === 'booking_cancelled') fa-times-circle
                                    @else fa-bell @endif
                                "></i>
                            </div>
                            
                            <div class="notifikasi-title-section">
                                <h4>{{ $notification['title'] }}</h4>
                                <span class="notification-badge badge-{{ str_replace('_success', '', $notification['type']) }}">
                                    <i class="fas 
                                        @if($notification['type'] === 'booking_success') fa-calendar-alt
                                        @elseif($notification['type'] === 'payment_success') fa-credit-card
                                        @elseif($notification['type'] === 'review') fa-comment
                                        @elseif($notification['type'] === 'promo') fa-percentage
                                        @elseif($notification['type'] === 'reminder') fa-clock
                                        @else fa-info-circle @endif
                                    "></i>
                                    {{ str_replace('_', ' ', $notification['type']) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="notifikasi-content">
                        <p>{{ $notification['content'] }}</p>
                        
                        <div class="notifikasi-meta">
                            <span class="notifikasi-time">
                                <i class="far fa-clock"></i>
                                {{ $notification['time'] }}
                            </span>
                            
                            <!-- Venue Info (if available) -->
                            @if(isset($notification['venue']))
                            <span class="venue-info">
                                <i class="fas fa-map-marker-alt"></i>
                                {{ $notification['venue'] }}
                            </span>
                            @endif
                            
                            <!-- Amount Info (if available) -->
                            @if(isset($notification['amount']))
                            <span class="venue-info" style="background: rgba(26, 196, 46, 0.08); color: var(--success);">
                                <i class="fas fa-coins"></i>
                                {{ $notification['amount'] }}
                            </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="notifikasi-actions">
                        @if(!$notification['read'])
                        <button class="btn-tandai" data-id="{{ $notification['id'] }}">
                            <i class="fas fa-check-circle"></i>
                            Tandai Dibaca
                        </button>
                        @else
                        <button class="btn-tandai" data-id="{{ $notification['id'] }}" disabled>
                            <i class="fas fa-check"></i>
                            Sudah Dibaca
                        </button>
                        @endif
                        <button class="btn-hapus" data-id="{{ $notification['id'] }}" data-title="{{ $notification['title'] }}">
                            <i class="fas fa-trash"></i>
                            Hapus
                        </button>
                    </div>
                </div>
                @endforeach
            @else
                <div class="empty-notification fade-in-up">
                    <div class="empty-icon">
                        <i class="far fa-bell-slash"></i>
                    </div>
                    <h3 class="empty-title">Tidak Ada Notifikasi</h3>
                    <p class="empty-description">
                        Belum ada notifikasi saat ini. Notifikasi akan muncul di sini ketika ada aktivitas baru seperti booking, pembayaran, atau promo.
                    </p>
                    <button class="empty-action" onclick="location.reload()">
                        <i class="fas fa-sync-alt"></i>
                        Muat Ulang
                    </button>
                </div>
            @endif
        </div>

        <!-- Modal Hapus Notifikasi -->
        <div class="modal-overlay" id="modalHapus">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Hapus Notifikasi</h2>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus notifikasi ini?</p>
                    <p>Notifikasi yang dihapus tidak dapat dikembalikan.</p>
                    
                    <div class="notifikasi-detail">
                        <h4>Detail Notifikasi:</h4>
                        <p id="modalNotifikasiTitle">Pembayaran Diterima</p>
                        <p id="modalNotifikasiType">Jenis: Pembayaran</p>
                        <p id="modalNotifikasiTime">Waktu: 2 menit yang lalu</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn-batal" id="btnBatal">Batal</button>
                    <button class="btn-hapus-modal" id="btnHapusModal">
                        <i class="fas fa-trash"></i>
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Variabel untuk menyimpan notifikasi yang akan dihapus
    let notifikasiToDelete = null;

    // Filter notifikasi
    document.querySelectorAll('.filter-notifikasi button').forEach(button => {
        button.addEventListener('click', function() {
            // Hapus class active dari semua button
            document.querySelectorAll('.filter-notifikasi button').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Tambah class active ke button yang diklik
            this.classList.add('active');
            
            const filter = this.getAttribute('data-filter');
            filterNotifikasi(filter);
        });
    });

    function filterNotifikasi(filter) {
        const notifikasiCards = document.querySelectorAll('.notifikasi-card');
        
        notifikasiCards.forEach(card => {
            if (filter === 'semua') {
                card.style.display = 'block';
            } else if (filter === 'belum-dibaca') {
                if (card.classList.contains('unread')) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            } else {
                const itemType = card.getAttribute('data-type');
                if (itemType === filter) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            }
        });
    }

    // Tandai notifikasi sebagai sudah dibaca
    document.querySelectorAll('.btn-tandai').forEach(button => {
        button.addEventListener('click', function() {
            if (this.disabled) return;
            
            const notifikasiId = this.getAttribute('data-id');
            const notifikasiCard = this.closest('.notifikasi-card');
            const unreadIndicator = notifikasiCard.querySelector('.unread-indicator');
            
            // Animasi loading
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            this.disabled = true;
            
            // Kirim request AJAX untuk update status
            fetch(`/notifikasi/${notifikasiId}/read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    notifikasiCard.classList.remove('unread');
                    if (unreadIndicator) unreadIndicator.remove();
                    
                    this.innerHTML = '<i class="fas fa-check"></i> Sudah Dibaca';
                    this.style.background = 'linear-gradient(135deg, #cbd5e0 0%, #94a3b8 100%)';
                    
                    // Update notification count
                    updateNotificationCount();
                    
                    showToast('Notifikasi ditandai sebagai dibaca', 'success');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                this.innerHTML = originalText;
                this.disabled = false;
                showToast('Terjadi kesalahan', 'error');
            });
        });
    });

    // Modal hapus notifikasi
    document.querySelectorAll('.btn-hapus').forEach(button => {
        button.addEventListener('click', function() {
            if (this.disabled) return;
            
            const notifikasiCard = this.closest('.notifikasi-card');
            const notifikasiId = this.getAttribute('data-id');
            const notifikasiTitle = this.getAttribute('data-title');
            const notifikasiType = notifikasiCard.getAttribute('data-type');
            const notifikasiTime = notifikasiCard.querySelector('.notifikasi-time').textContent;
            
            // Simpan referensi ke notifikasi yang akan dihapus
            notifikasiToDelete = {
                element: notifikasiCard,
                id: notifikasiId
            };
            
            // Isi data modal
            document.getElementById('modalNotifikasiTitle').textContent = notifikasiTitle;
            document.getElementById('modalNotifikasiType').textContent = `Jenis: ${capitalizeFirstLetter(notifikasiType.replace('_', ' '))}`;
            document.getElementById('modalNotifikasiTime').textContent = `Waktu: ${notifikasiTime.replace('<i class="far fa-clock"></i>', '').trim()}`;
            
            // Tampilkan modal
            document.getElementById('modalHapus').classList.add('active');
        });
    });

    // Fungsi helper untuk kapitalisasi huruf pertama
    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    // Tutup modal
    document.getElementById('btnBatal').addEventListener('click', function() {
        document.getElementById('modalHapus').classList.remove('active');
        notifikasiToDelete = null;
    });

    // Hapus notifikasi
    document.getElementById('btnHapusModal').addEventListener('click', function() {
        if (notifikasiToDelete) {
            const { element, id } = notifikasiToDelete;
            
            // Animasi loading
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            this.disabled = true;
            
            // Kirim request AJAX untuk menghapus
            fetch(`/notifikasi/${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Animasi penghapusan
                    element.style.opacity = '0';
                    element.style.transform = 'translateX(50px)';
                    element.style.height = '0';
                    element.style.margin = '0';
                    element.style.padding = '0';
                    
                    setTimeout(() => {
                        element.style.display = 'none';
                        
                        // Update notification count
                        updateNotificationCount();
                        
                        // Check if all notifications are gone
                        const remainingNotifications = document.querySelectorAll('.notifikasi-card[style*="display: block"], .notifikasi-card:not([style*="display: none"])');
                        if (remainingNotifications.length === 0) {
                            showEmptyState();
                        }
                        
                        document.getElementById('modalHapus').classList.remove('active');
                        notifikasiToDelete = null;
                        
                        this.innerHTML = originalText;
                        this.disabled = false;
                        
                        showToast('Notifikasi dihapus', 'success');
                    }, 300);
                } else {
                    this.innerHTML = originalText;
                    this.disabled = false;
                    showToast('Gagal menghapus', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                this.innerHTML = originalText;
                this.disabled = false;
                showToast('Terjadi kesalahan', 'error');
            });
        }
    });

    // Update notification count
    function updateNotificationCount() {
        const totalCount = document.querySelectorAll('.notifikasi-card').length;
        const unreadCount = document.querySelectorAll('.notifikasi-card.unread').length;
        const countElement = document.querySelector('.notification-count');
        
        if (countElement) {
            countElement.textContent = `${totalCount} Notifikasi`;
        }
    }

    // Show empty state
    function showEmptyState() {
        const container = document.querySelector('.notifikasi-container');
        container.innerHTML = `
            <div class="empty-notification fade-in-up">
                <div class="empty-icon">
                    <i class="far fa-bell-slash"></i>
                </div>
                <h3 class="empty-title">Semua Notifikasi Dihapus</h3>
                <p class="empty-description">
                    Semua notifikasi telah dihapus. Notifikasi baru akan muncul di sini ketika ada aktivitas.
                </p>
                <button class="empty-action" onclick="location.reload()">
                    <i class="fas fa-sync-alt"></i>
                    Muat Ulang
                </button>
            </div>
        `;
    }

    // Tutup modal ketika klik di luar modal
    document.getElementById('modalHapus').addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.remove('active');
            notifikasiToDelete = null;
        }
    });

    // Fungsi untuk menampilkan toast
    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `toast-message ${type}`;
        toast.textContent = message;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 300);
        }, 3000);
    }

    // Handle keyboard events
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('modalHapus');
            if (modal && modal.classList.contains('active')) {
                modal.classList.remove('active');
                notifikasiToDelete = null;
            }
        }
    });

    // Mark all as read function
    function markAllAsRead() {
        const unreadCards = document.querySelectorAll('.notifikasi-card.unread');
        
        if (unreadCards.length === 0) {
            showToast('Tidak ada notifikasi yang belum dibaca', 'info');
            return;
        }
        
        if (!confirm(`Tandai ${unreadCards.length} notifikasi sebagai dibaca?`)) return;
        
        const markAllBtn = document.querySelector('.mark-all-btn');
        const originalText = markAllBtn.innerHTML;
        markAllBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        markAllBtn.disabled = true;
        
        // Kirim request AJAX untuk menandai semua sebagai dibaca
        fetch('/notifikasi/mark-all-read', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                unreadCards.forEach(card => {
                    card.classList.remove('unread');
                    const unreadIndicator = card.querySelector('.unread-indicator');
                    if (unreadIndicator) unreadIndicator.remove();
                    
                    const btnTandai = card.querySelector('.btn-tandai');
                    if (btnTandai) {
                        btnTandai.innerHTML = '<i class="fas fa-check"></i> Sudah Dibaca';
                        btnTandai.disabled = true;
                        btnTandai.style.background = 'linear-gradient(135deg, #cbd5e0 0%, #94a3b8 100%)';
                    }
                });
                
                updateNotificationCount();
                showToast(`Semua ${unreadCards.length} notifikasi telah ditandai sebagai dibaca`, 'success');
            }
            markAllBtn.innerHTML = originalText;
            markAllBtn.disabled = false;
        })
        .catch(error => {
            console.error('Error:', error);
            markAllBtn.innerHTML = originalText;
            markAllBtn.disabled = false;
            showToast('Terjadi kesalahan', 'error');
        });
    }

    // Initialize on load
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.notifikasi-card');
        cards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.03}s`;
        });
        
        updateNotificationCount();
        
        console.log('Notification page loaded');
    });
</script>

@endsection