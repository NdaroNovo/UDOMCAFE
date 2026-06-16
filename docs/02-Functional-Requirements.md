# CIVE Cafeteria Management System - Functional Requirements

## 2. Functional Requirements (Mahitaji ya Kazi)

### 2.1 Overview
Functional requirements define what the system should do - the specific behaviors, functions, and operations that the system must support.

Mahitaji ya kazi yafafanua mfumo unapaswa kufanya nini - tabia, kazi, na operesheni maalum ambazo mfumo lazima usaidie.

---

### 2.2 User Roles (Wajibu wa Watumiaji)

```
┌─────────────────────────────────────────────────────────────┐
│                    USER ROLES / WAJIBU                      │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│  ┌──────────────┐    ┌──────────────┐    ┌──────────────┐  │
│  │   STUDENT    │    │    STAFF      │    │   MANAGER    │  │
│  │  (Wanafunzi) │    │  (Wafanyakazi)│    │  (Msimamizi) │  │
│  └──────┬───────┘    └──────┬───────┘    └──────┬───────┘  │
│         │                   │                   │           │
│    View Menu           Process Orders      View Reports   │
│    Place Order         Mark Ready           Manage Stock   │
│    Track Order         Accept Payment         Manage Menu    │
│    Give Feedback       Update Status          View Analytics │
│    Make Payment        Kitchen View           System Config  │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

---

### 2.3 Detailed Functional Requirements

#### FR-001: Student Registration & Authentication
**Priority:** Low  
**Description:** Students can use the system without mandatory registration

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-001.1 | System shall allow students to place orders with only name and optional phone number | High |
| FR-001.2 | System shall not require account creation for students | High |
| FR-001.3 | System shall provide optional order lookup by order number | Medium |

#### FR-002: Menu Display (Kuonyesha Menyu)
**Priority:** High

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-002.1 | System shall display all available food items with images/icons | High |
| FR-002.2 | System shall show prices in TSh (Tanzanian Shillings) | High |
| FR-002.3 | System shall indicate stock status (Available, Low, Finished) | High |
| FR-002.4 | System shall display food names in both English and Swahili | High |
| FR-002.5 | System shall categorize food (Main Dish, Side Dish, Drink, Extra) | Medium |
| FR-002.6 | System shall filter menu by category | Medium |
| FR-002.7 | System shall update stock status in real-time | High |

#### FR-003: Order Placement (Kuweka Agizo)
**Priority:** High

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-003.1 | System shall allow students to select multiple food items | High |
| FR-003.2 | System shall calculate total amount automatically | High |
| FR-003.3 | System shall display running total as items are added | High |
| FR-003.4 | System shall generate unique order number (e.g., ORD20250616-8A3B) | High |
| FR-003.5 | System shall capture customer name and optional phone number | High |
| FR-003.6 | System shall validate minimum order amount (if applicable) | Low |
| FR-003.7 | System shall prevent ordering of finished/low stock items | High |

#### FR-004: Payment Processing (Shughuli za Malipo)
**Priority:** High

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-004.1 | System shall support cash payment option | High |
| FR-004.2 | System shall integrate with M-Pesa for mobile payment | High |
| FR-004.3 | System shall integrate with Tigo Pesa for mobile payment | High |
| FR-004.4 | System shall integrate with Airtel Money for mobile payment | High |
| FR-004.5 | System shall send payment request to customer's phone | High |
| FR-004.6 | System shall confirm payment before order processing | High |
| FR-004.7 | System shall handle payment failures gracefully | Medium |

#### FR-005: Order Management - Cashier (Usimamizi wa Maagizo - Mhudumu wa Fedha)
**Priority:** High

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-005.1 | System shall display all pending orders with details | High |
| FR-005.2 | System shall allow cashier to view order items and total | High |
| FR-005.3 | System shall allow marking order as "Preparing" | High |
| FR-005.4 | System shall allow marking order as "Ready" | High |
| FR-005.5 | System shall allow marking order as "Completed" | High |
| FR-005.6 | System shall allow marking payment as "Paid" | High |
| FR-005.7 | System shall allow cancellation of orders with reason | Medium |
| FR-005.8 | System shall filter orders by status | Medium |

#### FR-006: Kitchen Management (Usimamizi wa Jikoni)
**Priority:** High

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-006.1 | System shall display orders in queue for kitchen staff | High |
| FR-006.2 | System shall show order items with quantities | High |
| FR-006.3 | System shall prioritize orders by time and status | High |
| FR-006.4 | System shall allow marking order as "Started" (Preparing) | High |
| FR-006.5 | System shall allow marking order as "Food Ready" | High |
| FR-006.6 | System shall display estimated preparation time | Medium |
| FR-006.7 | System shall show low stock alerts | High |

#### FR-007: Stock Management (Usimamizi wa Stock)
**Priority:** High

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-007.1 | System shall track stock quantity for each food item | High |
| FR-007.2 | System shall automatically reduce stock when order is placed | High |
| FR-007.3 | System shall set stock status based on thresholds | High |
| FR-007.4 | System shall alert when stock is low (< 10 items) | High |
| FR-007.5 | System shall allow manager to add stock | High |
| FR-007.6 | System shall maintain stock history log | Medium |
| FR-007.7 | System shall prevent orders when stock is finished | High |

#### FR-008: Reporting & Analytics (Ripoti na Uchanganuzi)
**Priority:** Medium

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-008.1 | System shall generate daily sales summary | High |
| FR-008.2 | System shall show total orders and revenue | High |
| FR-008.3 | System shall show popular food items | Medium |
| FR-008.4 | System shall show payment method breakdown | Medium |
| FR-008.5 | System shall support date range filtering | Medium |
| FR-008.6 | System shall allow export to PDF/Excel (future) | Low |

#### FR-009: Waiting Time Estimation (Utabiri wa Muda wa Kungoja)
**Priority:** Medium

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-009.1 | System shall calculate estimated wait time based on queue | High |
| FR-009.2 | System shall consider number of items in calculation | Medium |
| FR-009.3 | System shall display progress timeline to students | Medium |
| FR-009.4 | System shall update estimates in real-time | Medium |
| FR-009.5 | System shall show queue position | Low |

#### FR-010: Feedback System (Mfumo wa Maoni)
**Priority:** Medium

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-010.1 | System shall allow students to submit feedback | High |
| FR-010.2 | System shall support star rating (1-5) | Medium |
| FR-010.3 | System shall collect written comments | High |
| FR-010.4 | System shall categorize feedback (service, food, wait time) | Low |
| FR-010.5 | System shall display feedback to manager | Medium |

#### FR-011: Language Support (Usaidizi wa Lugha)
**Priority:** High

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-011.1 | System shall support English language | High |
| FR-011.2 | System shall support Swahili language | High |
| FR-011.3 | System shall allow instant language switching | High |
| FR-011.4 | System shall remember language preference | Medium |

#### FR-012: User Management (Usimamizi wa Watumiaji)
**Priority:** High

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-012.1 | System shall support role-based access (Manager, Cashier, Cook) | High |
| FR-012.2 | System shall require login for staff members | High |
| FR-012.3 | System shall enforce password security | High |
| FR-012.4 | System shall allow manager to add/remove users | Medium |
| FR-012.5 | System shall log user activities | Low |

---

### 2.4 Use Case Summary Table

| Use Case ID | Use Case Name | Actor | Priority |
|-------------|---------------|-------|----------|
| UC-001 | View Menu | Student | High |
| UC-002 | Place Order | Student | High |
| UC-003 | Make Payment | Student | High |
| UC-004 | Track Order | Student | Medium |
| UC-005 | Submit Feedback | Student | Medium |
| UC-006 | Process Order | Cashier | High |
| UC-007 | Manage Payments | Cashier | High |
| UC-008 | View Kitchen Queue | Cook | High |
| UC-009 | Update Order Status | Cook | High |
| UC-010 | Manage Stock | Manager | High |
| UC-011 | View Reports | Manager | Medium |
| UC-012 | Manage Users | Manager | Medium |
| UC-013 | Configure System | Manager | Low |

---

**Document Version:** 1.0  
**Date:** June 2026
