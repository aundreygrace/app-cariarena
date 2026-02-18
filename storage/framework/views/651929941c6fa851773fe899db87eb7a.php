

<?php $__env->startSection('title', 'Riwayat Pesanan'); ?>

<?php $__env->startSection('styles'); ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<style>
    /* ==== VARIABLES ==== */
    :root {
        --primary-color: #6293c4;
        --primary-hover: #4a7cb0;
        --primary-light: #E8F4FD;
        --text-dark: #1A202C;
        --text-light: #64748b;
        --bg-light: #f8fafc;
        --card-bg: #FFFFFF;
        --success: #1AC42E;
        --danger: #FE2222;
        --warning: #F59E0B;
        --info: #6293c4;
        --pending: #F59E0B; /* Warna untuk status menunggu */
        --card-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        --card-radius: 16px;
        --star-color: #FFD700;
        --star-hover: #FFC107;
    }

    /* MAIN CONTAINER - Match Beranda */
    .main-container {
        height: 100%;
        background: #f8fafc;
        min-height: 100vh;
        padding: 0 0;
        margin-top: -40px;
    }

    /* CONTENT WRAPPER - Match Beranda */
    .content-wrapper {
        width: 100%;
        max-width: 1600px;
        margin: 0 auto;
        padding: 0 40px;
        padding-top: 40px;
        padding-bottom: 80px;
    }

    @media (max-width: 1440px) {
        .content-wrapper {
            max-width: 1200px;
            padding: 0 30px;
            padding-top: 40px;
            padding-bottom: 60px;
        }
    }

    @media (max-width: 768px) {
        .content-wrapper {
            padding: 0 20px;
            padding-top: 30px;
            padding-bottom: 50px;
        }
    }

    @media (max-width: 480px) {
        .content-wrapper {
            padding: 0 16px;
            padding-top: 25px;
            padding-bottom: 40px;
        }
    }

    /* HEADER SECTION */
    .page-header {
        text-align: left;
        margin-bottom: 25px;
        padding-top: 0;
    }

    .page-title {
        font-size: 32px;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 8px;
        letter-spacing: -0.5px;
    }

    .page-subtitle {
        font-size: 16px;
        color: var(--text-light);
        line-height: 1.5;
        font-weight: 400;
        margin: 0;
    }

    /* FILTER TABS */
    .filter-tabs {
        display: flex;
        gap: 12px;
        margin: 0 0 25px 0;
        flex-wrap: nowrap;
        overflow-x: auto;
        padding: 10px 4px 15px 4px;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .filter-tabs::-webkit-scrollbar {
        display: none;
    }

    .filter-tab {
        padding: 12px 20px;
        border-radius: 25px;
        font-size: 14px;
        font-weight: 600;
        border: 2px solid #e2e8f0;
        background: white;
        color: var(--text-light);
        cursor: pointer;
        transition: all 0.3s ease;
        white-space: nowrap;
        flex-shrink: 0;
        min-width: max-content;
    }

    .filter-tab:hover {
        border-color: var(--primary-color);
        color: var(--primary-color);
        transform: translateY(-2px);
    }

    .filter-tab.active {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
        color: white;
        border-color: var(--primary-color);
        box-shadow: 0 4px 15px rgba(98, 147, 196, 0.3);
    }

    /* ORDER GRID */
    .order-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 40px;
    }

    /* ORDER CARD */
    .order-card {
        background: white;
        border-radius: 16px;
        padding: 0;
        box-shadow: var(--card-shadow);
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        min-height: 420px;
        position: relative;
    }

    .order-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.12);
        border-color: var(--primary-color);
    }

    /* VENUE HEADER - Sama seperti menu Pesan */
    .venue-header {
        position: relative;
    }

    .venue-image-container {
        width: 100%;
        height: 180px;
        overflow: hidden;
        position: relative;
    }

    .venue-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .order-card:hover .venue-image {
        transform: scale(1.05);
    }

    .image-placeholder {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-light);
    }

    .image-placeholder i {
        font-size: 48px;
        opacity: 0.5;
    }

    /* VENUE OVERLAY - Sama seperti menu Pesan: Badge di atas gambar */
    .venue-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        padding: 15px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    /* BADGE STYLING - Sama seperti menu Pesan */
    .badge {
        padding: 8px 16px;
        border-radius: 25px;
        font-size: 12px;
        font-weight: 700;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        border: none;
        white-space: nowrap;
    }

    .badge-category {
        background: var(--primary-color);
        color: white;
    }

    .badge-status {
        color: white;
    }

    .badge-menunggu {
        background: var(--pending); /* Warna kuning untuk menunggu */
    }

    .badge-selesai {
        background: var(--success);
    }

    .badge-dibatalkan {
        background: var(--danger);
    }

    /* ORDER HEADER - PERBAIKAN: Menghapus badge dari sini */
    .order-header {
        padding: 20px 20px 10px 20px;
        position: relative;
    }

    .venue-name {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-dark);
        margin: 0 0 15px 0;
        line-height: 1.4;
        height: 50px;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        word-break: break-word;
    }

    .order-price {
        font-size: 20px;
        font-weight: 700;
        color: var(--primary-color);
        text-align: right;
        margin-bottom: 15px;
    }

    /* ORDER DETAILS */
    .order-details {
        padding: 15px 20px;
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 10px;
        border-top: 1px solid #f1f5f9;
    }

    .detail-item {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        font-size: 14px;
        color: var(--text-light);
        line-height: 1.4;
    }

    .detail-item i {
        width: 14px;
        color: var(--primary-color);
        font-size: 14px;
        text-align: center;
        margin-top: 2px;
        flex-shrink: 0;
    }

    .detail-item span {
        line-height: 1.4;
        flex: 1;
        word-break: break-word;
        overflow-wrap: break-word;
        hyphens: auto;
    }

    /* Location text khusus */
    .location-text {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* RATING SECTION */
    .rating-section {
        padding: 15px 20px;
        border-top: 1px solid #f1f5f9;
    }

    .rating-label {
        font-size: 13px;
        color: var(--text-light);
        margin-bottom: 6px;
        font-weight: 600;
    }

    .rating-stars {
        display: flex;
        gap: 2px;
        color: var(--warning);
        margin-bottom: 8px;
    }

    .rating-stars .fa-star {
        font-size: 14px;
    }

    .rating-text {
        font-size: 13px;
        color: var(--text-dark);
        line-height: 1.4;
        background: #f8fafc;
        padding: 10px;
        border-radius: 8px;
        border-left: 3px solid var(--primary-color);
        margin-top: 8px;
        word-break: break-word;
        overflow-wrap: break-word;
    }

    .no-rating {
        color: #9ca3af;
        font-size: 13px;
        font-style: italic;
    }

    /* ACTION BUTTONS */
    .action-section {
        padding: 15px 20px;
        margin-top: auto;
        flex-shrink: 0;
        min-height: 38px;
        border-top: 1px solid #f1f5f9;
    }

    .action-btn {
        width: 100%;
        padding: 10px 12px;
        border-radius: 8px;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.3s ease;
        cursor: pointer;
        font-size: 13px;
        border: none;
        text-decoration: none;
        text-align: center;
        min-height: 38px;
    }

    .btn-rating {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
        color: white;
    }

    .btn-rating:hover {
        background: linear-gradient(135deg, var(--primary-hover) 0%, #3a6ea5 100%);
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(98, 147, 196, 0.3);
    }

    .btn-cancel {
        background: white;
        color: var(--danger);
        border: 2px solid var(--danger);
    }

    .btn-cancel:hover:not(:disabled) {
        background: var(--danger);
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(254, 34, 34, 0.3);
    }

    .btn-cancel:disabled {
        background: #f3f4f6;
        color: #9ca3af;
        border: 2px solid #d1d5db;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    /* EMPTY STATE */
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: var(--text-light);
        grid-column: 1 / -1;
        background: white;
        border-radius: 16px;
        box-shadow: var(--card-shadow);
        margin: 20px 0;
        border: 1px solid #e2e8f0;
    }

    .empty-icon {
        font-size: 48px;
        margin-bottom: 16px;
        color: #cbd5e0;
        opacity: 0.5;
    }

    .empty-title {
        font-size: 20px;
        margin-bottom: 8px;
        color: var(--text-dark);
        font-weight: 600;
    }

    .empty-description {
        font-size: 14px;
        max-width: 400px;
        margin: 0 auto;
        line-height: 1.5;
    }

    /* MODAL OVERLAY */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        padding: 20px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .modal-overlay.active {
        display: flex;
        opacity: 1;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    /* RATING MODAL */
    .modal-container {
        background: white;
        border-radius: 16px;
        width: 100%;
        max-width: 500px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        animation: slideUp 0.3s ease;
        position: relative;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .modal-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
        color: white;
        padding: 20px 25px;
        text-align: center;
        position: sticky;
        top: 0;
        z-index: 1;
    }

    .modal-title {
        font-size: 22px;
        font-weight: 700;
        margin: 0;
        color: white;
    }

    .modal-body {
        padding: 25px;
    }

    /* Rating Stars in Modal */
    .rating-stars-container {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin-bottom: 20px;
    }

    .star-rating {
        font-size: 32px;
        color: #e2e8f0;
        cursor: pointer;
        transition: all 0.2s ease;
        padding: 5px;
    }

    .star-rating:hover {
        color: var(--star-hover);
        transform: scale(1.1);
    }

    .star-rating.selected {
        color: var(--star-color);
    }

    .star-rating.hovered {
        color: var(--star-hover);
    }

    .rating-label-modal {
        text-align: center;
        font-size: 15px;
        color: var(--text-light);
        margin-bottom: 12px;
        min-height: 20px;
        font-weight: 500;
    }

    /* Review Textarea */
    .review-textarea {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        resize: vertical;
        font-size: 14px;
        font-family: inherit;
        min-height: 100px;
        margin-bottom: 20px;
        box-sizing: border-box;
        transition: all 0.3s ease;
        line-height: 1.5;
    }

    .review-textarea:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(98, 147, 196, 0.2);
    }

    /* Button Container */
    .button-container {
        display: flex;
        gap: 12px;
        margin-top: 10px;
    }

    .btn-modal {
        flex: 1;
        padding: 12px 15px;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid transparent;
        font-size: 14px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        box-sizing: border-box;
    }

    .btn-cancel-modal {
        background: white;
        color: var(--text-dark);
        border-color: #e2e8f0;
    }

    .btn-cancel-modal:hover {
        background: #f8fafc;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .btn-submit-modal {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
        color: white;
        border-color: var(--primary-color);
    }

    .btn-submit-modal:hover {
        background: linear-gradient(135deg, var(--primary-hover) 0%, #3a6ea5 100%);
        border-color: var(--primary-hover);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(98, 147, 196, 0.4);
    }

    /* CANCEL MODAL */
    .modal-container-cancel {
        background: white;
        border-radius: 16px;
        width: 100%;
        max-width: 500px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        animation: slideUp 0.3s ease;
        position: relative;
    }

    .modal-header-cancel {
        background: linear-gradient(135deg, var(--danger) 0%, #d91a1a 100%);
        color: white;
        padding: 20px 25px;
        text-align: center;
        position: sticky;
        top: 0;
        z-index: 1;
    }

    .modal-title-cancel {
        font-size: 22px;
        font-weight: 700;
        margin: 0 0 5px 0;
        color: white;
    }

    .modal-subtitle-cancel {
        font-size: 14px;
        margin: 0;
        color: rgba(255, 255, 255, 0.95);
        opacity: 0.9;
        font-weight: 400;
    }

    .cancel-modal-body {
        padding: 25px;
    }

    .refund-details {
        background: #f8fafc;
        border-radius: 10px;
        padding: 18px;
        margin-bottom: 20px;
        border: 1px solid #e2e8f0;
    }

    .refund-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px solid #e2e8f0;
    }

    .refund-row:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .refund-row.total {
        font-size: 18px;
        font-weight: 700;
        color: var(--primary-color);
        padding-top: 12px;
        margin-top: 8px;
        border-top: 2px solid #e2e8f0;
    }

    .refund-label {
        font-size: 14px;
        color: var(--text-light);
    }

    .refund-value {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-dark);
    }

    .refund-value.total {
        font-size: 20px;
        color: var(--primary-color);
    }

    .refund-note {
        text-align: center;
        font-size: 13px;
        color: var(--text-light);
        line-height: 1.5;
        margin-bottom: 20px;
        padding: 0;
    }

    .confirm-text {
        text-align: center;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 20px;
        font-size: 15px;
    }

    .cancel-modal-buttons {
        display: flex;
        gap: 12px;
    }

    .btn-back {
        flex: 1;
        padding: 12px 15px;
        border-radius: 10px;
        font-weight: 600;
        background: white;
        color: var(--text-dark);
        border: 2px solid #e2e8f0;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 14px;
    }

    .btn-back:hover {
        background: #f8fafc;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .btn-confirm-cancel {
        flex: 1;
        padding: 12px 15px;
        border-radius: 10px;
        font-weight: 600;
        background: linear-gradient(135deg, var(--danger) 0%, #d91a1a 100%);
        color: white;
        border: 2px solid var(--danger);
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-size: 14px;
    }

    .btn-confirm-cancel:hover {
        background: linear-gradient(135deg, #d91a1a 0%, #c41818 100%);
        border-color: #d91a1a;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(254, 34, 34, 0.3);
    }

    /* ==== RESPONSIVE DESIGN ==== */
    @media (max-width: 1199px) {
        .order-grid {
            grid-template-columns: repeat(3, 1fr);
        }
        
        .venue-image-container {
            height: 160px;
        }
        
        .modal-container,
        .modal-container-cancel {
            max-width: 550px;
        }
    }

    @media (max-width: 991px) {
        .order-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .page-title {
            font-size: 28px;
        }
        
        .venue-image-container {
            height: 150px;
        }
        
        .modal-container,
        .modal-container-cancel {
            max-width: 500px;
        }
        
        .badge {
            padding: 6px 12px;
            font-size: 11px;
        }
    }

    @media (max-width: 767px) {
        .content-wrapper {
            padding: 0 20px;
            padding-top: 30px;
            padding-bottom: 50px;
        }
        
        .page-header {
            margin-bottom: 20px;
        }
        
        .page-title {
            font-size: 24px;
        }
        
        .page-subtitle {
            font-size: 14px;
        }
        
        .filter-tabs {
            gap: 8px;
            margin-bottom: 20px;
            padding: 8px 2px 12px 2px;
        }
        
        .filter-tab {
            padding: 10px 16px;
            font-size: 13px;
        }
        
        .order-grid {
            grid-template-columns: 1fr;
            gap: 16px;
            margin-bottom: 30px;
        }
        
        .order-card {
            min-height: auto;
        }
        
        .venue-image-container {
            height: 140px;
        }
        
        .venue-overlay {
            padding: 12px;
        }
        
        .order-header {
            padding: 12px 15px 8px 15px;
        }
        
        .venue-name {
            font-size: 16px;
            height: auto;
            min-height: 40px;
            margin-bottom: 8px;
        }
        
        .order-price {
            font-size: 18px;
            margin-bottom: 10px;
        }
        
        .badge {
            padding: 5px 10px;
            font-size: 10px;
        }
        
        .order-details {
            padding: 12px 15px;
        }
        
        .detail-item {
            font-size: 13px;
        }
        
        .modal-container,
        .modal-container-cancel {
            max-width: 90%;
            margin: 0;
        }
        
        .modal-header,
        .modal-header-cancel {
            padding: 18px 20px;
        }
        
        .modal-title,
        .modal-title-cancel {
            font-size: 20px;
        }
        
        .modal-subtitle-cancel {
            font-size: 13px;
        }
        
        .modal-body,
        .cancel-modal-body {
            padding: 20px;
        }
        
        .star-rating {
            font-size: 28px;
        }
        
        .review-textarea {
            min-height: 90px;
            font-size: 13px;
            padding: 10px 12px;
        }
        
        .button-container,
        .cancel-modal-buttons {
            gap: 10px;
        }
        
        .btn-modal,
        .btn-back,
        .btn-confirm-cancel {
            padding: 10px 12px;
            font-size: 13px;
        }
        
        .refund-details {
            padding: 15px;
        }
        
        .refund-label,
        .refund-value {
            font-size: 13px;
        }
        
        .refund-value.total {
            font-size: 18px;
        }
        
        .empty-state {
            padding: 30px 15px;
        }
        
        .empty-icon {
            font-size: 40px;
        }
        
        .empty-title {
            font-size: 18px;
        }
        
        .empty-description {
            font-size: 13px;
        }
    }

    @media (max-width: 480px) {
        .content-wrapper {
            padding: 0 16px;
            padding-top: 25px;
            padding-bottom: 40px;
        }
        
        .page-title {
            font-size: 22px;
        }
        
        .filter-tabs {
            gap: 6px;
            padding: 6px 1px 10px 1px;
        }
        
        .filter-tab {
            padding: 8px 14px;
            font-size: 12px;
        }
        
        .order-grid {
            gap: 12px;
        }
        
        .venue-image-container {
            height: 120px;
        }
        
        .venue-overlay {
            padding: 8px;
        }
        
        .badge {
            padding: 4px 8px;
            font-size: 9px;
        }
        
        .order-header {
            padding: 10px 12px 6px 12px;
        }
        
        .venue-name {
            font-size: 15px;
            margin-bottom: 6px;
        }
        
        .order-price {
            font-size: 16px;
            margin-bottom: 8px;
        }
        
        .modal-container,
        .modal-container-cancel {
            max-width: 95%;
        }
        
        .modal-overlay {
            padding: 15px;
        }
        
        .button-container,
        .cancel-modal-buttons {
            flex-direction: column;
        }
        
        .btn-modal,
        .btn-back,
        .btn-confirm-cancel {
            width: 100%;
        }
        
        .star-rating {
            font-size: 24px;
        }
        
        .review-textarea {
            min-height: 80px;
            font-size: 12px;
        }

    @media (max-width: 360px) {
        .content-wrapper {
            padding: 0 12px;
            padding-top: 20px;
            padding-bottom: 30px;
        }
        
        .page-title {
            font-size: 20px;
        }
        
        .filter-tabs {
            gap: 4px;
            padding: 4px 0 8px 0;
        }
        
        .filter-tab {
            padding: 6px 12px;
            font-size: 11px;
        }
        
        .venue-image-container {
            height: 100px;
        }
        
        .venue-name {
            font-size: 14px;
        }
        
        .order-price {
            font-size: 15px;
        }
        
        .action-btn {
            font-size: 11px;
        }
        
        .badge {
            padding: 3px 6px;
            font-size: 8px;
        }
        
        .modal-overlay {
            padding: 10px;
        }
        
        .modal-container,
        .modal-container-cancel {
            max-width: 100%;
        }
        
        .modal-header,
        .modal-header-cancel {
            padding: 15px;
        }
        
        .modal-body,
        .cancel-modal-body {
            padding: 15px;
        }
    }

    /* ANIMATIONS */
    .fade-in-up {
        animation: fadeInUp 0.6s ease-out;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* SCROLLBAR STYLING */
    .modal-container::-webkit-scrollbar,
    .modal-container-cancel::-webkit-scrollbar {
        width: 6px;
    }

    .modal-container::-webkit-scrollbar-track,
    .modal-container-cancel::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }

    .modal-container::-webkit-scrollbar-thumb,
    .modal-container-cancel::-webkit-scrollbar-thumb {
        background: var(--primary-color);
        border-radius: 10px;
    }

    .modal-container::-webkit-scrollbar-thumb:hover,
    .modal-container-cancel::-webkit-scrollbar-thumb:hover {
        background: var(--primary-hover);
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="main-container">
    <div class="content-wrapper">

        <!-- Filter Tabs -->
        <div class="filter-tabs">
            <button class="filter-tab active" data-filter="all">Semua Pesanan</button>
            <button class="filter-tab" data-filter="menunggu">Menunggu</button>
            <button class="filter-tab" data-filter="selesai">Selesai</button>
            <button class="filter-tab" data-filter="dibatalkan">Dibatalkan</button>
        </div>

        <!-- Order Grid -->
        <div class="order-grid">
            <?php if(isset($orders) && count($orders) > 0): ?>
                <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    // Controller sudah kirim data lengkap dengan state!
                    // Tidak perlu logic kompleks di blade
                    $statusClass = $order['state']['statusBadge'];
                    $statusText = $order['state']['statusText'];
                    $filterStatus = strtolower($order['state']['status']);

                    // Review data (jika ada)
                    $hasRating = $order['state']['showReview'];
                    $rating = $hasRating ? $order['state']['review']['rating'] : 0;
                    $reviewComment = $hasRating ? $order['state']['review']['comment'] : '';

                    // Flags dari controller
                    $canRate = $order['state']['canReview'];
                    $canCancel = $order['state']['canCancel'];

                    // Data booking (sudah diformat controller)
                    $dateFormatted = $order['date_short'];
                    $timeRange = $order['time_range'];
                    $totalPrice = $order['total_raw'];
                    $formattedPrice = str_replace('Rp ', '', $order['total']);
                    $venueName = $order['venue_name'];
                    $location = $order['venue_location'];
                    $shortLocation = (strlen($location) > 60) ? substr($location, 0, 57) . '...' : $location;
                    $venueId = $order['venue_id'];
                    $bookingCode = $order['booking_code'];
                    $orderId = $order['id'];
                    $category = $order['venue_category'];
                    $venueImage = $order['venue_image'];
                ?>
                    <div class="order-card" data-status="<?php echo e($filterStatus); ?>">
                        <!-- VENUE HEADER SECTION -->
                        <div class="venue-header">
                            <div class="venue-image-container">
                                <?php if(!empty($venueImage)): ?>
                                    <img src="<?php echo e($venueImage); ?>" 
                                         alt="<?php echo e($venueName); ?>" 
                                         class="venue-image"
                                         onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80';">
                                <?php else: ?>
                                    <div class="image-placeholder">
                                        <i class="fa-solid fa-image"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- VENUE OVERLAY dengan Badge -->
                                <div class="venue-overlay">
                                    <span class="badge badge-category">
                                        <?php echo e($order->category ?? 'Badminton'); ?>

                                    </span>
                                    <span class="badge badge-status <?php echo e($statusClass); ?>">
                                        <?php echo e($statusText); ?>

                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="order-header">
                            <h3 class="venue-name"><?php echo e($venueName); ?></h3>
                            <div class="order-price">Rp <?php echo e($formattedPrice); ?></div>
                        </div>
                        
                        <div class="order-details">
                            <div class="detail-item">
                                <i class="fa-regular fa-calendar"></i>
                                <span><?php echo e($dateFormatted); ?></span>
                            </div>
                            <div class="detail-item">
                                <i class="fa-regular fa-clock"></i>
                                <span><?php echo e($timeRange); ?></span>
                            </div>
                            <div class="detail-item">
                                <i class="fa-solid fa-location-dot"></i>
                                <span class="location-text" title="<?php echo e($location); ?>"><?php echo e($shortLocation); ?></span>
                            </div>
                        </div>
                        <!-- BUTTON LOGIC DARI STATE CONTROLLER -->
                        <?php if($order['state']['showReview']): ?>
                            <!-- TAMPILAN REVIEW YANG SUDAH ADA -->
                            <div class="rating-section">
                                <div class="rating-label">Rating Anda:</div>
                                <div class="rating-stars">
                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                        <?php if($i <= $rating): ?>
                                            <i class="fa-solid fa-star"></i>
                                        <?php else: ?>
                                            <i class="fa-regular fa-star"></i>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                </div>
                                <?php if(!empty($reviewComment)): ?>
                                    <div class="rating-text"><?php echo e($reviewComment); ?></div>
                                <?php endif; ?>
                            </div>
                        <?php elseif($order['state']['buttonText']): ?>
                            <!-- TAMPILAN BUTTON (Review atau Cancel) -->
                            <div class="action-section">
                                <button 
                                    class="action-btn <?php echo e($order['state']['buttonAction'] === 'review' ? 'btn-rating' : 'btn-cancel'); ?>" 
                                    <?php if($order['state']['buttonDisabled']): ?> 
                                        disabled
                                        <?php if(isset($order['state']['disabledReason'])): ?>
                                            title="<?php echo e($order['state']['disabledReason']); ?>"
                                        <?php endif; ?>
                                    <?php else: ?>
                                        onclick="
                                        <?php if($order['state']['buttonAction'] === 'review'): ?>
                                            openRatingModal(<?php echo e($orderId); ?>, '<?php echo e($bookingCode); ?>', '<?php echo e(addslashes($venueName)); ?>', <?php echo e($venueId); ?>)
                                        <?php elseif($order['state']['buttonAction'] === 'cancel'): ?>
                                            openCancelModal(<?php echo e($orderId); ?>, '<?php echo e($bookingCode); ?>', '<?php echo e(addslashes($venueName)); ?>', <?php echo e($totalPrice); ?>)
                                        <?php endif; ?>
                                        "
                                    <?php endif; ?>
                                >
                                    <?php if($order['state']['buttonAction'] === 'review'): ?>
                                        <i class="fa-regular fa-star"></i>
                                    <?php elseif($order['state']['buttonAction'] === 'cancel'): ?>
                                        <i class="fa-solid fa-xmark"></i>
                                    <?php endif; ?>
                                    <?php echo e($order['state']['buttonText']); ?>

                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                <!-- Empty State -->
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fa-regular fa-calendar-xmark"></i>
                    </div>
                    <h3 class="empty-title">Belum Ada Riwayat Booking</h3>
                    <p class="empty-description">
                        Anda belum memiliki riwayat pemesanan venue. Yuk, booking venue favorit Anda sekarang!
                    </p>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>

<!-- Rating Modal -->
<div id="ratingModal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <h2 class="modal-title">Beri Rating & Ulasan</h2>
        </div>
        
        <div class="modal-body">
            <input type="hidden" id="ratingOrderId">
            <input type="hidden" id="ratingVenueId">
            
            <div class="rating-stars-container">
                <!-- Star ratings akan di-generate oleh JavaScript -->
            </div>
            
            <div class="rating-label-modal" id="ratingText">Beri rating dengan mengklik bintang</div>
            
            <textarea class="review-textarea" id="reviewText" placeholder="Bagikan pengalaman Anda menggunakan venue ini (opsional)..." rows="4"></textarea>
            
            <div class="button-container">
                <button type="button" class="btn-modal btn-cancel-modal" onclick="closeRatingModal()">
                    Batal
                </button>
                <button type="button" class="btn-modal btn-submit-modal" onclick="submitRating()" id="submitRatingBtn">
                    Kirim Rating
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Modal -->
<div id="cancelModal" class="modal-overlay">
    <div class="modal-container-cancel">
        <div class="modal-header-cancel">
            <h2 class="modal-title-cancel">Batalkan Pemesanan?</h2>
            <p class="modal-subtitle-cancel">Konfirmasi pembatalan booking Anda</p>
        </div>
        
        <div class="cancel-modal-body">
            <input type="hidden" id="cancelOrderId">
            
            <div class="refund-details">
                <div class="refund-row">
                    <div class="refund-label">Total Pembayaran</div>
                    <div class="refund-value" id="totalPayment">Rp 0</div>
                </div>
                <div class="refund-row total">
                    <div class="refund-label">Dana yang Dikembalikan</div>
                    <div class="refund-value total" id="refundAmount">Rp 0</div>
                </div>
            </div>
            
            <div class="refund-note">
                Dana akan dikembalikan ke metode pembayaran awal dalam <strong>1-3 hari</strong>
            </div>
            
            <div class="confirm-text">
                Apakah Anda yakin ingin membatalkan pemesanan?
            </div>
            
            <div class="cancel-modal-buttons">
                <button class="btn-back" onclick="closeCancelModal()">
                    Kembali
                </button>
                <button class="btn-confirm-cancel" onclick="confirmCancelBooking()">
                    <i class="fa-solid fa-check"></i>
                    Ya, Batalkan
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Global variables
let selectedRating = 0;
let currentOrderId = null;
let currentVenueId = null;

// Rating descriptions
const ratingDescriptions = {
    0: "Beri rating dengan mengklik bintang",
    1: "Sangat Buruk",
    2: "Buruk",
    3: "Cukup",
    4: "Bagus",
    5: "Sangat Bagus"
};

// Filter functionality
document.addEventListener('DOMContentLoaded', function() {
    const filterTabs = document.querySelectorAll('.filter-tab');
    const orderCards = document.querySelectorAll('.order-card');
    
    // Filter orders
    function filterOrders(status) {
        let visibleCount = 0;
        
        orderCards.forEach(card => {
            const cardStatus = card.getAttribute('data-status');
            let showCard = false;
            
            switch(status) {
                case 'all':
                    showCard = true;
                    break;
                case 'menunggu':
                    showCard = cardStatus === 'menunggu';
                    break;
                case 'selesai':
                    showCard = cardStatus === 'selesai';
                    break;
                case 'dibatalkan':
                    showCard = cardStatus === 'dibatalkan';
                    break;
                default:
                    showCard = true;
            }
            
            if (showCard) {
                card.style.display = 'flex';
                card.style.animation = 'fadeInUp 0.5s ease-out';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });
        
        // Tampilkan empty state jika tidak ada yang visible
        const orderGrid = document.querySelector('.order-grid');
        const existingEmptyState = orderGrid.querySelector('.empty-state');
        
        if (visibleCount === 0 && !existingEmptyState) {
            const emptyStateHtml = `
                <div class="empty-state" id="filter-empty-state">
                    <div class="empty-icon">
                        <i class="fa-regular fa-calendar-xmark"></i>
                    </div>
                    <h3 class="empty-title">Tidak Ada Data</h3>
                    <p class="empty-description">
                        Tidak ada riwayat booking dengan status ini.
                    </p>
                </div>
            `;
            orderGrid.insertAdjacentHTML('beforeend', emptyStateHtml);
        } else if (existingEmptyState && visibleCount > 0) {
            // Hapus hanya empty state dari filter, bukan yang asli
            if (existingEmptyState.id === 'filter-empty-state') {
                existingEmptyState.remove();
            }
        }
        
        // Update active tab
        filterTabs.forEach(tab => {
            tab.classList.toggle('active', tab.getAttribute('data-filter') === status);
        });
    }
    
    // Add event listeners to filter tabs
    filterTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');
            filterOrders(filter);
        });
    });
    
    // Initialize with all orders
    filterOrders('all');
});

// Function to set rating
function setRating(rating) {
    selectedRating = rating;
    highlightStars(rating);
    document.getElementById('ratingText').textContent = ratingDescriptions[rating];
}

// Function to highlight stars
function highlightStars(rating) {
    const stars = document.querySelectorAll('.star-rating');
    stars.forEach(star => {
        const starRating = parseInt(star.getAttribute('data-rating'));
        const starIcon = star.querySelector('i');
        
        star.classList.remove('selected', 'hovered');
        
        if (starRating <= rating) {
            star.classList.add('selected');
            starIcon.className = 'fa-solid fa-star';
        } else {
            starIcon.className = 'fa-regular fa-star';
        }
    });
}

// Rating Modal Functions
function openRatingModal(orderId, bookingCode, venueName, venueId) {
    currentOrderId = orderId;
    currentVenueId = venueId;
    
    // Set hidden inputs
    document.getElementById('ratingOrderId').value = orderId;
    document.getElementById('ratingVenueId').value = venueId;
    
    const modal = document.getElementById('ratingModal');
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
    
    // Set modal title with booking info
    const modalTitle = modal.querySelector('.modal-title');
    modalTitle.textContent = `Beri Rating - ${venueName}`;
    
    // Generate star ratings HTML
    const starsContainer = modal.querySelector('.rating-stars-container');
    starsContainer.innerHTML = '';
    
    for (let i = 1; i <= 5; i++) {
        const star = document.createElement('div');
        star.className = 'star-rating';
        star.setAttribute('data-rating', i);
        star.innerHTML = '<i class="fa-regular fa-star"></i>';
        
        star.addEventListener('click', function() {
            setRating(i);
        });
        
        star.addEventListener('mouseover', function() {
            const hoverRating = parseInt(this.getAttribute('data-rating'));
            highlightStars(hoverRating);
            document.getElementById('ratingText').textContent = ratingDescriptions[hoverRating];
        });
        
        star.addEventListener('mouseout', function() {
            highlightStars(selectedRating);
            document.getElementById('ratingText').textContent = ratingDescriptions[selectedRating];
        });
        
        starsContainer.appendChild(star);
    }
    
    // Reset rating
    selectedRating = 0;
    highlightStars(0);
    document.getElementById('ratingText').textContent = ratingDescriptions[0];
    document.getElementById('reviewText').value = '';
}

function closeRatingModal() {
    const modal = document.getElementById('ratingModal');
    modal.classList.remove('active');
    document.body.style.overflow = '';
    currentOrderId = null;
    currentVenueId = null;
}

async function submitRating() {
    if (selectedRating === 0) {
        alert('Silakan beri rating terlebih dahulu!');
        return;
    }
    
    const orderId = document.getElementById('ratingOrderId').value;
    const reviewText = document.getElementById('reviewText').value.trim();
    
    // ✅ Validation
    if (reviewText.length < 10) {
        alert('Ulasan minimal 10 karakter');
        return;
    }
    
    // Disable button
    const submitBtn = document.getElementById('submitRatingBtn');
    submitBtn.disabled = true;
    submitBtn.textContent = 'Mengirim...';
    
    try {
        // ✅ POST ke route baru: /riwayat/review/{id}
        const response = await fetch(`/riwayat/review/${orderId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                rating: selectedRating,
                comment: reviewText
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert(data.message || 'Review berhasil dikirim!');
            window.location.reload();
        } else {
            alert(data.message || 'Gagal mengirim review');
            submitBtn.disabled = false;
            submitBtn.textContent = 'Kirim Rating';
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengirim review');
        submitBtn.disabled = false;
        submitBtn.textContent = 'Kirim Rating';
    }
}

// Cancel Modal Functions
function openCancelModal(orderId, bookingCode, venueName, totalPrice) {
    currentOrderId = orderId;
    
    // Set hidden input
    document.getElementById('cancelOrderId').value = orderId;
    
    const modal = document.getElementById('cancelModal');
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
    
    // Set modal title
    const modalTitle = modal.querySelector('.modal-title-cancel');
    modalTitle.textContent = `Batalkan Pemesanan - ${venueName}`;
    
    // Update refund details dengan data dinamis
    const adminFee = 5000;
    const refundAmount = totalPrice - adminFee;
    const finalRefundAmount = refundAmount > 0 ? refundAmount : 0;
    
    // Format numbers with thousand separators
    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
    
    document.getElementById('totalPayment').textContent = 'Rp ' + formatNumber(totalPrice);
    document.getElementById('refundAmount').textContent = 'Rp ' + formatNumber(finalRefundAmount);
}

/**
 * ✅ CONFIRM CANCEL BOOKING
 * Called when user clicks confirm in cancel modal
 */
async function confirmCancelBooking() {
    const orderId = document.getElementById('cancelOrderId').value;
    const cancelBtn = document.querySelector('.btn-confirm-cancel');
    
    if (!orderId) {
        alert('Order ID tidak valid');
        return;
    }
    
    // Disable button
    if (cancelBtn) {
        cancelBtn.disabled = true;
        cancelBtn.textContent = 'Membatalkan...';
    }
    
    try {
        // ✅ POST ke route: /riwayat/cancel/{id}
        const response = await fetch(`/riwayat/cancel/${orderId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert(data.message || 'Booking berhasil dibatalkan');
            window.location.reload();
        } else {
            alert(data.message || 'Gagal membatalkan booking');
            if (cancelBtn) {
                cancelBtn.disabled = false;
                cancelBtn.textContent = 'Ya, Batalkan';
            }
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat membatalkan booking');
        if (cancelBtn) {
            cancelBtn.disabled = false;
            cancelBtn.textContent = 'Ya, Batalkan';
        }
    }
}

function closeCancelModal() {
    const modal = document.getElementById('cancelModal');
    modal.classList.remove('active');
    document.body.style.overflow = '';
    currentOrderId = null;
}

function confirmCancellation() {
    const orderId = document.getElementById('cancelOrderId').value;
    
    if (!confirm('Apakah Anda yakin ingin membatalkan pemesanan ini?')) {
        return;
    }
    
    // Kirim request ke server - PERBAIKAN: Gunakan URL langsung
    fetch('/riwayat/cancel-booking', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            order_id: orderId
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert(data.message);
            // Refresh halaman
            window.location.reload();
        } else {
            alert(data.message || 'Gagal membatalkan pesanan');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat membatalkan pemesanan. Silakan coba lagi.');
    });
}

// Close modals when clicking outside
document.addEventListener('click', function(event) {
    const ratingModal = document.getElementById('ratingModal');
    const cancelModal = document.getElementById('cancelModal');
    
    if (event.target === ratingModal) {
        closeRatingModal();
    }
    
    if (event.target === cancelModal) {
        closeCancelModal();
    }
});

// Close modals with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeRatingModal();
        closeCancelModal();
    }
});

// Add scroll animation for cards
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.animation = 'fadeInUp 0.6s ease-out';
        }
    });
}, observerOptions);

document.querySelectorAll('.order-card').forEach(card => {
    observer.observe(card);
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.user', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\CariArena\resources\views/user/riwayat.blade.php ENDPATH**/ ?>