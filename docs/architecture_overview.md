# Planogolny.info — Architecture Overview

## 1. System Description

Planogolny.info is a web application that analyzes parcels in Poland using GIS data, demographic data, and a rules-based scoring engine. Users enter an address or parcel number, purchase a report, and receive a generated PDF with analysis and recommendations.

The system is structured as a **modular monolith**, where each domain module is isolated inside `packages/`, while Laravel handles web delivery and Inertia/Vue drives the presentation layer.

The architecture favors:
- Clear separation of concerns
- Maintainability and scalability
- Clean code and clean architecture principles
- Independence between domain modules
- Compatibility with external GIS APIs

---

## 2. High-Level Architecture

┌───────────────────────────────────────────────────────────┐
│ Frontend (SPA) │
│ Inertia.js + Vue 3 + TailwindCSS │
│ - User address input │
│ - Report preview │
│ - Payment flow UI │
└───────────────────────────────────────────────────────────┘
▲                        ▲
│                        │ Inertia responses
HTTP Requests │ │ JSON data/props
│ ▼
┌───────────────────────────────────────────────────────────┐
│ Laravel App │
│ - Routing (web + API) │
│ - Authentication (Breeze) │
│ - Controllers dispatching domain actions │
└───────────────────────────────────────────────────────────┘

           ▼
┌───────────────────────────────────────────────────────────┐
│ Modular Monolith: packages/ │
│ │
│ /Analysis → Scoring engine, parcel evaluation │
│ /GIS → Integrations (OSM, Google, Geoportal) │
│ /Payments → Stripe/Tpay payment handling │
│ /Reporting → PDF generation + email delivery │
│ /Orders → Order records, transaction logs │
│ │
└───────────────────────────────────────────────────────────┘
▼
┌───────────────────────────────────────────────────────────┐
│ External Services │
│ - Google Maps Geocoding │
│ - OSM Overpass API │
│ - Geoportal WMS / GUGIK │
│ - GUS / BDL demography API │
│ - Stripe / Tpay payment gateway │
└───────────────────────────────────────────────────────────┘

## 3. Domain Events (Optional Orchestration Layer)

The system may optionally use a minimal event-driven workflow to decouple the
payment, reporting, and order lifecycle. While not required for the core
functionality, the following events provide a clean architectural separation:

- **OrderPaid** – published after payment confirmation
- **ReportGenerationRequested** – signals that a report should be generated
- **ReportGenerated** – dispatched after the PDF is created

These events keep the processing pipeline modular and easy to extend without
overcomplicating the system.
---

## 4. Key Architectural Decisions

### 4.1 Modular Monolith
Each domain module lives in `packages/<ModuleName>` and contains:
- Actions (use cases)
- DTOs (data transfer objects)
- Services (business logic)
- Models (domain models)
- Providers (module bootstrap)
- Optional: routes, policies, events

The application layer (controllers, Inertia pages) never contains domain logic.

---

## 4.2 Frontend Architecture
- Inertia.js provides an SPA-like architecture without a separate API layer.
- Vue 3 is used for dynamic UI (map preview, report progress, etc.).
- TailwindCSS ensures fast styling and consistent UI.

---

## 4.3 Payment Flow
- Stripe Checkout or TPay handled through module `/Payments`
- No Laravel Cashier (Cashier is for subscriptions)
- Clean abstraction layer for switching providers

---

## 4.4 GIS Layer
GIS integrations are isolated in `/GIS` to keep external API logic separate from domain logic.  
This allows:
- Independent testing
- Ability to swap GIS providers
- Central place for caching and fallback logic

---

## 5. Future Extensions
- Real-time job progress using WebSockets (optional)
- Admin panel (Filament) for managing orders
- Advanced ML-based parcel classification

---

## 6. Summary
This architecture is optimized for:
- Clean maintainability
- Extensibility
- Demonstration value (portfolio-grade)
- Clear domain separation
- High performance in GIS-heavy environments  

### Invoicing Module (ING Accounting Integration)

The system includes an **Invoicing** module responsible for handling integration with
the ING Accounting platform.

This module performs:

- creating an invoice after a successful payment (via ING API),
- retrieving the generated invoice PDF,
- passing the invoice document to the Reporting module,
- storing the invoice for bookkeeping purposes (optional archive integration).

The invoicing pipeline fits naturally between the Payments and Reporting modules:
