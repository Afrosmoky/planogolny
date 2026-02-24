Planogolny.info – GIS-Based Parcel Analysis Platform

What It Does

Planogolny.info is a Laravel-based web application that analyzes land parcels in Poland using GIS data, demographic information, and a rules-based scoring engine.

## Use Case

Planogolny.info helps users analyze land parcels before purchase or development.

Typical scenario:
- User enters parcel ID or address
- System fetches GIS + demographic data
- Payment is processed (one-time)
- Report is generated and delivered via email

The system is designed for one-time transactional usage without user accounts.

⸻

Why This Project Is Interesting

This project demonstrates:  
•	Modular monolith architecture  
•	Clean separation of domain modules  
•	Advanced external API orchestration (GIS + Demography)  
•	Payment gateway abstraction (TPay)  
•	Event-driven pipeline  
•	Clean DTO + Action pattern usage

⸻

Architecture Overview  
•	Laravel 12  
•	Modular monolith (packages/)  
•	DTO + Action pattern  
•	Strategy pattern (payment gateways)  
•	Facade pattern (GIS & invoicing APIs)  
•	Minimal event-driven workflow

## Detailed Documentation

Detailed technical documentation is available in the `/docs` directory:

- [Architecture Overview](docs/architecture_overview.md)
- [Modules Overview](docs/modules_overview.md)
- [Backend Architecture Guide](docs/Planogolny_Backend_Architecture_Guide.md)
- [Technical Stack](docs/technical_stack.md)

⸻

End-to-End Flow

User → Payment → OrderPaid → Invoice → PDF Report → Email

Each step is isolated and testable.

Tech Stack

Backend:  
•	Laravel 12  
•	Modular domain packages  
•	TPay  
•	ING Accounting API

Frontend:  
•	Inertia.js  
•	Vue 3  
•	TailwindCSS

External APIs:  
•	Google Maps  
•	OSM Overpass  
•	GUS / BDL


My Role  
•	Backend architecture design  
•	Modular system structure  
•	Payment & invoice integration  
•	Domain logic implementation  
•	End-to-end pipeline design

## Payment Integration

The system integrates with TPay for one-time payment processing.

Flow:
- Payment session is created
- Webhook verifies transaction status
- Order is marked as paid
- Invoice and report generation are triggered

The payment logic is isolated inside the Payments module to keep domain logic clean.

## Observability & Logging

The system logs:
- external API failures (GIS providers)
- payment verification events
- invoice creation results

This allows easier monitoring and debugging in production environments.
