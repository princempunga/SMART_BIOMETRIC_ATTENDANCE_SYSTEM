# SmartAttend: Biometric Attendance & Real-time Management System

SmartAttend is a premium, enterprise-grade attendance management system built with Laravel 12. It integrates biometric hardware (ESP32) with a sophisticated web-based management portal for Admins, Deans, and Lecturers.

## 🚀 Key Features

*   **Multi-Role Dashboard**: Tailored experiences for Admins, Faculty Deans, and Lecturers.
*   **Real-time Synchronization**: Live tracking of student attendance via biometric devices.
*   **Course & Unit Management**: Faculty-scoped management of courses, units, and timetables.
*   **Automated Analytics**: Detailed attendance rate analysis and eligibility tracking.
*   **Secure Biometrics**: Integration with ESP32 and fingerprint sensors for spoof-proof attendance.
*   **Professional PDF Reports**: Generate and download semester-wide attendance analysis.

## 🛠 Tech Stack

*   **Backend**: Laravel 12, PHP 8.2+
*   **Frontend**: Vanilla CSS (Tailwind-compatible styling), Blade, Vite
*   **Database**: MySQL / PostgreSQL
*   **Hardware**: ESP32, Fingerprint Sensors (C++ / Arduino)

## 📦 Project Structure

```text
├── app/                # Core logic (Controllers, Models, Middleware)
├── hardware/           # ESP32/Arduino source code (.ino)
├── resources/          # UI components and views
├── routes/             # Web and API routing
├── tools/scripts/      # Internal utility and generation scripts
└── storage/            # Logs and local file storage
```

## ⚙️ Quick Start

1.  **Clone & Install**:
    ```bash
    git clone https://github.com/princempunga/SMART_BIOMETRIC_ATTENDANCE_SYSTEM.git
    cd SMART_BIOMETRIC_ATTENDANCE_SYSTEM
    composer install
    npm install
    ```

2.  **Environment Setup**:
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

3.  **Database Migration**:
    ```bash
    php artisan migrate --seed
    ```

4.  **Run Development Server**:
    ```bash
    npm run dev
    # In a separate terminal
    php artisan serve
    ```

## 🔒 Security & Resilience

This project implements **Resilient Attendance Architecture**, ensuring that even in intermittent network conditions, biometric data is captured locally and synchronized in real-time when the connection is restored.

---
© 2026 SmartAttend System. All rights reserved.
