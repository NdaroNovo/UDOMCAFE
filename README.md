# CIVE Cafeteria Management System

Mfumo wa Usimamizi wa Cafeteria ya CIVE - UDOM

## Overview / Muhtasari

A modern, mobile-friendly web-based cafeteria management system for CIVE (College of Informatics and Virtual Education) at the University of Dodoma (UDOM).

Mfumo wa kisasa unaofaa kwa simu za mkononi kwa ajili ya usimamizi wa Cafeteria ya CIVE - Chuo cha Taarifa na Elimu Mtandao (UDOM).

## Features / Sifa

### For Students / Kwa Wanafunzi:
- **View Menu** (Angalia Orodha ya Chakula) - See available food with stock status
- **Place Orders** (Weka Agizo) - Order food without waiting in line
- **Check Order Status** (Angalia Hali ya Agizo) - Track your order
- **Give Feedback** (Toa Maoni) - Rate and review the service
- **Mobile Friendly** (Rahisi kwa Simu) - Works on any smartphone
- **No Login Required** (Hakuna Kuingia) - Just enter your name

### For Staff / Kwa Wafanyakazi:
- **Cashier View** (Mhudumu wa Fedha) - Manage orders and payments
- **Kitchen View** (Jikoni) - See incoming orders in real-time
- **Manager Dashboard** (Dashibodi ya Msimamizi) - Sales reports and stock management
- **Low Stock Alerts** (Arifa za Mzigo Mdogo) - Get notified when food is running low
- **Automatic Calculations** (Hisabati Kiotomatiki) - No manual math needed

## Tech Stack / Tekinolojia

- **Backend:** PHP 7.4+
- **Database:** MySQL 5.7+
- **Frontend:** HTML5, CSS3, JavaScript
- **Styling:** Custom CSS (Mobile-first design)
- **Icons:** SVG Icons
- **PWA:** Service Worker for offline menu viewing

## Installation / Ufafanuzi

### Prerequisites / Mahitaji:
1. XAMPP or WAMP server
2. PHP 7.4 or higher
3. MySQL 5.7 or higher

### Steps / Hatua:

1. **Copy files to htdocs:**
   ```
   Copy all files to C:\xampp\htdocs\UDOMCAFE\
   ```

2. **Create Database:**
   - Open phpMyAdmin (http://localhost/phpmyadmin)
   - Import the `database.sql` file
   - Or run: `mysql -u root -p < database.sql`

3. **Configure Database:**
   - Edit `config.php` if needed:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USERNAME', 'root');
   define('DB_PASSWORD', '');
   define('DB_NAME', 'cive_cafeteria');
   ```

4. **Access the System:**
   - Student Menu: http://localhost/UDOMCAFE/
   - Login Page: http://localhost/UDOMCAFE/login.php

## Default Login Credentials / Nywila za Kuingia

| Role | Username | Password |
|------|----------|----------|
| Manager | manager | password123 |
| Cashier | cashier1 | password123 |
| Cook | cook1 | password123 |
| Admin | admin | password123 |

## File Structure / Muundo wa Faili

```
UDOMCAFE/
├── index.php              # Student menu view (Homepage)
├── place-order.php        # Process orders
├── order-success.php      # Order confirmation
├── my-order.php           # Order lookup
├── feedback.php           # Feedback form
├── login.php              # Staff login
├── logout.php             # Logout
├── cashier.php            # Cashier view
├── kitchen.php            # Kitchen view
├── dashboard.php          # Manager dashboard
├── update-order.php       # Order status updates
├── update-stock.php       # Stock management
├── set-language.php       # Language switcher
├── config.php             # Configuration & functions
├── database.sql           # Database schema
├── sw.js                  # Service Worker (PWA)
├── manifest.json          # PWA manifest
├── README.md              # This file
└── assets/
    └── css/
        └── style.css      # Main stylesheet
```

## Pages / Kurasa

### Student Pages:
- **index.php** - Menu with food items and stock status
- **my-order.php** - Order status lookup
- **feedback.php** - Submit feedback

### Staff Pages (Login Required):
- **cashier.php** - View and manage orders
- **kitchen.php** - See orders to prepare
- **dashboard.php** - Sales stats and stock management

## Language Support / Lugha

The system supports both English and Swahili:
- Click EN/SW buttons to switch language
- All labels and messages are bilingual

Mfumo una Lugha ya Kiingereza na Kiswahili:
- Bonyeza vifungo vya EN/SW kubadilisha lugha
- Lebo na ujumbe wote ni katika lugha mbili

## Mobile Features / Vifaa vya Simu

- Responsive design for all screen sizes
- Bottom navigation for easy thumb access
- Touch-friendly buttons
- Fast loading on slow connections
- Works offline (menu page cached)

## Troubleshooting / Kutatua Shida

### Database Connection Error:
- Check if MySQL is running
- Verify database credentials in `config.php`
- Ensure database `cive_cafeteria` exists

### Pages Not Loading:
- Check XAMPP is running (Apache and MySQL)
- Verify files are in correct directory
- Check PHP version (7.4+ required)

### Login Not Working:
- Default password is `password123`
- Ensure users table is populated (check database.sql)

## Security / Usalama

- Passwords are hashed with bcrypt
- SQL injection prevention with prepared statements
- XSS protection with htmlspecialchars
- Session-based authentication for staff pages

## Future Enhancements / Uboreshaji wa Baadaye

- [ ] Mobile Money (M-Pesa) integration
- [ ] QR code order lookup
- [ ] Push notifications for order status
- [ ] Advanced reporting and analytics
- [ ] Multi-cafeteria support
- [ ] Student ID integration

## Support / Msaada

For support or questions:
- Email: support@civecafeteria.udom.ac.tz
- Phone: +255 XXX XXX XXX

## License / Leseni

This system is developed for CIVE, UDOM. All rights reserved.

Mfumo huu umeundwa kwa ajili ya CIVE, UDOM. Haki zote zimehifadhiwa.

---

**Developed with ❤️ for CIVE Cafeteria**
**Imeundwa kwa ❤️ kwa ajili ya Cafeteria ya CIVE**

© 2026 CIVE Cafeteria, University of Dodoma
