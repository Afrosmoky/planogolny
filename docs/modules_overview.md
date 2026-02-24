# Planogolny.info — Modules Overview

This document describes the structure and purpose of each domain module implemented inside the `packages/` directory.  
Each module follows clean architecture principles and exposes its functionality through Actions and DTOs.

---

# 1. Analysis Module

**Path:** `packages/Analysis`

### Purpose
Implements the core logic of the system: parcel evaluation, scoring, and prediction of land-use probability based on GIS data, demographic information, and business rules.

### Responsibilities:
- Running the full scoring pipeline
- Interpreting data from GIS module
- Evaluating parcel surroundings (50 m, 200 m)
- Applying restrictions (roads, rivers, power lines, etc.)
- Applying demographic modifiers (GUS)
- Producing normalized scoring results (0–100%)
- Returning structured output for PDF generation

### Components:
- `Actions/RunAnalysisAction.php`
- `DTO/AnalysisInputDTO.php`
- `DTO/AnalysisResultDTO.php`
- `Services/ScoringService.php`
- `Services/RestrictionService.php`

---

# 2. GIS Module

**Path:** `packages/GIS`

### Purpose
Unified gateway for fetching geospatial data from external APIs.

### Responsibilities:
- Geocoding addresses (Google API)
- Fetching parcel boundaries (GUGIK / Geoportal)
- Fetching buildings and land cover (OSM Overpass)
- Normalizing GIS responses into DTOs
- Acting as an abstraction layer (facade pattern)

### Components:
- `Providers/OsmProvider.php`
- `Providers/GoogleProvider.php`
- `Providers/GeoportalProvider.php`
- `DTO/BuildingDTO.php`
- `DTO/ParcelDTO.php`
- `Services/GisFacade.php`

---

# 3. Payments Module

**Path:** `packages/Payments`

### Purpose
Handles one-time payments via Stripe or TPay.

### Responsibilities:
- Creating payment sessions
- Verifying payment status via webhook
- Returning confirmation to Orders module
- Abstracting payment gateway behind interface (strategy pattern)

### Components:
- `Actions/CreatePaymentSessionAction.php`
- `Actions/VerifyPaymentAction.php`
- `Services/StripeGateway.php`
- `Services/TpayGateway.php`
- `DTO/PaymentRequestDTO.php`

---

# 4. Orders Module

**Path:** `packages/Orders`

### Purpose
Stores information about purchased reports and manages the order lifecycle.

### Responsibilities:
- Creating order records after payment confirmation
- Storing transaction data and metadata
- Tracking report generation status
- Linking reports to users (optional)

### Components:
- `Models/Order.php`
- `Actions/CreateOrderAction.php`
- `Actions/MarkOrderAsCompletedAction.php`

### Events Published:
- **OrderPaid** – dispatched after successful payment verification
---
# 5. Invoicing Module (ING Accounting Integration)

**Path:** `packages/Invoicing`

## Purpose
Handles automatic invoice generation and retrieval through the **ING Accounting API**
after an order is successfully paid.

## Responsibilities
- Listen for the `OrderPaid` event inside the modular system
- Create an invoice using the ING “invoice/create” endpoint
- Retrieve the invoice PDF using the ING “invoice/download” endpoint
- Forward the invoice document to the Reporting module for email delivery
- Optionally archive invoices in external storage (local filesystem or Google Drive)

## Components
- `Actions/CreateInvoiceAction.php`
- `Actions/DownloadInvoiceAction.php`
- `DTO/InvoiceRequestDTO.php`
- `DTO/InvoiceDTO.php`
- `Services/IngInvoiceApi.php`
- `Events/InvoiceCreated.php`
- `Events/InvoiceDownloaded.php`

## Events Consumed
- `OrderPaid`

## Events Published
(Only lightweight module-internal events, not full event-driven architecture.)
- `InvoiceCreated`
- `InvoiceDownloaded`


---

# 6. Reporting Module

**Path:** `packages/Reporting`

### Purpose
Generates the final PDF report and handles email delivery.

### Responsibilities:
- Formatting report content
- Rendering map snippets (optional)
- Generating PDF files
- Sending email with report link or attachment
- Storing report in storage or S3

### Components:
- `Actions/GenerateReportAction.php`
- `DTO/ReportDataDTO.php`
- `Services/PdfRenderer.php`
- `Services/EmailService.php`

### Events Consumed:
- OrderPaid
- ReportGenerationRequested (optional)

### Events Published:
- ReportGenerated
---

# 7. Customer Module (optional)

**Path:** `packages/Customer`

### Purpose
Stores user/customer information beyond authentication.

### Responsibility:
- Customer profiles
- Saved parcels
- Notification settings

---

# 8. Landing Module (optional)

**Path:** `packages/Landing`

### Purpose
Manages CMS-like content for the marketing website.

---

# Summary

Each module is fully isolated, testable, and replaceable.  
Controllers only orchestrate these modules — all logic lives inside packages.
