Planogolny.info – GIS-Based Parcel Analysis Platform

What It Does

Planogolny.info is a Laravel-based web application that analyzes land parcels in Poland using GIS data, demographic information, and a rules-based scoring engine.

Users:
1.	Enter an address or parcel number
2.	Purchase a detailed report
3.	Receive a generated PDF with recommendations

⸻

Why This Project Is Interesting

This project demonstrates:
•	Modular monolith architecture
•	Clean separation of domain modules
•	Advanced external API orchestration (GIS + Demography)
•	Payment gateway abstraction (Stripe / TPay)
•	Event-driven invoice + reporting pipeline
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
•	Stripe / TPay
•	ING Accounting API

Frontend:
•	Inertia.js
•	Vue 3
•	TailwindCSS

External APIs:
•	Google Maps
•	OSM Overpass
•	Geoportal (WMS)
•	GUS / BDL

⸻

Payment Integration

The system abstracts payment gateways using the Strategy pattern:

PaymentGatewayInterface
├── StripeGateway
└── TpayGateway

This allows easy provider switching without modifying domain logic.

My Role
•	Backend architecture design
•	Modular system structure
•	Payment & invoice integration
•	Domain logic implementation
•	End-to-end pipeline design

## Observability & Logging

The system logs:
- external API failures (GIS providers)
- payment verification events
- invoice creation results

This allows easier monitoring and debugging in production environments.
