# CIVE Cafeteria Management System - Database Design

## 9. Database Design (Muundo wa Database)

### 9.1 Entity-Relationship Diagram (Mchoro wa Huluki na Uhusiano)

```
┌─────────────────────────────────────────────────────────────────────────────────────┐
│                    ENTITY-RELATIONSHIP DIAGRAM                                     │
│                (MCHORO WA HULUKI NA UHUSIANO)                                      │
├─────────────────────────────────────────────────────────────────────────────────────┤
│                                                                                     │
│    ┌─────────────────┐              ┌─────────────────┐                              │
│    │     USER        │              │   FOOD_ITEM     │                              │
│    ├─────────────────┤              ├─────────────────┤                              │
│    │ PK id           │              │ PK id           │                              │
│    │ username        │              │ name            │                              │
│    │ password        │              │ name_sw         │                              │
│    │ full_name       │              │ price           │                              │
│    │ role            │              │ category        │                              │
│    │ is_active       │              │ stock_quantity  │                              │
│    │ last_login      │              │ stock_status    │                              │
│    │ created_at      │              │ low_threshold   │                              │
│    └────────┬────────┘              │ is_active       │                              │
│             │                       └────────┬────────┘                              │
│             │                              │                                         │
│             │ 1                          * │                                         │
│             │                            │                                         │
│             │ manages                    │ is_in                                    │
│             │                            │                                         │
│             ▼                            ▼                                         │
│    ┌─────────────────┐              ┌─────────────────┐                            │
│    │     ORDER       │◄────────────►│   ORDER_ITEM    │                            │
│    ├─────────────────┤      1   *   ├─────────────────┤                            │
│    │ PK id           │              │ PK id           │                            │
│    │ order_number    │              │ FK order_id     │                            │
│    │ customer_name   │              │ FK food_item_id │────────────────────────────┤
│    │ customer_phone  │              │ quantity        │                            │
│    │ total_amount    │              │ unit_price      │                            │
│    │ status          │              │ subtotal        │                            │
│    │ payment_status  │              └─────────────────┘                            │
│    │ payment_method  │                                                            │
│    │ created_at      │                                                            │
│    │ completed_at    │                                                            │
│    └────────┬────────┘                                                            │
│             │                                                                     │
│             │ 1                                                                   │
│             │                                                                     │
│             │ recorded_in                                                         │
│             │                                                                     │
│             ▼                                                                     │
│    ┌─────────────────┐                                                            │
│    │   DAILY_SALES   │                                                            │
│    ├─────────────────┤                                                            │
│    │ PK id           │                                                            │
│    │ sale_date (UQ)  │                                                            │
│    │ total_orders    │                                                            │
│    │ total_revenue   │                                                            │
│    │ total_items     │                                                            │
│    └─────────────────┘                                                            │
│                                                                                   │
│    ┌─────────────────┐              ┌─────────────────┐                            │
│    │   STOCK_LOG     │              │    FEEDBACK     │                            │
│    ├─────────────────┤              ├─────────────────┤                            │
│    │ PK id           │              │ PK id           │                            │
│    │ FK food_item_id │────────────►│ customer_name   │                            │
│    │ quantity_change │              │ message         │                            │
│    │ change_type     │              │ rating          │                            │
│    │ reason          │              │ category        │                            │
│    │ created_at      │              │ is_resolved     │                            │
│    └─────────────────┘              │ created_at      │                            │
│                                    └─────────────────┘                            │
│                                                                                   │
│  LEGEND (UFANUZI):                                                               │
│  ─────────────────                                                               │
│  PK = Primary Key (Ufunguo Msingi)                                              │
│  FK = Foreign Key (Ufunguo wa Kigeni)                                           │
│  UQ = Unique (Pekee)                                                            │
│  ────────► = Foreign Key Reference (Marejeo wa Ufunguo wa Kigeni)               │
│  1    = One (Moja)                                                              │
│  *    = Many (Nyingi)                                                           │
│                                                                                   │
└───────────────────────────────────────────────────────────────────────────────────┘
```

### 9.2 Relationships Summary (Muhtasari wa Uhusiano)

```
┌─────────────────────────────────────────────────────────────────────┐
│                    RELATIONSHIPS (UHUSIANO)                        │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  USER ───────1────── manages ──────*──────► ORDER                  │
│  (Mfumo ─────moja────simamia─────nyingi───── Agizo)                 │
│                                                                     │
│  ORDER ──────1────── contains ─────*──────► ORDER_ITEM              │
│  (Agizo ─────moja───vinavyomo───nyingi─────Kipengee cha Agizo)    │
│                                                                     │
│  FOOD_ITEM ──1────── is_in ───────*──────► ORDER_ITEM               │
│  (Chakula ───moja───vinamo───────nyingi─────Kipengee cha Agizo)    │
│                                                                     │
│  FOOD_ITEM ──1────── has ─────────*──────► STOCK_LOG                │
│  (Chakula ───moja───kina────────nyingi─────Kumbukumbu za Stock)    │
│                                                                     │
│  ORDER ──────*────── recorded_in ──1─────► DAILY_SALES             │
│  (Agizo ────nyingi───rekodiwa─────moja─────Mauzo ya Kila Siku)      │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 9.3 Detailed Table Specifications

#### 9.3.1 Table: users (Watumiaji)

```sql
┌─────────────────────────────────────────────────────────────────────┐
│                          USERS TABLE                                │
│                       (Jedwali la Watumiaji)                        │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│ Column Name          │ Data Type     │ Constraints │ Description   │
│──────────────────────│───────────────│─────────────│───────────────│
│ id                   │ INT           │ PK, AI      │ Unique ID     │
│ username             │ VARCHAR(50)   │ UQ, NN      │ Login name    │
│ password             │ VARCHAR(255)  │ NN          │ Bcrypt hash   │
│ full_name            │ VARCHAR(100)  │ NN          │ Display name  │
│ role                 │ ENUM          │ NN          │ User role     │
│ is_active            │ BOOLEAN       │ DF:1        │ Account status│
│ last_login           │ TIMESTAMP     │ NULL        │ Last login    │
│ created_at           │ TIMESTAMP     │ DF:NOW      │ Created date  │
│                                                                     │
├─────────────────────────────────────────────────────────────────────┤
│ INDEXES:                                                            │
│   • PRIMARY KEY (id)                                                │
│   • UNIQUE KEY (username)                                           │
│                                                                     │
│ DATA SAMPLE:                                                        │
│   (1, 'manager', '$2y$10...', 'Cafeteria Manager', 'manager', 1)   │
│   (2, 'cashier1', '$2y$10...', 'Cashier One', 'cashier', 1)        │
│   (3, 'cook1', '$2y$10...', 'Head Cook', 'cook', 1)                │
│                                                                     │
│ ROLE VALUES:                                                        │
│   • 'manager'  - Full system access                                 │
│   • 'cashier'  - Order and payment management                       │
│   • 'cook'     - Kitchen queue view and update                      │
│   • 'admin'    - System administration                              │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

#### 9.3.2 Table: food_items (Vipengee vya Chakula)

```sql
┌─────────────────────────────────────────────────────────────────────┐
│                       FOOD_ITEMS TABLE                              │
│                   (Jedwali la Vipengee vya Chakula)                │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│ Column Name          │ Data Type     │ Constraints │ Description   │
│──────────────────────│───────────────│─────────────│───────────────│
│ id                   │ INT           │ PK, AI      │ Unique ID     │
│ name                 │ VARCHAR(100)  │ NN          │ English name  │
│ name_sw              │ VARCHAR(100)  │ NN          │ Swahili name  │
│ price                │ DECIMAL(10,2) │ NN          │ Price in TSh  │
│ category             │ ENUM          │ DF:'main'   │ Food category │
│ stock_quantity       │ INT           │ DF:0        │ Current stock │
│ stock_status         │ ENUM          │ DF:'avail'  │ Availability  │
│ low_stock_threshold  │ INT           │ DF:10       │ Alert level   │
│ image_url            │ VARCHAR(255)  │ NULL        │ Food image    │
│ is_active            │ BOOLEAN       │ DF:1        │ Show in menu  │
│ created_at           │ TIMESTAMP     │ DF:NOW      │ Added date    │
│ updated_at           │ TIMESTAMP     │ AUTO        │ Modified date │
│                                                                     │
├─────────────────────────────────────────────────────────────────────┤
│ CATEGORY VALUES:                                                    │
│   • 'main_dish'   - Wali, Ugali, Chapati with accompaniments        │
│   • 'side_dish'   - Vegetables, Salads                              │
│   • 'drink'       - Water, Soda, Juice, Tea, Coffee                 │
│   • 'extra'       - Samosa, Mandazi, Bread, Eggs, Fries             │
│                                                                     │
│ STOCK_STATUS VALUES:                                                │
│   • 'available'   - In stock, can be ordered (Ipo)                  │
│   • 'low'         - Below threshold, limited (Imeisha Mzigo Mdogo)│
│   • 'finished'    - Out of stock (Imeisha)                          │
│                                                                     │
│ SAMPLE DATA:                                                        │
│   (1, 'Rice with Beans', 'Wali na Maharage', 2500.00, 'main_dish', │
│       50, 'available', 10, NULL, 1)                                 │
│   (2, 'Rice with Beef', 'Wali na Nyama', 4500.00, 'main_dish',     │
│       5, 'low', 8, NULL, 1)                                         │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

#### 9.3.3 Table: orders (Maagizo)

```sql
┌─────────────────────────────────────────────────────────────────────┐
│                          ORDERS TABLE                               │
│                       (Jedwali la Maagizo)                          │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│ Column Name          │ Data Type     │ Constraints │ Description   │
│──────────────────────│───────────────│─────────────│───────────────│
│ id                   │ INT           │ PK, AI      │ Unique ID     │
│ order_number         │ VARCHAR(20)   │ UQ, NN      │ Human ID      │
│ customer_name        │ VARCHAR(100)  │ NN          │ Who ordered   │
│ customer_phone       │ VARCHAR(20)   │ NULL        │ Contact       │
│ total_amount         │ DECIMAL(10,2) │ NN          │ Order total   │
│ status               │ ENUM          │ DF:'pend'   │ Order state   │
│ payment_status       │ ENUM          │ DF:'unpaid' │ Paid status   │
│ payment_method       │ ENUM          │ DF:'cash'   │ How paid      │
│ created_at           │ TIMESTAMP     │ DF:NOW      │ Order time    │
│ updated_at           │ TIMESTAMP     │ AUTO        │ Last change   │
│ completed_at         │ TIMESTAMP     │ NULL        │ Finished time │
│ notes                │ TEXT          │ NULL        │ Instructions  │
│                                                                     │
├─────────────────────────────────────────────────────────────────────┤
│ INDEXES:                                                            │
│   • PRIMARY KEY (id)                                                │
│   • UNIQUE KEY (order_number)                                       │
│   • INDEX (status)                                                  │
│   • INDEX (created_at)                                              │
│                                                                     │
│ STATUS WORKFLOW:                                                    │
│   pending → preparing → ready → completed                           │
│      ↓                                                               │
│   cancelled                                                          │
│                                                                     │
│ STATUS VALUES:                                                      │
│   • 'pending'    - Waiting to be processed (Inasubiri)              │
│   • 'preparing'  - Being cooked (Inaandaliwa)                       │
│   • 'ready'      - Ready for pickup (Iko Tayari)                    │
│   • 'completed'  - Order finished (Imekamilika)                   │
│   • 'cancelled'  - Order cancelled (Imeghairiwa)                │
│                                                                     │
│ PAYMENT_STATUS VALUES:                                              │
│   • 'unpaid'     - Not yet paid (Haijalipiwa)                       │
│   • 'paid'       - Payment received (Imelipiwa)                     │
│                                                                     │
│ PAYMENT_METHOD VALUES:                                              │
│   • 'cash'       - Cash payment (Fedha)                             │
│   • 'mpesa'      - M-Pesa mobile money                              │
│   • 'tigopesa'   - Tigo Pesa mobile money                           │
│   • 'airtelmoney'│ Airtel Money mobile money                         │
│   • 'card'       - Card payment (future)                            │
│                                                                     │
│ ORDER_NUMBER FORMAT:                                                │
│   ORD + YYYYMMDD + - + 4 random chars                               │
│   Example: ORD20250616-8A3B                                          │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

#### 9.3.4 Table: order_items (Vipengee vya Agizo)

```sql
┌─────────────────────────────────────────────────────────────────────┐
│                       ORDER_ITEMS TABLE                             │
│                   (Jedwali la Vipengee vya Agizo)                   │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│ Column Name          │ Data Type     │ Constraints │ Description   │
│──────────────────────│───────────────│─────────────│───────────────│
│ id                   │ INT           │ PK, AI      │ Unique ID     │
│ order_id             │ INT           │ FK, NN      │ Parent order  │
│ food_item_id         │ INT           │ FK, NN      │ Food ordered  │
│ quantity             │ INT           │ DF:1        │ How many      │
│ unit_price           │ DECIMAL(10,2) │ NN          │ Price at time │
│ subtotal             │ DECIMAL(10,2) │ NN          │ quantity ×    │
│                      │               │             │ unit_price      │
│                                                                     │
├─────────────────────────────────────────────────────────────────────┤
│ FOREIGN KEYS:                                                       │
│   • FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE  │
│   • FOREIGN KEY (food_item_id) REFERENCES food_items(id)            │
│                                                                     │
│ INDEXES:                                                            │
│   • PRIMARY KEY (id)                                                │
│   • INDEX (order_id)                                                  │
│   • INDEX (food_item_id)                                              │
│                                                                     │
│ SAMPLE DATA:                                                        │
│   (1, 1, 1, 2, 2500.00, 5000.00)  - Order 1, 2x Rice with Beans     │
│   (2, 1, 9, 1, 1000.00, 1000.00)  - Order 1, 1x Vegetables          │
│                                                                     │
│ TRIGGERS:                                                           │
│   • Auto-calculate subtotal on insert/update                        │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

#### 9.3.5 Table: daily_sales (Mauzo ya Kila Siku)

```sql
┌─────────────────────────────────────────────────────────────────────┐
│                       DAILY_SALES TABLE                             │
│                   (Jedwali la Mauzo ya Kila Siku)                  │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│ Column Name          │ Data Type     │ Constraints │ Description   │
│──────────────────────│───────────────│─────────────│───────────────│
│ id                   │ INT           │ PK, AI      │ Unique ID     │
│ sale_date            │ DATE          │ UQ, NN      │ The day       │
│ total_orders         │ INT           │ DF:0        │ Orders count  │
│ total_revenue        │ DECIMAL(12,2) │ DF:0.00     │ Money made    │
│ total_items_sold     │ INT           │ DF:0        │ Items count   │
│                                                                     │
├─────────────────────────────────────────────────────────────────────┤
│ INDEXES:                                                            │
│   • PRIMARY KEY (id)                                                │
│   • UNIQUE KEY (sale_date)                                          │
│                                                                     │
│ AUTO-UPDATE:                                                        │
│   • Incremented by trigger when order is placed                   │
│   • ON DUPLICATE KEY UPDATE pattern used                            │
│                                                                     │
│ SAMPLE DATA:                                                        │
│   (1, '2026-06-16', 45, 125000.00, 89)                              │
│   - On June 16, 2026: 45 orders, TSh 125,000 revenue, 89 items       │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

#### 9.3.6 Table: stock_logs (Kumbukumbu za Stock)

```sql
┌─────────────────────────────────────────────────────────────────────┐
│                       STOCK_LOGS TABLE                              │
│                   (Jedwali la Kumbukumbu za Stock)                 │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│ Column Name          │ Data Type     │ Constraints │ Description   │
│──────────────────────│───────────────│─────────────│───────────────│
│ id                   │ INT           │ PK, AI      │ Unique ID     │
│ food_item_id         │ INT           │ FK, NN      │ What changed  │
│ quantity_change      │ INT           │ NN          │ + added,      │
│                      │               │             │ - reduced       │
│ change_type          │ ENUM          │ NN          │ Why changed   │
│ reason               │ VARCHAR(255)  │ NULL        │ Explanation   │
│ created_at           │ TIMESTAMP     │ DF:NOW      │ When changed  │
│                                                                     │
├─────────────────────────────────────────────────────────────────────┤
│ FOREIGN KEYS:                                                       │
│   • FOREIGN KEY (food_item_id) REFERENCES food_items(id)            │
│                                                                     │
│ CHANGE_TYPE VALUES:                                                 │
│   • 'addition'        - Stock added by manager                    │
│   • 'reduction'       - Manual stock adjustment                    │
│   • 'order_consumption' - Stock used for order                     │
│                                                                     │
│ SAMPLE DATA:                                                        │
│   (1, 1, -2, 'order_consumption', 'Order ORD20250616-8A3B', NOW())│
│   - 2 Rice with Beans used for order                                │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

#### 9.3.7 Table: feedback (Maoni)

```sql
┌─────────────────────────────────────────────────────────────────────┐
│                        FEEDBACK TABLE                               │
│                       (Jedwali la Maoni)                            │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│ Column Name          │ Data Type     │ Constraints │ Description   │
│──────────────────────│───────────────│─────────────│───────────────│
│ id                   │ INT           │ PK, AI      │ Unique ID     │
│ customer_name        │ VARCHAR(100)  │ NULL        │ Who (optional)│
│ message              │ TEXT          │ NN          │ The feedback  │
│ rating               │ INT           │ NULL        │ 1-5 stars     │
│ category             │ ENUM          │ DF:'other'  │ Type          │
│ is_resolved          │ BOOLEAN       │ DF:0        │ Handled?      │
│ created_at           │ TIMESTAMP     │ DF:NOW      │ Submitted     │
│                                                                     │
├─────────────────────────────────────────────────────────────────────┤
│ CATEGORY VALUES:                                                    │
│   • 'service'    - About service quality                            │
│   • 'food'       - About food quality/taste                         │
│   • 'wait_time'  - About waiting time                               │
│   • 'other'      - General feedback                                 │
│                                                                     │
│ RATING:                                                             │
│   • 1 = Very Poor (Mbaya sana)                                      │
│   • 2 = Poor (Mbaya)                                                │
│   • 3 = Average (Wastani)                                             │
│   • 4 = Good (Nzuri)                                                │
│   • 5 = Excellent (Bora sana)                                       │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 9.4 Database Schema Summary (Muhtasari wa Muundo wa Database)

```
┌─────────────────────────────────────────────────────────────────────┐
│                    TABLES OVERVIEW (MUHTASARI WA JEDWALI)            │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  ┌─────────────────┐  ┌─────────────────┐  ┌─────────────────┐       │
│  │     users       │  │   food_items    │  │     orders      │       │
│  │   4 records     │  │   20 items      │  │   ~200/day      │       │
│  │   (staff)       │  │   (menu)        │  │   (orders)      │       │
│  └─────────────────┘  └─────────────────┘  └─────────────────┘       │
│                                                                     │
│  ┌─────────────────┐  ┌─────────────────┐  ┌─────────────────┐       │
│  │  order_items    │  │  daily_sales    │  │   stock_logs    │       │
│  │   ~400/day      │  │   1 record/day  │  │   ~300/day      │       │
│  │   (line items)  │  │   (summary)     │  │   (audit)       │       │
│  └─────────────────┘  └─────────────────┘  └─────────────────┘       │
│                                                                     │
│  ┌─────────────────┐                                                │
│  │    feedback     │                                                │
│  │   ~50/month     │                                                │
│  │   (reviews)     │                                                │
│  └─────────────────┘                                                │
│                                                                     │
│  ESTIMATED DATA VOLUME (KIWANGO CHA TAKWIMU):                      │
│  ─────────────────────────────────────────────                      │
│  • Orders: 200 per day × 180 days = 36,000/year                     │
│  • Order Items: ~2 per order = 72,000/year                          │
│  • Stock Logs: ~300 per day = 54,000/year                           │
│  • Database Size: ~50 MB per year                                 │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

**Document Version:** 1.0  
**Date:** June 2026
