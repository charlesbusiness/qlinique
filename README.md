<p align="center"><strong style="font-size: 2rem;">Klinical</strong></p>

<p align="center">Clinical Facility Management System</p>

<p align="center">
A complete electronic health record (EHR) and clinic management system built for
clinics in low-resource settings. Manages patients, treatment charts, antenatal
care, billing, compliance tracking, and reporting.
</p>

---

## Features

### Patient Management
- Patient registration with auto-generated file numbers (`FAC-YYYY-NNNNN`)
- Support for Individual, Family, and Corporate account types
- Next-of-kin and consent tracking, patient photo uploads
- Search by name, file number, or phone

### Treatment Charts
- Per-visit charts categorized as Checkup, Treatment, or Emergency
- Vitals recording (temperature, BP, pulse, respiratory rate, weight, height, BMI, SpO₂)
- Primary and secondary diagnoses with clinical notes
- Lab test prescriptions with findings and attachments
- Medication management with dosage, quantity, and cost calculation
- First aid tracking and treatment schedules (e.g. `3/7`, `5/7`, `7/7`)

### Antenatal Care
- Antenatal records with EDD, gestation weeks, obstetric history, and risk levels
- Partograph management (cervical dilation, fetal heart rate, labour progress)

### Compliance Monitoring
- Daily attendance tracking (attended / missed / excused)
- Compliance percentage calculation and missed-session alerts
- Compliance reports with >75% threshold identification

### Billing & Finance
- Invoice generation with auto-numbering (`INV-YYYY-NNNNN`)
- Payment recording (cash, card, mobile money, bank transfer)
- Auto-generated receipt numbers and balance tracking

### Reports
- Daily reports (new patients, treatments, emergencies, revenue)
- Treatment reports (by category and completion stats)
- Compliance reports (active treatments, compliant vs. non-compliant)
- Financial reports (total invoiced, collected, outstanding)

### User Management & Security
- Role-based access control with 6 roles: Super Admin, Matron, Doctor, Nurse, Receptionist, Accountant
- Permission matrix via `config/permissions.php`
- Forced password change on first login
- Full audit trail on all major entities

---

## Technology Stack

| Layer | Technology |
|---|---|
| Backend | PHP 8.2, Laravel 12 |
| Frontend | Blade, Bootstrap 5, Livewire 4 |
| Database | MySQL |
| Build | Vite |
| Auth | Laravel Breeze |

---

## Setup

```bash
# Install dependencies and run migrations
composer run setup

# Configure your database in .env, then create a super admin
php artisan make:super-user

# Start development server
composer run dev
```

The `setup` script runs `composer install`, creates `.env` from `.env.example`, generates an app key, and runs migrations.

---

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
