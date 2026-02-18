@extends('layouts.user')
@section('title', 'Profil Saya')
@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
/* ===== BACKGROUND UTAMA ===== */
body {
    background-color: #EDF2F7 !important;
}

/* ===== VARIABLES ===== */
:root {
    --primary-color: #6293c4ff;
    --primary-hover: #4a7cb0;
    --text-dark: #1A202C;
    --text-light: #718096;
    --bg-light: #FFFFFF;
    --card-bg: #FFFFFF;
    --success: #1AC42E;
    --danger: #FE2222;
    --warning: #D69E2E;
}

/* ===== MAIN CONTAINER ===== */
.profile-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 16px;
}

/* ===== HORIZONTAL NAVIGATION ===== */
.horizontal-nav {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 0;
    margin-bottom: 20px;
    gap: 0;
    border-bottom: 1px solid #e2e8f0;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: thin;
    background: transparent;
}

.horizontal-nav::-webkit-scrollbar {
    height: 3px;
}

.horizontal-nav::-webkit-scrollbar-thumb {
    background: #cbd5e0;
    border-radius: 2px;
}

.horizontal-nav a {
    color: var(--text-light);
    text-decoration: none;
    padding: 12px 20px;
    border-radius: 0;
    transition: all 0.3s;
    font-weight: 500;
    white-space: nowrap;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    border-bottom: 2px solid transparent;
    margin-bottom: -1px;
    flex-shrink: 0;
    font-size: 14px;
    background: transparent;
}

.horizontal-nav a:hover {
    color: var(--primary-color);
    background-color: transparent;
}

.horizontal-nav a.active {
    color: var(--primary-color);
    font-weight: 600;
    background-color: transparent;
    border-bottom: 2px solid var(--primary-color);
}

/* ===== PROFILE CONTENT ===== */
.profile-content {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

@media (min-width: 1024px) {
    .profile-content {
        flex-direction: row;
    }
}

/* ===== SETTINGS CONTENT ===== */
.settings-content {
    background: var(--card-bg);
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.06);
    border: 1px solid #e2e8f0;
    flex: 1;
    transition: all 0.3s ease;
}

.settings-content h2 {
    color: var(--primary-color);
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 1px solid #f0f4f8;
    font-size: 22px;
}

.settings-content h3 {
    color: var(--text-dark);
    margin: 16px 0 12px;
    font-size: 17px;
}

.settings-section {
    display: none;
}

.settings-section.active {
    display: block;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* ===== EDIT PROFILE LAYOUT ===== */
.edit-profile-layout {
    display: grid;
    grid-template-columns: 1fr;
    gap: 24px;
}

@media (min-width: 1024px) {
    .edit-profile-layout {
        grid-template-columns: 1fr 2fr;
        gap: 32px;
    }
}

/* Profile Info Section */
.profile-info-section {
    background: #f8fafc;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
    border: 1px solid #e2e8f0;
}

.profile-header {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    margin-bottom: 20px;
    padding-bottom: 16px;
    border-bottom: 1px solid #f0f4f8;
}

.profile-header .user-avatar {
    width: 80px;
    height: 80px;
    background: linear-gradient(to bottom, var(--primary-color), var(--primary-hover));
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    font-weight: bold;
    margin-bottom: 15px;
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.08);
}

.profile-header-info h3 {
    font-size: 1.3rem;
    font-weight: 600;
    color: var(--text-dark);
    margin: 0 0 5px 0;
}

.profile-header-info p {
    color: var(--text-light);
    margin: 0;
    font-size: 0.9rem;
}

/* Profile Stats */
.edit-profile-layout .profile-stats {
    display: flex;
    justify-content: space-around;
    margin: 16px 0;
    padding: 12px;
    background: var(--card-bg);
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.02);
}

.edit-profile-layout .stat-item {
    text-align: center;
}

.edit-profile-layout .stat-number {
    display: block;
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--primary-color);
}

.edit-profile-layout .stat-label {
    display: block;
    font-size: 0.8rem;
    color: var(--text-light);
    margin-top: 4px;
}

/* Profile Info Card */
.profile-info-card {
    margin-top: 20px;
    padding-top: 16px;
    border-top: 1px solid #f0f4f8;
}

.profile-info-card h4 {
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 12px;
    text-align: center;
}

.profile-info-card .info-item {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    margin-bottom: 12px;
    padding: 10px;
    background: var(--card-bg);
    border-radius: 6px;
    border: 1px solid #e2e8f0;
    transition: all 0.3s ease;
}

.profile-info-card .info-item:hover {
    border-color: var(--primary-color);
    transform: translateX(2px);
}

.profile-info-card .info-item i {
    width: 18px;
    color: var(--primary-color);
    margin-top: 2px;
    flex-shrink: 0;
    text-align: center;
    font-size: 14px;
}

.profile-info-card .info-item div {
    display: flex;
    flex-direction: column;
    flex: 1;
}

.profile-info-card .info-item strong {
    font-size: 0.8rem;
    color: var(--text-light);
    margin-bottom: 3px;
}

.profile-info-card .info-item span {
    font-size: 0.9rem;
    color: var(--text-dark);
    font-weight: 500;
}

/* Edit Form Section */
.edit-form-section {
    background: #f8fafc;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
    border: 1px solid #e2e8f0;
}

.edit-form-section h3 {
    color: var(--primary-color);
    font-size: 1.2rem;
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 1px solid #e2e8f0;
}

/* ===== PHOTO UPLOAD STYLES ===== */
.photo-upload {
    display: flex;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 15px;
}

.photo-preview {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: linear-gradient(135deg, #ebf5fb 0%, #ffffff 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    border: 2px solid #e2e8f0;
    position: relative;
    flex-shrink: 0;
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.photo-preview:hover {
    border-color: var(--primary-color);
    transform: scale(1.02);
}

.photo-preview img.profile-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
}

.photo-preview .default-avatar {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 8px;
}

.photo-preview .default-avatar i {
    font-size: 2rem;
    color: var(--primary-color);
    margin-bottom: 6px;
}

.photo-preview .default-avatar span {
    font-size: 11px;
    color: var(--text-light);
    font-weight: 500;
}

.photo-loading {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.9);
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
}

.photo-loading i {
    font-size: 1.5rem;
    color: var(--primary-color);
}

.upload-controls {
    flex: 1;
    min-width: 250px;
}

.upload-btn,
.remove-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 10px 16px;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 500;
    transition: all 0.3s;
    font-size: 13px;
    margin-bottom: 8px;
    width: 100%;
    text-align: center;
    border: none;
}

.upload-btn {
    background: #ebf5fb;
    border: 2px dashed var(--primary-color);
    color: var(--primary-color);
}

.upload-btn:hover {
    background: var(--primary-color);
    color: white;
    border-style: solid;
}

.remove-btn {
    background: #fff5f5;
    border: 1px solid var(--danger);
    color: var(--danger);
}

.remove-btn:hover {
    background: var(--danger);
    color: white;
}

.upload-info {
    font-size: 12px;
    color: var(--text-light);
    margin-top: 3px;
    text-align: center;
}

/* ===== FORM STYLES ===== */
.form-group {
    margin-bottom: 16px;
}

.form-group label {
    display: block;
    margin-bottom: 6px;
    font-weight: 500;
    color: var(--text-dark);
    font-size: 14px;
}

.form-group input, .form-group select, .form-group textarea {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
    transition: border 0.3s;
    box-sizing: border-box;
}

.form-group input:focus, .form-group select:focus, .form-group textarea:focus {
    border-color: var(--primary-color);
    outline: none;
    box-shadow: 0 0 0 2px rgba(98, 147, 196, 0.15);
}

.form-group textarea {
    height: 100px;
    resize: vertical;
    min-height: 80px;
}

.form-group small {
    display: block;
    margin-top: 5px;
    color: var(--text-light);
    font-size: 12px;
}

/* ===== ERROR STYLES ===== */
.is-invalid {
    border-color: var(--danger) !important;
    background-color: #fff8f8;
}

.invalid-feedback {
    color: var(--danger);
    font-size: 12px;
    margin-top: 4px;
    display: block;
}

/* ===== CHAR COUNT ===== */
.char-count {
    text-align: right;
    font-size: 11px;
    color: var(--text-light);
    margin-top: 3px;
}

/* ===== ALERT MESSAGES ===== */
.alert {
    padding: 12px 16px;
    border-radius: 6px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.alert i {
    margin-right: 8px;
    font-size: 16px;
}

/* ===== VENUE CARDS ===== */
.venue-card {
    background: #f8fafc;
    border-radius: 10px;
    padding: 16px;
    border: 1px solid #e2e8f0;
    transition: all 0.3s ease;
    margin-bottom: 16px;
}

.venue-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.08);
    border-color: var(--primary-color);
}

.venue-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 12px;
}

.venue-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 4px;
}

.venue-badges {
    display: flex;
    gap: 6px;
    margin-bottom: 8px;
}

.badge {
    font-size: 0.7rem;
    padding: 0.3em 0.6em;
    font-weight: 500;
    border-radius: 4px;
}

.badge-sport {
    background-color: #E3F2FD;
    color: var(--primary-color);
}

.badge-available {
    background-color: #E8F5E8;
    color: var(--success);
}

.badge-full {
    background-color: #FFEBEE;
    color: var(--danger);
}

.venue-details {
    margin-bottom: 12px;
}

.venue-detail {
    display: flex;
    align-items: center;
    margin-bottom: 6px;
    color: var(--text-light);
    font-size: 0.85rem;
}

.venue-detail i {
    width: 14px;
    margin-right: 6px;
    color: var(--primary-color);
    font-size: 12px;
}

.venue-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 12px;
    border-top: 1px solid #e2e8f0;
}

.venue-price {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--primary-color);
}

/* ===== PAYMENT METHODS ===== */
.payment-method {
    background: #f8fafc;
    border-radius: 10px;
    padding: 16px;
    border: 1px solid #e2e8f0;
    transition: all 0.3s ease;
    margin-bottom: 12px;
    cursor: pointer;
}

.payment-method:hover {
    border-color: var(--primary-color);
    transform: translateX(3px);
}

.payment-header {
    display: flex;
    align-items: center;
    margin-bottom: 12px;
}

.payment-icon {
    width: 45px;
    height: 45px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
    font-size: 1.3rem;
    background: #E3F2FD;
    color: var(--primary-color);
}

.payment-info h4 {
    margin: 0 0 4px 0;
    color: var(--text-dark);
    font-size: 1rem;
}

.payment-info p {
    margin: 0;
    color: var(--text-light);
    font-size: 0.85rem;
}

/* ===== NOTIFICATION SETTINGS ===== */
.notification-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    margin-bottom: 8px;
    background: #f8fafc;
}

.notification-info h5 {
    margin: 0 0 4px 0;
    color: var(--text-dark);
    font-size: 0.95rem;
}

.notification-info p {
    margin: 0;
    color: var(--text-light);
    font-size: 0.8rem;
}

/* ===== TOGGLE SWITCH ===== */
.toggle-switch {
    position: relative;
    display: inline-block;
    width: 48px;
    height: 24px;
}

.toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.toggle-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: .4s;
    border-radius: 24px;
}

.toggle-slider:before {
    position: absolute;
    content: "";
    height: 16px;
    width: 16px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
}

input:checked + .toggle-slider {
    background-color: var(--primary-color);
}

input:checked + .toggle-slider:before {
    transform: translateX(24px);
}

/* ===== ACTION BUTTONS ===== */
.action-buttons {
    display: flex;
    justify-content: flex-end;
    margin-top: 20px;
    gap: 8px;
    flex-wrap: wrap;
}

.btn {
    background: var(--primary-color);
    color: white;
    border: none;
    padding: 10px 16px;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 500;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    font-size: 14px;
    text-decoration: none;
    border: 1px solid transparent;
}

.btn:hover {
    background: var(--primary-hover);
    transform: translateY(-1px);
    box-shadow: 0 3px 6px rgba(0,0,0,0.08);
}

.btn-outline {
    background: transparent;
    border: 1px solid var(--primary-color);
    color: var(--primary-color);
}

.btn-outline:hover {
    background: var(--primary-color);
    color: white;
}

.btn-danger {
    background: var(--danger);
}

.btn-danger:hover {
    background: #c53030;
}

.btn-logout {
    width: 100%;
    margin-top: 16px;
    background: var(--danger);
    padding: 10px 16px;
}

.btn-logout:hover {
    background: #c53030;
}

/* ===== PASSWORD VISIBILITY TOGGLE ===== */
.password-input {
    position: relative;
}

.password-toggle {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: var(--text-light);
    cursor: pointer;
    padding: 0;
    width: 26px;
    height: 26px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
}

/* ===== FAQ & SUPPORT STYLES ===== */
.help-search {
    margin-bottom: 20px;
}

.search-container {
    position: relative;
    display: flex;
    align-items: center;
    margin-bottom: 8px;
}

.search-container i {
    position: absolute;
    left: 12px;
    color: var(--text-light);
    font-size: 14px;
    z-index: 1;
}

.search-box {
    flex: 1;
    padding: 10px 12px 10px 38px;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    font-size: 14px;
    width: 100%;
}

.search-info {
    color: var(--text-light);
    font-size: 12px;
    margin: 4px 0 0 0;
}

/* ===== FAQ CATEGORIES ===== */
.faq-categories {
    margin-bottom: 20px;
}

.category-tabs {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.category-tab {
    padding: 6px 14px;
    background: #ebf5fb;
    border: 1px solid transparent;
    border-radius: 16px;
    color: var(--primary-color);
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s;
}

.category-tab:hover {
    background: var(--primary-color);
    color: white;
}

.category-tab.active {
    background: var(--primary-color);
    color: white;
}

/* ===== FAQ CONTAINER ===== */
.faq-category {
    margin-bottom: 20px;
}

.faq-category h3 {
    color: var(--text-dark);
    padding-bottom: 8px;
    border-bottom: 1px solid #e2e8f0;
    margin-bottom: 16px;
    font-size: 17px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.faq-item {
    margin-bottom: 8px;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    overflow: hidden;
    transition: all 0.3s;
}

.faq-item:hover {
    border-color: var(--primary-color);
    box-shadow: 0 3px 8px rgba(98, 147, 196, 0.08);
}

.faq-question {
    padding: 12px 16px;
    background: #f8fafc;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-weight: 500;
    color: var(--text-dark);
    transition: background 0.3s;
    font-size: 14px;
}

.faq-question:hover {
    background: #f0f7ff;
}

.faq-question.active {
    background: #f0f7ff;
    color: var(--primary-color);
}

.faq-question i {
    transition: transform 0.3s;
    font-size: 13px;
}

.faq-question.active i {
    transform: rotate(180deg);
}

.faq-answer {
    padding: 0 16px;
    max-height: 0;
    overflow: hidden;
    transition: all 0.3s;
    background: white;
}

.faq-answer.show {
    padding: 16px;
    max-height: 400px;
}

.faq-answer p {
    margin: 0;
    color: var(--text-light);
    line-height: 1.5;
    font-size: 13px;
}

/* ===== NO RESULTS ===== */
.no-results {
    text-align: center;
    padding: 30px 16px;
    color: var(--text-light);
}

.no-results i {
    font-size: 2.5rem;
    margin-bottom: 12px;
    color: #ddd;
}

.no-results h4 {
    margin-bottom: 8px;
    color: var(--text-dark);
    font-size: 17px;
}

/* ===== CONTACT SUPPORT ===== */
.contact-support {
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #e2e8f0;
}

.support-options {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 15px;
    margin-top: 15px;
}

.support-option {
    background: var(--card-bg);
    padding: 16px;
    border-radius: 8px;
    text-align: center;
    border: 1px solid #e2e8f0;
    transition: all 0.3s;
    display: flex;
    flex-direction: column;
    align-items: center;
    min-height: 160px;
}

.support-option:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    border-color: var(--primary-color);
}

.support-option i {
    font-size: 1.4rem;
    color: var(--primary-color);
    margin-bottom: 8px;
}

.support-option h4 {
    margin: 6px 0;
    color: var(--text-dark);
    font-size: 15px;
    font-weight: 600;
}

.support-option p {
    color: var(--text-light);
    margin-bottom: 10px;
    font-size: 12px;
    line-height: 1.4;
    flex-grow: 1;
}

/* ===== SUPPORT BUTTON HOVER FIX ===== */
.support-option .btn-outline {
    background: transparent;
    border: 1px solid var(--primary-color);
    color: var(--primary-color);
    padding: 8px 12px;
    font-size: 13px;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    width: 100%;
    margin-top: auto;
    text-decoration: none;
}

.support-option .btn-outline i {
    font-size: 12px;
    transition: all 0.3s ease;
    margin-bottom: 0;
}

.support-option .btn-outline:hover {
    background: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
}

.support-option .btn-outline:hover i {
    color: white !important;
}

/* ===== JUDUL SUPPORT DENGAN JARAK YANG PAS ===== */
.support-title {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 25px;
}

.support-title i {
    font-size: 1.3rem;
    color: var(--primary-color);
}

.support-title span {
    font-size: 18px;
    font-weight: 600;
    color: var(--text-dark);
}

/* ===== INFORMASI AKUN STYLES ===== */
.account-info-container {
    background: #f0f9ff;
    border: 1px solid #bae6fd;
    border-radius: 10px;
    padding: 16px;
    margin-bottom: 16px;
}

.account-info-header {
    display: flex;
    align-items: center;
    margin-bottom: 12px;
}

.account-info-header i {
    color: #0ea5e9;
    font-size: 1.2rem;
    margin-right: 8px;
}

.account-info-header h4 {
    color: #0369a1;
    font-size: 16px;
    font-weight: 600;
    margin: 0;
}

.account-info-details {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 12px;
}

.account-info-item {
    display: flex;
    align-items: center;
    padding: 8px;
    background: white;
    border-radius: 6px;
    border: 1px solid #e0f2fe;
}

.account-info-item i {
    color: #0ea5e9;
    margin-right: 8px;
    font-size: 14px;
    width: 20px;
    text-align: center;
}

.account-info-item div {
    flex: 1;
}

.account-info-item strong {
    display: block;
    font-size: 12px;
    color: #64748b;
    margin-bottom: 2px;
}

.account-info-item span {
    display: block;
    font-size: 13px;
    color: #1e293b;
    font-weight: 500;
}

.verified-badge {
    background: #d1fae5;
    color: #065f46;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 500;
    margin-left: 6px;
}

/* ===== DANGEROUS ACTIONS STYLES ===== */
.dangerous-actions-container {
    background: #fef2f2;
    border: 1px solid #fecaca;
    border-radius: 10px;
    padding: 16px;
    margin-top: 16px;
}

.dangerous-actions-header {
    display: flex;
    align-items: center;
    margin-bottom: 12px;
}

.dangerous-actions-header i {
    color: #ef4444;
    font-size: 1.2rem;
    margin-right: 8px;
}

.dangerous-actions-header h4 {
    color: #b91c1c;
    font-size: 16px;
    font-weight: 600;
    margin: 0;
}

.dangerous-actions-warning {
    background: white;
    border-radius: 8px;
    padding: 12px;
    margin-bottom: 16px;
    border: 1px solid #fee2e2;
}

.dangerous-actions-warning p {
    color: #991b1b;
    font-size: 13px;
    margin: 0;
    line-height: 1.5;
}

.dangerous-actions-checkbox {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    margin-bottom: 16px;
    padding: 10px;
    background: white;
    border-radius: 8px;
    border: 1px solid #fecaca;
}

.dangerous-actions-checkbox input[type="checkbox"] {
    width: 18px;
    height: 18px;
    margin-top: 2px;
    cursor: pointer;
}

.dangerous-actions-checkbox label {
    color: #991b1b;
    font-size: 13px;
    line-height: 1.4;
    cursor: pointer;
}

.delete-account-btn {
    width: 100%;
    background: #ef4444;
    color: white;
    border: none;
    padding: 12px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 500;
    font-size: 14px;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.delete-account-btn:hover {
    background: #dc2626;
    transform: translateY(-1px);
    box-shadow: 0 3px 6px rgba(239, 68, 68, 0.2);
}

.delete-account-btn:disabled {
    background: #fca5a5;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

/* ===== RESPONSIVE STYLES ===== */
@media (max-width: 768px) {
    .profile-container {
        padding: 12px;
    }

    .horizontal-nav {
        justify-content: flex-start;
        padding-bottom: 4px;
        overflow-x: auto;
    }
    
    .horizontal-nav a {
        padding: 10px 16px;
        font-size: 13px;
    }
    
    .settings-content {
        padding: 16px;
    }
    
    .settings-content h2 {
        font-size: 20px;
        margin-bottom: 16px;
    }
    
    .settings-content h3 {
        font-size: 16px;
    }
    
    .edit-profile-layout {
        gap: 16px;
    }
    
    .profile-info-section,
    .edit-form-section {
        padding: 16px;
    }
    
    .profile-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .profile-header .user-avatar {
        width: 70px;
        height: 70px;
    }
    
    .venue-header {
        flex-direction: column;
        gap: 8px;
    }
    
    .action-buttons {
        flex-direction: column;
        width: 100%;
    }
    
    .action-buttons .btn {
        width: 100%;
        justify-content: center;
    }
    
    .venue-footer {
        flex-direction: column;
        gap: 12px;
        align-items: stretch;
    }
    
    .venue-footer .btn {
        width: 100%;
    }

    .edit-profile-layout .profile-stats {
        flex-direction: column;
        gap: 12px;
    }

    .profile-info-card .info-item {
        flex-direction: row;
        align-items: center;
    }

    .support-options {
        grid-template-columns: 1fr;
    }

    .support-option {
        min-height: 150px;
    }

    .faq-category h3 {
        font-size: 15px;
    }

    .faq-question {
        padding: 10px 12px;
        font-size: 13px;
    }
    
    .photo-preview {
        width: 90px;
        height: 90px;
    }
    
    .photo-preview .default-avatar i {
        font-size: 1.8rem;
    }
    
    .upload-btn,
    .remove-btn {
        padding: 8px 14px;
        font-size: 12px;
    }
    
    .account-info-details {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .horizontal-nav a {
        padding: 8px 12px;
        font-size: 12px;
    }
    
    .settings-content {
        padding: 12px;
    }
    
    .settings-content h2 {
        font-size: 18px;
        margin-bottom: 12px;
        padding-bottom: 8px;
    }
    
    .settings-content h3 {
        font-size: 15px;
    }
    
    .form-group {
        margin-bottom: 12px;
    }
    
    .form-group input, .form-group select, .form-group textarea {
        padding: 8px 10px;
        font-size: 13px;
    }
    
    .profile-info-section,
    .edit-form-section {
        padding: 12px;
    }
    
    .btn {
        padding: 8px 12px;
        font-size: 13px;
    }

    .profile-info-card .info-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 6px;
    }

    .category-tab {
        padding: 5px 10px;
        font-size: 12px;
    }
    
    .support-option {
        min-height: 140px;
        padding: 14px;
    }
    
    .photo-preview {
        width: 80px;
        height: 80px;
    }
    
    .photo-preview .default-avatar i {
        font-size: 1.6rem;
    }
    
    .photo-preview .default-avatar span {
        font-size: 10px;
    }
    
    .upload-controls {
        min-width: 200px;
    }
}

/* ===== POPUP LOGOUT STYLES ===== */
#logout-popup {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    display: none;
}

.logout-popup-content {
    background: white;
    border-radius: 16px;
    padding: 30px;
    max-width: 400px;
    width: 90%;
    text-align: center;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    animation: popupFadeIn 0.3s ease;
}

@keyframes popupFadeIn {
    from {
        opacity: 0;
        transform: scale(0.9) translateY(-20px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

.logout-icon {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, #FE2222, #ff5555);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    box-shadow: 0 5px 15px rgba(254, 34, 34, 0.3);
}

.logout-icon i {
    font-size: 32px;
    color: white;
}

.logout-title {
    font-size: 24px;
    font-weight: 600;
    color: #1A202C;
    margin-bottom: 10px;
}

.logout-message {
    font-size: 16px;
    color: #718096;
    margin-bottom: 30px;
    line-height: 1.5;
}

.logout-buttons {
    display: flex;
    gap: 15px;
    justify-content: center;
}

.logout-btn {
    flex: 1;
    padding: 14px;
    border: none;
    border-radius: 10px;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s;
}

.logout-cancel {
    background: #f1f5f9;
    color: #64748b;
    border: 2px solid #e2e8f0;
}

.logout-cancel:hover {
    background: #e2e8f0;
    transform: translateY(-2px);
}

.logout-confirm {
    background: linear-gradient(135deg, #FE2222, #ff5555);
    color: white;
    border: 2px solid #FE2222;
    box-shadow: 0 4px 12px rgba(254, 34, 34, 0.2);
}

.logout-confirm:hover {
    background: linear-gradient(135deg, #e60000, #ff4444);
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(254, 34, 34, 0.3);
}

/* Responsive popup */
@media (max-width: 480px) {
    .logout-popup-content {
        padding: 25px;
        width: 85%;
    }
    
    .logout-icon {
        width: 60px;
        height: 60px;
        margin-bottom: 15px;
    }
    
    .logout-icon i {
        font-size: 28px;
    }
    
    .logout-title {
        font-size: 20px;
    }
    
    .logout-message {
        font-size: 14px;
        margin-bottom: 25px;
    }
    
    .logout-buttons {
        flex-direction: column;
        gap: 10px;
    }
    
    .logout-btn {
        padding: 12px;
        font-size: 15px;
    }
}

/* ===== SCROLLBAR STYLING ===== */
::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

::-webkit-scrollbar-thumb {
    background: var(--primary-color);
    border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
    background: var(--primary-hover);
}
</style>
@endsection

@section('content')
<div class="profile-container">
    <!-- Notifikasi -->
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        </div>
    @endif
    
    @if($errors->any())
        <div class="alert alert-error">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Horizontal Navigation -->
    <div class="horizontal-nav">
        <a href="#" class="{{ session('activeSection', 'edit-profil') == 'edit-profil' ? 'active' : '' }}" data-target="edit-profil">Edit Profil</a>
        <a href="#" class="{{ session('activeSection') == 'venue-favorit' ? 'active' : '' }}" data-target="venue-favorit">Venue Favorit</a>
        <a href="#" class="{{ session('activeSection') == 'metode-pembayaran' ? 'active' : '' }}" data-target="metode-pembayaran">Metode Pembayaran</a>
        <a href="#" class="{{ session('activeSection') == 'notifikasi' ? 'active' : '' }}" data-target="notifikasi">Notifikasi</a>
        <a href="#" class="{{ session('activeSection') == 'keamanan' ? 'active' : '' }}" data-target="keamanan">Keamanan</a>
        <a href="#" class="{{ session('activeSection') == 'pengaturan' ? 'active' : '' }}" data-target="pengaturan">Pengaturan</a>
        <a href="#" class="{{ session('activeSection') == 'faq-support' ? 'active' : '' }}" data-target="faq-support">FAQ & Support</a>
    </div>

    <div class="profile-content">
        <!-- Konten Utama -->
        <div class="settings-content">
            <!-- Edit Profil Section -->
            <section id="edit-profil" class="settings-section {{ session('activeSection', 'edit-profil') == 'edit-profil' ? 'active' : '' }}">
                <h2>👤 Edit Profil</h2>
                
                <div class="edit-profile-layout">
                    <!-- Bagian Kiri: Informasi Profil -->
                    <div class="profile-info-section">
                        <!-- Avatar dan Info Dasar -->
                        <div class="profile-header">
                            <div class="photo-preview" id="photoPreview">
                            @php
                                $user = auth()->user();
                                //Using Model Accessor - Auto detect role!
                                $photoUrl = $user->profile_photo_url;
                                $hasPhoto = !is_null($photoUrl);
                                
                                // Add timestamp for cache busting
                                if(session('profile_photo_updated') && $hasPhoto) {
                                    $photoUrl .= '?t=' . time();
                                    session()->forget('profile_photo_updated');
                                }
                            @endphp
                                
                                @if($hasPhoto && $photoUrl)
                                    <img src="{{ $photoUrl }}" 
                                         alt="Foto Profil" 
                                         id="currentProfilePhoto"
                                         class="profile-image"
                                         data-filename="{{ $user->profile_photo }}"
                                         onerror="this.onerror=null; this.src='{{ asset('images/default-avatar.png') }}';">
                                @else
                                    <div class="user-avatar">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div class="photo-loading" id="photoLoading" style="display: none;">
                                    <i class="fas fa-spinner fa-spin"></i>
                                </div>
                            </div>
                            <div class="profile-header-info">
                                <h3>{{ Auth::user()->name }}</h3>
                                <p>{{ Auth::user()->email }}</p>
                            </div>
                        </div>

                        <!-- Stats -->
                        <div class="profile-stats">
                            <div class="stat-item">
                                @php
                                    use App\Models\Pemesanan;
                                    $totalPemesanan = Pemesanan::where('user_id', Auth::id())->count();
                                @endphp
                                <span class="stat-number">{{ $totalPemesanan }}</span>
                                <span class="stat-label">Pemesanan</span>
                            </div>
                            <div class="stat-item">
                                @php
                                    use Illuminate\Support\Facades\DB;
                                    $venueCount = DB::table('booking')
                                        ->where('user_id', Auth::id())
                                        ->distinct('venue_id')
                                        ->count('venue_id');
                                @endphp
                                <span class="stat-number">{{ $venueCount }}</span>
                                <span class="stat-label">Venue</span>
                            </div>
                            <div class="stat-item">
                                @php
                                    use App\Models\Transaksi;
                                    $totalTransaksi = Transaksi::where('pengguna', Auth::user()->email)->sum('amount');
                                    if ($totalTransaksi >= 1000000) {
                                        $formattedTotal = number_format($totalTransaksi / 1000000, 1) . 'JT';
                                    } else {
                                        $formattedTotal = number_format($totalTransaksi, 0, ',', '.');
                                    }
                                @endphp
                                <span class="stat-number">Rp {{ $formattedTotal }}</span>
                                <span class="stat-label">Total</span>
                            </div>
                        </div>

                        <!-- Informasi Personal -->
                        <div class="profile-info-card">
                            <h4>Informasi Personal</h4>
                            <div class="info-item">
                                <i class="fa-solid fa-phone"></i>
                                <div>
                                    <strong>Nomor Telepon:</strong>
                                    <span>{{ Auth::user()->phone ?? 'Belum diisi' }}</span>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="fa-solid fa-location-dot"></i>
                                <div>
                                    <strong>Alamat:</strong>
                                    <span>{{ Auth::user()->description ?? 'Belum diisi' }}</span>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="fa-regular fa-calendar"></i>
                                <div>
                                    <strong>Bergabung Sejak:</strong>
                                    <span>{{ Auth::user()->created_at ? Auth::user()->created_at->format('F Y') : '-' }}</span>
                                </div>
                            </div>
                            @if(Auth::user()->venue_name)
                            <div class="info-item">
                                <i class="fa-solid fa-store"></i>
                                <div>
                                    <strong>Nama Venue:</strong>
                                    <span>{{ Auth::user()->venue_name }}</span>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Tombol Logout -->
                        <button id="logout-btn" class="btn btn-logout">
                            <i class="fa-solid fa-right-from-bracket me-2"></i>Keluar Akun
                        </button>
                    </div>

                    <!-- Bagian Kanan: Form Edit -->
                    <div class="edit-form-section">
                        <h3>Ubah Data Profil</h3>
                        <form id="editProfileForm" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <!-- Upload Foto Profil -->
                            <div class="form-group">
                                <label>Foto Profil</label>
                                <div class="photo-upload">
                                    <div class="photo-preview" id="formPhotoPreview">
                                        @if($hasPhoto && $photoUrl)
                                            <img src="{{ $photoUrl }}" 
                                                 alt="Foto Profil" 
                                                 class="profile-image"
                                                 id="formProfileImage">
                                        @else
                                            <div class="default-avatar">
                                                <i class="fas fa-user"></i>
                                                <span>Foto Profil</span>
                                            </div>
                                        @endif
                                        <div class="photo-loading" id="formPhotoLoading" style="display: none;">
                                            <i class="fas fa-spinner fa-spin"></i>
                                        </div>
                                    </div>
                                    <div class="upload-controls">
                                        <input type="file" id="profile_photo" name="profile_photo" style="display: none;" 
                                               accept="image/*" onchange="handlePhotoUpload(this)">
                                        <div class="upload-btn" onclick="document.getElementById('profile_photo').click()">
                                            <i class="fas fa-upload"></i> Upload Foto Baru
                                        </div>
                                        @if($hasPhoto)
                                        <div class="remove-btn" onclick="removeProfilePhoto()">
                                            <i class="fas fa-trash"></i> Hapus Foto
                                        </div>
                                        @endif
                                        <p class="upload-info">Format: JPG, PNG (Maks. 2MB)</p>
                                        <input type="hidden" name="remove_photo" id="removePhoto" value="0">
                                    </div>
                                </div>
                                @error('profile_photo')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="nama-lengkap">Nama Lengkap *</label>
                                <input type="text" id="nama-lengkap" name="name" placeholder="Masukkan nama lengkap" value="{{ old('name', Auth::user()->name) }}" required class="@error('name') is-invalid @enderror">
                                @error('name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email *</label>
                                <input type="email" id="email" name="email" placeholder="Masukkan email" value="{{ old('email', Auth::user()->email) }}" required class="@error('email') is-invalid @enderror">
                                <small>Email akan digunakan untuk login dan notifikasi</small>
                                @error('email')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="telepon">Nomor Telepon</label>
                                <input type="tel" id="telepon" name="phone" placeholder="Contoh: 081234567890" value="{{ old('phone', Auth::user()->phone) }}" class="@error('phone') is-invalid @enderror">
                                <small>Nomor telepon akan digunakan untuk konfirmasi booking</small>
                                @error('phone')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="venue_name">Nama Venue (Opsional)</label>
                                <input type="text" id="venue_name" name="venue_name" placeholder="Masukkan nama venue jika pemilik" value="{{ old('venue_name', Auth::user()->venue_name) }}" class="@error('venue_name') is-invalid @enderror">
                                <small>Hanya diisi jika Anda pemilik venue</small>
                                @error('venue_name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="description">Deskripsi/Alamat</label>
                                <textarea id="description" name="description" placeholder="Masukkan deskripsi atau alamat lengkap" class="@error('description') is-invalid @enderror">{{ old('description', Auth::user()->description) }}</textarea>
                                <div class="char-count">
                                    <span id="charCount">{{ strlen(old('description', Auth::user()->description)) }}</span>/500 karakter
                                </div>
                                @error('description')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                                                
                            <div class="action-buttons">
                                <button type="button" class="btn btn-outline" onclick="resetForm()">Batal</button>
                                <button type="submit" class="btn" id="simpanProfil">
                                    <i class="fas fa-save me-2"></i>Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>

            <!-- Venue Favorit Section -->
            <section id="venue-favorit" class="settings-section {{ session('activeSection') == 'venue-favorit' ? 'active' : '' }}">
                <h2>⭐ Venue Favorit</h2>
                
                @php
                    $venueIds = DB::table('booking')
                        ->where('user_id', Auth::id())
                        ->distinct()
                        ->pluck('venue_id')
                        ->toArray();
                    
                    if (!empty($venueIds)) {
                        $favoriteVenues = DB::table('venues')
                            ->whereIn('id', $venueIds)
                            ->select('id', 'name', 'category', 'status', 'address', 'rating', 'reviews_count', 'price_per_hour')
                            ->limit(10)
                            ->get();
                    } else {
                        $favoriteVenues = DB::table('venues')
                            ->where('status', 'Aktif')
                            ->select('id', 'name', 'category', 'status', 'address', 'rating', 'reviews_count', 'price_per_hour')
                            ->orderBy('rating', 'desc')
                            ->limit(5)
                            ->get();
                    }
                @endphp
                
                @forelse($favoriteVenues as $venue)
                <div class="venue-card">
                    <div class="venue-header">
                        <div>
                            <h3 class="venue-title">{{ $venue->name ?? 'Nama Venue' }}</h3>
                            <div class="venue-badges">
                                <span class="badge badge-sport">{{ $venue->category ?? 'Umum' }}</span>
                                @if(($venue->status ?? '') == 'Aktif')
                                <span class="badge badge-available">{{ $venue->status }}</span>
                                @else
                                <span class="badge badge-full">{{ $venue->status ?? 'Tidak Aktif' }}</span>
                                @endif
                            </div>
                        </div>
                        <button class="favorite-btn" data-venue="{{ $venue->id }}">
                            <i class="fa-regular fa-heart"></i>
                        </button>
                    </div>
                    
                    <div class="venue-details">
                        <div class="venue-detail">
                            <i class="fa-solid fa-location-dot"></i>
                            <span>{{ Str::limit($venue->address ?? 'Alamat tidak tersedia', 50) }}</span>
                        </div>
                        <div class="venue-detail">
                            <i class="fa-solid fa-star"></i>
                            <span>{{ number_format($venue->rating ?? 0, 1) }} ({{ $venue->reviews_count ?? 0 }} reviews)</span>
                        </div>
                        <div class="venue-detail">
                            <i class="fa-solid fa-money-bill"></i>
                            <span>Rp {{ number_format($venue->price_per_hour ?? 0, 0, ',', '.') }}/jam</span>
                        </div>
                    </div>
                    
                    <div class="venue-footer">
                        <span class="venue-price">Rp {{ number_format($venue->price_per_hour ?? 0, 0, ',', '.') }}/jam</span>
                        <a href="{{ route('venue.detail', $venue->id ?? '#') }}" class="btn">
                            <i class="fa-solid fa-calendar-check me-2"></i>Pesan
                        </a>
                    </div>
                </div>
                @empty
                <div id="empty-favorite" class="text-center py-8">
                    <i class="fa-regular fa-heart text-4xl text-gray-300 mb-4"></i>
                    <h4 class="text-lg font-medium text-gray-600 mb-2">Belum ada venue favorit</h4>
                    <p class="text-gray-500 text-sm">Mulailah dengan memesan venue untuk menambahkannya ke favorit</p>
                    <a href="{{ route('venue.list') }}" class="btn mt-4">
                        <i class="fa-solid fa-search me-2"></i>Cari Venue
                    </a>
                </div>
                @endforelse
            </section>

            <!-- Metode Pembayaran Section -->
            <section id="metode-pembayaran" class="settings-section {{ session('activeSection') == 'metode-pembayaran' ? 'active' : '' }}">
                <h2>💳 Metode Pembayaran</h2>
                
                @php
                    $transactions = DB::table('transactions')
                        ->where('pengguna', Auth::user()->email)
                        ->orderBy('transaction_date', 'desc')
                        ->limit(5)
                        ->get();
                @endphp
                
                @if($transactions->count() > 0)
                <h3>Riwayat Metode Pembayaran</h3>
                <div class="mb-6">
                    @foreach($transactions as $transaction)
                    <div class="payment-method" data-method="{{ $transaction->metode_pembayaran }}">
                        <div class="payment-header">
                            <div class="payment-icon">
                                @if($transaction->metode_pembayaran == 'transfer')
                                <i class="fa-solid fa-building-columns"></i>
                                @elseif($transaction->metode_pembayaran == 'cash')
                                <i class="fa-solid fa-money-bill"></i>
                                @else
                                <i class="fa-solid fa-wallet"></i>
                                @endif
                            </div>
                            <div class="payment-info">
                                <h4>
                                    @if($transaction->metode_pembayaran == 'transfer')
                                    Transfer Bank
                                    @elseif($transaction->metode_pembayaran == 'cash')
                                    Tunai
                                    @else
                                    E-Wallet
                                    @endif
                                </h4>
                                <p>Rp {{ number_format($transaction->amount, 0, ',', '.') }} - {{ date('d M Y', strtotime($transaction->transaction_date)) }}</p>
                                <p class="text-sm {{ $transaction->status == 'completed' ? 'text-green-600' : ($transaction->status == 'pending' ? 'text-yellow-600' : 'text-red-600') }}">
                                    Status: {{ ucfirst($transaction->status) }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
                
                <h3>Tambahkan Kartu Baru</h3>
                <form id="paymentForm">
                    @csrf
                    
                    <div class="form-group">
                        <label for="nomor-kartu">Nomor Kartu</label>
                        <input type="text" id="nomor-kartu" name="card_number" placeholder="Masukkan nomor kartu" maxlength="19">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="form-group">
                            <label for="tanggal-kadaluarsa">Tanggal Kadaluarsa</label>
                            <input type="text" id="tanggal-kadaluarsa" name="expiry_date" placeholder="MM/YY" maxlength="5">
                        </div>
                        <div class="form-group">
                            <label for="kode-keamanan">Kode Keamanan</label>
                            <input type="text" id="kode-keamanan" name="cvv" placeholder="CVV" maxlength="3">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="nama-pemilik">Nama Pemilik Kartu</label>
                        <input type="text" id="nama-pemilik" name="card_holder" placeholder="Masukkan nama sesuai kartu">
                    </div>
                </form>
                
                <h3>Metode Pembayaran Lainnya</h3>
                <div class="payment-method" data-method="ewallet">
                    <div class="payment-header">
                        <div class="payment-icon">
                            <i class="fa-solid fa-wallet"></i>
                        </div>
                        <div class="payment-info">
                            <h4>E-Wallet</h4>
                            <p>Gopay, OVO, Dana, LinkAja</p>
                        </div>
                    </div>
                </div>

                <div class="payment-method" data-method="bank">
                    <div class="payment-header">
                        <div class="payment-icon">
                            <i class="fa-solid fa-building-columns"></i>
                        </div>
                        <div class="payment-info">
                            <h4>Transfer Bank</h4>
                            <p>BCA, BNI, Mandiri, BRI, BSI</p>
                        </div>
                    </div>
                </div>

                <div class="payment-method" data-method="qris">
                    <div class="payment-header">
                        <div class="payment-icon">
                            <i class="fa-solid fa-qrcode"></i>
                        </div>
                        <div class="payment-info">
                            <h4>QRIS</h4>
                            <p>Scan QR Code untuk pembayaran</p>
                        </div>
                    </div>
                </div>

                <div class="action-buttons">
                    <button type="button" class="btn" id="savePayment">
                        <i class="fa-solid fa-credit-card me-2"></i>Simpan Metode Pembayaran
                    </button>
                </div>
            </section>

            <!-- Notifikasi Section -->
            <section id="notifikasi" class="settings-section {{ session('activeSection') == 'notifikasi' ? 'active' : '' }}">
                <h2>🔔 Notifikasi</h2>
                
                @php
                    $notifications = DB::table('notifications')
                        ->where('user_id', Auth::id())
                        ->orderBy('created_at', 'desc')
                        ->limit(10)
                        ->get();
                @endphp
                
                <h3>Notifikasi Terbaru</h3>
                @if($notifications->count() > 0)
                    @foreach($notifications as $notification)
                    <div class="notification-item {{ $notification->is_read ? '' : 'bg-blue-50' }}">
                        <div class="notification-info">
                            <h5>{{ $notification->title }}</h5>
                            <p>{{ $notification->message }}</p>
                            <small class="text-gray-500">{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</small>
                        </div>
                        @if(!$notification->is_read)
                        <span class="badge badge-sport">Baru</span>
                        @endif
                    </div>
                    @endforeach
                @else
                    <p class="text-gray-500 text-center py-4">Belum ada notifikasi</p>
                @endif
                
                <h3>Pengaturan Notifikasi</h3>
                <div class="notification-item">
                    <div class="notification-info">
                        <h5>Konfirmasi pemesanan lapangan</h5>
                        <p>Notifikasi ketika pemesanan berhasil</p>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" checked>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                
                <div class="notification-item">
                    <div class="notification-info">
                        <h5>Pembatalan atau perubahan jadwal</h5>
                        <p>Notifikasi untuk perubahan jadwal</p>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" checked>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                
                <div class="notification-item">
                    <div class="notification-info">
                        <h5>Bukti pembayaran atau Invoice</h5>
                        <p>Kirim bukti pembayaran via email</p>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" checked>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                
                <h3>Notifikasi Browser</h3>
                <div class="notification-item">
                    <div class="notification-info">
                        <h5>Pengingat jadwal main</h5>
                        <p>Pengingat sebelum jadwal bermain</p>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" checked>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                
                <div class="action-buttons">
                    <button class="btn" id="saveNotifications">
                        <i class="fa-solid fa-save me-2"></i>Simpan Pengaturan
                    </button>
                </div>
            </section>

            <!-- Keamanan Section -->
            <section id="keamanan" class="settings-section {{ session('activeSection') == 'keamanan' ? 'active' : '' }}">
                <h2>🔒 Keamanan</h2>
                
                <form id="securityForm">
                    @csrf
                    
                    <div class="form-group">
                        <label for="password-saat-ini">Password Saat Ini</label>
                        <div class="password-input">
                            <input type="password" id="password-saat-ini" name="current_password" placeholder="Masukkan password saat ini" required>
                            <button type="button" class="password-toggle" onclick="togglePassword('password-saat-ini')">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="password-baru">Password Baru</label>
                        <div class="password-input">
                            <input type="password" id="password-baru" name="new_password" placeholder="Masukkan password baru" required>
                            <button type="button" class="password-toggle" onclick="togglePassword('password-baru')">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                        </div>
                        <small>Minimal 8 karakter, kombinasi huruf dan angka</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="konfirmasi-password">Konfirmasi Password Baru</label>
                        <div class="password-input">
                            <input type="password" id="konfirmasi-password" name="new_password_confirmation" placeholder="Konfirmasi password baru" required>
                            <button type="button" class="password-toggle" onclick="togglePassword('konfirmasi-password')">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="action-buttons">
                        <button type="button" class="btn" id="savePassword">
                            <i class="fa-solid fa-lock me-2"></i>Simpan Password Baru
                        </button>
                    </div>
                </form>
                
                <h3 class="mt-8">Sesi Aktif</h3>
                <div class="notification-item">
                    <div class="notification-info">
                        <h5>Sesi Browser Saat Ini</h5>
                        <p>{{ request()->header('User-Agent') }}</p>
                        <small class="text-gray-500">Login sejak: {{ \Carbon\Carbon::now()->format('d M Y H:i') }}</small>
                    </div>
                    <button class="btn btn-outline btn-sm" onclick="endOtherSessions()">
                        <i class="fa-solid fa-power-off me-1"></i> Hapus Sesi Lain
                    </button>
                </div>
            </section>

            <!-- Pengaturan Section -->
            <section id="pengaturan" class="settings-section {{ session('activeSection') == 'pengaturan' ? 'active' : '' }}">
                <h2>⚙ Pengaturan</h2>
                
                <h3>Pengaturan Aplikasi</h3>
                <div class="notification-item">
                    <div class="notification-info">
                        <h5>Tema Gelap</h5>
                        <p>Aktifkan mode gelap untuk aplikasi</p>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" id="darkModeToggle">
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                
                <div class="notification-item">
                    <div class="notification-info">
                        <h5>Notifikasi Suara</h5>
                        <p>Suara untuk notifikasi penting</p>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" checked>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                
                <div class="notification-item">
                    <div class="notification-info">
                        <h5>Getar</h5>
                        <p>Getar untuk notifikasi</p>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox">
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                
                <div class="notification-item">
                    <div class="notification-info">
                        <h5>Bahasa</h5>
                        <p>Pilih bahasa untuk aplikasi</p>
                    </div>
                    <select class="border rounded-lg px-3 py-2">
                        <option value="id">Bahasa Indonesia</option>
                        <option value="en">English</option>
                    </select>
                </div>
                
                <!-- BAGIAN INFORMASI AKUN YANG SUDAH DIRAPIKAN -->
                <h3 class="mt-8">Informasi Akun</h3>
                <div class="account-info-container">
                    <div class="account-info-header">
                        <i class="fa-solid fa-info-circle"></i>
                        <h4>Status Akun</h4>
                    </div>
                    <div class="account-info-details">
                        <div class="account-info-item">
                            <i class="fa-solid fa-envelope"></i>
                            <div>
                                <strong>Email</strong>
                                <span>{{ Auth::user()->email }}</span>
                            </div>
                            @if(Auth::user()->email_verified_at)
                            <span class="verified-badge">
                                <i class="fa-solid fa-check"></i> Terverifikasi
                            </span>
                            @else
                            <span class="verified-badge" style="background: #fef3c7; color: #92400e;">
                                <i class="fa-solid fa-exclamation"></i> Belum diverifikasi
                            </span>
                            @endif
                        </div>
                        <div class="account-info-item">
                            <i class="fa-solid fa-calendar"></i>
                            <div>
                                <strong>Bergabung</strong>
                                <span>{{ Auth::user()->created_at ? Auth::user()->created_at->format('d F Y') : '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- BAGIAN AKSI BERBAHAYA YANG SUDAH DIRAPIKAN -->
                <h3 class="mt-8" style="color: #b91c1c;">Aksi Berbahaya</h3>
                <div class="dangerous-actions-container">
                    <div class="dangerous-actions-header">
                        <i class="fa-solid fa-exclamation-triangle"></i>
                        <h4>Hapus Akun</h4>
                    </div>
                    
                    <div class="dangerous-actions-warning">
                        <p>Setelah menghapus akun, semua data Anda akan dihapus secara permanen dan tidak dapat dikembalikan.</p>
                    </div>
                    
                    <div class="dangerous-actions-checkbox">
                        <input type="checkbox" id="confirmDelete" onchange="toggleDeleteButton()">
                        <label for="confirmDelete">Saya memahami bahwa semua data akan dihapus secara permanen</label>
                    </div>
                    
                    <button id="deleteAccountBtn" class="delete-account-btn" onclick="confirmDeleteAccount()" disabled>
                        <i class="fa-solid fa-trash"></i> Hapus Akun Saya
                    </button>
                </div>
            </section>

            <!-- FAQ & Support Section -->
            <section id="faq-support" class="settings-section {{ session('activeSection') == 'faq-support' ? 'active' : '' }}">
                <h2>❓ FAQ & Support</h2>
                
                <div class="help-search">
                    <div class="search-container">
                        <i class="fas fa-search"></i>
                        <input type="text" class="search-box" placeholder="Cari pertanyaan atau masalah..." id="search-faq">
                    </div>
                    <p class="search-info">Ketik kata kunci untuk mencari solusi</p>
                </div>
                
                <div class="faq-categories">
                    <div class="category-tabs">
                        <button class="category-tab active" data-category="all">Semua</button>
                        <button class="category-tab" data-category="booking">Booking</button>
                        <button class="category-tab" data-category="payment">Pembayaran</button>
                        <button class="category-tab" data-category="account">Akun</button>
                        <button class="category-tab" data-category="venue">Venue</button>
                    </div>
                </div>
                
                <div class="faq-container" id="faqContainer">
                    <div class="faq-category" data-category="booking">
                        <h3><i class="fas fa-calendar-check me-2"></i>Booking & Reservasi</h3>
                        
                        <div class="faq-item" data-category="booking">
                            <div class="faq-question" onclick="toggleFAQ(this)">
                                <span>1. Bagaimana cara melakukan booking venue?</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="faq-answer">
                                <p>Cari venue di halaman utama, pilih venue yang diinginkan, pilih tanggal dan waktu, lalu klik "Pesan Sekarang". Isi data diri dan pilih metode pembayaran.</p>
                            </div>
                        </div>
                        
                        <div class="faq-item" data-category="booking">
                            <div class="faq-question" onclick="toggleFAQ(this)">
                                <span>2. Bagaimana cara membatalkan booking?</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="faq-answer">
                                <p>Pergi ke menu "Riwayat Booking", cari booking yang ingin dibatalkan, klik tombol "Batalkan". Pembatalan hanya bisa dilakukan minimal 24 jam sebelum waktu booking.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="faq-category" data-category="payment">
                        <h3><i class="fas fa-credit-card me-2"></i>Pembayaran</h3>
                        
                        <div class="faq-item" data-category="payment">
                            <div class="faq-question" onclick="toggleFAQ(this)">
                                <span>3. Metode pembayaran apa saja yang tersedia?</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="faq-answer">
                                <p>Kami mendukung Transfer Bank (BCA, BNI, Mandiri, BRI), E-Wallet (Gopay, OVO, Dana, ShopeePay), dan QRIS.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="faq-category" data-category="account">
                        <h3><i class="fas fa-user-cog me-2"></i>Akun & Profil</h3>
                        
                        <div class="faq-item" data-category="account">
                            <div class="faq-question" onclick="toggleFAQ(this)">
                                <span>4. Bagaimana cara mengganti password?</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="faq-answer">
                                <p>Pergi ke menu "Pengaturan → Keamanan", masukkan password lama dan password baru, lalu klik "Simpan Password Baru".</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="no-results" id="noResults" style="display: none;">
                    <i class="fas fa-search"></i>
                    <h4>Tidak ditemukan</h4>
                    <p>Tidak ada hasil untuk pencarian Anda. Coba kata kunci lain atau hubungi support.</p>
                </div>
                
                <!-- BAGIAN SUPPORT YANG DIPERBAIKI -->
                <h3 class="support-title">
                    <i class="fas fa-headset"></i>
                    <span>Butuh Bantuan Lebih Lanjut?</span>
                </h3>
                    <div class="support-options">
                        <div class="support-option">
                            <i class="fas fa-envelope"></i>
                            <h4>Email Support</h4>
                            <p>support@cariarena.com</p>
                            <a href="mailto:cariarena.app@gmail.com" class="btn btn-outline">
                                <i class="fas fa-paper-plane me-1"></i>Kirim Email
                            </a>
                        </div>
                        
                        <div class="support-option">
                            <i class="fab fa-whatsapp"></i>
                            <h4>WhatsApp Support</h4>
                            <p>08:00 - 22:00 WIB</p>
                            <a href="wa.me/6285731125834" 
                            target="_blank" class="btn btn-outline">
                                <i class="fab fa-whatsapp me-1"></i>Chat WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

<!-- Popup Konfirmasi Logout -->
<div id="logout-popup" class="logout-popup">
    <div class="logout-popup-content">
        <div class="logout-icon">
            <i class="fa-solid fa-right-from-bracket"></i>
        </div>
        
        <h3 class="logout-title">Konfirmasi Logout</h3>
        <p class="logout-message">Apakah Anda yakin ingin keluar dari sistem?</p>
        
        <div class="logout-buttons">
            <button id="cancel-logout" class="logout-btn logout-cancel">
                Batal
            </button>
            <form id="logout-form" method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" id="confirm-logout" class="logout-btn logout-confirm">
                    Logout
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ========== NAVIGASI ANTAR SECTION ==========
        function initSettingsPage() {
            const menuLinks = document.querySelectorAll('.horizontal-nav a');
            const settingsSections = document.querySelectorAll('.settings-section');
            
            const activeSection = '{{ session('activeSection', 'edit-profil') }}';
            
            menuLinks.forEach(link => {
                if (link.getAttribute('data-target') === activeSection) {
                    link.classList.add('active');
                }
            });
            
            settingsSections.forEach(section => {
                if (section.id === activeSection) {
                    section.classList.add('active');
                }
            });
            
            menuLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    menuLinks.forEach(l => l.classList.remove('active'));
                    settingsSections.forEach(s => s.classList.remove('active'));
                    
                    this.classList.add('active');
                    const targetId = this.getAttribute('data-target');
                    const targetSection = document.getElementById(targetId);
                    
                    if (targetSection) {
                        targetSection.classList.add('active');
                        window.scrollTo({
                            top: targetSection.offsetTop - 100,
                            behavior: 'smooth'
                        });
                    }
                });
            });
        }
        
        initSettingsPage();

        // ========== CHARACTER COUNT FOR DESCRIPTION ==========
        const descriptionTextarea = document.getElementById('description');
        if (descriptionTextarea) {
            const charCount = document.getElementById('charCount');
            
            descriptionTextarea.addEventListener('input', function() {
                const count = this.value.length;
                charCount.textContent = count;
                
                if (count > 500) {
                    charCount.style.color = 'var(--danger)';
                    this.classList.add('is-invalid');
                } else if (count > 450) {
                    charCount.style.color = 'var(--warning)';
                    this.classList.remove('is-invalid');
                } else {
                    charCount.style.color = 'var(--success)';
                    this.classList.remove('is-invalid');
                }
            });
            
            charCount.textContent = descriptionTextarea.value.length;
        }

        // ========== PHOTO UPLOAD HANDLING ==========
        window.handlePhotoUpload = function(input) {
            const file = input.files[0];
            if (!file) return;
            
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file maksimal 2MB');
                input.value = '';
                return;
            }
            
            const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
            if (!validTypes.includes(file.type)) {
                alert('Format file harus JPG, PNG, atau GIF');
                input.value = '';
                return;
            }
            
            const loadingElement = document.getElementById('formPhotoLoading');
            const previewContainer = document.getElementById('formPhotoPreview');
            
            if (loadingElement) loadingElement.style.display = 'flex';
            
            const reader = new FileReader();
            reader.onload = function(e) {
                if (previewContainer) {
                    const defaultAvatar = previewContainer.querySelector('.default-avatar');
                    if (defaultAvatar) defaultAvatar.remove();
                    
                    const existingImage = previewContainer.querySelector('img');
                    if (existingImage) existingImage.remove();
                    
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = 'Preview Foto';
                    img.className = 'profile-image';
                    img.style.cssText = 'width: 100%; height: 100%; object-fit: cover; border-radius: 50%;';
                    img.onerror = function() {
                        this.src = '{{ asset('images/default-avatar.png') }}';
                    };
                    
                    previewContainer.insertBefore(img, loadingElement);
                    
                    const mainPreviewContainer = document.getElementById('photoPreview');
                    if (mainPreviewContainer) {
                        const mainAvatar = mainPreviewContainer.querySelector('.user-avatar');
                        if (mainAvatar) mainAvatar.style.display = 'none';
                        
                        const mainImage = mainPreviewContainer.querySelector('img');
                        if (mainImage) mainImage.remove();
                        
                        const mainImg = document.createElement('img');
                        mainImg.src = e.target.result;
                        mainImg.alt = 'Foto Profil';
                        mainImg.className = 'profile-image';
                        mainImg.style.cssText = 'width: 100%; height: 100%; object-fit: cover; border-radius: 50%;';
                        mainPreviewContainer.insertBefore(mainImg, mainPreviewContainer.querySelector('.photo-loading'));
                    }
                    
                    if (loadingElement) loadingElement.style.display = 'none';
                    
                    localStorage.setItem('profilePhotoPreview', e.target.result);
                    
                    const removeBtn = document.querySelector('.remove-btn');
                    if (removeBtn && removeBtn.style.display === 'none') {
                        removeBtn.style.display = 'block';
                    }
                }
            };
            
            reader.onerror = function(error) {
                console.error('Error reading file:', error);
                alert('Gagal membaca file gambar');
                input.value = '';
                if (loadingElement) loadingElement.style.display = 'none';
            };
            
            reader.readAsDataURL(file);
        };

        // ========== REMOVE PROFILE PHOTO ==========
        window.removeProfilePhoto = function() {
            if (confirm('Apakah Anda yakin ingin menghapus foto profil?')) {
                const previewContainer = document.getElementById('formPhotoPreview');
                const mainPreviewContainer = document.getElementById('photoPreview');
                const removePhotoInput = document.getElementById('removePhoto');
                
                if (removePhotoInput) removePhotoInput.value = '1';
                
                if (previewContainer) {
                    const existingImage = previewContainer.querySelector('img');
                    if (existingImage) existingImage.remove();
                    
                    const defaultAvatar = document.createElement('div');
                    defaultAvatar.className = 'default-avatar';
                    defaultAvatar.innerHTML = '<i class="fas fa-user"></i><span>Foto Profil</span>';
                    
                    previewContainer.appendChild(defaultAvatar);
                }
                
                if (mainPreviewContainer) {
                    const mainImage = mainPreviewContainer.querySelector('img');
                    if (mainImage) mainImage.remove();
                    
                    const mainAvatar = mainPreviewContainer.querySelector('.user-avatar');
                    if (mainAvatar) mainAvatar.style.display = 'flex';
                }
                
                const removeBtn = document.querySelector('.remove-btn');
                if (removeBtn) removeBtn.style.display = 'none';
                
                localStorage.removeItem('profilePhotoPreview');
            }
        };

        // ========== RESTORE PHOTO FROM LOCALSTORAGE ==========
        function restorePhotoPreview() {
            const preview = localStorage.getItem('profilePhotoPreview');
            const previewContainer = document.getElementById('formPhotoPreview');
            const mainPreviewContainer = document.getElementById('photoPreview');
            
            if (preview && previewContainer && !previewContainer.querySelector('img')) {
                const defaultAvatar = previewContainer.querySelector('.default-avatar');
                if (defaultAvatar) defaultAvatar.remove();
                
                const img = document.createElement('img');
                img.src = preview;
                img.alt = 'Preview Foto';
                img.className = 'profile-image';
                img.style.cssText = 'width: 100%; height: 100%; object-fit: cover; border-radius: 50%;';
                
                previewContainer.insertBefore(img, document.getElementById('formPhotoLoading'));
                
                if (mainPreviewContainer) {
                    const mainAvatar = mainPreviewContainer.querySelector('.user-avatar');
                    if (mainAvatar) mainAvatar.style.display = 'none';
                    
                    const mainImg = document.createElement('img');
                    mainImg.src = preview;
                    mainImg.alt = 'Foto Profil';
                    mainImg.className = 'profile-image';
                    mainImg.style.cssText = 'width: 100%; height: 100%; object-fit: cover; border-radius: 50%;';
                    mainPreviewContainer.insertBefore(mainImg, mainPreviewContainer.querySelector('.photo-loading'));
                }
                
                const removeBtn = document.querySelector('.remove-btn');
                if (removeBtn) removeBtn.style.display = 'block';
            }
        }
        
        restorePhotoPreview();

        // ========== TOGGLE PASSWORD VISIBILITY ==========
        window.togglePassword = function(inputId) {
            const input = document.getElementById(inputId);
            const toggleBtn = input.nextElementSibling;
            const icon = toggleBtn.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        };

        // ========== RESET FORM ==========
        window.resetForm = function() {
            if (confirm('Batalkan perubahan? Semua data yang belum disimpan akan hilang.')) {
                document.getElementById('editProfileForm').reset();
                
                const previewContainer = document.getElementById('formPhotoPreview');
                const mainPreviewContainer = document.getElementById('photoPreview');
                
                if (previewContainer) {
                    previewContainer.innerHTML = `
                        @if($hasPhoto && $photoUrl)
                            <img src="{{ $photoUrl }}" 
                                 alt="Foto Profil" 
                                 class="profile-image"
                                 id="formProfileImage">
                        @else
                            <div class="default-avatar">
                                <i class="fas fa-user"></i>
                                <span>Foto Profil</span>
                            </div>
                        @endif
                        <div class="photo-loading" id="formPhotoLoading" style="display: none;">
                            <i class="fas fa-spinner fa-spin"></i>
                        </div>
                    `;
                }
                
                if (mainPreviewContainer) {
                    mainPreviewContainer.innerHTML = `
                        @if($hasPhoto && $photoUrl)
                            <img src="{{ $photoUrl }}" 
                                 alt="Foto Profil" 
                                 id="currentProfilePhoto"
                                 class="profile-image"
                                 data-filename="{{ $user->profile_photo }}"
                                 onerror="this.onerror=null; this.src='{{ asset('images/default-avatar.png') }}';">
                        @else
                            <div class="user-avatar">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        @endif
                        <div class="photo-loading" id="photoLoading" style="display: none;">
                            <i class="fas fa-spinner fa-spin"></i>
                        </div>
                    `;
                }
                
                localStorage.removeItem('profilePhotoPreview');
                
                @if(!$hasPhoto)
                const removeBtn = document.querySelector('.remove-btn');
                if (removeBtn) removeBtn.style.display = 'none';
                @endif
                
                const removePhotoInput = document.getElementById('removePhoto');
                if (removePhotoInput) removePhotoInput.value = '0';
                
                const charCount = document.getElementById('charCount');
                if (charCount) charCount.textContent = '{{ strlen(Auth::user()->description) }}';
            }
        };

        // ========== LOGOUT FUNCTIONALITY ==========
        const logoutBtn = document.getElementById('logout-btn');
        const logoutPopup = document.getElementById('logout-popup');
        const cancelLogout = document.getElementById('cancel-logout');

        function showLogoutPopup() {
            logoutPopup.style.display = 'flex';
        }

        function hideLogoutPopup() {
            logoutPopup.style.display = 'none';
        }

        if (logoutBtn) {
            logoutBtn.addEventListener('click', showLogoutPopup);
        }

        if (cancelLogout) {
            cancelLogout.addEventListener('click', hideLogoutPopup);
        }

        if (logoutPopup) {
            logoutPopup.addEventListener('click', function(e) {
                if (e.target === logoutPopup) {
                    hideLogoutPopup();
                }
            });
        }

        // ========== PAYMENT METHOD SELECTION ==========
        const paymentMethods = document.querySelectorAll('.payment-method');
        paymentMethods.forEach(method => {
            method.addEventListener('click', function() {
                paymentMethods.forEach(m => {
                    m.style.borderColor = '#e2e8f0';
                    m.style.backgroundColor = '#f8fafc';
                });
                
                this.style.borderColor = 'var(--primary-color)';
                this.style.backgroundColor = '#E3F2FD';
            });
        });

        // ========== SAVE PAYMENT METHOD ==========
        const savePaymentBtn = document.getElementById('savePayment');
        if (savePaymentBtn) {
            savePaymentBtn.addEventListener('click', function() {
                const cardNumber = document.getElementById('nomor-kartu').value.replace(/\s+/g, '');
                const expiryDate = document.getElementById('tanggal-kadaluarsa').value;
                const cvv = document.getElementById('kode-keamanan').value;
                const cardHolder = document.getElementById('nama-pemilik').value;
                
                if (!cardNumber || !expiryDate || !cvv || !cardHolder) {
                    alert('Harap lengkapi semua informasi kartu!');
                    return;
                }
                
                if (cardNumber.length < 16) {
                    alert('Nomor kartu harus 16 digit!');
                    return;
                }
                
                if (!/^\d{2}\/\d{2}$/.test(expiryDate)) {
                    alert('Format tanggal kadaluarsa tidak valid! Gunakan format MM/YY');
                    return;
                }
                
                if (cvv.length !== 3) {
                    alert('CVV harus 3 digit!');
                    return;
                }
                
                alert('Metode pembayaran berhasil disimpan!');
                document.getElementById('paymentForm').reset();
            });
        }

        // ========== SAVE NOTIFICATIONS ==========
        const saveNotificationsBtn = document.getElementById('saveNotifications');
        if (saveNotificationsBtn) {
            saveNotificationsBtn.addEventListener('click', function() {
                const notificationSettings = {
                    booking_confirmation: document.querySelector('#notifikasi input[type="checkbox"]:nth-of-type(1)').checked,
                    schedule_changes: document.querySelector('#notifikasi input[type="checkbox"]:nth-of-type(2)').checked,
                    payment_receipt: document.querySelector('#notifikasi input[type="checkbox"]:nth-of-type(3)').checked,
                    game_reminder: document.querySelector('#notifikasi input[type="checkbox"]:nth-of-type(4)').checked
                };
                
                localStorage.setItem('notificationSettings', JSON.stringify(notificationSettings));
                alert('Pengaturan notifikasi berhasil disimpan!');
            });
        }

        // ========== SAVE PASSWORD ==========
        const savePasswordBtn = document.getElementById('savePassword');
        if (savePasswordBtn) {
            savePasswordBtn.addEventListener('click', async function() {
                const form = document.getElementById('securityForm');
                const currentPassword = form.querySelector('#password-saat-ini').value;
                const newPassword = form.querySelector('#password-baru').value;
                const confirmPassword = form.querySelector('#konfirmasi-password').value;
                
                if (!currentPassword || !newPassword || !confirmPassword) {
                    alert('Harap lengkapi semua field password!');
                    return;
                }
                
                if (newPassword !== confirmPassword) {
                    alert('Password baru dan konfirmasi password tidak cocok!');
                    return;
                }
                
                if (newPassword.length < 8) {
                    alert('Password baru minimal 8 karakter!');
                    return;
                }
                
                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const response = await fetch('/change-password', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            current_password: currentPassword,
                            new_password: newPassword,
                            new_password_confirmation: confirmPassword
                        })
                    });
                    
                    if (response.ok) {
                        const data = await response.json();
                        
                        if (data.success) {
                            alert('Password berhasil diubah!');
                            form.reset();
                        } else {
                            alert('Gagal mengubah password: ' + (data.message || 'Password saat ini salah'));
                        }
                    } else {
                        alert('Terjadi kesalahan saat mengubah password');
                    }
                } catch (error) {
                    console.error('Error changing password:', error);
                    alert('Terjadi kesalahan saat mengubah password');
                }
            });
        }

        // ========== FAQ FUNCTIONALITY ==========
        window.searchFAQ = function() {
            const searchTerm = document.getElementById('search-faq').value.toLowerCase().trim();
            const faqItems = document.querySelectorAll('.faq-item');
            const noResults = document.getElementById('noResults');
            let found = false;
            
            if (searchTerm === '') {
                faqItems.forEach(item => {
                    item.style.display = 'block';
                });
                if (noResults) noResults.style.display = 'none';
                return;
            }
            
            faqItems.forEach(item => {
                const question = item.querySelector('.faq-question span')?.textContent.toLowerCase() || '';
                const answer = item.querySelector('.faq-answer p')?.textContent.toLowerCase() || '';
                
                if (question.includes(searchTerm) || answer.includes(searchTerm)) {
                    item.style.display = 'block';
                    found = true;
                    
                    const questionElement = item.querySelector('.faq-question');
                    if (questionElement && !questionElement.classList.contains('active')) {
                        toggleFAQ(questionElement);
                    }
                } else {
                    item.style.display = 'none';
                }
            });
            
            if (noResults) {
                noResults.style.display = found ? 'none' : 'block';
            }
        };
        
        const searchInput = document.getElementById('search-faq');
        if (searchInput) {
            searchInput.addEventListener('keyup', function(e) {
                if (e.key === 'Enter') {
                    searchFAQ();
                }
            });
            
            let searchTimeout;
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(searchFAQ, 300);
            });
        }

        // FAQ Toggle Function
        window.toggleFAQ = function(element) {
            const answer = element.nextElementSibling;
            const icon = element.querySelector('i');
            
            if (!answer || !icon) return;
            
            if (answer.classList.contains('show')) {
                answer.classList.remove('show');
                element.classList.remove('active');
                icon.style.transform = 'rotate(0deg)';
            } else {
                document.querySelectorAll('.faq-answer.show').forEach(openAnswer => {
                    openAnswer.classList.remove('show');
                    const prevElement = openAnswer.previousElementSibling;
                    if (prevElement && prevElement.classList.contains('faq-question')) {
                        prevElement.classList.remove('active');
                        const prevIcon = prevElement.querySelector('i');
                        if (prevIcon) prevIcon.style.transform = 'rotate(0deg)';
                    }
                });
                
                answer.classList.add('show');
                element.classList.add('active');
                icon.style.transform = 'rotate(180deg)';
            }
        };

        // FAQ Category Filter
        document.querySelectorAll('.category-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.category-tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                
                const category = this.getAttribute('data-category');
                const faqItems = document.querySelectorAll('.faq-item');
                const faqCategories = document.querySelectorAll('.faq-category');
                
                if (category === 'all') {
                    faqItems.forEach(item => item.style.display = 'block');
                    faqCategories.forEach(cat => cat.style.display = 'block');
                } else {
                    faqItems.forEach(item => {
                        if (item.getAttribute('data-category') === category) {
                            item.style.display = 'block';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                    
                    faqCategories.forEach(cat => {
                        if (cat.getAttribute('data-category') === category) {
                            cat.style.display = 'block';
                        } else {
                            cat.style.display = 'none';
                        }
                    });
                }
                
                if (searchInput) {
                    searchInput.value = '';
                    searchFAQ();
                }
                
                const noResults = document.getElementById('noResults');
                if (noResults) noResults.style.display = 'none';
            });
        });

        // ========== FORMAT CARD NUMBER ==========
        const nomorKartuInput = document.getElementById('nomor-kartu');
        if (nomorKartuInput) {
            nomorKartuInput.addEventListener('input', function() {
                let value = this.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
                let formattedValue = '';
                
                for (let i = 0; i < value.length; i++) {
                    if (i > 0 && i % 4 === 0) {
                        formattedValue += ' ';
                    }
                    formattedValue += value[i];
                }
                
                this.value = formattedValue.substring(0, 19);
            });
        }

        // ========== FORMAT EXPIRY DATE ==========
        const tanggalKadaluarsaInput = document.getElementById('tanggal-kadaluarsa');
        if (tanggalKadaluarsaInput) {
            tanggalKadaluarsaInput.addEventListener('input', function() {
                let value = this.value.replace(/\D/g, '');
                
                if (value.length >= 2) {
                    this.value = value.substring(0, 2) + '/' + value.substring(2, 4);
                } else {
                    this.value = value;
                }
            });
        }

        // ========== DARK MODE TOGGLE ==========
        const darkModeToggle = document.getElementById('darkModeToggle');
        if (darkModeToggle) {
            darkModeToggle.addEventListener('change', function() {
                if (this.checked) {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('darkMode', 'enabled');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('darkMode', 'disabled');
                }
            });
            
            if (localStorage.getItem('darkMode') === 'enabled') {
                darkModeToggle.checked = true;
                document.documentElement.classList.add('dark');
            }
        }

        // ========== END OTHER SESSIONS ==========
        window.endOtherSessions = async function() {
            if (confirm('Apakah Anda yakin ingin menghapus semua sesi lain? Anda akan logout dari semua perangkat kecuali yang ini.')) {
                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const response = await fetch('/logout-other-sessions', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    });
                    
                    if (response.ok) {
                        alert('Semua sesi lain telah dihapus!');
                    } else {
                        alert('Gagal menghapus sesi lain');
                    }
                } catch (error) {
                    console.error('Error ending sessions:', error);
                    alert('Terjadi kesalahan saat menghapus sesi');
                }
            }
        };

        // ========== TOGGLE DELETE ACCOUNT BUTTON ==========
        window.toggleDeleteButton = function() {
            const confirmCheckbox = document.getElementById('confirmDelete');
            const deleteAccountBtn = document.getElementById('deleteAccountBtn');
            
            if (confirmCheckbox && deleteAccountBtn) {
                deleteAccountBtn.disabled = !confirmCheckbox.checked;
            }
        };

        // ========== CONFIRM DELETE ACCOUNT ==========
        window.confirmDeleteAccount = function() {
            const deleteAccountBtn = document.getElementById('deleteAccountBtn');
            if (deleteAccountBtn && deleteAccountBtn.disabled) {
                alert('Harap centang konfirmasi terlebih dahulu.');
                return;
            }
            
            if (confirm('PERINGATAN: Ini akan menghapus akun Anda secara permanen. Semua data termasuk riwayat booking, favorit, dan transaksi akan hilang. Lanjutkan?')) {
                const confirmDelete = prompt('Ketik "HAPUS" untuk mengonfirmasi penghapusan akun:');
                if (confirmDelete === 'HAPUS') {
                    // Kirim permintaan penghapusan akun ke server
                    alert('Fitur penghapusan akun sedang dalam pengembangan.');
                    
                    // Contoh kode untuk menghapus akun:
                    /*
                    fetch('/delete-account', {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Akun berhasil dihapus. Anda akan dialihkan ke halaman utama.');
                            window.location.href = '/';
                        } else {
                            alert('Gagal menghapus akun: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat menghapus akun.');
                    });
                    */
                } else {
                    alert('Penghapusan akun dibatalkan.');
                }
            }
        };

        // ========== AUTO-HIDE ALERTS ==========
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.opacity = '0';
                alert.style.transition = 'opacity 0.5s ease';
                setTimeout(() => {
                    alert.style.display = 'none';
                }, 500);
            });
        }, 5000);
    });
</script>
@endsection