# 🏟️ CariArena

CariArena is a web-based sports venue booking platform that connects customers with sports venue owners through a centralized booking system.

The platform enables users to discover sports venues, check real-time availability, make online reservations, manage booking history, submit reviews, and receive notifications. Venue owners can manage their venues, schedules, bookings, and reports through a dedicated dashboard, while administrators oversee the entire platform ecosystem.

---

## ✨ Key Features

### Customer Features

- User registration and authentication
- Email verification
- Password reset
- Venue discovery and search
- Real-time schedule availability
- Online booking system
- Booking history management
- Booking cancellation
- Venue reviews and ratings
- Account management
- Notification center

### Venue Owner Features

- Owner dashboard
- Venue management (CRUD)
- Schedule management
- Incoming booking management
- Review management
- Business reports and analytics
- Profile and settings management

### Administrator Features

- Admin dashboard
- Venue moderation
- Booking monitoring
- Transaction monitoring
- User management
- Reporting and analytics
- System configuration

---

## 🏗️ System Architecture

```text
Customer
    │
    ▼
Laravel Application
    │
    ├── Authentication & Authorization
    ├── Booking Engine
    ├── Payment Processing
    ├── Review System
    ├── Notification System
    │
    ├── PostgreSQL Database
    ├── Supabase Storage
    └── Email Service
```

---

## 🛠️ Technology Stack

### Backend

- PHP 8.3
- Laravel 12
- PostgreSQL
- Laravel Scheduler
- Laravel Queue

### Frontend

- Blade Templates
- Bootstrap
- JavaScript
- AJAX

### Authentication & Authorization

- Laravel Authentication
- Email Verification
- Password Reset
- Spatie Laravel Permission

### Storage & Media

- Supabase Storage

### Infrastructure

- Docker
- Render
- Neon PostgreSQL

---

## 📂 Core Modules

### Authentication Module

- Login
- Registration
- Email Verification
- Password Reset
- Role-Based Access Control

### Venue Management Module

- Create Venue
- Update Venue
- Delete Venue
- Venue Gallery
- Venue Status Management

### Booking Module

- Venue Booking
- Schedule Validation
- Booking Confirmation
- Booking Cancellation

### Review Module

- Venue Ratings
- Customer Reviews
- Review History

### Transaction Module

- Payment Recording
- Transaction History
- Financial Reporting

---

## 🗄️ Database Overview

Main Entities:

```text
users
venues
jadwal
booking
transactions
reviews
notifications
admins
customers
```

Relationship Overview:

```text
User
 ├── Venues
 ├── Bookings
 └── Reviews

Venue
 ├── Schedules
 ├── Bookings
 └── Reviews

Booking
 ├── User
 ├── Venue
 ├── Schedule
 └── Transaction
```

---

## 📸 Media Storage Structure

### Profile Photos

```text
profile-photos/
├── owners/
└── users/
```

### Venue Photos

```text
venues/
```

Venue images are stored in Supabase Storage. The system automatically provides fallback images when no custom image is available.

---

## 🔐 User Roles

### Customer

- Search venues
- Create bookings
- Manage account
- Submit reviews

### Venue Owner

- Manage venues
- Manage schedules
- Manage bookings
- Access reports

### Administrator

- Manage users
- Manage venues
- Monitor bookings
- Monitor transactions
- Configure platform settings

---

## 🌐 Main Routes

### Customer

```text
/beranda
/pesan
/riwayat
/akun
/notifikasi
```

### Venue Owner

```text
/venue/dashboard
/venue/venue-saya
/venue/jadwal
/venue/booking-masuk
/venue/ulasan
/venue/reports
/venue/pengaturan
```

### Administrator

```text
/admin/dashboard
/admin/venues
/admin/pemesanan
/admin/transaksi
/admin/laporan
/admin/pengguna
/admin/pengaturan
```

---

## ⚙️ Local Development Setup

### Clone Repository

```bash
git clone https://github.com/yourusername/cariarena.git
cd cariarena
```

### Install Dependencies

```bash
composer install
```

### Create Environment File

```bash
cp .env.example .env
```

### Configure Database

```env
DB_CONNECTION=pgsql
DB_HOST=
DB_PORT=
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```

### Generate Application Key

```bash
php artisan key:generate
```

### Run Database Migration

```bash
php artisan migrate
```

### Start Development Server

```bash
php artisan serve
```

---

## 🐳 Docker Deployment

### Build Image

```bash
docker build -t cariarena .
```

### Run Container

```bash
docker run -p 8000:8000 cariarena
```

---

## 🚀 Production Stack

The current production environment uses:

- Render (Application Hosting)
- Neon PostgreSQL (Database)
- Supabase Storage (File Storage)
- Custom Domain Configuration
- Email Verification & Password Reset System

---

## 📋 Known Issues

### High Priority

- Inconsistent admin fee calculation logic
- Duplicate payment handling implementation
- Legacy registration file outside Laravel architecture
- Incorrect Review model relationship reference

### Medium Priority

- Notification module still uses mock data
- Transaction trend queries contain MySQL-specific functions
- Historical venue naming dependency in transactions

### Low Priority

- Incomplete Lapangan module
- Hardcoded slot availability logic

---

## 🛣️ Future Roadmap

- Payment Gateway Integration
- Real-Time Booking Updates
- Dynamic Notification System
- Mobile Experience Improvements
- Venue Analytics Dashboard
- AI-Based Venue Recommendations
- Multi-City Expansion

---
