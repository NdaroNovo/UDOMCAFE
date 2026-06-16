# CIVE Cafeteria Management System - Use Case Descriptions

## 5. Use Case Descriptions (Maelezo ya Matumizi)

### 5.1 UC-001: View Menu (Angalia Menyu)

**Use Case ID:** UC-001  
**Use Case Name:** View Menu / Angalia Menyu  
**Actor:** Student (Mwanafunzi)  
**Priority:** High

#### Description (Maelezo):
The student browses the available food menu to see what items are available, their prices, and stock status before making an order.

#### Pre-Conditions (Masharti ya Mwanzo):
- System is running
- Student has internet connection (or cached menu)
- Food items are loaded in database

#### Post-Conditions (Masharti ya Mwisho):
- Student sees current menu with up-to-date information
- Stock status is clearly displayed

#### Basic Flow (Mtiririko wa Kawaida):

| Step | Actor Action | System Response |
|------|--------------|-----------------|
| 1 | Student opens menu page | System displays available food categories |
| 2 | Student selects category filter (optional) | System filters items by selected category |
| 3 | Student views food items | System shows item name (EN & SW), price, stock status |
| 4 | Student clicks on food item (optional) | System shows item details |
| 5 | Student decides to order | Use case UC-002 (Place Order) is initiated |

#### Alternative Flows (Mtiririko Mbadala):

**A1: Item is out of stock**
- System displays "Finished" badge
- "Add to Order" button is disabled
- Student cannot select item

**A2: Low stock**
- System displays "Low Stock" warning
- Student can still order (while available)

**A3: Network offline**
- System shows cached menu (if available)
- Warning displayed: "Menu may be outdated"

#### Exception Flows (Mtiririko wa Kipekee):

**E1: Database connection error**
- System displays error message
- Suggests trying again later

#### Business Rules (Kanuni za Biashara):
- Only active items are displayed
- Prices must be current
- Stock status must update in real-time

---

### 5.2 UC-002: Place Order (Weka Agizo)

**Use Case ID:** UC-002  
**Use Case Name:** Place Order / Weka Agizo  
**Actor:** Student (Mwanafunzi)  
**Priority:** High

#### Description:
Student selects food items, enters their details, and submits an order for preparation.

#### Pre-Conditions:
- Student has viewed menu (UC-001)
- Items selected are in stock
- System is accepting orders

#### Post-Conditions:
- Order is saved in database
- Order number is generated
- Stock is reduced
- Kitchen is notified
- Student receives confirmation

#### Basic Flow:

| Step | Actor Action | System Response |
|------|--------------|-----------------|
| 1 | Student clicks "Add to Order" on food item | Item added to cart, running total updated |
| 2 | Student adjusts quantity (if needed) | Quantity updated, total recalculated |
| 3 | Student adds more items (repeat 1-2) | Cart updated with each addition |
| 4 | Student clicks "View Cart" | Cart page displays with all items |
| 5 | Student enters name | Name field validated (required) |
| 6 | Student enters phone number (optional) | Phone field validated (optional) |
| 7 | Student clicks "Submit Order" | System validates order |
| 8 | | System generates unique order number |
| 9 | | System reduces stock quantities |
| 10 | | System saves order to database |
| 11 | | System displays confirmation with order number |

#### Alternative Flows:

**A1: Remove item from cart**
- Student clicks remove icon
- System removes item and updates total

**A2: Empty cart**
- If cart is empty, "Submit" button is disabled
- System prompts to add items

**A3: Insufficient stock**
- If stock becomes insufficient during ordering
- System displays warning
- Adjusts maximum quantity allowed

#### Exception Flows:

**E1: Payment required before submission (if enabled)**
- System redirects to payment page
- Order saved as "Pending Payment"

**E2: Database error during save**
- System displays error
- Order is not saved
- Student asked to retry

#### Business Rules:
- Minimum order value: None (configurable)
- Maximum items per order: No limit
- Name is required
- Phone is optional but recommended
- Stock is reserved when order is placed

---

### 5.3 UC-003: Make Payment (Fanya Malipo)

**Use Case ID:** UC-003  
**Use Case Name:** Make Payment / Fanya Malipo  
**Actor:** Student (Mwanafunzi)  
**Priority:** High

#### Description:
Student pays for their order using available payment methods (Cash, M-Pesa, Tigo Pesa, Airtel Money).

#### Pre-Conditions:
- Order has been placed (UC-002)
- Order status is "Pending" or "Unpaid"

#### Post-Conditions:
- Payment is recorded
- Order status updated to "Paid" or "Processing"
- Receipt/confirmation provided

#### Basic Flow (Mobile Money):

| Step | Actor Action | System Response |
|------|--------------|-----------------|
| 1 | Student selects "Pay Now" | System displays payment options |
| 2 | Student selects payment method (e.g., M-Pesa) | System highlights selected method |
| 3 | Student enters phone number | System validates phone number format |
| 4 | Student clicks "Pay Now" | System initiates payment request |
| 5 | | System sends STK push/USSD prompt to phone |
| 6 | Student confirms payment on phone | Payment provider processes transaction |
| 7 | | System receives payment confirmation |
| 8 | | System updates order payment status to "Paid" |
| 9 | | System displays payment success message |
| 10 | | Order proceeds to kitchen |

#### Alternative Flows:

**A1: Cash payment**
- Student selects "Pay at Counter"
- Order status remains "Unpaid"
- Cashier marks as paid when receiving cash

**A2: Payment failure**
- Student doesn't confirm on phone
- Payment times out
- System allows retry

**A3: Insufficient balance**
- Payment provider reports insufficient funds
- System suggests alternative payment method
- Order remains pending

#### Exception Flows:

**E1: Payment provider service down**
- System displays error
- Suggests cash payment or retry later

**E2: Duplicate payment**
- System detects duplicate transaction
- Prevents double charging
- Shows previous payment confirmation

#### Business Rules:
- Payment must be completed within 15 minutes
- Failed payments can be retried 3 times
- Cash payments must be verified by cashier
- Refunds require manager approval

---

### 5.4 UC-006: Process Order (Shughulikia Agizo)

**Use Case ID:** UC-006  
**Use Case Name:** Process Order / Shughulikia Agizo  
**Actor:** Cashier (Mhudumu wa Fedha)  
**Priority:** High

#### Description:
Cashier views incoming orders, processes payments, and updates order status through the fulfillment workflow.

#### Pre-Conditions:
- Cashier is logged in
- Orders exist in system
- Cashier has appropriate permissions

#### Post-Conditions:
- Order status is updated
- Customer is informed of status change
- Payment is recorded (if applicable)

#### Basic Flow:

| Step | Actor Action | System Response |
|------|--------------|-----------------|
| 1 | Cashier logs into system | System displays cashier dashboard |
| 2 | Cashier views pending orders | System lists all pending orders |
| 3 | Cashier selects order to process | System displays order details |
| 4 | Cashier verifies order items | System shows items, quantities, total |
| 5 | Cashier accepts cash payment | System records payment method |
| 6 | Cashier clicks "Mark as Preparing" | System updates order status |
| 7 | | System notifies kitchen |
| 8 | When food ready, Cashier marks "Ready" | System updates status |
| 9 | Customer picks up food | Cashier marks "Completed" |
| 10 | | System records completion time |

#### Alternative Flows:

**A1: Order cancellation**
- Cashier clicks "Cancel"
- System prompts for reason
- Stock is restored
- Customer is notified

**A2: Partial payment**
- Customer pays part of amount
- System records partial payment
- Remaining balance tracked

**A3: Wrong order**
- Cashier identifies error
- System allows order modification (if not yet preparing)
- Or cancel and create new order

#### Business Rules:
- Only orders from current day displayed by default
- Status changes must be chronological
- Cancellation requires reason
- Completed orders cannot be modified

---

### 5.5 UC-009: Prepare Food (Pika Chakula)

**Use Case ID:** UC-009  
**Use Case Name:** Prepare Food / Pika Chakula  
**Actor:** Cook (Mpishi)  
**Priority:** High

#### Description:
Cook views order queue, prepares food, and marks orders as ready for pickup.

#### Pre-Conditions:
- Cook is logged in
- Kitchen view is accessible
- Orders are in "Pending" or "Preparing" status

#### Post-Conditions:
- Food is prepared
- Order marked as "Ready"
- Customer can collect food

#### Basic Flow:

| Step | Actor Action | System Response |
|------|--------------|-----------------|
| 1 | Cook opens kitchen view | System displays order queue |
| 2 | Cook views order details | System shows items, quantities, special notes |
| 3 | Cook clicks "Start Cooking" | System marks order as "Preparing" |
| 4 | | System shows estimated time |
| 5 | Cook prepares food | (External activity) |
| 6 | Cook finishes preparation | System allows marking as "Ready" |
| 7 | Cook clicks "Food Ready" | System updates status |
| 8 | | System notifies cashier/customer |
| 9 | Order moves to ready queue | Awaiting pickup |

#### Alternative Flows:

**A1: Multiple orders**
- Cook can view multiple orders simultaneously
- Prioritize by time or complexity

**A2: Ingredient shortage**
- If ingredient runs out during prep
- Cook informs manager
- Order may need cancellation

**A3: Special requests**
- Customer notes are displayed
- Cook follows special instructions

#### Business Rules:
- Orders processed in FIFO order (with priority option)
- Estimated prep time: 5 min base + 2 min per item
- Low stock alerts visible to cook
- Multiple cooks can view same queue

---

### 5.6 UC-011: View Reports (Angalia Ripoti)

**Use Case ID:** UC-011  
**Use Case Name:** View Reports / Angalia Ripoti  
**Actor:** Manager (Msimamizi)  
**Priority:** Medium

#### Description:
Manager generates and views sales reports, popular items, and operational analytics.

#### Pre-Conditions:
- Manager is logged in
- Historical data exists
- Report date range specified

#### Post-Conditions:
- Report is generated
- Data is displayed clearly
- Can be exported (future feature)

#### Basic Flow:

| Step | Actor Action | System Response |
|------|--------------|-----------------|
| 1 | Manager navigates to Reports | System shows report dashboard |
| 2 | Manager selects date range | System accepts start and end dates |
| 3 | Manager selects report type | System shows options (Sales, Items, Payments) |
| 4 | Manager clicks "Generate" | System queries database |
| 5 | | System calculates totals, averages |
| 6 | | System displays formatted report |
| 7 | Manager views charts/tables | System renders visualizations |
| 8 | Manager filters data (optional) | System updates display |
| 9 | Manager exports (optional) | System generates downloadable file |

#### Report Types Available:
- Daily Sales Summary
- Popular Food Items
- Payment Method Breakdown
- Hourly Order Volume
- Stock Consumption
- Customer Feedback Summary

#### Business Rules:
- Reports include only non-cancelled orders
- Data updated in real-time
- Date range maximum: 90 days (for performance)

---

### 5.7 Summary Table of All Use Cases

| ID | Use Case | Actor | Complexity | Priority |
|----|----------|-------|------------|----------|
| UC-001 | View Menu | Student | Low | High |
| UC-002 | Place Order | Student | Medium | High |
| UC-003 | Make Payment | Student | High | High |
| UC-004 | Track Order | Student | Low | Medium |
| UC-005 | Submit Feedback | Student | Low | Medium |
| UC-006 | Process Order | Cashier | Medium | High |
| UC-007 | Manage Payments | Cashier | Medium | High |
| UC-008 | View Kitchen Queue | Cook | Low | High |
| UC-009 | Prepare Food | Cook | Medium | High |
| UC-010 | Manage Stock | Manager | Medium | High |
| UC-011 | View Reports | Manager | Medium | Medium |
| UC-012 | Manage Users | Manager | Medium | Low |
| UC-013 | Configure Menu | Manager | Medium | Medium |

---

**Document Version:** 1.0  
**Date:** June 2026
