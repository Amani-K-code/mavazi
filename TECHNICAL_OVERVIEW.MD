
---

# 👕 Mavazi Inventory & Sales System: Technical Guide

This document provides a high-level technical map of the Mavazi application, designed for developers and system administrators to understand the interaction between roles, data models, and automated logic.

---

## 🏗️ 1. Core Architecture & RBAC

The system utilizes a custom **Role-Based Access Control (RBAC)** via `RoleMiddleware`.

### User Roles:

1. **Admin:** Full system oversight, financial overrides (Price Locking), bulk inventory actions, and delivery approvals.
2. **Cashier:** Front-end sales, receipt generation, customer feedback collection, and booking management.
3. **Storekeeper:** Stock intake, delivery logging (pending approval), and restock history monitoring.

---

## 🔄 2. The Sales & Stock Lifecycle

### Cashier Workflow (`SaleController`)

* **Transaction Safety:** All sales are wrapped in `DB::transaction` to prevent partial data writes.
* **Sales Modes:**
* **CONFIRMED:** Immediate stock decrement from `Inventory`.
* **BOOKED:** Stock is moved from `stock_quantity` to `reserved_quantity`. A `Reservation` record is created with a 30-day expiry.


* **Data Integrity:** * **Unique Reference IDs:** Strict validation on `reference_id` prevents duplicate M-PESA entries.
* **Receipts:** Uses `Barryvdh\DomPDF` to generate professional receipt PDFs.



### Stock Adjustments (`InventoryAdjustment`)

Every change to stock levels—whether by sale, restock, or manual admin restoration—is logged in the `inventory_adjustments` table. This provides a perfect audit trail of *who* changed *what* and *why*.

---

## 🛡️ 3. Administrative Safeguards

### The Price Locking Engine (`AdminInventoryController`)

To protect the integrity of the school's pricing:

* Items marked as `is_locked` require the Admin's **current account password** to be updated.
* This prevents unauthorized price manipulation by anyone with temporary access to an Admin terminal.

### Bulk Discount Logic

* **Action:** Apply a percentage discount across a specific `category` (e.g., "Outerwear").
* **Precision:** Logic uses a `multiplier` calculation to avoid floating-point errors and ensures each item's `save()` method is called to trigger observers.

### Stock Restoration (`AdminRestoreController`)

* Admins can manually expire a booking and return the `reserved_quantity` back to the active `stock_quantity`.

---

## 📦 4. Procurement & Deliveries

### Storekeeper Workflow (`DeliveriesController`)

* **Intake:** Storekeepers create a "Delivery" containing multiple `DeliveryItems`.
* **Status:** Deliveries start as `pending` and **do not** affect inventory levels until an Admin reviews and clicks **Approve**.
* **Automation:** Upon Admin approval, the system iterates through items and increments the `Inventory` stock automatically.

---

## 🔔 5. Automation & Notifications

### Low Stock Alerts

The `Inventory` model handles its own "health checks."

* If `stock_quantity` <= `low_stock_threshold`, a system notification is generated for the Admin.
* **De-duplication logic:** Prevents creating multiple alerts for the same item unless the previous alert was resolved/read.

### Automated Housekeeping (`AutoRestoreStock.php`)

A scheduled console command that:

1. Checks for `Reservations` that have passed their `expires_at` date.
2. Automatically returns that stock to the main inventory.
3. Marks the reservation as `expired`.

---

## 🧪 6. System Health (Testing)

The system is protected by 17+ Feature Tests (`run-test.sh`).

* **Feature/AdminTest:** Validates RBAC and Price Security.
* **Feature/InventorySaleTest:** Validates stock math and auto-alerts.
* **Feature/DeliveryTest:** Validates the multi-step approval workflow.

---

## 📂 7. Important File Locations

| Component | Path |
| --- | --- |
| **Logic Controllers** | `app/Http/Controllers/` |
| **Data Models** | `app/Models/` |
| **Database Schema** | `database/migrations/` |
| **UI Templates** | `resources/views/` |
| **Custom Middleware** | `app/Http/Middleware/RoleMiddleware.php` |
| **Automated Tasks** | `app/Console/Commands/AutoRestoreStock.php` |

---