# CIVE Cafeteria Management System - Sequence Diagrams

## 7. Sequence Diagrams (Michoro ya Mfuatano)

### 7.1 Order Placement Sequence (Mfuatano wa Kuweka Agizo)

```
┌─────────────────────────────────────────────────────────────────────────────────────┐
│                    SEQUENCE: PLACE ORDER (KUWEKA AGIZO)                            │
├─────────────────────────────────────────────────────────────────────────────────────┤
│                                                                                     │
│ Student        System         Database       Kitchen        Payment               │
│   │              │                │              │              │                 │
│   │ 1: View Menu │                │              │              │                 │
│   │─────────────>│                │              │              │                 │
│   │              │ 2: Query Food  │              │              │                 │
│   │              │───────────────>│              │              │                 │
│   │              │ 3: Return Items│              │              │                 │
│   │              │<───────────────│              │              │                 │
│   │ 4: Show Menu │                │              │              │                 │
│   │<─────────────│                │              │              │                 │
│   │                │               │              │              │                 │
│   │ 5: Select Items               │              │              │                 │
│   │─────────────>│                │              │              │                 │
│   │              │ 6: Check Stock │              │              │                 │
│   │              │───────────────>│              │              │                 │
│   │              │ 7: Stock OK    │              │              │                 │
│   │              │<───────────────│              │              │                 │
│   │ 8: Add to Cart                │              │              │                 │
│   │<─────────────│                │              │              │                 │
│   │                │               │              │              │                 │
│   │ 9: Enter Details (Name, Phone)              │              │                 │
│   │─────────────>│                │              │              │                 │
│   │              │ 10: Validate  │              │              │                 │
│   │              │───────────────>│              │              │                 │
│   │              │ 11: Valid      │              │              │                 │
│   │              │<───────────────│              │              │                 │
│   │ 12: Submit Order              │              │              │                 │
│   │─────────────>│                │              │              │                 │
│   │              │ 13: Save Order │              │              │                 │
│   │              │───────────────>│              │              │                 │
│   │              │ 14: Generate Order Number       │              │                 │
│   │              │<───────────────│              │              │                 │
│   │              │ 15: Reduce Stock               │              │                 │
│   │              │───────────────>│              │              │                 │
│   │              │ 16: Stock Updated              │              │                 │
│   │              │<───────────────│              │              │                 │
│   │              │ 17: Notify Kitchen             │              │                 │
│   │              │───────────────────────────────>│              │                 │
│   │ 18: Show Confirmation with Order Number       │              │                 │
│   │<─────────────│                │              │              │                 │
│   │                │               │              │              │                 │
│   │ 19: Choose Payment Method                     │              │                 │
│   │─────────────>│                │              │              │                 │
│   │              │ 20: Process Payment            │              │                 │
│   │              │─────────────────────────────────────────────>│                 │
│   │              │ 21: Payment Confirmation                      │                 │
│   │              │<─────────────────────────────────────────────│                 │
│   │              │ 22: Update Order Status                      │                 │
│   │              │───────────────>│              │              │                 │
│   │ 23: Payment Success           │              │              │                 │
│   │<─────────────│                │              │              │                 │
│   │                │               │              │              │                 │

LEGEND:
──>  : Synchronous message
───> : Return message
────> : Asynchronous message
│     : Lifeline

EXPLANATION (Maelezo):
1. Student opens menu page
2-4. System fetches and displays food items
5-8. Student selects items, system verifies stock
9-11. Student enters details, system validates
12-16. Order is saved, stock reduced
17. Kitchen is notified of new order
18. Student receives confirmation
19-23. Payment is processed and confirmed
```

### 7.2 Kitchen Order Preparation Sequence (Mfuatano wa Kuandaa Agizo Jikoni)

```
┌─────────────────────────────────────────────────────────────────────────────────────┐
│              SEQUENCE: KITCHEN ORDER PREPARATION                                    │
│           (MFUATANO WA KUANDAA AGIZO JIKONI)                                       │
├─────────────────────────────────────────────────────────────────────────────────────┤
│                                                                                     │
│ Cook          Kitchen View       Database        Cashier         Student          │
│   │               │                  │               │               │              │
│   │ 1: Login      │                │               │               │              │
│   │──────────────>│                │               │               │              │
│   │               │ 2: Authenticate                │               │              │
│   │               │───────────────>│               │               │              │
│   │               │ 3: Success     │               │               │              │
│   │               │<───────────────│               │               │              │
│   │ 4: Load Dashboard              │               │               │              │
│   │<──────────────│                │               │               │              │
│   │               │ 5: Fetch Orders                │               │              │
│   │               │───────────────>│               │               │              │
│   │               │ 6: Return Queue│               │               │              │
│   │               │<───────────────│               │               │              │
│   │ 7: Display Queue               │               │               │              │
│   │<──────────────│                │               │               │              │
│   │                │               │               │               │              │
│   │ 8: Select Order to Start       │               │               │              │
│   │──────────────>│                │               │               │              │
│   │               │ 9: Update Status               │               │              │
│   │               │───────────────>│               │               │              │
│   │               │ 10: Status Updated             │               │              │
│   │               │<───────────────│               │               │              │
│   │               │ 11: Notify Cashier             │               │              │
│   │               │───────────────────────────────>│               │              │
│   │               │ 12: Notify Student             │               │              │
│   │               │───────────────────────────────────────────────>│              │
│   │ 13: Show "Preparing" Status    │               │               │              │
│   │<──────────────│                │               │               │              │
│   │                │               │               │               │              │
│   │ 14: [Prepare Food]             │               │               │              │
│   │════════════════│═══════════════│═══════════════│═══════════════│══════════════│
│   │                │               │               │               │              │
│   │ 15: Mark Ready │               │               │               │              │
│   │──────────────>│                │               │               │              │
│   │               │ 16: Update Status              │               │              │
│   │               │───────────────>│               │               │              │
│   │               │ 17: Status Updated              │               │              │
│   │               │<───────────────│               │               │              │
│   │               │ 18: Notify Cashier             │               │              │
│   │               │───────────────────────────────>│               │              │
│   │               │ 19: Notify Student             │               │              │
│   │               │───────────────────────────────────────────────>│              │
│   │ 20: Show "Ready" Status        │               │               │              │
│   │<──────────────│                │               │               │              │
│   │                │               │               │               │              │

EXPLANATION (Maelezo):
═══════════════ : Time passing (food preparation)
1-7. Cook logs in and views order queue
8-13. Cook starts preparing order
14. Cooking time (external process)
15-20. Cook marks order as ready, notifications sent
```

### 7.3 Payment Processing Sequence (Mfuatano wa Malipo)

```
┌─────────────────────────────────────────────────────────────────────────────────────┐
│              SEQUENCE: MOBILE PAYMENT (MALIPO KWA SIMU)                            │
├─────────────────────────────────────────────────────────────────────────────────────┤
│                                                                                     │
│ Student      System      Database      Payment Gateway      Mobile Phone          │
│   │            │             │                  │                  │              │
│   │ 1: Select Pay Now       │                  │                  │              │
│   │───────────>│             │                  │                  │              │
│   │ 2: Show Payment Options                  │                  │              │
│   │<───────────│             │                  │                  │              │
│   │ 3: Choose Method (M-Pesa)                │                  │              │
│   │───────────>│             │                  │                  │              │
│   │ 4: Enter Phone Number   │                  │                  │              │
│   │───────────>│             │                  │                  │              │
│   │            │ 5: Validate Number             │                  │              │
│   │            │────────────>│                  │                  │              │
│   │            │ 6: Valid     │                  │                  │              │
│   │            │<────────────│                  │                  │              │
│   │ 7: Click Pay Now        │                  │                  │              │
│   │───────────>│             │                  │                  │              │
│   │            │ 8: Create Payment Request      │                  │              │
│   │            │───────────────────────────────>│                  │              │
│   │            │ 9: Send STK Push               │                  │              │
│   │            │───────────────────────────────────────────────────>│              │
│   │ 10: Enter PIN on Phone  │                  │                  │              │
│   │◄═════════════════════════════════════════════════════════════│              │
│   │            │             │                  │                  │              │
│   │            │             │                  │ 11: Process Payment             │
│   │            │             │                  │<═══════════════════════════════│
│   │            │             │                  │ 12: Verify PIN   │              │
│   │            │             │                  │<═══════════════════════════════│
│   │            │             │                  │ 13: Check Balance │              │
│   │            │             │                  │<═══════════════════════════════│
│   │            │             │                  │ 14: Deduct Amount │              │
│   │            │             │                  │<═══════════════════════════════│
│   │            │             │                  │ 15: Send Confirmation             │
│   │            │ 16: Payment Success            │                  │              │
│   │            │<───────────────────────────────│                  │              │
│   │            │ 17: Update Order                │                  │              │
│   │            │────────────>│                  │                  │              │
│   │            │ 18: Save Record               │                  │                  │
│   │            │<────────────│                  │                  │              │
│   │ 19: Show Success        │                  │                  │              │
│   │<───────────│             │                  │                  │              │
│   │            │             │                  │                  │              │

◄════════════════ : External/Time-delayed process

EXPLANATION (Maelezo):
1-6. Student selects payment method and enters phone
7-9. System initiates payment via gateway (STK Push)
10-15. User confirms on phone, payment processed
16-19. System receives confirmation, updates order
```

### 7.4 Stock Management Sequence (Mfuatano wa Usimamizi wa Stock)

```
┌─────────────────────────────────────────────────────────────────────────────────────┐
│              SEQUENCE: STOCK MANAGEMENT                                            │
│           (MFUATANO WA USIMAMIZI WA STOCK)                                        │
├─────────────────────────────────────────────────────────────────────────────────────┤
│                                                                                     │
│ Manager      Dashboard      Database      Kitchen View      Low Stock Alert      │
│   │            │               │                  │                  │             │
│   │ 1: View Stock           │                  │                  │             │
│   │───────────>│             │                  │                  │             │
│   │            │ 2: Query Stock                │                  │             │
│   │            │─────────────>│                 │                  │             │
│   │            │ 3: Return Stock Levels          │                  │             │
│   │            │<─────────────│                 │                  │             │
│   │ 4: Display Stock        │                  │                  │             │
│   │<───────────│             │                  │                  │             │
│   │                │          │                  │                  │             │
│   │ 5: Add Stock to Item    │                  │                  │             │
│   │───────────>│             │                  │                  │             │
│   │            │ 6: Update Stock               │                  │             │
│   │            │─────────────>│                 │                  │             │
│   │            │ 7: Stock Updated              │                  │             │
│   │            │<─────────────│                 │                  │             │
│   │            │ 8: Create Stock Log           │                  │             │
│   │            │─────────────>│                 │                  │             │
│   │            │ 9: Log Created                │                  │             │
│   │            │<─────────────│                 │                  │             │
│   │ 10: Show Updated Stock  │                  │                  │             │
│   │<───────────│             │                  │                  │             │
│   │                │          │                  │                  │             │

ALT: WHEN STOCK IS LOW (WAKATI STOCK IKO CHINI)
│   │                │          │                  │                  │             │
│   │                │ 11: Check Threshold         │                  │             │
│   │                │══════════>│                  │                  │             │
│   │                │ 12: Stock Below Threshold   │                  │             │
│   │                │<══════════│                  │                  │             │
│   │                │ 13: Update Status to 'low'  │                  │             │
│   │                │───────────>│                  │                  │             │
│   │                │ 14: Trigger Alert           │                  │             │
│   │                │────────────────────────────>│                  │             │
│   │                │ 15: Show Alert on Kitchen   │                  │             │
│   │                │─────────────────────────────────────────────>│             │
│   │ 16: Receive Alert       │                  │                  │             │
│   │<───────────│             │                  │                  │             │
│   │                │          │                  │                  │             │

EXPLANATION (Maelezo):
══════════ : Conditional/Alternative flow
1-10. Manager adds stock, system updates and logs
11-16. If stock below threshold, alert triggered
```

### 7.5 Summary of Sequences (Muhtasari wa Mifuatano)

| Sequence | Actors Involved | Key Interactions |
|----------|-----------------|------------------|
| Place Order | Student, System, Database, Kitchen, Payment | 23 messages |
| Kitchen Preparation | Cook, System, Database, Cashier, Student | 20 messages |
| Mobile Payment | Student, System, Database, Gateway, Phone | 19 messages |
| Stock Management | Manager, System, Database, Kitchen | 16 messages |

---

**Document Version:** 1.0  
**Date:** June 2026
