# CIVE Cafeteria Management System - Class Diagram

## 6. Class Diagram (Mchoro wa Madarasa)

### 6.1 System Class Diagram Overview

```
┌─────────────────────────────────────────────────────────────────────────────────────┐
│                         CIVE CAFETERIA - CLASS DIAGRAM                              │
│                      (Mchoro wa Madarasa wa CIVE Cafeteria)                         │
├─────────────────────────────────────────────────────────────────────────────────────┤
│                                                                                     │
│  ┌───────────────────┐                                                              │
│  │     User          │───────────────────┐                                          │
│  │───────────────────│                   │                                          │
│  │ - id: int         │                   │                                          │
│  │ - username: string│                   │                                          │
│  │ - password: string│                   │                                          │
│  │ - full_name: string                              │                              │
│  │ - role: enum      │                   │                                          │
│  │ - is_active: bool │                   │                                          │
│  │ - last_login: datetime                          │                              │
│  │───────────────────│                   │                                          │
│  │ + login()         │                   │                                          │
│  │ + logout()        │                   │                                          │
│  │ + updateProfile() │                   │                                          │
│  └─────────┬─────────┘                   │                                          │
│            │ 1                           │ *                                        │
│            │                             │                                          │
│            │ manages                     │ creates                                  │
│            ▼                             ▼                                          │
│  ┌───────────────────┐        ┌───────────────────┐                              │
│  │   FoodItem          │        │      Order        │                              │
│  │───────────────────│        │───────────────────│                              │
│  │ - id: int           │        │ - id: int         │                              │
│  │ - name: string      │        │ - order_number: string                      │                              │
│  │ - name_sw: string   │◄───────│ - customer_name: string                     │                              │
│  │ - price: decimal    │  *     │ - customer_phone: string                    │                              │
│  │ - category: enum    │        │ - total_amount: decimal                     │                              │
│  │ - stock_quantity: int                      │ - status: enum                               │                              │
│  │ - stock_status: enum                       │ - payment_status: enum                       │                              │
│  │ - low_stock_threshold: int                   │ - payment_method: enum                       │                              │
│  │ - is_active: bool   │        │ - created_at: datetime                       │                              │
│  │───────────────────│        │ - completed_at: datetime                     │                              │
│  │ + checkStock()      │        │───────────────────│                              │
│  │ + updateStock()     │        │ + placeOrder()    │                              │
│  │ + isAvailable()     │        │ + updateStatus()  │                              │
│  └─────────┬─────────┘        │ + calculateTotal()│                              │
│            │ 1                  │ + getEstimatedTime()                         │                              │
│            │                    │ + cancel()        │                              │
│            │ contains          └─────────┬─────────┘                              │
│            ▼                              │ 1                                        │
│  ┌───────────────────┐                   │                                          │
│  │   OrderItem         │                   │                                          │
│  │───────────────────│                   │                                          │
│  │ - id: int           │                   │                                          │
│  │ - order_id: int     │                   │                                          │
│  │ - food_item_id: int │                   │                                          │
│  │ - quantity: int     │                   │                                          │
│  │ - unit_price: decimal                      │                                          │
│  │ - subtotal: decimal │                   │                                          │
│  │───────────────────│                   │                                          │
│  │ + calculateSubtotal()                      │                                          │
│  └───────────────────┘                   │                                          │
│                                          │                                          │
│  ┌───────────────────┐                   │                                          │
│  │   DailySales        │                   │                                          │
│  │───────────────────│                   │                                          │
│  │ - id: int           │                   │                                          │
│  │ - sale_date: date   │                   │                                          │
│  │ - total_orders: int │                   │                                          │
│  │ - total_revenue: decimal                   │                                          │
│  │ - total_items_sold: int                    │                                          │
│  │───────────────────│                   │                                          │
│  │ + generateReport()  │                   │                                          │
│  └───────────────────┘                   │                                          │
│                                          │                                          │
│  ┌───────────────────┐                   │                                          │
│  │   StockLog          │                   │                                          │
│  │───────────────────│                   │                                          │
│  │ - id: int           │                   │                                          │
│  │ - food_item_id: int │                   │                                          │
│  │ - quantity_change: int                     │                                          │
│  │ - change_type: enum │                   │                                          │
│  │ - reason: string    │                   │                                          │
│  │ - created_at: datetime                     │                                          │
│  └───────────────────┘                   │                                          │
│                                          │                                          │
│  ┌───────────────────┐                   │                                          │
│  │   Feedback          │                   │                                          │
│  │───────────────────│                   │                                          │
│  │ - id: int           │                   │                                          │
│  │ - customer_name: string                    │                                          │
│  │ - message: text     │                   │                                          │
│  │ - rating: int       │                   │                                          │
│  │ - category: enum    │                   │                                          │
│  │ - is_resolved: bool │                   │                                          │
│  │ - created_at: datetime                     │                                          │
│  │───────────────────│                   │                                          │
│  │ + submit()          │                   │                                          │
│  │ + resolve()         │                   │                                          │
│  └───────────────────┘                   │                                          │
│                                                                                     │
└─────────────────────────────────────────────────────────────────────────────────────┘
```

### 6.2 Detailed Class Specifications

#### 6.2.1 User Class (Darasa la Mtumiaji)

```php
┌─────────────────────────────────────────────────────────────┐
│                        USER                                  │
│                   (Mtumiaji)                                 │
├─────────────────────────────────────────────────────────────┤
│                                                              │
│ ATTRIBUTES (Sifa):                                          │
│ ──────────────────────────────────────────────────────────  │
│ - id: int [PK]                 - Unique identifier          │
│ - username: string [Unique]    - Login name                 │
│ - password: string             - Hashed password (bcrypt)     │
│ - full_name: string            - Display name               │
│ - role: enum                   - manager|cashier|cook|admin  │
│ - is_active: boolean           - Account status             │
│ - last_login: datetime         - Last login timestamp       │
│ - created_at: timestamp        - Account creation date      │
│                                                              │
│ METHODS (Mbinu):                                            │
│ ──────────────────────────────────────────────────────────  │
│ + login(username, password): boolean                        │
│ + logout(): void                                            │
│ + updateProfile(data): boolean                              │
│ + isAuthorized(role): boolean                               │
│ + getRole(): enum                                           │
│                                                              │
│ RELATIONSHIPS (Uhusiano):                                   │
│ ──────────────────────────────────────────────────────────  │
│ • 1 User manages * Orders                                   │
│ • 1 User creates * StockLogs                                │
│                                                              │
└─────────────────────────────────────────────────────────────┘
```

#### 6.2.2 FoodItem Class (Darasa la Kipengee cha Chakula)

```php
┌─────────────────────────────────────────────────────────────┐
│                     FOOD ITEM                                │
│                 (Kipengee cha Chakula)                      │
├─────────────────────────────────────────────────────────────┤
│                                                              │
│ ATTRIBUTES (Sifa):                                          │
│ ──────────────────────────────────────────────────────────  │
│ - id: int [PK]                 - Unique identifier          │
│ - name: string                 - English name               │
│ - name_sw: string              - Swahili name               │
│ - price: decimal(10,2)        - Price in TSh               │
│ - category: enum               - main_dish|side_dish|...    │
│ - stock_quantity: int          - Current stock level        │
│ - stock_status: enum           - available|low|finished      │
│ - low_stock_threshold: int      - Alert threshold             │
│ - image_url: string [Nullable] - Food image path            │
│ - is_active: boolean           - Item visibility             │
│ - created_at: timestamp        - Added date                 │
│ - updated_at: timestamp        - Last modified              │
│                                                              │
│ METHODS (Mbinu):                                            │
│ ──────────────────────────────────────────────────────────  │
│ + checkStock(): int                                         │
│ + updateStock(quantity): boolean                            │
│ + isAvailable(): boolean                                    │
│ + getStockStatus(): enum                                    │
│ + reduceStock(amount): boolean                              │
│ + addStock(amount): boolean                                 │
│ + calculateStockStatus(): void                              │
│                                                              │
│ RELATIONSHIPS (Uhusiano):                                   │
│ ──────────────────────────────────────────────────────────  │
│ • 1 FoodItem is in * OrderItems                             │
│ • 1 FoodItem has * StockLogs                                │
│                                                              │
│ BUSINESS RULES (Kanuni za Biashara):                        │
│ ──────────────────────────────────────────────────────────  │
│ • stock_status = 'finished' if stock_quantity <= 0         │
│ • stock_status = 'low' if stock_quantity <= threshold        │
│ • stock_status = 'available' otherwise                      │
│                                                              │
└─────────────────────────────────────────────────────────────┘
```

#### 6.2.3 Order Class (Darasa la Agizo)

```php
┌─────────────────────────────────────────────────────────────┐
│                        ORDER                                 │
│                      (Agizo)                                 │
├─────────────────────────────────────────────────────────────┤
│                                                              │
│ ATTRIBUTES (Sifa):                                          │
│ ──────────────────────────────────────────────────────────  │
│ - id: int [PK]                 - Unique identifier          │
│ - order_number: string [Unique] - Human-readable ID         │
│ - customer_name: string          - Who ordered              │
│ - customer_phone: string [Nullable] - Contact number        │
│ - total_amount: decimal(10,2)  - Order total               │
│ - status: enum                 - pending|preparing|ready|...   │
│ - payment_status: enum         - unpaid|paid                │
│ - payment_method: enum         - cash|mpesa|tigopesa|...     │
│ - created_at: timestamp        - Order time                 │
│ - updated_at: timestamp        - Last modified              │
│ - completed_at: timestamp [Nullable] - Completion time      │
│ - notes: text [Nullable]       - Special instructions       │
│                                                              │
│ METHODS (Mbinu):                                            │
│ ──────────────────────────────────────────────────────────  │
│ + placeOrder(items, customer): Order                        │
│ + updateStatus(newStatus): boolean                            │
│ + calculateTotal(): decimal                                 │
│ + getEstimatedTime(): int                                   │
│ + cancel(reason): boolean                                   │
│ + markPaid(method): boolean                                 │
│ + generateOrderNumber(): string                             │
│ + getQueuePosition(): int                                   │
│                                                              │
│ RELATIONSHIPS (Uhusiano):                                   │
│ ──────────────────────────────────────────────────────────  │
│ • 1 Order has * OrderItems                                  │
│ • 1 Order belongs to 1 User (who created it)               │
│                                                              │
│ STATUS FLOW (Mtiririko wa Hali):                           │
│ ──────────────────────────────────────────────────────────  │
│   pending → preparing → ready → completed                   │
│      ↓                                                    │
│   cancelled                                                 │
│                                                              │
└─────────────────────────────────────────────────────────────┘
```

#### 6.2.4 OrderItem Class (Darasa la Kipengee cha Agizo)

```php
┌─────────────────────────────────────────────────────────────┐
│                     ORDER ITEM                               │
│               (Kipengee cha Agizo)                          │
├─────────────────────────────────────────────────────────────┤
│                                                              │
│ ATTRIBUTES (Sifa):                                          │
│ ──────────────────────────────────────────────────────────  │
│ - id: int [PK]                 - Unique identifier          │
│ - order_id: int [FK]           - Parent order               │
│ - food_item_id: int [FK]       - Food item ordered          │
│ - quantity: int                - How many                   │
│ - unit_price: decimal(10,2)    - Price at time of order    │
│ - subtotal: decimal(10,2)      - quantity × unit_price     │
│                                                              │
│ METHODS (Mbinu):                                            │
│ ──────────────────────────────────────────────────────────  │
│ + calculateSubtotal(): decimal                              │
│ + getFoodItem(): FoodItem                                   │
│ + updateQuantity(qty): boolean                              │
│                                                              │
│ RELATIONSHIPS (Uhusiano):                                   │
│ ──────────────────────────────────────────────────────────  │
│ • 1 OrderItem belongs to 1 Order                            │
│ • 1 OrderItem references 1 FoodItem                         │
│                                                              │
└─────────────────────────────────────────────────────────────┘
```

### 6.3 Entity Relationship Summary

```
┌─────────────────────────────────────────────────────────────────────┐
│                    ENTITY RELATIONSHIPS                            │
│                    (Uhusiano wa Huluki)                            │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  ┌─────────────┐         ┌─────────────┐         ┌─────────────┐│
│  │    USER     │1───────* │    ORDER    │1───────* │  ORDER ITEM ││
│  │             │ manages  │             │ contains │             ││
│  └─────────────┘         └─────────────┘         └──────┬──────┘│
│                                                         │        │
│                                                         │*       │
│                                                         │        │
│                                                    ┌────┴────┐   │
│                                                    │ FOOD    │   │
│                                                    │  ITEM   │   │
│                                                    └────┬────┘   │
│                                                         │        │
│                                                         │1       │
│                                                         │        │
│                                                    ┌────┴────┐   │
│                                                    │ STOCK   │   │
│                                                    │  LOG    │   │
│                                                    └─────────┘   │
│                                                                     │
│  ┌─────────────┐         ┌─────────────┐                         │
│  │    ORDER    │*───────1 │ DAILY SALES │                         │
│  │             │ recorded │             │                         │
│  └─────────────┘    in    └─────────────┘                         │
│                                                                     │
│  ┌─────────────┐                                                  │
│  │   FEEDBACK  │                                                  │
│  │             │                                                  │
│  └─────────────┘                                                  │
│                                                                     │
│  RELATIONSHIP TYPES:                                               │
│  ─────────────────────────────────────────────────────────────    │
│  • 1:1  - One to One (Moja kwa Moja)                              │
│  • 1:*  - One to Many (Moja kwa Wengi)                            │
│  • *:*  - Many to Many (Wengi kwa Wengi)                          │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 6.4 Class Responsibilities (Wajibu wa Madarasa)

| Class | Primary Responsibility | Secondary Responsibilities |
|-------|------------------------|---------------------------|
| User | Authentication & Authorization | Profile management, Activity logging |
| FoodItem | Menu item management | Stock tracking, Availability checking |
| Order | Order lifecycle management | Payment tracking, Status updates |
| OrderItem | Order line item details | Subtotal calculation |
| DailySales | Aggregate sales data | Report generation |
| StockLog | Stock change tracking | Audit trail |
| Feedback | Customer feedback collection | Rating analysis |

### 6.5 Design Patterns Used (Mifumo ya Ubunifu)

1. **Active Record Pattern** - Classes handle their own database operations
2. **Factory Pattern** - Order number generation
3. **Observer Pattern** - Stock status updates trigger alerts
4. **State Pattern** - Order status transitions

---

**Document Version:** 1.0  
**Date:** June 2026
