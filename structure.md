clinical-system/
├── app/
│   ├── Models/
│   │   ├── Patient.php
│   │   ├── TreatmentChart.php
│   │   ├── AntenatalRecord.php
│   │   ├── User.php
│   │   ├── Invoice.php
│   │   ├── Payment.php
│   │   ├── Medication.php
│   │   ├── LabTest.php
│   │   └── Vital.php
│   │
│   ├── Services/
│   │   ├── PatientService.php
│   │   ├── TreatmentService.php
│   │   ├── AntenatalService.php
│   │   ├── FinanceService.php
│   │   ├── ReportService.php
│   │   ├── ComplianceService.php
│   │   ├── NotificationService.php
│   │   └── FileService.php
│   │
│   ├── Repositories/
│   │   ├── BaseRepository.php
│   │   ├── PatientRepository.php
│   │   ├── TreatmentRepository.php
│   │   ├── InvoiceRepository.php
│   │   └── AntenatalRepository.php
│   │
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── PatientController.php
│   │   │   ├── TreatmentController.php
│   │   │   ├── AntenatalController.php
│   │   │   ├── FinanceController.php
│   │   │   ├── ReportController.php
│   │   │   ├── DashboardController.php
│   │   │   └── AuthController.php
│   │   ├── Requests/
│   │   │   ├── StorePatientRequest.php
│   │   │   ├── StoreTreatmentChartRequest.php
│   │   │   ├── StoreAntenatalRequest.php
│   │   │   └── StoreInvoiceRequest.php
│   │   └── Resources/
│   │       ├── PatientResource.php
│   │       ├── TreatmentResource.php
│   │       └── InvoiceResource.php
│   │
│   ├── Traits/
│   │   ├── HasFileNumber.php
│   │   ├── HasAuditTrail.php
│   │   ├── HasCompliance.php
│   │   └── HasTimestamps.php
│   │
│   ├── Exceptions/
│   │   ├── PatientNotFoundException.php
│   │   ├── TreatmentException.php
│   │   └── ComplianceException.php
│   │
│   ├── Enums/
│   │   ├── UserRole.php
│   │   ├── AccountType.php
│   │   ├── TreatmentCategory.php
│   │   └── ComplianceStatus.php
│   │
│   └── Jobs/
│       ├── GenerateComplianceReport.php
│       ├── SendComplianceAlert.php
│       └── ProcessInvoicePayment.php
│
├── database/
│   ├── migrations/
│   │   ├── 2024_01_01_000001_create_users_table.php
│   │   ├── 2024_01_01_000002_create_patients_table.php
│   │   ├── 2024_01_01_000003_create_treatment_charts_table.php
│   │   ├── 2024_01_01_000004_create_antenatal_records_table.php
│   │   ├── 2024_01_01_000005_create_vitals_table.php
│   │   ├── 2024_01_01_000006_create_medications_table.php
│   │   ├── 2024_01_01_000007_create_lab_tests_table.php
│   │   ├── 2024_01_01_000008_create_invoices_table.php
│   │   ├── 2024_01_01_000009_create_payments_table.php
│   │   ├── 2024_01_01_000010_create_compliance_logs_table.php
│   │   └── 2024_01_01_000011_create_audit_logs_table.php
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── UserSeeder.php
│       └── PatientSeeder.php
│
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   ├── app.blade.php
│   │   │   ├── auth.blade.php
│   │   │   └── sidebar.blade.php
│   │   ├── dashboard/
│   │   │   └── index.blade.php
│   │   ├── patients/
│   │   │   ├── index.blade.php
│   │   │   ├── create.blade.php
│   │   │   ├── show.blade.php
│   │   │   └── edit.blade.php
│   │   ├── treatments/
│   │   │   ├── index.blade.php
│   │   │   ├── create.blade.php
│   │   │   ├── show.blade.php
│   │   │   └── compliance.blade.php
│   │   ├── antenatal/
│   │   │   ├── index.blade.php
│   │   │   ├── create.blade.php
│   │   │   └── partograph.blade.php
│   │   ├── finance/
│   │   │   ├── invoices.blade.php
│   │   │   ├── payments.blade.php
│   │   │   └── reports.blade.php
│   │   ├── reports/
│   │   │   ├── daily.blade.php
│   │   │   ├── treatment.blade.php
│   │   │   ├── compliance.blade.php
│   │   │   └── financial.blade.php
│   │   └── auth/
│   │       ├── login.blade.php
│   │       └── register.blade.php
│   └── css/
│       └── app.css
│
├── routes/
│   ├── web.php
│   ├── api.php
│   ├── auth.php
│   └── admin.php
│
├── config/
│   ├── app.php
│   ├── database.php
│   └── services.php
│
├── tests/
│   ├── Unit/
│   │   ├── Services/
│   │   │   ├── PatientServiceTest.php
│   │   │   ├── TreatmentServiceTest.php
│   │   │   └── FinanceServiceTest.php
│   │   └── Models/
│   │       └── PatientTest.php
│   └── Feature/
│       ├── PatientManagementTest.php
│       ├── TreatmentChartTest.php
│       └── FinanceTest.php
│
├── storage/
│   ├── app/
│   │   ├── patients/ (photos)
│   │   ├── receipts/
│   │   ├── lab_results/
│   │   └── invoices/
│   └── logs/
│
├── .env
├── .env.example
├── composer.json
├── artisan
├── README.md
└── docker-compose.yml (optional)