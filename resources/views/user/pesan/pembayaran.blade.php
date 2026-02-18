@extends('layouts.user')
@section('title', 'Booking Venue - ' . ($venue->name ?? 'Venue'))
@section('content')

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

body {
    background: #f8f9fa;
    color: #333;
    line-height: 1.6;
    min-height: 100vh;
}

.booking-container {
    max-width: 1200px;
    margin: 0 auto;
    background: white;
    border-radius: 0 0 15px 15px;
    box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    animation: fadeIn 0.8s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.venue-info-card {
    background: white;
    padding: 25px 30px;
    border-bottom: 1px solid #eaeaea;
}

.venue-name {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.venue-name i {
    color: #3498db;
    font-size: 1.3rem;
}

.venue-details {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-top: 15px;
}

.venue-detail-item {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 0.95rem;
    color: #5a6c7d;
    padding: 10px 15px;
    background: #f8fafc;
    border-radius: 8px;
    border: 1px solid #e8edf2;
    transition: all 0.3s ease;
}

.venue-detail-item:hover {
    background: #edf4fc;
    border-color: #3498db;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(52, 152, 219, 0.15);
}

.venue-detail-item i {
    color: #3498db;
    width: 18px;
    height: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
}

.progress-indicator {
    display: flex;
    justify-content: space-between;
    padding: 25px 30px;
    background: white;
    border-bottom: 1px solid #eaeaea;
    position: relative;
    align-items: flex-start;
}

.progress-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    z-index: 2;
    flex: 1;
    min-width: 0;
}

.step-number {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #e0e7ff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    margin-bottom: 8px;
    transition: all 0.3s ease;
    border: 3px solid white;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    font-size: 1rem;
    color: #5a6c7d;
    position: relative;
    z-index: 3;
}

.step-label {
    font-size: 0.9rem;
    color: #5a6c7d;
    text-align: center;
    font-weight: 600;
    transition: all 0.3s ease;
    width: 100%;
    padding: 0 5px;
    box-sizing: border-box;
    white-space: normal;
    word-wrap: break-word;
    line-height: 1.3;
}

.progress-step.active .step-number {
    background: #3498db;
    color: white;
    box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
}

.progress-step.active .step-label {
    color: #3498db;
    font-weight: 700;
}

.progress-step.completed .step-number {
    background: #2ecc71;
    color: white;
}

@media (max-width: 768px) {
    .progress-indicator {
        padding: 15px;
        flex-wrap: wrap;
    }
    
    .progress-step {
        flex: 0 0 25%;
        margin-bottom: 10px;
        min-width: 0;
    }
    
    .step-number {
        width: 32px;
        height: 32px;
        font-size: 0.9rem;
        margin-bottom: 6px;
    }
    
    .step-label {
        font-size: 0.75rem;
        padding: 0 3px;
        line-height: 1.2;
    }
}

@media (max-width: 480px) {
    .step-number {
        width: 30px;
        height: 30px;
        font-size: 0.85rem;
    }
    
    .step-label {
        font-size: 0.7rem;
        padding: 0 2px;
    }
}

.booking-section {
    padding: 30px;
    background: white;
    display: none;
    animation: slideIn 0.5s ease-out;
}

@keyframes slideIn {
    from { opacity: 0; transform: translateX(20px); }
    to { opacity: 1; transform: translateX(0); }
}

.booking-section.active {
    display: block;
}

.booking-section h2 {
    font-size: 1.3rem;
    margin: 0 0 25px 0;
    color: #2c3e50;
    font-weight: 700;
    padding-bottom: 12px;
    border-bottom: 2px solid #3498db;
    display: flex;
    align-items: center;
    gap: 10px;
}

.booking-section h2 i {
    color: #3498db;
}

.calendar-container {
    background: white;
    border-radius: 10px;
    padding: 25px;
    border: 1px solid #e8edf2;
    box-shadow: 0 2px 15px rgba(0,0,0,0.05);
}

.calendar-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    padding: 0 10px;
    flex-wrap: nowrap;
    gap: 10px;
}

.calendar-nav {
    background: white;
    border: 2px solid #3498db;
    font-size: 0.95rem;
    cursor: pointer;
    color: #3498db;
    padding: 8px 20px;
    border-radius: 6px;
    transition: all 0.3s ease;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
    white-space: nowrap;
    flex-shrink: 0;
}

.calendar-nav:hover {
    background: #3498db;
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(52, 152, 219, 0.2);
}

.calendar-title {
    font-size: 1.2rem;
    font-weight: 700;
    color: #2c3e50;
    background: #f8fafc;
    padding: 8px 25px;
    border-radius: 30px;
    border: 2px solid #e0e7ff;
    text-align: center;
    flex-grow: 1;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.calendar {
    width: 100%;
    border-collapse: separate;
    border-spacing: 6px;
    margin-bottom: 0;
}

.calendar th {
    padding: 14px 5px;
    text-align: center;
    background: #3498db;
    color: white;
    font-weight: 600;
    border-radius: 8px;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.calendar td {
    padding: 14px 5px;
    text-align: center;
    background: white;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 600;
    border: 2px solid transparent;
    font-size: 0.9rem;
    position: relative;
    box-shadow: 0 1px 4px rgba(0,0,0,0.05);
}

.calendar td:hover {
    background: #f0f7ff;
    border-color: #3498db;
    transform: translateY(-1px);
    box-shadow: 0 3px 10px rgba(52, 152, 219, 0.15);
}

.calendar td.selected {
    background: #3498db;
    color: white;
    font-weight: 700;
    transform: translateY(-1px);
    box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
}

.calendar td.today {
    background: #fff9e6;
    color: #d35400;
    font-weight: 700;
    border: 2px solid #f1c40f;
}

.calendar td.booked {
    background: #ffeaea !important;
    color: #e74c3c !important;
    cursor: not-allowed !important;
    position: relative;
    opacity: 0.8;
}

.time-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 12px;
    margin-bottom: 0;
}

.time-slot {
    border: 2px solid #e8edf2;
    border-radius: 10px;
    padding: 18px 10px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background: white;
    position: relative;
    overflow: hidden;
}

.time-slot:hover {
    border-color: #3498db;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(52, 152, 219, 0.15);
}

.time-slot.selected {
    background: #3498db;
    color: white;
    border-color: #3498db;
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(52, 152, 219, 0.25);
}

.time-slot.booked {
    background: #ffeaea !important;
    color: #e74c3c !important;
    border-color: #ffcccc !important;
    cursor: not-allowed !important;
    opacity: 0.8;
    position: relative;
}

.time {
    font-weight: 700;
    margin-bottom: 6px;
    font-size: 1.05rem;
}

.price {
    font-size: 0.8rem;
    color: #7f8c8d;
    font-weight: 600;
}

.time-slot.selected .price {
    color: rgba(255,255,255,0.9);
}

.duration-options {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
    margin-bottom: 0;
}

.duration-option {
    border: 2px solid #e8edf2;
    border-radius: 10px;
    padding: 16px 8px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background: white;
    font-weight: 700;
    font-size: 0.9rem;
    position: relative;
    overflow: hidden;
    min-height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.duration-option:hover {
    border-color: #3498db;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(52, 152, 219, 0.15);
}

.duration-option.selected {
    background: #3498db;
    color: white;
    border-color: #3498db;
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(52, 152, 219, 0.25);
}

.duration-option.disabled {
    background: #f8f9fa !important;
    color: #95a5a6 !important;
    cursor: not-allowed !important;
    opacity: 0.6;
    border-color: #dfe6e9 !important;
}

.summary-section {
    background: white;
    margin: 0;
    border-radius: 10px;
    padding: 25px;
    border: 2px solid #e8edf2;
    box-shadow: 0 2px 15px rgba(0,0,0,0.05);
}

.summary-section h2 {
    margin-bottom: 20px;
    color: #2c3e50;
    border-bottom: 2px solid #3498db;
    padding-bottom: 12px;
    font-size: 1.3rem;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    padding: 14px;
    border-bottom: 1px dashed #e8edf2;
    background: #f8fafc;
    border-radius: 8px;
    transition: all 0.3s ease;
    min-height: 60px;
}

.summary-row:hover {
    transform: translateX(3px);
    box-shadow: 0 3px 10px rgba(0,0,0,0.05);
}

.summary-label {
    color: #5a6c7d;
    font-weight: 600;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 40%;
    flex-shrink: 0;
}

.summary-label i {
    color: #3498db;
    font-size: 1rem;
    width: 20px;
    text-align: center;
}

.summary-value {
    font-weight: 700;
    color: #2c3e50;
    font-size: 0.95rem;
    text-align: right;
    flex: 1;
    padding-left: 10px;
    word-break: break-word;
}

.total-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 20px;
    padding-top: 18px;
    border-top: 2px solid #e8edf2;
    font-size: 1.1rem;
    font-weight: 700;
    background: white;
    padding: 18px;
    border-radius: 10px;
    border: 2px solid #3498db;
    min-height: 70px;
}

.total-label {
    color: #2c3e50;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
}

.total-price {
    color: #3498db;
    font-size: 1.3rem;
    font-weight: 800;
    text-align: right;
}

@media (max-width: 768px) {
    .summary-section {
        padding: 15px;
    }
    
    .summary-row {
        padding: 12px;
        flex-direction: row;
        align-items: center;
        min-height: 55px;
        margin-bottom: 12px;
    }
    
    .summary-label {
        font-size: 0.85rem;
        min-width: 45%;
        gap: 8px;
    }
    
    .summary-value {
        font-size: 0.9rem;
        text-align: right;
        padding-left: 5px;
    }
    
    .total-row {
        flex-direction: row;
        align-items: center;
        padding: 15px;
        font-size: 1rem;
        min-height: 60px;
        gap: 10px;
    }
    
    .total-price {
        font-size: 1.1rem;
        text-align: right;
        flex: 1;
    }
}

.payment-button {
    display: block;
    width: 100%;
    margin: 0;
    padding: 18px;
    background: #3498db;
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 1.05rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 5px 20px rgba(52, 152, 219, 0.25);
    position: relative;
    overflow: hidden;
    letter-spacing: 0.5px;
    flex: 1;
    text-align: center;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.payment-button:hover {
    background: #2980b9;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(52, 152, 219, 0.3);
}

.payment-button:disabled {
    background: #95a5a6 !important;
    cursor: not-allowed !important;
    transform: none !important;
    box-shadow: none !important;
}

.nav-buttons {
    display: flex;
    justify-content: space-between;
    padding: 0;
    gap: 15px;
    margin-top: 25px;
}

.nav-button {
    padding: 18px 25px;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 600;
    font-size: 1.05rem;
    text-align: center;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    flex: 1;
}

.nav-button.prev {
    background: white;
    color: #3498db;
    border: 2px solid #3498db;
}

.nav-button.next {
    background: #3498db;
    color: white;
    border: 2px solid #3498db;
}

.nav-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.nav-button.prev:hover {
    background: #3498db;
    border-color: #3498db;
    color: white;
}

.nav-button.next:hover {
    background: #2980b9;
    border-color: #2980b9;
}

.nav-button:disabled {
    background: #bdc3c7 !important;
    border-color: #95a5a6 !important;
    color: #7f8c8d !important;
    cursor: not-allowed !important;
    transform: none !important;
    box-shadow: none !important;
}

.summary-buttons {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-top: 25px;
}

.back-button {
    background: white;
    color: #3498db;
    border: 2px solid #3498db;
    padding: 18px 25px;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 600;
    font-size: 1.05rem;
    text-align: center;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    flex: 1;
}

.back-button:hover {
    background: #3498db;
    border-color: #3498db;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.alert-message {
    background: #ffeaea;
    color: #e74c3c;
    padding: 12px 18px;
    border-radius: 8px;
    margin-bottom: 20px;
    border: 2px solid #ffcccc;
    font-size: 0.9rem;
    font-weight: 500;
    display: none;
}

.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}

.modal-overlay.active {
    opacity: 1;
    visibility: visible;
}

.modal {
    background: white;
    border-radius: 12px;
    padding: 25px;
    max-width: 500px;
    width: 90%;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    transform: translateY(-20px);
    transition: all 0.3s ease;
}

.modal-overlay.active .modal {
    transform: translateY(0);
}

.modal-header {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
    padding-bottom: 12px;
    border-bottom: 2px solid #e8edf2;
}

.modal-icon {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background: #ffeaea;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    color: #e74c3c;
    font-size: 1.2rem;
}

.modal-title {
    font-size: 1.2rem;
    font-weight: 700;
    color: #2c3e50;
}

.modal-body {
    margin-bottom: 20px;
    line-height: 1.5;
    color: #5a6c7d;
    font-size: 0.95rem;
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

.modal-button {
    padding: 10px 20px;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s ease;
    border: none;
    font-size: 0.9rem;
}

.modal-button.primary {
    background: #3498db;
    color: white;
}

.modal-button.secondary {
    background: white;
    color: #3498db;
    border: 2px solid #3498db;
}

.modal-button:hover {
    transform: translateY(-1px);
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
}

.disabled-date {
    background: #f8f9fa !important;
    color: #bdc3c7 !important;
    cursor: not-allowed !important;
    opacity: 0.6 !important;
}

.selectable-date {
    cursor: pointer !important;
}

.other-month {
    opacity: 0.4;
    cursor: not-allowed;
}

.no-slots {
    grid-column: 1 / -1;
    text-align: center;
    padding: 25px;
    color: #7f8c8d;
    font-style: italic;
    font-size: 1rem;
    background: #f8fafc;
    border-radius: 10px;
    border: 2px dashed #dfe6e9;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

@media (max-width: 1200px) {
    .booking-container {
        margin: 0 20px;
    }
    
    .venue-details {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .time-grid {
        grid-template-columns: repeat(4, 1fr);
    }
    
    .duration-options {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
}

@media (max-width: 768px) {
    .booking-container {
        margin: 0;
        border-radius: 0;
        box-shadow: none;
    }
    
    .venue-info-card {
        padding: 15px;
    }
    
    .venue-name {
        font-size: 1.2rem;
        margin-bottom: 10px;
        gap: 8px;
    }
    
    .venue-details {
        grid-template-columns: 1fr !important;
        gap: 10px;
    }
    
    .venue-detail-item {
        padding: 8px 12px;
        font-size: 0.9rem;
    }
    
    .progress-indicator {
        padding: 15px;
        flex-wrap: wrap;
    }
    
    .progress-step {
        flex: 0 0 25%;
        margin-bottom: 10px;
    }
    
    .step-number {
        width: 32px;
        height: 32px;
        font-size: 0.9rem;
    }
    
    .step-label {
        font-size: 0.75rem;
        white-space: nowrap;
    }
    
    .booking-section {
        padding: 15px;
    }
    
    .booking-section h2 {
        font-size: 1.1rem;
        margin-bottom: 15px;
        padding-bottom: 8px;
    }
    
    .calendar-container {
        padding: 15px;
        overflow-x: auto;
    }
    
    .calendar-header {
        flex-direction: row;
        flex-wrap: nowrap;
        gap: 8px;
        margin-bottom: 15px;
        padding: 0 5px;
    }
    
    .calendar-nav {
        flex: 0 0 auto;
        padding: 8px 12px;
        font-size: 0.8rem;
        white-space: nowrap;
    }
    
    .calendar-title {
        flex: 1;
        padding: 8px 10px;
        font-size: 0.95rem;
        text-align: center;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .calendar {
        min-width: 320px;
        border-spacing: 3px;
    }
    
    .calendar th {
        padding: 10px 2px;
        font-size: 0.8rem;
    }
    
    .calendar td {
        padding: 12px 2px;
        font-size: 0.85rem;
        min-height: 45px;
    }
    
    .time-grid {
        grid-template-columns: repeat(3, 1fr) !important;
        gap: 8px;
    }
    
    .time-slot {
        padding: 12px 5px;
    }
    
    .time {
        font-size: 0.9rem;
        margin-bottom: 4px;
    }
    
    .price {
        font-size: 0.7rem;
    }
    
    .duration-options {
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 8px;
    }
    
    .duration-option {
        padding: 14px 5px;
        font-size: 0.85rem;
        min-height: 55px;
    }
    
    .summary-section {
        padding: 15px;
    }
    
    .summary-row {
        padding: 12px;
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
    }
    
    .summary-label {
        font-size: 0.85rem;
    }
    
    .summary-value {
        font-size: 0.9rem;
        width: 100%;
        text-align: right;
    }
    
    .total-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
        padding: 15px;
        font-size: 1rem;
    }
    
    .total-price {
        font-size: 1.1rem;
        width: 100%;
        text-align: right;
    }
    
    .nav-buttons {
        flex-direction: row;
        gap: 10px;
        margin-top: 20px;
    }
    
    .nav-button {
        width: auto;
        padding: 16px 12px;
        font-size: 0.9rem;
        flex: 1;
        min-height: 56px;
    }
    
    #summary-section .nav-buttons {
        gap: 10px;
    }
    
    #summary-section .nav-button,
    #summary-section .payment-button {
        padding: 16px 12px;
        font-size: 0.9rem;
        min-height: 56px;
    }
    
    .summary-buttons {
        gap: 10px;
        margin-top: 20px;
    }
    
    .back-button,
    .payment-button {
        width: 100%;
        padding: 16px 12px;
        font-size: 0.9rem;
        min-height: 56px;
    }
    
    .modal {
        width: 95%;
        padding: 20px;
        margin: 0 10px;
    }
    
    .modal-header {
        flex-direction: column;
        text-align: center;
        gap: 10px;
    }
    
    .modal-icon {
        margin-right: 0;
    }
    
    .modal-footer {
        flex-direction: column;
        gap: 8px;
    }
    
    .modal-button {
        width: 100%;
    }
}

@media (max-width: 480px) {
    .calendar {
        min-width: 300px;
    }
    
    .calendar td {
        padding: 10px 1px;
        font-size: 0.8rem;
    }
    
    .calendar-header {
        gap: 5px;
    }
    
    .calendar-nav {
        font-size: 0;
        padding: 8px;
        width: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .calendar-nav i {
        font-size: 0.85rem;
        margin: 0;
    }
    
    .calendar-nav span {
        display: none;
    }
    
    .calendar-title {
        font-size: 0.85rem;
        padding: 8px 5px;
    }
    
    .time-grid {
        grid-template-columns: repeat(2, 1fr) !important;
    }
    
    .time-slot {
        padding: 10px 5px;
    }
    
    .time {
        font-size: 0.85rem;
    }
    
    .price {
        font-size: 0.65rem;
    }
    
    .duration-options {
        grid-template-columns: 1fr !important;
        gap: 6px;
    }
    
    .duration-option {
        padding: 12px 5px;
        font-size: 0.8rem;
        min-height: 50px;
    }
    
    .nav-buttons {
        gap: 8px;
    }
    
    .nav-button {
        padding: 14px 10px;
        font-size: 0.85rem;
        min-height: 52px;
    }
    
    #summary-section .nav-buttons {
        gap: 8px;
    }
    
    #summary-section .nav-button,
    #summary-section .payment-button {
        padding: 14px 10px;
        font-size: 0.85rem;
        min-height: 52px;
    }
    
    .calendar td.today::after {
        content: '';
        display: none;
    }
    
    .time-slot.booked::after {
        font-size: 0.6rem;
        padding: 2px 5px;
    }
}

@media (max-width: 375px) {
    .time-grid {
        grid-template-columns: 1fr !important;
    }
    
    .duration-options {
        grid-template-columns: 1fr !important;
    }
    
    .nav-buttons {
        flex-direction: column;
    }
    
    .nav-button {
        width: 100%;
    }
    
    #summary-section .nav-buttons {
        flex-direction: column;
    }
}

@media (hover: none) and (pointer: coarse) {
    .calendar td,
    .time-slot,
    .duration-option,
    .nav-button,
    .payment-button,
    .back-button {
        min-height: 56px;
    }
    
    .calendar td {
        min-width: 44px;
    }
    
    .calendar td:hover,
    .time-slot:hover,
    .duration-option:hover,
    .nav-button:hover,
    .payment-button:hover,
    .back-button:hover {
        transform: none;
        box-shadow: none;
    }
    
    .calendar td.selected,
    .time-slot.selected,
    .duration-option.selected {
        transform: scale(0.98);
    }
}

@media (max-height: 600px) and (orientation: landscape) {
    .venue-details {
        grid-template-columns: repeat(2, 1fr) !important;
    }
    
    .progress-indicator {
        padding: 10px 15px;
    }
    
    .booking-section {
        padding: 15px;
        min-height: auto;
    }
    
    .time-grid {
        grid-template-columns: repeat(4, 1fr) !important;
        max-height: 150px;
        overflow-y: auto;
    }
    
    .duration-options {
        grid-template-columns: repeat(4, 1fr) !important;
        gap: 8px;
    }
    
    .duration-option {
        padding: 10px 5px;
        min-height: 50px;
        font-size: 0.8rem;
    }
    
    .nav-buttons {
        margin-top: 15px;
    }
    
    .nav-button {
        min-height: 50px;
        padding: 12px 15px;
    }
    
    #summary-section .nav-button,
    #summary-section .payment-button {
        min-height: 50px;
        padding: 12px 15px;
    }
}

.mobile-optimized .venue-detail-item {
    font-size: 0.9rem;
    padding: 10px;
}

.mobile-compact .step-label {
    font-size: 0.8rem;
}

.calendar td,
.time-slot,
.duration-option,
.nav-button,
.payment-button,
.back-button {
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}

.booking-container {
    -webkit-overflow-scrolling: touch;
}

@supports (-webkit-touch-callout: none) {
    .booking-container {
        min-height: -webkit-fill-available;
    }
}

.time-slot.locked {
    background: #fff3cd !important;
    border-color: #ffeaa7 !important;
    color: #856404 !important;
    cursor: not-allowed !important;
    position: relative;
}

.time-slot.locked::after {
    content: '⏳ Diproses';
    position: absolute;
    bottom: 5px;
    right: 5px;
    font-size: 0.7rem;
    color: #856404;
}

.loading-spinner {
    display: inline-block;
    width: 20px;
    height: 20px;
    border: 3px solid #f3f3f3;
    border-top: 3px solid #3498db;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-right: 10px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.alert-toast {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    padding: 15px 20px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    max-width: 400px;
    animation: slideIn 0.3s ease;
}

.alert-toast.success {
    background: #d4edda;
    border: 1px solid #c3e6cb;
    color: #155724;
}

.alert-toast.error {
    background: #f8d7da;
    border: 1px solid #f5c6cb;
    color: #721c24;
}

.alert-toast.warning {
    background: #fff3cd;
    border: 1px solid #ffeaa7;
    color: #856404;
}

@keyframes slideIn {
    from { transform: translateX(100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}

.no-slots {
    grid-column: 1 / -1;
    text-align: center;
    padding: 40px 20px;
    color: #6c757d;
    font-style: italic;
    background: #f8f9fa;
    border-radius: 10px;
    border: 2px dashed #dee2e6;
}

.no-slots i {
    font-size: 2rem;
    margin-bottom: 15px;
    color: #adb5bd;
}

#time-alert, #date-alert, #duration-alert {
    display: none;
}

</style>

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="desktop-container">
    <div class="venue-info-card mobile-optimized">
        <div class="venue-name">
            <i class="fas fa-map-pin"></i>
            {{ $venue->name ?? 'Venue Tidak Ditemukan' }}
        </div>
        <div class="venue-details">
            <div class="venue-detail-item">
                <i class="fas fa-map-marker-alt"></i>
                <span>{{ $venue->location ?? 'Lokasi tidak tersedia' }}</span>
            </div>
            <div class="venue-detail-item">
                <i class="fas fa-clock"></i>
                <span>Buka: 06.00–23.00</span>
            </div>
            <div class="venue-detail-item">
                <i class="fas fa-tag"></i>
                <span>Kategori: {{ $venue->category ?? 'Olahraga' }}</span>
            </div>
            <div class="venue-detail-item">
                <i class="fas fa-money-bill-wave"></i>
                <span>Harga: Rp {{ number_format($venue->price_per_hour ?? 0, 0, ',', '.') }}/jam</span>
            </div>
        </div>
    </div>

    <div class="progress-indicator mobile-compact">
        <div class="progress-step active" data-step="date-section">
            <div class="step-number">1</div>
            <div class="step-label">Pilih Tanggal</div>
        </div>
        <div class="progress-step" data-step="time-section">
            <div class="step-number">2</div>
            <div class="step-label">Pilih Waktu</div>
        </div>
        <div class="progress-step" data-step="duration-section">
            <div class="step-number">3</div>
            <div class="step-label">Pilih Durasi</div>
        </div>
        <div class="progress-step" data-step="summary-section">
            <div class="step-number">4</div>
            <div class="step-label">Konfirmasi</div>
        </div>
    </div>
    
    <section class="booking-section active" id="date-section">
        <h2><i class="far fa-calendar-alt"></i> Pilih Tanggal</h2>
        <div id="date-alert" class="alert-message" style="display: none;"></div>
        <div class="calendar-container">
            <div class="calendar-header">
                <button class="calendar-nav" id="prev-month">
                    <i class="fas fa-chevron-left"></i> <span>Bulan Sebelumnya</span>
                </button>
                <div class="calendar-title" id="current-month">November 2023</div>
                <button class="calendar-nav" id="next-month">
                    <span>Bulan Selanjutnya</span> <i class="fas fa-chevron-right"></i>
                </button>
            </div>
            <table class="calendar" id="calendar">
                <thead>
                    <tr>
                        <th>Min</th>
                        <th>Sen</th>
                        <th>Sel</th>
                        <th>Rab</th>
                        <th>Kam</th>
                        <th>Jum</th>
                        <th>Sab</th>
                    </tr>
                </thead>
                <tbody id="calendar-body"></tbody>
            </table>
        </div>
        
        <div class="nav-buttons">
            <div class="nav-button prev" data-prev="previous-page">
                <i class="fas fa-arrow-left"></i> <span>Kembali</span>
            </div>
            <div class="nav-button next" data-next="time-section" id="date-next-btn">
                <span>Lanjut</span> <i class="fas fa-arrow-right"></i>
            </div>
        </div>
    </section>
    
    <section class="booking-section" id="time-section">
        <h2><i class="far fa-clock"></i> Pilih Waktu</h2>
        <div id="time-alert" class="alert-message" style="display: none;"></div>
        <div class="time-grid" id="time-grid"></div>
        
        <div class="nav-buttons">
            <div class="nav-button prev" data-prev="date-section">
                <i class="fas fa-arrow-left"></i> <span>Kembali</span>
            </div>
            <div class="nav-button next" data-next="duration-section" id="time-next-btn">
                <span>Lanjut</span> <i class="fas fa-arrow-right"></i>
            </div>
        </div>
    </section>
    
    <section class="booking-section" id="duration-section">
        <h2><i class="fas fa-hourglass-half"></i> Pilih Durasi</h2>
        <div id="duration-alert" class="alert-message" style="display: none;"></div>
        <div class="duration-options" id="duration-options">
            <div class="duration-option" data-hours="1">1 Jam</div>
            <div class="duration-option selected" data-hours="2">2 Jam</div>
            <div class="duration-option" data-hours="3">3 Jam</div>
            <div class="duration-option" data-hours="4">4 Jam</div>
        </div>
        
        <div class="nav-buttons">
            <div class="nav-button prev" data-prev="time-section">
                <i class="fas fa-arrow-left"></i> <span>Kembali</span>
            </div>
            <div class="nav-button next" data-next="summary-section" id="duration-next-btn">
                <span>Lanjut</span> <i class="fas fa-arrow-right"></i>
            </div>
        </div>
    </section>
    
    <section class="booking-section" id="summary-section">
        <h2><i class="fas fa-file-alt"></i> Ringkasan Booking</h2>
        <div class="summary-section">
            <div class="summary-row">
                <div class="summary-label">
                    <i class="fas fa-map-marker-alt"></i> Venue :
                </div>
                <div class="summary-value" id="summary-venue">{{ $venue->name ?? 'Venue' }}</div>
            </div>
            <div class="summary-row">
                <div class="summary-label">
                    <i class="far fa-calendar-alt"></i> Tanggal :
                </div>
                <div class="summary-value" id="summary-date">Minggu, 2 Nov 2023</div>
            </div>
            <div class="summary-row">
                <div class="summary-label">
                    <i class="far fa-clock"></i> Waktu :
                </div>
                <div class="summary-value" id="summary-time">07.00</div>
            </div>
            <div class="summary-row">
                <div class="summary-label">
                    <i class="fas fa-hourglass-half"></i> Durasi :
                </div>
                <div class="summary-value" id="summary-duration">2 Jam</div>
            </div>
            <div class="total-row">
                <div>Total Pembayaran :</div>
                <div class="total-price" id="summary-total">Rp 240.000</div>
            </div>
        </div>
        
        <div class="summary-buttons">
            <div class="nav-buttons">
                <button class="back-button" data-prev="duration-section">
                    <i class="fas fa-arrow-left"></i> <span>Kembali ke Durasi</span>
                </button>
                <button type="button" class="payment-button" id="payment-button">
                    <i class="fas fa-lock"></i> <span>Lanjut ke Pembayaran</span>
                </button>
            </div>
        </div>
    </section>
</div>

<div class="modal-overlay" id="conflict-modal">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="modal-title">Slot Waktu Tidak Tersedia</div>
        </div>
        <div class="modal-body">
            Maaf, slot waktu yang Anda pilih sudah dipesan oleh pengguna lain. Silakan pilih waktu atau tanggal lain.
        </div>
        <div class="modal-footer">
            <button class="modal-button secondary" id="modal-close">Tutup</button>
            <button class="modal-button primary" id="modal-refresh">Refresh Ketersediaan</button>
        </div>
    </div>
</div>

<!-- Form untuk submit booking -->
<form id="booking-form" method="POST" action="{{ route('pesan.process-booking') }}" style="display: none;">
    @csrf
    <input type="hidden" name="venue_id" value="{{ $venue->id }}">
    <input type="hidden" name="tanggal_booking" id="form-tanggal-booking">
    <input type="hidden" name="waktu_booking" id="form-waktu-booking">
    <input type="hidden" name="durasi" id="form-durasi">
</form>

<script>
    const backendSelectedDate = "{{ $selectedDate }}";
    // ✅ Data dari backend
    const jadwalList = @json($jadwalList ?? []);

    // ✅ DEBUG: Log data yang diterima (HAPUS setelah production)
    console.log('=== INIT BOOKING DATA ===');
    console.log('Jadwal List:', jadwalList);
    console.log('Total Jadwal:', jadwalList.length);
    if (jadwalList.length > 0) {
        console.log('Sample Jadwal:', jadwalList[0]);
    }

    let currentVenue = {
        id: {{ $venue->id ?? 0 }},
        price_per_hour: {{ $venue->price_per_hour ?? 0 }},
        name: "{{ $venue->name ?? 'Venue' }}",
        address: "{{ $venue->location ?? 'Lokasi tidak tersedia' }}"
    };

    let existingBookings = [];
    let bookedDates = new Set();
    let bookedTimeSlots = {};

    const sections = document.querySelectorAll('.booking-section');
    const progressSteps = document.querySelectorAll('.progress-step');
    
    const nextButtons = document.querySelectorAll('.nav-button.next');
    const prevButtons = document.querySelectorAll('.nav-button.prev');
    const backButtons = document.querySelectorAll('.back-button');
    
    nextButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-next');
            navigateToSection(targetId);
        });
    });
    
    prevButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-prev');
            if (targetId === 'previous-page') {
                window.history.back();
            } else {
                navigateToSection(targetId);
            }
        });
    });
    
    backButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-prev');
            navigateToSection(targetId);
        });
    });
    
    function navigateToSection(targetId) {
        sections.forEach(section => {
            section.classList.remove('active');
            if (section.id === targetId) {
                section.classList.add('active');
            }
        });
        
        progressSteps.forEach(step => {
            step.classList.remove('active', 'completed');
            const stepId = step.getAttribute('data-step');
            
            if (stepId === targetId) {
                step.classList.add('active');
            } else {
                const currentIndex = Array.from(sections).findIndex(s => s.id === targetId);
                const stepIndex = Array.from(progressSteps).findIndex(s => s.getAttribute('data-step') === stepId);
                
                if (stepIndex < currentIndex) {
                    step.classList.add('completed');
                }
            }
        });
        
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    
    let currentDate = new Date();
    let selectedDate = backendSelectedDate
    ? new Date(backendSelectedDate + 'T00:00:00')
    : new Date();
    
    function updateCalendar() {
        const calendarBody = document.getElementById('calendar-body');
        const currentMonthElement = document.getElementById('current-month');
        
        calendarBody.innerHTML = '';
        
        const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni",
            "Juli", "Agustus", "September", "Oktober", "November", "Desember"
        ];
        currentMonthElement.textContent = `${monthNames[currentDate.getMonth()]} ${currentDate.getFullYear()}`;
        
        const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
        const lastDay = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
        const daysInMonth = lastDay.getDate();
        
        let firstDayOfWeek = firstDay.getDay();
        
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        let date = 1;
        for (let i = 0; i < 6; i++) {
            const row = document.createElement('tr');
            
            for (let j = 0; j < 7; j++) {
                const cell = document.createElement('td');
                
                if (i === 0 && j < firstDayOfWeek) {
                    const prevMonthLastDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 0).getDate();
                    cell.textContent = prevMonthLastDay - firstDayOfWeek + j + 1;
                    cell.classList.add('other-month');
                } else if (date > daysInMonth) {
                    cell.textContent = date - daysInMonth;
                    cell.classList.add('other-month');
                    date++;
                } else {
                    cell.textContent = date;
                    cell.setAttribute('data-date', date);
                    
                    const cellDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), date);
                    const dateString = cellDate.toISOString().split('T')[0];
                    
                    if (date === today.getDate() && 
                        currentDate.getMonth() === today.getMonth() && 
                        currentDate.getFullYear() === today.getFullYear()) {
                        cell.classList.add('today');
                    }
                    
                    if (date === selectedDate.getDate() && 
                        currentDate.getMonth() === selectedDate.getMonth() && 
                        currentDate.getFullYear() === selectedDate.getFullYear()) {
                        cell.classList.add('selected');
                    }
                    
                    if (bookedDates.has(dateString)) {
                        cell.classList.add('booked');
                    }
                    else if (cellDate < today) {
                        cell.classList.add('disabled-date');
                    } else {
                        cell.classList.add('selectable-date');
                    }
                    
                    date++;
                }
                
                row.appendChild(cell);
            }
            
            calendarBody.appendChild(row);
            
            if (date > daysInMonth) {
                break;
            }
        }
        
        updateSummary();
        checkDateSelection();
    }
    
    function selectDate(day) {
        const selectedDay = new Date(
            currentDate.getFullYear(),
            currentDate.getMonth(),
            day
        );

        selectedDate = selectedDay;
        updateCalendar();

        const dateString = formatDateLocal(selectedDate);
        
        console.log('=== DATE SELECTED ===');
        console.log('Selected date:', dateString);
        
        initializeTimeSlots(dateString); 
    }
    
    document.getElementById('prev-month').addEventListener('click', () => navigateMonth(-1));
    document.getElementById('next-month').addEventListener('click', () => navigateMonth(1));
    
    function navigateMonth(direction) {
        currentDate.setMonth(currentDate.getMonth() + direction);
        updateCalendar();
    }
    
    document.getElementById('calendar-body').addEventListener('click', function(e) {
        const target = e.target;
        if (target.tagName === 'TD' && target.classList.contains('selectable-date')) {
            const day = parseInt(target.getAttribute('data-date'));
            selectDate(day);
        } else if (target.tagName === 'TD' && target.classList.contains('disabled-date')) {
            alert('Tidak bisa memilih tanggal yang sudah lewat');
        } else if (target.tagName === 'TD' && target.classList.contains('booked')) {
            alert('Tanggal ini sudah fully booked. Silakan pilih tanggal lain.');
        }
    });

    function checkDateSelection() {
        const dateString = formatDateLocal(selectedDate);
        const nextButton = document.getElementById('date-next-btn');
        const alertDiv = document.getElementById('date-alert');

        if (bookedDates.has(dateString)) {
            alertDiv.style.display = 'block';
            alertDiv.textContent = 'Tanggal yang dipilih sudah fully booked. Silakan pilih tanggal lain.';
            nextButton.disabled = true;
            nextButton.classList.add('disabled');
        } else {
            alertDiv.style.display = 'none';
            nextButton.disabled = false;
            nextButton.classList.remove('disabled');
        }
    }

    function checkTimeSelection() {
        const selectedTimeSlot = document.querySelector('.time-slot.selected:not(.booked)');
        const nextButton = document.getElementById('time-next-btn');
        const alertDiv = document.getElementById('time-alert');

        if (!selectedTimeSlot) {
            alertDiv.style.display = 'block';
            alertDiv.textContent = 'Silakan pilih waktu yang tersedia.';
            nextButton.disabled = true;
            nextButton.classList.add('disabled');
        } else {
            alertDiv.style.display = 'none';
            nextButton.disabled = false;
            nextButton.classList.remove('disabled');
        }
    }

    function checkDurationSelection() {
        const selectedDuration = document.querySelector('.duration-option.selected:not(.disabled)');
        const nextButton = document.getElementById('duration-next-btn');
        const alertDiv = document.getElementById('duration-alert');

        if (!selectedDuration) {
            alertDiv.style.display = 'block';
            alertDiv.textContent = 'Durasi yang dipilih tidak tersedia untuk kombinasi tanggal dan waktu ini.';
            nextButton.disabled = true;
            nextButton.classList.add('disabled');
        } else {
            alertDiv.style.display = 'none';
            nextButton.disabled = false;
            nextButton.classList.remove('disabled');
        }
    }
    
    // ✅ FUNGSI YANG DIPERBAIKI - HAPUS FILTER STATUS
    function initializeTimeSlots(dateString) {
        const timeGrid = document.getElementById('time-grid');
        timeGrid.innerHTML = '';

        console.log('=== INIT TIME SLOTS ===');
        console.log('Date to filter:', dateString);
        console.log('Total jadwal available:', jadwalList.length);

        // ✅ PERBAIKAN: Filter HANYA berdasarkan tanggal
        // Status sudah di-filter di backend (controller hanya kirim yang Available)
        const filtered = jadwalList.filter(j => {
            const match = j.tanggal === dateString;
            console.log(`Jadwal ID ${j.id}: tanggal=${j.tanggal}, looking for=${dateString}, match=${match}`);
            return match;
        });

        console.log('Filtered slots:', filtered.length);

        if (filtered.length === 0) {
            timeGrid.innerHTML = `
                <div style="text-align: center; padding: 2rem; color: #666; grid-column: 1 / -1;">
                    <i class="fas fa-calendar-times" style="font-size: 3rem; color: #ddd; margin-bottom: 1rem;"></i>
                    <p style="font-size: 1.1rem; margin-bottom: 0.5rem; font-weight: 500;">
                        Tidak ada slot tersedia untuk tanggal ini
                    </p>
                    <small style="color: #999;">
                        Tanggal yang dipilih: ${dateString}
                    </small>
                </div>
            `;
            checkTimeSelection();
            return;
        }

        filtered.forEach(jadwal => {
            // ✅ PERBAIKAN: Gunakan waktu_mulai langsung (sudah format HH:MM dari controller)
            const start = jadwal.waktu_mulai;

            const slot = document.createElement('div');
            slot.className = 'time-slot';
            slot.dataset.jadwalId = jadwal.id;
            slot.dataset.startTime = start;

            slot.innerHTML = `
                <div class="time">${start}</div>
                <div class="price">
                    Rp ${currentVenue.price_per_hour.toLocaleString('id-ID')} / jam
                </div>
            `;

            slot.addEventListener('click', function () {
                document.querySelectorAll('.time-slot').forEach(s =>
                    s.classList.remove('selected')
                );
                this.classList.add('selected');
                updateDurationOptions();
                updateSummary();
                checkTimeSelection();
            });

            timeGrid.appendChild(slot);
        });

        checkTimeSelection();
    }

    function initializeDurationOptions() {
        document.querySelectorAll('.duration-option').forEach(option => {
            option.addEventListener('click', function() {
                if (this.classList.contains('disabled')) {
                    alert('Durasi ini tidak tersedia untuk kombinasi tanggal dan waktu yang dipilih.');
                    return;
                }
                document.querySelectorAll('.duration-option').forEach(o => {
                    o.classList.remove('selected');
                });
                this.classList.add('selected');
                updateSummary();
                checkDurationSelection();
            });
        });
    }

    function updateDurationOptions() {
        const selectedTimeSlot = document.querySelector('.time-slot.selected');
        if (!selectedTimeSlot) return;

        const selectedTime = selectedTimeSlot.querySelector('.time').textContent;
        const dateString = formatDateLocal(selectedDate);
        
        document.querySelectorAll('.duration-option').forEach(option => {
            option.classList.remove('disabled');
        });

        document.querySelectorAll('.duration-option').forEach(option => {
            const hours = parseInt(option.getAttribute('data-hours'));
            if (isDurationConflict(dateString, selectedTime, hours)) {
                option.classList.add('disabled');
                if (option.classList.contains('selected')) {
                    option.classList.remove('selected');
                }
            }
        });

        const firstAvailableDuration = document.querySelector('.duration-option:not(.disabled)');
        if (firstAvailableDuration && !document.querySelector('.duration-option.selected:not(.disabled)')) {
            firstAvailableDuration.classList.add('selected');
        }

        checkDurationSelection();
        updateSummary();
    }

    function isDurationConflict(dateString, startTime, duration) {
        const startHour = parseInt(startTime.split(':')[0]);

        for (let i = 0; i < duration; i++) {
            const checkHour = startHour + i;
            const checkTime = `${checkHour.toString().padStart(2, '0')}:00`;

            const key = `${dateString}_${checkTime}`;
            if (bookedTimeSlots[key]) {
                return true;
            }
        }
        return false;
    }
    
    function updateSummary() {
        const dayNames = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
        const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", 
                           "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        
        document.getElementById('summary-date').textContent = 
            `${dayNames[selectedDate.getDay()]}, ${selectedDate.getDate()} ${monthNames[selectedDate.getMonth()]} ${selectedDate.getFullYear()}`;
        
        const selectedTimeSlot = document.querySelector('.time-slot.selected');
        if (selectedTimeSlot) {
            const startTime = selectedTimeSlot.dataset.startTime;
            const hours = parseInt(
                document.querySelector('.duration-option.selected')?.dataset.hours ?? 0
            );

            if (hours > 0) {
                const endHour = parseInt(startTime.split(':')[0]) + hours;
                const endTime = `${endHour.toString().padStart(2,'0')}:00`;

                document.getElementById('summary-time').textContent =
                    `${startTime} - ${endTime}`;
            }
        }
        
        const selectedDuration = document.querySelector('.duration-option.selected');
        if (selectedDuration && !selectedDuration.classList.contains('disabled')) {
            const duration = selectedDuration.textContent;
            document.getElementById('summary-duration').textContent = duration;
            
            const hours = parseInt(selectedDuration.getAttribute('data-hours'));
            const totalPrice = currentVenue.price_per_hour * hours;
            document.getElementById('summary-total').textContent = `Rp ${totalPrice.toLocaleString('id-ID')}`;
        }
    }
    

// ================================================================
// REPLACE FUNCTION proceedToPayment() DI pembayaran.blade.php
// Cari line ~2061 dan ganti seluruh function dengan ini
// ================================================================

function proceedToPayment() {
    console.log('🚀 === PROCEED TO PAYMENT CALLED ===');
    
    const button = document.getElementById('payment-button');
    if (!button) {
        console.error('❌ ERROR: Payment button not found!');
        alert('Error: Tombol pembayaran tidak ditemukan!');
        return;
    }
    
    const originalText = button.innerHTML;
    
    // Tampilkan loading
    button.innerHTML = '<span class="loading"></span> Memproses...';
    button.disabled = true;
    
    try {
        // Validasi: pastikan waktu dan durasi sudah dipilih
        const selectedTimeSlot = document.querySelector('.time-slot.selected');
        const selectedDuration = document.querySelector('.duration-option.selected');
        
        console.log('🔍 Selected time slot:', selectedTimeSlot);
        console.log('🔍 Selected duration:', selectedDuration);
        
        if (!selectedTimeSlot) {
            console.error('❌ No time slot selected!');
            alert('Silakan pilih waktu terlebih dahulu');
            button.innerHTML = originalText;
            button.disabled = false;
            return;
        }
        
        if (!selectedDuration) {
            console.error('❌ No duration selected!');
            alert('Silakan pilih durasi terlebih dahulu');
            button.innerHTML = originalText;
            button.disabled = false;
            return;
        }
        
        if (selectedDuration.classList.contains('disabled')) {
            console.error('❌ Selected duration is disabled!');
            alert('Durasi yang dipilih tidak valid');
            button.innerHTML = originalText;
            button.disabled = false;
            return;
        }
        
        // ✅ Ambil data dari pilihan user
        const waktu = selectedTimeSlot.dataset.startTime;
        const durasi = parseInt(selectedDuration.dataset.hours);
        
        console.log('📊 Waktu:', waktu);
        console.log('📊 Durasi:', durasi);
        console.log('📊 selectedDate variable:', selectedDate);
        console.log('📊 selectedDate type:', typeof selectedDate);
        
        if (!selectedDate) {
            console.error('❌ selectedDate is undefined!');
            alert('Error: Tanggal tidak valid!');
            button.innerHTML = originalText;
            button.disabled = false;
            return;
        }
        
        // Format tanggal
        let tanggal;
        try {
            // Jika selectedDate adalah string, convert ke Date object
            if (typeof selectedDate === 'string') {
                const dateObj = new Date(selectedDate + 'T00:00:00');
                tanggal = formatDateLocal(dateObj);
            } else if (selectedDate instanceof Date) {
                tanggal = formatDateLocal(selectedDate);
            } else {
                throw new Error('selectedDate format tidak valid: ' + typeof selectedDate);
            }
            
            console.log('✅ Formatted tanggal:', tanggal);
        } catch (e) {
            console.error('❌ ERROR formatting date:', e);
            alert('Error: Gagal memformat tanggal! ' + e.message);
            button.innerHTML = originalText;
            button.disabled = false;
            return;
        }
        
        console.log('📋 === FINAL BOOKING DATA ===');
        console.log('Venue ID:', {{ $venue->id }});
        console.log('Tanggal:', tanggal);
        console.log('Waktu:', waktu);
        console.log('Durasi:', durasi);
        
        // ✅ Set form values
        document.getElementById('form-tanggal-booking').value = tanggal;
        document.getElementById('form-waktu-booking').value = waktu;
        document.getElementById('form-durasi').value = durasi;
        
        // DEBUG: Cek form values setelah di-set
        console.log('📝 === FORM VALUES AFTER SET ===');
        console.log('venue_id:', document.querySelector('input[name="venue_id"]').value);
        console.log('tanggal_booking:', document.getElementById('form-tanggal-booking').value);
        console.log('waktu_booking:', document.getElementById('form-waktu-booking').value);
        console.log('durasi:', document.getElementById('form-durasi').value);
        
        // ✅ Submit form ke backend
        const form = document.getElementById('booking-form');
        if (!form) {
            console.error('❌ Form not found!');
            alert('Error: Form tidak ditemukan!');
            button.innerHTML = originalText;
            button.disabled = false;
            return;
        }
        
        console.log('🔗 Form action:', form.action);
        console.log('🔗 Form method:', form.method);
        console.log('🚀 Submitting form...');
        
        // Submit!
        form.submit();
        
        console.log('✅ Form submitted successfully!');
        
    } catch (error) {
        console.error('💥 FATAL ERROR in proceedToPayment:', error);
        console.error('Stack trace:', error.stack);
        alert('Error: ' + error.message);
        button.innerHTML = originalText;
        button.disabled = false;
    }
}





    document.getElementById('payment-button').addEventListener('click', proceedToPayment);

    function handleResponsiveLayout() {
        const isMobile = window.innerWidth <= 768;
        
        if (isMobile) {
            const timeGrid = document.getElementById('time-grid');
            if (timeGrid) {
                timeGrid.style.gridTemplateColumns = 'repeat(3, 1fr)';
            }
            
            const calendarContainer = document.querySelector('.calendar-container');
            if (calendarContainer) {
                calendarContainer.style.overflowX = 'auto';
            }
        } else {
            const timeGrid = document.getElementById('time-grid');
            if (timeGrid) {
                timeGrid.style.gridTemplateColumns = '';
            }
            
            const calendarContainer = document.querySelector('.calendar-container');
            if (calendarContainer) {
                calendarContainer.style.overflowX = '';
            }
        }
    }

    function setViewportHeight() {
        let vh = window.innerHeight * 0.01;
        document.documentElement.style.setProperty('--vh', `${vh}px`);
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        console.log('=== DOM LOADED ===');
        
        updateCalendar();
        
        const todayString = selectedDate.toISOString().split('T')[0];
        console.log('Initializing with date:', todayString);
        
        initializeTimeSlots(todayString);
        initializeDurationOptions();
        handleResponsiveLayout();
        setViewportHeight();
        
        let resizeTimer;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => {
                handleResponsiveLayout();
                setViewportHeight();
                updateCalendar();
            }, 250);
        });
        
        document.addEventListener('touchstart', function() {}, {passive: true});
        window.addEventListener('resize', setViewportHeight);
            });

            // Helper function untuk format tanggal (sudah ada di code, pastikan ada)
            function formatDateLocal(date) {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            }

            // ========================================
// PATCH JAVASCRIPT - Tambahkan di pembayaran.blade.php
// Letakkan SEBELUM function proceedToPayment()
// ========================================

// DEBUG: Cek elemen-elemen penting
console.log('=== DEBUGGING FORM ELEMENTS ===');
console.log('Form exists:', document.getElementById('booking-form') !== null);
console.log('Button exists:', document.getElementById('payment-button') !== null);
console.log('Tanggal input exists:', document.getElementById('form-tanggal-booking') !== null);
console.log('Waktu input exists:', document.getElementById('form-waktu-booking') !== null);
console.log('Durasi input exists:', document.getElementById('form-durasi') !== null);

// REPLACE function proceedToPayment dengan yang ini:
function proceedToPayment() {
    console.log('=== PROCEED TO PAYMENT CALLED ===');
    
    const button = document.getElementById('payment-button');
    if (!button) {
        console.error('ERROR: Payment button not found!');
        alert('Error: Tombol pembayaran tidak ditemukan!');
        return;
    }
    
    const originalText = button.innerHTML;
    
    // Tampilkan loading
    button.innerHTML = '<span class="loading"></span> Memproses...';
    button.disabled = true;
    
    // Validasi: pastikan waktu dan durasi sudah dipilih
    const selectedTimeSlot = document.querySelector('.time-slot.selected');
    const selectedDuration = document.querySelector('.duration-option.selected');
    
    console.log('Selected time slot:', selectedTimeSlot);
    console.log('Selected duration:', selectedDuration);
    
    if (!selectedTimeSlot) {
        console.error('ERROR: No time slot selected!');
        alert('Silakan pilih waktu terlebih dahulu');
        button.innerHTML = originalText;
        button.disabled = false;
        return;
    }
    
    if (!selectedDuration) {
        console.error('ERROR: No duration selected!');
        alert('Silakan pilih durasi terlebih dahulu');
        button.innerHTML = originalText;
        button.disabled = false;
        return;
    }
    
    if (selectedDuration.classList.contains('disabled')) {
        console.error('ERROR: Selected duration is disabled!');
        alert('Durasi yang dipilih tidak valid');
        button.innerHTML = originalText;
        button.disabled = false;
        return;
    }
    
    // ✅ Ambil data dari pilihan user
    const waktu = selectedTimeSlot.dataset.startTime; // Format: "08:00"
    const durasi = parseInt(selectedDuration.dataset.hours); // Integer: 1, 2, 3, dll
    
    console.log('selectedDate variable:', selectedDate);
    console.log('selectedDate type:', typeof selectedDate);
    
    if (!selectedDate) {
        console.error('ERROR: selectedDate is undefined!');
        alert('Error: Tanggal tidak valid!');
        button.innerHTML = originalText;
        button.disabled = false;
        return;
    }
    
    let tanggal;
    try {
        tanggal = formatDateLocal(selectedDate); // Format: "2025-02-10"
        console.log('Formatted tanggal:', tanggal);
    } catch (e) {
        console.error('ERROR formatting date:', e);
        alert('Error: Gagal memformat tanggal!');
        button.innerHTML = originalText;
        button.disabled = false;
        return;
    }
    
    console.log('=== FINAL BOOKING DATA ===');
    console.log('Venue ID:', {{ $venue->id }});
    console.log('Tanggal:', tanggal);
    console.log('Waktu:', waktu);
    console.log('Durasi:', durasi);
    
    // ✅ Set form values (TANPA jadwal_id)
    document.getElementById('form-tanggal-booking').value = tanggal;
    document.getElementById('form-waktu-booking').value = waktu;
    document.getElementById('form-durasi').value = durasi;
    
    // DEBUG: Cek form values setelah di-set
    console.log('=== FORM VALUES AFTER SET ===');
    console.log('venue_id:', document.querySelector('input[name="venue_id"]').value);
    console.log('tanggal_booking:', document.getElementById('form-tanggal-booking').value);
    console.log('waktu_booking:', document.getElementById('form-waktu-booking').value);
    console.log('durasi:', document.getElementById('form-durasi').value);
    
    // ✅ Submit form ke backend
    console.log('Submitting form...');
    
    const form = document.getElementById('booking-form');
    if (!form) {
        console.error('ERROR: Form not found!');
        alert('Error: Form tidak ditemukan!');
        button.innerHTML = originalText;
        button.disabled = false;
        return;
    }
    
    console.log('Form action:', form.action);
    console.log('Form method:', form.method);
    
    // Submit!
    form.submit();
    
    console.log('Form submitted!');
}

</script>

@endsection