# CIVE Cafeteria Management System - Documentation

## System Design Documents (Nyaraka za Ubunifu wa Mfumo)

This folder contains all system design documentation for the CIVE Cafeteria Management System.

### 📁 Document List (Orodha ya Nyaraka)

| # | Document | Description | Language |
|---|----------|-------------|----------|
| 1 | [01-System-Scope.md](01-System-Scope.md) | System boundaries, objectives, stakeholders | EN/SW |
| 2 | [02-Functional-Requirements.md](02-Functional-Requirements.md) | What the system does - features & functions | EN/SW |
| 3 | [03-Non-Functional-Requirements.md](03-Non-Functional-Requirements.md) | How the system performs - quality attributes | EN/SW |
| 4 | [04-Use-Case-Diagram.md](04-Use-Case-Diagram.md) | Visual diagram of system interactions | EN/SW |
| 5 | [05-Use-Case-Descriptions.md](05-Use-Case-Descriptions.md) | Detailed scenarios for each use case | EN/SW |
| 6 | [06-Class-Diagram.md](06-Class-Diagram.md) | Object-oriented system structure | EN/SW |
| 7 | [07-Sequence-Diagram.md](07-Sequence-Diagram.md) | Message flow between objects over time | EN/SW |
| 8 | [08-Activity-Diagram.md](08-Activity-Diagram.md) | Workflow and process flow diagrams | EN/SW |
| 9 | [09-Database-Design.md](09-Database-Design.md) | ER diagram and table specifications | EN/SW |

---

## 📊 Documentation Overview (Muhtasari wa Nyaraka)

### 1. System Scope (Wigo wa Mfumo)
Defines:
- Problem statement (Kauli ya tatizo)
- System boundaries (Mipaka ya mfumo)
- Objectives (Malengo)
- Stakeholders (Wadau)
- Success criteria (Vigezo vya mafanikio)

### 2. Functional Requirements (Mahitaji ya Kazi)
Describes:
- 12+ functional requirement categories
- User roles and permissions
- System features and capabilities
- Use case summary table

### 3. Non-Functional Requirements (Mahitaji Isiyo ya Kazi)
Covers:
- Performance (Response time, throughput)
- Usability (Mobile-friendly design)
- Security (Authentication, data protection)
- Reliability (Fault tolerance, backups)
- Scalability (Growth handling)

### 4. Use Case Diagram (Mchoro wa Matumizi)
Shows:
- Actor-system interactions
- 13 use cases identified
- Include/extend relationships
- Priority matrix

### 5. Use Case Descriptions (Maelezo ya Matumizi)
Details:
- 7 detailed use case scenarios
- Pre/post conditions
- Basic and alternative flows
- Business rules

### 6. Class Diagram (Mchoro wa Madarasa)
Presents:
- 7 main classes
- Attributes and methods
- Relationships (1:1, 1:*, *:*)
- Design patterns used

### 7. Sequence Diagrams (Michoro ya Mfuatano)
Illustrates:
- Order placement flow
- Kitchen preparation workflow
- Mobile payment process
- Stock management

### 8. Activity Diagrams (Michoro ya Shughuli)
Depicts:
- Order processing workflow
- Kitchen operations
- Stock management activities
- Decision points and parallel activities

### 9. Database Design (Muundo wa Database)
Contains:
- ER Diagram (Entity-Relationship)
- 7 table specifications
- Column details with data types
- Indexes and constraints
- Sample data

---

## 🎯 Key Features Documented (Vigezo Muhimu Vilivyoandikwa)

### For Students (Kwa Wanafunzi):
- ✅ Online menu browsing
- ✅ Order placement with automatic calculation
- ✅ Mobile payment (M-Pesa, Tigo Pesa, Airtel Money)
- ✅ Real-time order tracking
- ✅ Feedback submission

### For Staff (Kwa Wafanyakazi):
- ✅ Order processing (Cashier)
- ✅ Kitchen queue management
- ✅ Stock management
- ✅ Sales reporting
- ✅ User management

---

## 📐 Design Principles (Kanuni za Ubunifu)

1. **Mobile-First** - Designed for smartphones first
2. **Bilingual** - English and Swahili support
3. **Real-Time** - Live updates and notifications
4. **Secure** - Password hashing, input validation
5. **Scalable** - Handles 100+ concurrent users
6. **Reliable** - 99.5% uptime target

---

## 🗂️ Database Tables (Jedwali za Database)

1. **users** - Staff authentication
2. **food_items** - Menu items with stock
3. **orders** - Order headers
4. **order_items** - Order line items
5. **daily_sales** - Aggregated daily statistics
6. **stock_logs** - Stock change audit trail
7. **feedback** - Customer reviews

---

## 👥 User Roles (Wajibu wa Watumiaji)

| Role | Description | Access Level |
|------|-------------|--------------|
| Student | Customer who orders food | Menu, Order, Track, Feedback |
| Cashier | Processes orders and payments | Orders, Payments, Kitchen View |
| Cook | Prepares food | Kitchen Queue, Status Updates |
| Manager | System administrator | All features, Reports, Configuration |

---

## 🔄 Order Status Flow (Mtiririko wa Hali ya Agizo)

```
PENDING → PREPARING → READY → COMPLETED
   ↓
CANCELLED
```

**Translation:**
- PENDING = Inasubiri
- PREPARING = Inaandaliwa
- READY = Iko Tayari
- COMPLETED = Imekamilika
- CANCELLED = Imeghairiwa

---

## 📱 Technology Stack (Rabeti la Tekinolojia)

- **Frontend:** HTML5, CSS3, JavaScript
- **Backend:** PHP 7.4+
- **Database:** MySQL 5.7+
- **Server:** Apache (XAMPP)
- **Design:** Mobile-first, Responsive

---

## 📧 For Questions (Kwa Maswali)

Contact: CIVE Development Team  
Institution: University of Dodoma (UDOM)  
College: CIVE (College of Informatics and Virtual Education)

---

**Document Version:** 1.0  
**Created:** June 2026  
**Language:** English & Swahili (Bilingual)
