@extends('layouts.venue')
@section('title', 'Booking Masuk - CariArena Venue')
@section('page-title', 'Booking Masuk')
@push('styles')
<style>
    /* ==== CONTENT STYLES ==== */
    .page-subtitle {
        color: var(--text-light);
        margin-bottom: 20px;
        font-size: 14px;
    }
   
    /* ==== STATUS CARDS ==== */
    .status-cards {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
    }
   
    .status-card {
        background-color: white;
        border-radius: 10px;
        padding: 20px;
        flex: 1;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        text-align: center;
        border-left: 4px solid var(--primary-color);
        transition: all 0.2s ease;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        min-height: 100px;
    }

    .status-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
   
    .status-card.all {
        border-left-color: #4299E1;
    }
   
    .status-card.waiting {
        border-left-color: var(--warning);
    }
   
    .status-card.confirmed {
        border-left-color: var(--success);
    }
   
    .status-card.cancelled {
        border-left-color: var(--danger);
    }
   
    .status-count {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 5px;
        line-height: 1;
    }

    /* Warna teks untuk setiap status card */
    .status-card.all .status-count {
        color: #4299E1;
    }
    
    .status-card.waiting .status-count {
        color: var(--warning);
    }
    
    .status-card.confirmed .status-count {
        color: var(--success);
    }
    
    .status-card.cancelled .status-count {
        color: var(--danger);
    }
   
    .status-label {
        font-size: 0.9rem;
        color: var(--text-light);
        font-weight: 500;
    }
    
    /* ==== SEARCH AND FILTER ==== */
    .search-filter {
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        margin-bottom: 20px;
    }

    .form-control {
        border: 1.5px solid #E5E7EB;
        border-radius: 8px;
        padding: 10px 12px;
        font-size: 0.9rem;
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 2px rgba(99, 179, 237, 0.1);
    }

    .btn-primary-custom {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
        border: none;
        color: white;
        border-radius: 8px;
        padding: 10px 16px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(99, 179, 237, 0.3);
    }

    /* ==== TABLE CONTAINER ==== */
    .table-container {
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }

    .section-header {
        border-bottom: 1px solid #f1f5f9;
        padding: 0 0 15px 0;
        margin-bottom: 15px;
    }

    .section-header h5 {
        font-size: 16px;
        font-weight: 600;
        margin: 0;
        color: var(--primary-color);
    }

    .table {
        margin-bottom: 0;
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
        color: var(--text-dark);
        border-bottom: 2px solid #e9ecef;
        padding: 12px 15px;
        font-size: 0.85rem;
    }

    .table td {
        padding: 12px 15px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.85rem;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
    }

    /* ==== BADGE STYLES ==== */
    .badge {
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
        font-weight: 500;
        border-radius: 6px;
        display: inline-block;
        text-align: center;
        min-width: 80px;
    }

    .badge-confirmed {
        background-color: #E8F5E8;
        color: var(--success);
        border: 1px solid var(--success);
    }

    .badge-pending {
        background-color: #FFF3E0;
        color: var(--warning);
        border: 1px solid var(--warning);
    }

    .badge-cancelled {
        background-color: #FFEBEE;
        color: var(--danger);
        border: 1px solid var(--danger);
    }

    /* ==== ACTION BUTTONS - STYLE KONSISTEN DENGAN MANAJEMEN PENGGUNA ==== */
    .btn-group-sm .btn {
        padding: 0.4rem 0.6rem;
        font-size: 0.75rem;
        margin: 1px;
        border: none;
        background: transparent !important;
        width: auto;
        border-radius: 6px;
        transition: all 0.3s ease;
    }

    .btn-info {
        color: var(--info) !important;
        background: transparent !important;
        border: none !important;
        padding: 0.4rem 0.6rem;
    }

    .btn-info:hover {
        color: var(--primary-hover) !important;
        background: transparent !important;
        transform: translateY(-1px);
    }

    .btn-warning {
        color: var(--warning) !important;
        background: transparent !important;
        border: none !important;
        padding: 0.4rem 0.6rem;
    }

    .btn-warning:hover {
        color: #e0a800 !important;
        background: transparent !important;
        transform: translateY(-1px);
    }

    .btn-danger {
        color: var(--danger) !important;
        background: transparent !important;
        border: none !important;
        padding: 0.4rem 0.6rem;
    }

    .btn-danger:hover {
        color: #c82333 !important;
        background: transparent !important;
        transform: translateY(-1px);
    }

    .btn-info .fas,
    .btn-warning .fas,
    .btn-danger .fas {
        color: inherit !important;
    }

    /* Text styles for table */
    .text-success {
        color: var(--success) !important;
        font-weight: 700;
    }

    .text-muted {
        color: var(--text-light) !important;
        font-size: 0.8rem;
    }

    .fw-bold {
        font-weight: 700 !important;
    }

    /* ==== MODAL STYLES ==== */
    .section-title {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 15px;
        padding-bottom: 8px;
        border-bottom: 2px solid var(--primary-light);
    }

    .section-divider {
        height: 1px;
        background: #e2e8f0;
        margin: 20px 0;
    }

    .btn-cancel {
        background: #f7fafc;
        border: 1px solid #e2e8f0;
        color: var(--text-light);
        padding: 10px 20px;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .btn-cancel:hover {
        background: #edf2f7;
        color: var(--text-dark);
    }

    .btn-save {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
        border: none;
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(99, 179, 237, 0.3);
    }

    .required::after {
        content: " *";
        color: var(--danger);
    }

    /* ==== MODAL TAMBAH BOOKING STYLES ==== */
    #addBookingModal .modal-content {
        border-radius: 12px;
        border: none;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }

    #addBookingModal .modal-header {
        background-color: var(--primary-light);
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        border-radius: 12px 12px 0 0;
        padding: 1.2rem 1.5rem;
    }

    #addBookingModal .modal-title {
        color: var(--text-dark);
        font-weight: 700;
        font-size: 1.25rem;
    }

    #addBookingModal .modal-body {
        padding: 24px;
    }

    #addBookingModal .modal-body .text-muted {
        font-size: 0.875rem;
        color: #64748b;
        margin-bottom: 20px;
        font-weight: 400;
    }

    #addBookingModal .section-title {
        font-size: 1rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 16px;
        padding-bottom: 8px;
        border-bottom: 2px solid #e2e8f0;
    }

    #addBookingModal .section-divider {
        height: 1px;
        background: #e2e8f0;
        margin: 24px 0;
        border: none;
    }

    #addBookingModal .form-label {
        font-weight: 500;
        color: #374151;
        margin-bottom: 8px;
        font-size: 0.875rem;
    }

    #addBookingModal .form-label.required::after {
        content: " *";
        color: #ef4444;
    }

    #addBookingModal .form-control {
        border: 1.5px solid #e5e7eb;
        border-radius: 8px;
        padding: 10px 12px;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        background-color: white;
    }

    #addBookingModal .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(99, 179, 237, 0.1);
        outline: none;
    }

    #addBookingModal .form-control[readonly] {
        background-color: #f9fafb;
        color: #6b7280;
        cursor: not-allowed;
    }

    #addBookingModal select.form-control {
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.5rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
        padding-right: 2.5rem;
    }

    #addBookingModal textarea.form-control {
        resize: vertical;
        min-height: 80px;
    }

    #addBookingModal .modal-footer {
        border-top: 1px solid #e2e8f0;
        padding: 16px 24px 20px;
        background: #f8fafc;
        border-radius: 0 0 12px 12px;
    }

    #addBookingModal .btn-cancel {
        background: white;
        border: 1px solid #d1d5db;
        color: #374151;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s ease;
        font-size: 0.875rem;
    }

    #addBookingModal .btn-cancel:hover {
        background: #f3f4f6;
        border-color: #9ca3af;
        color: #1f2937;
    }

    #addBookingModal .btn-save {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
        border: none;
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
        font-size: 0.875rem;
    }

    #addBookingModal .btn-save:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(99, 179, 237, 0.3);
    }

    /* ==== MODAL DETAIL & EDIT STYLES ==== */
    .modal-header {
        background-color: var(--primary-light);
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        border-radius: 12px 12px 0 0;
        padding: 1.2rem 1.5rem;
    }

    .modal-title {
        color: var(--text-dark);
        font-weight: 700;
        font-size: 1.25rem;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        border-top: 1px solid #e2e8f0;
        padding: 1rem 1.5rem;
        background: #f8fafc;
    }

    .info-row {
        margin-bottom: 15px;
        display: flex;
        align-items: flex-start;
    }

    .info-label {
        font-weight: 600;
        color: #374151;
        min-width: 140px;
    }

    .info-value {
        color: #6b7280;
        flex: 1;
    }

    .detail-section {
        margin-bottom: 25px;
    }

    .detail-section-title {
        font-size: 1rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 15px;
        padding-bottom: 8px;
        border-bottom: 2px solid #e2e8f0;
    }

    .detail-divider {
        height: 1px;
        background: #e2e8f0;
        margin: 20px 0;
    }

    .edit-form .form-label {
        font-weight: 500;
        color: #374151;
        margin-bottom: 8px;
        font-size: 0.875rem;
    }

    .edit-form .form-control {
        border: 1.5px solid #e5e7eb;
        border-radius: 8px;
        padding: 10px 12px;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        background-color: white;
    }

    .edit-form .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(99, 179, 237, 0.1);
        outline: none;
    }

    .edit-form .form-control[readonly] {
        background-color: #f9fafb;
        color: #6b7280;
        cursor: not-allowed;
    }

    .edit-form select.form-control {
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.5rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
        padding-right: 2.5rem;
    }

    .edit-form textarea.form-control {
        resize: vertical;
        min-height: 80px;
    }

    .status-badge {
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
        font-weight: 500;
        border-radius: 6px;
        display: inline-block;
        text-align: center;
        min-width: 100px;
    }

    .status-badge.confirmed {
        background-color: #E8F5E8;
        color: var(--success);
        border: 1px solid var(--success);
    }

    .status-badge.pending {
        background-color: #FFF3E0;
        color: var(--warning);
        border: 1px solid var(--warning);
    }

    .status-badge.cancelled {
        background-color: #FFEBEE;
        color: var(--danger);
        border: 1px solid var(--danger);
    }

    /* ==== RESPONSIVE STYLES ==== */

    /* Tablet Landscape (992px - 1200px) */
    @media (max-width: 1200px) {
        .status-cards {
            gap: 12px;
        }
        
        .status-card {
            padding: 18px 15px;
            min-height: 90px;
        }
        
        .status-count {
            font-size: 1.8rem;
        }
        
        .status-label {
            font-size: 0.85rem;
        }
        
        .search-filter {
            padding: 18px;
        }
        
        .table-container {
            padding: 18px;
        }
    }

    /* Tablet Portrait (768px - 992px) */
    @media (max-width: 992px) {
        .page-subtitle {
            font-size: 13px;
            margin-bottom: 18px;
        }
        
        .status-cards {
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .status-card {
            flex: 1 1 calc(50% - 10px);
            min-width: 140px;
            padding: 15px;
            min-height: 85px;
        }
        
        .status-count {
            font-size: 1.6rem;
        }
        
        .status-label {
            font-size: 0.8rem;
        }
        
        .search-filter {
            padding: 15px;
            margin-bottom: 18px;
        }
        
        .form-control {
            padding: 9px 11px;
            font-size: 0.85rem;
        }
        
        .btn-primary-custom {
            padding: 9px 14px;
            font-size: 0.85rem;
        }
        
        .table-container {
            padding: 15px;
        }
        
        .section-header h5 {
            font-size: 15px;
        }
        
        .table th,
        .table td {
            padding: 10px 12px;
            font-size: 0.8rem;
        }
        
        .badge {
            font-size: 0.7rem;
            min-width: 70px;
        }
        
        .btn-group-sm .btn {
            padding: 0.35rem 0.45rem;
            font-size: 0.7rem;
        }
        
        /* Modal adjustments for tablet */
        .modal-dialog {
            max-width: 95%;
            margin: 1.75rem auto;
        }
        
        #addBookingModal .modal-body,
        .modal-body {
            padding: 1.2rem;
        }
        
        #addBookingModal .modal-header,
        .modal-header {
            padding: 1rem 1.2rem;
        }
        
        .info-row {
            margin-bottom: 12px;
        }
        
        .info-label {
            min-width: 120px;
            font-size: 0.85rem;
        }
        
        .info-value {
            font-size: 0.85rem;
        }
    }

    /* Mobile Landscape (576px - 768px) */
    @media (max-width: 768px) {
        .page-subtitle {
            font-size: 12px;
            margin-bottom: 15px;
        }
        
        .status-cards {
            gap: 8px;
            margin-bottom: 15px;
        }
        
        .status-card {
            flex: 1 1 calc(50% - 8px);
            padding: 12px;
            min-height: 80px;
            border-radius: 8px;
        }
        
        .status-count {
            font-size: 1.4rem;
            margin-bottom: 3px;
        }
        
        .status-label {
            font-size: 0.75rem;
        }
        
        .search-filter {
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 8px;
        }
        
        .form-control {
            padding: 8px 10px;
            font-size: 0.8rem;
            border-radius: 6px;
        }
        
        .btn-primary-custom {
            padding: 8px 12px;
            font-size: 0.8rem;
            border-radius: 6px;
        }
        
        .table-container {
            padding: 12px;
            border-radius: 8px;
        }
        
        .section-header {
            padding: 0 0 12px 0;
            margin-bottom: 12px;
        }
        
        .section-header h5 {
            font-size: 14px;
        }
        
        .table th,
        .table td {
            padding: 8px 10px;
            font-size: 0.75rem;
        }
        
        .badge {
            font-size: 0.65rem;
            min-width: 65px;
            padding: 0.3em 0.5em;
        }
        
        .btn-group-sm .btn {
            padding: 0.3rem 0.4rem;
            font-size: 0.65rem;
            margin: 0 1px;
        }
        
        .text-muted {
            font-size: 0.75rem;
        }
        
        /* Mobile modal adjustments */
        .modal-dialog {
            margin: 0.5rem;
            max-width: calc(100% - 1rem);
        }
        
        #addBookingModal .modal-body,
        .modal-body {
            padding: 1rem;
        }
        
        #addBookingModal .modal-header,
        .modal-header {
            padding: 0.8rem 1rem;
        }
        
        .modal-title {
            font-size: 1.1rem;
        }
        
        .info-row {
            flex-direction: column;
            margin-bottom: 10px;
        }
        
        .info-label {
            min-width: auto;
            margin-bottom: 3px;
            font-size: 0.8rem;
        }
        
        .info-value {
            font-size: 0.8rem;
        }
        
        .detail-section-title {
            font-size: 0.9rem;
            margin-bottom: 12px;
        }
        
        .btn-cancel,
        .btn-save {
            padding: 8px 16px;
            font-size: 0.8rem;
        }
        
        /* Form adjustments for mobile */
        #addBookingModal .form-label,
        .edit-form .form-label {
            font-size: 0.8rem;
        }
        
        #addBookingModal .form-control,
        .edit-form .form-control {
            padding: 8px 10px;
            font-size: 0.8rem;
        }
    }

    /* Mobile Portrait (max-width: 576px) */
    @media (max-width: 576px) {
        .status-cards {
            flex-direction: column;
            gap: 8px;
        }
        
        .status-card {
            flex: 1 1 100%;
            min-height: 70px;
            padding: 10px 15px;
            flex-direction: row;
            justify-content: space-between;
            text-align: left;
        }
        
        .status-count {
            font-size: 1.3rem;
            margin-bottom: 0;
        }
        
        .status-label {
            font-size: 0.75rem;
        }
        
        .search-filter .row {
            margin: 0;
        }
        
        .search-filter .col-md-4,
        .search-filter .col-md-3,
        .search-filter .col-md-2 {
            margin-bottom: 10px;
        }
        
        .search-filter .col-md-2:last-child {
            margin-bottom: 0;
        }
        
        .table-responsive {
            border: 1px solid #e9ecef;
            border-radius: 6px;
        }
        
        .table th,
        .table td {
            white-space: nowrap;
            padding: 6px 8px;
            font-size: 0.7rem;
        }
        
        .section-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
        
        .btn-action {
            padding: 6px 12px;
            font-size: 0.75rem;
            width: 100%;
        }
        
        /* Mobile table column adjustments */
        .table th:nth-child(4),
        .table td:nth-child(4) {
            min-width: 120px;
        }
        
        .table th:nth-child(5),
        .table td:nth-child(5) {
            min-width: 100px;
        }
        
        .table th:nth-child(7),
        .table td:nth-child(7) {
            min-width: 110px;
        }
        
        /* Mobile modal adjustments */
        .modal-dialog {
            margin: 0.25rem;
            max-width: calc(100% - 0.5rem);
        }
        
        #addBookingModal .modal-body,
        .modal-body {
            padding: 0.8rem;
        }
        
        #addBookingModal .modal-header,
        .modal-header {
            padding: 0.7rem 0.8rem;
        }
        
        .modal-title {
            font-size: 1rem;
        }
        
        .detail-section {
            margin-bottom: 20px;
        }
        
        .detail-section-title {
            font-size: 0.85rem;
        }
        
        .btn-cancel,
        .btn-save {
            padding: 6px 12px;
            font-size: 0.75rem;
        }
        
        /* Form grid adjustments for mobile */
        .row.mb-3 .col-md-6,
        .row.mb-3 .col-md-4,
        .row.mb-3 .col-md-3 {
            margin-bottom: 10px;
        }
        
        .row.mb-3 .col-md-6:last-child,
        .row.mb-3 .col-md-4:last-child,
        .row.mb-3 .col-md-3:last-child {
            margin-bottom: 0;
        }
    }

    /* Very Small Mobile (max-width: 375px) */
    @media (max-width: 375px) {
        .status-card {
            padding: 8px 12px;
            min-height: 65px;
        }
        
        .status-count {
            font-size: 1.2rem;
        }
        
        .status-label {
            font-size: 0.7rem;
        }
        
        .search-filter {
            padding: 10px;
        }
        
        .form-control {
            padding: 6px 8px;
            font-size: 0.75rem;
        }
        
        .btn-primary-custom {
            padding: 6px 10px;
            font-size: 0.75rem;
        }
        
        .table-container {
            padding: 10px;
        }
        
        .table th,
        .table td {
            padding: 5px 6px;
            font-size: 0.65rem;
        }
        
        .badge {
            font-size: 0.6rem;
            min-width: 60px;
            padding: 0.25em 0.4em;
        }
        
        .btn-group-sm .btn {
            padding: 0.25rem 0.3rem;
            font-size: 0.6rem;
        }
        
        /* Very small modal adjustments */
        .modal-body {
            padding: 0.6rem;
        }
        
        .modal-header {
            padding: 0.6rem 0.8rem;
        }
        
        .modal-title {
            font-size: 0.9rem;
        }
        
        .info-row {
            margin-bottom: 8px;
        }
        
        .info-label,
        .info-value {
            font-size: 0.75rem;
        }
        
        .btn-cancel,
        .btn-save {
            padding: 5px 10px;
            font-size: 0.7rem;
        }
    }

    /* Touch Device Optimizations */
    @media (max-width: 768px) {
        .status-card,
        .btn-action,
        .btn-primary-custom,
        .btn-group-sm .btn {
            min-height: 44px;
        }
        
        .form-control,
        select.form-control {
            font-size: 16px; /* Prevent zoom on iOS */
        }
        
        .status-card:hover {
            transform: none; /* Disable hover effects on touch devices */
        }
        
        .btn-action:hover,
        .btn-primary-custom:hover {
            transform: none;
        }
    }

    /* Improve table readability on mobile */
    @media (max-width: 768px) {
        .table tbody tr {
            border-bottom: 1px solid #e9ecef;
        }
        
        .table tbody tr:last-child {
            border-bottom: none;
        }
    }

    /* Ensure modal forms are usable on mobile */
    @media (max-width: 576px) {
        #addBookingModal select.form-control,
        .edit-form select.form-control,
        #addBookingModal input[type="date"],
        .edit-form input[type="date"],
        #addBookingModal input[type="time"],
        .edit-form input[type="time"] {
            font-size: 16px; /* Prevent zoom on iOS */
        }
    }
</style>
@endpush

@section('content')
    <p class="page-subtitle">Kelola semua booking yang masuk</p>
   
        <!-- Status Cards Warna-Warni -->
        <div class="status-cards">
        <div class="status-card all" data-filter="all">
            <div class="status-count">{{ $totalBookings ?? 0 }}</div>
            <div class="status-label">Semua Status</div>
        </div>
        <div class="status-card waiting" data-filter="pending">
            <div class="status-count">{{ $pendingBookings ?? 0 }}</div>
            <div class="status-label">Menunggu</div>
        </div>
        <div class="status-card confirmed" data-filter="confirmed">
            <div class="status-count">{{ $confirmedBookings ?? 0 }}</div>
            <div class="status-label">Dikonfirmasi</div>
        </div>
        <div class="status-card cancelled" data-filter="cancelled">
            <div class="status-count">{{ $cancelledBookings ?? 0 }}</div>
            <div class="status-label">Dibatalkan</div>
        </div>
    </div>

<!-- Search dan Filter -->
<div class="search-filter">
    <form id="filterForm" method="GET" action="{{ route('venue.booking.masuk.index') }}">
        <div class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" id="searchInput" placeholder="🔍 Cari booking, customer, atau venue..." value="{{ request('search') }}">
            </div>
            
            <div class="col-md-3">
                <select class="form-control" name="status" id="statusFilter">
                    <option value="">Semua Status</option>

                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>
                        Terkonfirmasi
                    </option>

                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                        Menunggu
                    </option>

                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                        Dibatalkan
                    </option>
                </select>
            </div>

            <div class="col-md-3">
                <select class="form-control" name="venue_id" id="venueFilter">
                    <option value="">Semua Venue</option>
                    @foreach($venues as $venue)
                        <option value="{{ $venue->id }}" {{ request('venue_id') == $venue->id ? 'selected' : '' }}>{{ $venue->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary-custom w-100" id="applyFilterBtn">
                    <i class="fas fa-filter me-2"></i>Filter
                </button>
            </div>
        </div>
    </form>
</div>

    <!-- Daftar Booking -->
    <div class="table-container">
        <div class="section-header d-flex justify-content-between align-items-center">
            <h5>📋 Data Booking (<span id="bookingCount">{{ $totalBookings ?? 0 }}</span>)</h5>
            <button class="btn btn-primary-custom btn-action" data-bs-toggle="modal" data-bs-target="#addBookingModal">
                <i class="fas fa-plus me-2"></i>Tambah Booking
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID Booking</th>
                        <th>Customer</th>
                        <th>Venue</th>
                        <th>Tanggal & Waktu</th>
                        <th>Total Biaya</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="bookingTableBody">
                    @forelse($bookings as $booking)
                    <tr>
                        <td class="fw-bold">{{ $booking->booking_code }}</td>
                        <td>
                            <div>{{ $booking->nama_customer }}</div>
                            <small class="text-muted">{{ $booking->customer_phone }}</small>
                        </td>
                        <td>{{ $booking->venue->name }}</td>
                        <td>
                            <div>{{ $booking->tanggal_booking->format('d/m/Y') }}</div>
                            <small class="text-muted">{{ date('H.i', strtotime($booking->waktu_booking)) }} - {{ date('H.i', strtotime($booking->end_time)) }}</small>
                        </td>
                        <td class="fw-bold text-success">Rp {{ number_format($booking->total_biaya, 0, ',', '.') }}</td>
                        <td>
                        @if($booking->status == 'confirmed')
                            <span class="badge badge-confirmed">Terkonfirmasi</span>
                        @elseif($booking->status == 'pending')
                            <span class="badge badge-pending">Menunggu</span>
                        @elseif($booking->status == 'cancelled')
                            <span class="badge badge-cancelled">Dibatalkan</span>
                        @endif

                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-info" title="Lihat Detail" onclick="viewBooking({{ $booking->id }})">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-warning" title="Edit" onclick="editBooking({{ $booking->id }})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger" title="Hapus" onclick="deleteBooking({{ $booking->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <p>Tidak ada data booking</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah Booking -->
    <div class="modal fade" id="addBookingModal" tabindex="-1" aria-labelledby="addBookingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addBookingModalLabel">Tambah Booking Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addBookingForm">
                    <div class="modal-body">
                        <p class="text-muted mb-4">Isi informasi booking baru</p>
                        
                        <h6 class="section-title">Informasi Customer</h6>
                        
                        <div class="mb-3">
                            <label for="customerName" class="form-label required">Nama Pemesan</label>
                            <input type="text" class="form-control" id="customerName" name="nama_customer" placeholder="Masukkan nama pemesan" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="customerPhone" class="form-label required">Nomor Telepon</label>
                            <input type="text" class="form-control" id="customerPhone" name="customer_phone" placeholder="Masukkan nomor telepon" required>
                        </div>
                        
                        <div class="section-divider"></div>
                        
                        <h6 class="section-title">Informasi Booking</h6>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="bookingDate" class="form-label required">Tanggal Booking</label>
                                <input type="date" class="form-control" id="bookingDate" name="tanggal_booking" required>
                            </div>
                            <div class="col-md-3">
                                <label for="bookingTime" class="form-label required">Waktu Mulai</label>
                                <input type="time" class="form-control" id="bookingTime" name="waktu_booking" required>
                            </div>
                            <div class="col-md-3">
                                <label for="duration" class="form-label required">Durasi (jam)</label>
                                <select class="form-control" id="duration" name="durasi" required>
                                    <option value="">Pilih durasi</option>
                                    <option value="1">1 jam</option>
                                    <option value="2">2 jam</option>
                                    <option value="3">3 jam</option>
                                    <option value="4">4 jam</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="section-divider"></div>
                        
                        <h6 class="section-title">Detail Venue & Pembayaran</h6>
                        
                        <div class="mb-3">
                            <label for="venue" class="form-label required">Venue</label>
                            <select class="form-control" id="venue" name="venue_id" required>
                                <option value="">Pilih venue</option>
                                @foreach($venues as $venue)
                                    <option value="{{ $venue->id }}" data-price="{{ $venue->price_per_hour }}">{{ $venue->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="pricePerHour" class="form-label">Harga per Jam</label>
                                <input type="text" class="form-control" id="pricePerHour" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="totalCost" class="form-label">Total Biaya</label>
                                <input type="text" class="form-control" id="totalCost" name="total_biaya" readonly>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="notes" class="form-label">Catatan (opsional)</label>
                            <textarea class="form-control" id="notes" name="catatan" rows="3" placeholder="Catatan tambahan"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-save" id="saveBookingBtn">
                            <span class="loading-spinner" style="display: none;"></span>
                            <span class="btn-text">Simpan Booking</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<!-- Modal View Booking -->
<div class="modal fade" id="viewBookingModal" tabindex="-1" aria-labelledby="viewBookingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewBookingModalLabel">Detail Booking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="detail-section">
                    <div class="info-row">
                        <div class="info-label">ID Booking:</div>
                        <div class="info-value" id="viewBookingId">-</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Status:</div>
                        <div class="info-value">
                            <span class="status-badge" id="viewBookingStatus">-</span>
                        </div>
                    </div>
                </div>

                <div class="detail-section">
                    <h6 class="detail-section-title">Informasi Booking</h6>
                    <div class="info-row">
                        <div class="info-label">Nama Pemesan:</div>
                        <div class="info-value" id="viewCustomerName">-</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Nomor Telepon:</div>
                        <div class="info-value" id="viewCustomerPhone">-</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Tanggal Booking:</div>
                        <div class="info-value" id="viewBookingDate">-</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Waktu Booking:</div>
                        <div class="info-value" id="viewBookingTime">-</div>
                    </div>
                </div>

                <div class="detail-divider"></div>

                <div class="detail-section">
                    <h6 class="detail-section-title">Detail Venue & Pembayaran</h6>
                    <div class="info-row">
                        <div class="info-label">Venue:</div>
                        <div class="info-value" id="viewVenue">-</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Durasi:</div>
                        <div class="info-value" id="viewDuration">-</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Harga per Jam:</div>
                        <div class="info-value" id="viewPricePerHour">-</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Total Biaya:</div>
                        <div class="info-value" id="viewTotalCost">-</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Catatan:</div>
                        <div class="info-value" id="viewNotes">-</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>


    <!-- Modal Edit Booking -->
<div class="modal fade" id="editBookingModal" tabindex="-1" aria-labelledby="editBookingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editBookingModalLabel">Edit Booking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editBookingForm">
                <div class="modal-body">
                    <p class="text-muted mb-4">Ubah informasi booking yang sudah ada</p>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">ID Booking:</label>
                            <input type="text" class="form-control" id="editBookingId" readonly>
                            <input type="hidden" name="booking_id" id="editBookingIdHidden">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status Saat Ini:</label>
                            <input type="text" class="form-control" id="editCurrentStatus" readonly>
                        </div>
                    </div>

                    <div class="detail-section edit-form">
                        <h6 class="detail-section-title">Informasi Customer</h6>
                        
                        <div class="mb-3">
                            <label for="editCustomerName" class="form-label required">Nama Pemesan</label>
                            <input type="text" class="form-control" id="editCustomerName" name="nama_customer" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="editCustomerPhone" class="form-label required">Nomor Telepon</label>
                            <input type="text" class="form-control" id="editCustomerPhone" name="customer_phone" required>
                        </div>
                    </div>

                    <div class="detail-divider"></div>

                    <div class="detail-section edit-form">
                        <h6 class="detail-section-title">Informasi Booking</h6>
                        
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="editBookingDate" class="form-label required">Tanggal Booking</label>
                                <input type="date" class="form-control" id="editBookingDate" name="tanggal_booking" required>
                            </div>
                            <div class="col-md-4">
                                <label for="editBookingTime" class="form-label required">Waktu Mulai</label>
                                <input type="time" class="form-control" id="editBookingTime" name="waktu_booking" required>
                            </div>
                            <div class="col-md-4">
                                <label for="editDuration" class="form-label required">Durasi (jam)</label>
                                <select class="form-control" id="editDuration" name="durasi" required>
                                    <option value="">Pilih durasi</option>
                                    <option value="1">1 jam</option>
                                    <option value="2">2 jam</option>
                                    <option value="3">3 jam</option>
                                    <option value="4">4 jam</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="detail-divider"></div>

                    <div class="detail-section edit-form">
                        <h6 class="detail-section-title">Detail Venue & Pembayaran</h6>
                        
                        <div class="mb-3">
                            <label for="editVenue" class="form-label required">Venue</label>
                            <select class="form-control" id="editVenue" name="venue_id" required>
                                <option value="">Pilih venue</option>
                                @foreach($venues as $venue)
                                    <option value="{{ $venue->id }}" data-price="{{ $venue->price_per_hour }}">{{ $venue->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="editPricePerHour" class="form-label">Harga per Jam</label>
                                <input type="text" class="form-control" id="editPricePerHour" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="editTotalCost" class="form-label">Total Biaya</label>
                                <input type="text" class="form-control" id="editTotalCost" name="total_biaya" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="detail-section edit-form">
                        <h6 class="detail-section-title">Status Booking</h6>
                        
                        <div class="mb-3">
                            <select class="form-control" id="editStatus" name="status" required>
                                <option value="confirmed">Terkonfirmasi</option>
                                <option value="pending">Menunggu</option>
                                <option value="cancelled">Dibatalkan</option>
                            </select>
                        </div>
                    </div>

                    <div class="detail-section edit-form">
                        <div class="mb-3">
                            <label for="editNotes" class="form-label">Catatan (opsional)</label>
                            <textarea class="form-control" id="editNotes" name="catatan" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-save" id="updateBookingBtn">
                        <span class="loading-spinner" style="display: none;"></span>
                        <span class="btn-text">Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal Delete Booking -->
<div class="modal fade" id="deleteBookingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center py-4">
                <i class="fas fa-trash fa-2x text-danger mb-3"></i>
                <h5>Konfirmasi Hapus</h5>
                <p class="text-muted">Apakah Anda yakin ingin menghapus booking ini?</p>
                <div class="mt-3">
                    <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Hapus</button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// CSRF Token untuk AJAX
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// ===== VARIABEL GLOBAL =====
let currentBookingId = null;

// ===== FUNGSI BACKEND UNTUK TOMBOL AKSI =====

// Fungsi untuk view booking
async function viewBooking(bookingId) {
    try {
        console.log('Loading booking detail for:', bookingId);
        
        const response = await fetch(`/venue/booking-masuk/detail/${bookingId}`);
        const result = await response.json();
        
        if (result.success) {
            const booking = result.data;
            console.log('Booking data:', booking);
            
            // Format tanggal
            const bookingDate = new Date(booking.tanggal_booking);
            const formattedDate = bookingDate.toLocaleDateString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            // Update status badge
            let statusClass = 'confirmed';
            let statusText = 'Terkonfirmasi';

            if (booking.status === 'pending') {
                statusClass = 'pending';
                statusText = 'Menunggu';
            } else if (booking.status === 'cancelled') {
                statusClass = 'cancelled';
                statusText = 'Dibatalkan';
            }

            // Update modal content dengan data real
            document.getElementById('viewBookingId').textContent = booking.booking_code;
            document.getElementById('viewBookingStatus').textContent = statusText;
            document.getElementById('viewBookingStatus').className = `status-badge ${statusClass}`;
            document.getElementById('viewCustomerName').textContent = booking.nama_customer;
            document.getElementById('viewCustomerPhone').textContent = booking.customer_phone;
            document.getElementById('viewBookingDate').textContent = formattedDate;
            document.getElementById('viewBookingTime').textContent = `${booking.waktu_booking} - ${booking.end_time}`;
            document.getElementById('viewVenue').textContent = booking.venue?.name || 'Venue tidak ditemukan';
            document.getElementById('viewDuration').textContent = `${booking.durasi} jam`;
            document.getElementById('viewPricePerHour').textContent = `Rp ${Math.round(booking.total_biaya / booking.durasi).toLocaleString('id-ID')}`;
            document.getElementById('viewTotalCost').textContent = `Rp ${booking.total_biaya.toLocaleString('id-ID')}`;
            document.getElementById('viewNotes').textContent = booking.catatan || 'Tidak ada catatan';

            // Show modal menggunakan Bootstrap
            const modal = new bootstrap.Modal(document.getElementById('viewBookingModal'));
            modal.show();
            
        } else {
            console.error('API Error:', result.message);
            alert('Gagal memuat detail booking: ' + result.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memuat detail booking');
    }
}

// Fungsi untuk edit booking
async function editBooking(bookingId) {
    try {
        console.log('Loading booking for edit:', bookingId);
        currentBookingId = bookingId;
        
        const response = await fetch(`/venue/booking-masuk/detail/${bookingId}`);
        const result = await response.json();
        
        if (result.success) {
            const booking = result.data;
            console.log('Edit booking data:', booking);
            
            // Update modal content dengan data real
            document.getElementById('editBookingId').value = booking.booking_code;
            document.getElementById('editBookingIdHidden').value = booking.id;
            
            // Format status untuk display
            let statusDisplay = 'Menunggu';
            if (booking.status === 'confirmed') statusDisplay = 'Terkonfirmasi';
            if (booking.status === 'cancelled') statusDisplay = 'Dibatalkan';
            document.getElementById('editCurrentStatus').value = statusDisplay;
            
            document.getElementById('editCustomerName').value = booking.nama_customer;
            document.getElementById('editCustomerPhone').value = booking.customer_phone;
            document.getElementById('editBookingDate').value = booking.tanggal_booking;
            document.getElementById('editBookingTime').value = booking.waktu_booking;
            document.getElementById('editDuration').value = booking.durasi;
            document.getElementById('editVenue').value = booking.venue_id;
            
            // Hitung harga per jam dari total biaya
            const pricePerHour = Math.round(booking.total_biaya / booking.durasi);
            document.getElementById('editPricePerHour').value = `Rp ${pricePerHour.toLocaleString('id-ID')}`;
            document.getElementById('editTotalCost').value = `Rp ${booking.total_biaya.toLocaleString('id-ID')}`;
            document.getElementById('editStatus').value = booking.status;
            document.getElementById('editNotes').value = booking.catatan || '';

            // Set data-price untuk venue select
            const venueSelect = document.getElementById('editVenue');
            const selectedOption = venueSelect.options[venueSelect.selectedIndex];
            if (selectedOption) {
                selectedOption.setAttribute('data-price', pricePerHour);
            }

            // Show modal menggunakan Bootstrap
            const modal = new bootstrap.Modal(document.getElementById('editBookingModal'));
            modal.show();

        } else {
            console.error('API Error:', result.message);
            alert('Gagal memuat data booking untuk edit: ' + result.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memuat data booking');
    }
}

// Fungsi untuk delete booking
async function deleteBooking(bookingId) {
    currentBookingId = bookingId;
    
    // Show delete confirmation modal
    const modal = new bootstrap.Modal(document.getElementById('deleteBookingModal'));
    modal.show();
}

// ===== FUNGSI BANTUAN UNTUK FORM =====

// Fungsi untuk update harga di form edit
function updateEditPrice() {
    const venueSelect = document.getElementById('editVenue');
    const durationSelect = document.getElementById('editDuration');
    const pricePerHourInput = document.getElementById('editPricePerHour');
    const totalCostInput = document.getElementById('editTotalCost');
    
    if (!venueSelect || !durationSelect) return;
    
    const selectedOption = venueSelect.options[venueSelect.selectedIndex];
    const pricePerHour = selectedOption ? parseInt(selectedOption.getAttribute('data-price')) : 0;
    const duration = parseInt(durationSelect.value) || 0;
    const totalCost = pricePerHour * duration;
    
    if (pricePerHour > 0) {
        pricePerHourInput.value = `Rp ${pricePerHour.toLocaleString('id-ID')}`;
    } else {
        pricePerHourInput.value = '';
    }
    
    if (totalCost > 0) {
        totalCostInput.value = `Rp ${totalCost.toLocaleString('id-ID')}`;
    } else {
        totalCostInput.value = '';
    }
}

// Fungsi untuk kalkulasi harga di form tambah
function updatePriceAndCalculate() {
    const venueSelect = document.getElementById('venue');
    const durationSelect = document.getElementById('duration');
    const pricePerHourInput = document.getElementById('pricePerHour');
    const totalCostInput = document.getElementById('totalCost');
    
    const selectedOption = venueSelect.options[venueSelect.selectedIndex];
    const pricePerHour = selectedOption ? parseInt(selectedOption.getAttribute('data-price')) : 0;
    const duration = parseInt(durationSelect.value) || 0;
    const totalCost = pricePerHour * duration;
    
    if (pricePerHour > 0) {
        pricePerHourInput.value = `Rp ${pricePerHour.toLocaleString('id-ID')}`;
    } else {
        pricePerHourInput.value = '';
    }
    
    if (totalCost > 0) {
        totalCostInput.value = `Rp ${totalCost.toLocaleString('id-ID')}`;
    } else {
        totalCostInput.value = '';
    }
}

// ===== INITIALIZATION =====

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing booking functionality...');

    // Event listeners untuk price calculation di form tambah
    const venueSelect = document.getElementById('venue');
    const durationSelect = document.getElementById('duration');
    
    if (venueSelect) {
        venueSelect.addEventListener('change', updatePriceAndCalculate);
    }
    if (durationSelect) {
        durationSelect.addEventListener('change', updatePriceAndCalculate);
    }

    // Event listeners untuk form edit
    const editVenueSelect = document.getElementById('editVenue');
    const editDurationSelect = document.getElementById('editDuration');
    
    if (editVenueSelect) {
        editVenueSelect.addEventListener('change', updateEditPrice);
    }
    if (editDurationSelect) {
        editDurationSelect.addEventListener('change', updateEditPrice);
    }

    const addBookingForm = document.getElementById('addBookingForm');

if (addBookingForm) {
    addBookingForm.addEventListener('submit', async function (e) {
        e.preventDefault();
        console.log('Submitting new booking...');

        const saveButton = document.getElementById('saveBookingBtn');
        const originalText = saveButton.querySelector('.btn-text').textContent;

        saveButton.querySelector('.loading-spinner').style.display = 'inline-block';
        saveButton.querySelector('.btn-text').textContent = 'Menyimpan...';
        saveButton.disabled = true;

        try {
            // 1️⃣ HITUNG TOTAL BIAYA (WAJIB)
            const totalCostValue = document.getElementById('totalCost').value;
            let totalBiaya = 0;

            if (totalCostValue) {
                totalBiaya = parseInt(totalCostValue.replace(/[^\d]/g, '')) || 0;
            }

            // 2️⃣ BUAT FORMDATA
            const formData = new FormData();
            formData.append('venue_id', document.getElementById('venue').value);
            formData.append('nama_customer', document.getElementById('customerName').value);
            formData.append('customer_phone', document.getElementById('customerPhone').value);
            formData.append('tanggal_booking', document.getElementById('bookingDate').value);
            formData.append('waktu_booking', document.getElementById('bookingTime').value);
            formData.append('durasi', document.getElementById('duration').value);
            formData.append('total_biaya', totalBiaya);
            formData.append('catatan', document.getElementById('notes').value);

            // 3️⃣ DEBUG
            for (let [key, value] of formData.entries()) {
                console.log(key + ': ' + value);
            }

            // 4️⃣ KIRIM KE STORE
            const response = await fetch('/venue/booking-masuk/store', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: formData
            });

            const result = await response.json();
            console.log(result);

            if (result.success) {
                alert('Booking berhasil dibuat!');
                bootstrap.Modal.getInstance(
                    document.getElementById('addBookingModal')
                ).hide();

                addBookingForm.reset();
                window.location.reload();
            } else {
                alert(result.message);
            }

        } catch (error) {
            console.error(error);
            alert('Terjadi kesalahan saat membuat booking');
        } finally {
            saveButton.querySelector('.loading-spinner').style.display = 'none';
            saveButton.querySelector('.btn-text').textContent = originalText;
            saveButton.disabled = false;
        }
    });
}


    // Confirm delete button
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', async function() {
            if (currentBookingId) {
                try {
                    console.log('Deleting booking:', currentBookingId);
                    
                    const response = await fetch(`/venue/booking-masuk/delete/${currentBookingId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json'
                        }
                    });
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        alert('Booking berhasil dihapus!');
                        bootstrap.Modal.getInstance(document.getElementById('deleteBookingModal')).hide();
                        // Reload page untuk menampilkan perubahan
                        window.location.reload();
                    } else {
                        alert('Gagal menghapus booking: ' + result.message);
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menghapus booking');
                }
            }
        });
    }

    // ===== FUNGSI FILTER BACKEND =====

    // Fungsi untuk filter berdasarkan status card
    function filterByStatus(status) {
        // Reset semua filter
        document.getElementById('searchInput').value = '';
        document.getElementById('statusFilter').value = status || '';
        document.getElementById('venueFilter').value = '';
        
        // Submit form filter
        document.getElementById('filterForm').submit();
    }

    // Event listener untuk tombol filter
    document.getElementById('applyFilterBtn')?.addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('filterForm').submit();
    });

    // Event listener untuk status cards
    document.querySelectorAll('.status-card').forEach(card => {
        card.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');
            let status = '';
            
            switch(filter) {
                case 'pending':
                    status = 'pending';
                    break;
                case 'confirmed':
                    status = 'confirmed';
                    break;
                case 'cancelled':
                    status = 'cancelled';
                    break;
                default:
                    status = '';
            }

            filterByStatus(status);
        });
    });

// Form edit booking - dengan debugging lebih detail
// Form edit booking - SIMPLE FIX
const editBookingForm = document.getElementById('editBookingForm');
if (editBookingForm) {
    editBookingForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const updateButton = document.getElementById('updateBookingBtn');
        const originalText = updateButton.querySelector('.btn-text').textContent;
        updateButton.querySelector('.loading-spinner').style.display = 'inline-block';
        updateButton.querySelector('.btn-text').textContent = 'Menyimpan...';
        updateButton.disabled = true;

        try {
            // 1. Hitung total biaya
            const totalCostValue = document.getElementById('editTotalCost').value;
            let totalBiaya = 0;
            if (totalCostValue) {
                totalBiaya = parseInt(totalCostValue.replace(/[^\d]/g, '')) || 0;
            }

            // 2. Buat FormData (bukan JSON) - INI YANG PALING AMAN
            const formData = new FormData();
            formData.append('nama_customer', document.getElementById('editCustomerName').value);
            formData.append('customer_phone', document.getElementById('editCustomerPhone').value);
            formData.append('tanggal_booking', document.getElementById('editBookingDate').value);
            formData.append('waktu_booking', document.getElementById('editBookingTime').value);
            formData.append('durasi', document.getElementById('editDuration').value);
            formData.append('total_biaya', totalBiaya);
            formData.append('status', document.getElementById('editStatus').value);
            formData.append('catatan', document.getElementById('editNotes').value);
            formData.append('venue_id', document.getElementById('editVenue').value);
            
            // 3. Untuk PUT method, gunakan method spoofing
            formData.append('_method', 'PUT');

            console.log('Sending FormData:');
            for (let [key, value] of formData.entries()) {
                console.log(key + ': ' + value);
            }

            // 4. Kirim request - PAKAI FORMDATA, BUKAN JSON
            const response = await fetch(`/venue/booking-masuk/update/${currentBookingId}`, {
                method: 'POST', // Selalu POST untuk method spoofing
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                    // JANGAN pakai 'Content-Type': 'application/json'
                    // Biarkan browser set Content-Type otomatis untuk FormData
                },
                body: formData
            });

            const result = await response.json();
            console.log('Update Response:', result);

            if (result.success) {
                alert('Booking berhasil diupdate!');
                bootstrap.Modal.getInstance(document.getElementById('editBookingModal')).hide();
                window.location.reload();
            } else {
                alert('Gagal mengupdate booking: ' + result.message);
            }
        } catch (error) {
            console.error('Error:', error);
            
            // Coba tampilkan response text jika error
            try {
                const response = await fetch(`/venue/booking-masuk/update/${currentBookingId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: new FormData()
                });
                const text = await response.text();
                console.log('Error response text:', text.substring(0, 500));
            } catch (e) {
                console.error('Cannot get error response:', e);
            }
            
            alert('Terjadi kesalahan saat mengupdate booking');
        } finally {
            updateButton.querySelector('.loading-spinner').style.display = 'none';
            updateButton.querySelector('.btn-text').textContent = originalText;
            updateButton.disabled = false;
        }
    });
}


    // Set minimum date to today untuk booking date
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('bookingDate')?.setAttribute('min', today);
    document.getElementById('editBookingDate')?.setAttribute('min', today);

    console.log('Booking functionality initialized successfully');
});
</script>
@endpush
[file content end]