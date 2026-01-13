# Planogolny.info — Technical Stack Overview

This document describes the technologies used in the project and the reasons behind their selection.

---

# 1. Backend — Laravel 12

Laravel serves as the application layer for routing, authentication, controllers, queues, and integration with domain modules.

### Why Laravel?
- Clean architecture friendly
- Excellent developer experience
- Built-in authentication (Breeze)
- Native support for queues, events, jobs
- First-class integration with Vue via Inertia

---

# 2. Frontend — Inertia.js + Vue 3 + TailwindCSS

### Why Inertia?
- SPA-like experience without maintaining a separate API
- Faster development
- Perfect for domain-heavy apps
- Extremely clean architecture

### Why Vue 3?
- Powerful composition API
- Lightweight and reactive
- Great ecosystem

### Why TailwindCSS?
- Fast design iteration
- Consistent UI
- Utility-first for custom layouts

---

# 3. Build System — Vite 6

### Benefits:
- Fast reload
- Easy Vue + Tailwind integration
- Modern ES module support

---

# 4. GIS Integrations

### External APIs:
- **Google Maps Geocoding** → parcel lookup
- **OSM Overpass API** → buildings, land use
- **GUGIK WMS / Geoportal** → parcel geometry
- **GUS / BDL** → demographic trends

These are abstracted behind the `/GIS` module.

---

# 5. Payments (Stripe / TPay)

### Why not Laravel Cashier?
- Cashier is designed for subscriptions
- This project uses **one-time payments**
- Stripe Checkout or PaymentIntent fits the use case
- A custom Payments module is cleaner and more flexible

### Payment Flow:
1. User enters parcel → Analysis preview
2. User pays (Stripe/TPay checkout)
3. Backend receives webhook
4. Orders module creates order record
5. Reporting module generates PDF
6. Email with report is sent

## 5.1 Integration with ING Accounting (Automatic Invoice Generation)

The application will integrate with **ING Księgowość (ING Accounting)** to automate
the invoicing process after a successful payment.

The integration uses two official ING API endpoints:

1. **Invoice Creation** – sends customer data, product details, and order metadata
2. **Invoice PDF Retrieval** – downloads the generated invoice document, which is then:

    - attached to the email containing the report,
    - optionally stored in the client’s accounting/archival folder (e.g., Google Drive).

The invoicing workflow is triggered immediately after the payment module confirms the order:

This functionality is implemented in a dedicated module:  
`packages/Invoicing`.
---

# 6. PDF Generation

Likely technologies:
- DOMPDF
- Snappy / wkhtmltopdf
- Browsershot (Headless Chrome)

PDF generation lives entirely in `/Reporting`.

---

# 7. Clean Architecture Principles

- Domain logic in `packages/*`
- Controllers are orchestration only
- DTOs ensure strict data passing
- Actions represent use cases
- No business logic in controllers/views

---

# 8. Development Tools (recommended)

- Laravel Pint (code style)
- Laravel Telescope (debugging)
- Larastan (static analysis)
- PHPUnit (tests)
- Pest (alternative testing framework)

---

# 9. Environment Requirements

- PHP 8.3+
- Node 18+
- Vite 6.x
- Tailwind 3.x
- MySQL/PostgreSQL
- Redis (optional — queues, caching)
- Supervisor (queue workers)

---

# 10. Summary

The chosen stack is optimized for:

- Rapid development
- Clean, modular design
- High performance
- Easy maintenance
- Strong portfolio value

This stack provides a solid foundation for GIS analysis, scoring logic, and real-time or batch PDF generation.

## Optional Event-Driven Components

The application may use a lightweight event-driven workflow to orchestrate
payment confirmation, report generation, and email notification. This includes:

- OrderPaid
- ReportGenerationRequested
- ReportGenerated

These events provide clean separation of responsibilities without introducing
unnecessary complexity.
