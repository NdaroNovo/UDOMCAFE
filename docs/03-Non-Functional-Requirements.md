# CIVE Cafeteria Management System - Non-Functional Requirements

## 3. Non-Functional Requirements (Mahitaji Isiyo ya Kazi)

### 3.1 Overview
Non-functional requirements define how the system should behave and the constraints under which it must operate. They specify the quality attributes and operational characteristics.

---

### 3.2 Performance Requirements (Mahitaji ya Utendaji)

#### NFR-001: Response Time (Muda wa Majibu)
| ID | Requirement | Target | Priority |
|----|-------------|--------|----------|
| NFR-001.1 | Menu page load time | < 3 seconds | High |
| NFR-001.2 | Order submission processing | < 2 seconds | High |
| NFR-001.3 | Kitchen view update | < 1 second | High |
| NFR-001.4 | Search/filter results | < 1 second | Medium |
| NFR-001.5 | Report generation | < 5 seconds | Medium |

#### NFR-002: Throughput (Kiwango cha Shuguli)
| ID | Requirement | Target | Priority |
|----|-------------|--------|----------|
| NFR-002.1 | Support concurrent users | 100+ simultaneous | High |
| NFR-002.2 | Process orders per hour | 200+ orders/hour | High |
| NFR-002.3 | Database transactions | 50+ per minute | Medium |

#### NFR-003: Availability (Upatikanaji)
| ID | Requirement | Target | Priority |
|----|-------------|--------|----------|
| NFR-003.1 | System uptime during operating hours | 99.5% | High |
| NFR-003.2 | Maximum planned downtime | 2 hours/month | Medium |
| NFR-003.3 | Recovery time after failure | < 10 minutes | High |

---

### 3.3 Usability Requirements (Mahitaji ya Matumizi)

#### NFR-004: User Interface (Kiolesura cha Mtumiaji)
```
┌─────────────────────────────────────────────────────────────┐
│               USABILITY PRINCIPLES / KANUNI ZA MATUMIZI       │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐      │
│  │   SIMPLE     │  │   MOBILE     │  │   ACCESSIBLE │      │
│  │   (Rahisi)   │  │   (Simu)     │  │   (Rahisi    │      │
│  │              │  │              │  │   kufikia)   │      │
│  └──────────────┘  └──────────────┘  └──────────────┘      │
│                                                             │
│  • Large buttons (> 44px)       • Responsive design         │
│  • Clear text (14px+)           • Touch-friendly            │
│  • No confusing menus           • Works on 3G/4G            │
│  • Visual feedback              • Offline menu cache        │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

| ID | Requirement | Priority |
|----|-------------|----------|
| NFR-004.1 | Mobile-first responsive design | High |
| NFR-004.2 | Minimum touch target size 44x44 pixels | High |
| NFR-004.3 | Readable font size (minimum 14px) | High |
| NFR-004.4 | Color contrast ratio > 4.5:1 | Medium |
| NFR-004.5 | Visual feedback for all actions | High |
| NFR-004.6 | Error messages in both languages | High |
| NFR-004.7 | No more than 3 taps to complete order | High |

#### NFR-005: Learnability (Uwezo wa Kujifunza)
| ID | Requirement | Target | Priority |
|----|-------------|--------|----------|
| NFR-005.1 | New user task completion time | < 2 minutes | High |
| NFR-005.2 | Contextual help availability | Available | Medium |
| NFR-005.3 | Intuitive navigation | No training needed | High |

---

### 3.4 Reliability Requirements (Mahitaji ya Uaminifu)

#### NFR-006: Data Integrity (Uadilifu wa Data)
| ID | Requirement | Priority |
|----|-------------|----------|
| NFR-006.1 | No data loss during transactions | High |
| NFR-006.2 | Automatic backup daily | High |
| NFR-006.3 | Transaction rollback on failure | High |
| NFR-006.4 | Audit trail for all orders | Medium |

#### NFR-007: Fault Tolerance (Uvumilivu wa Makosa)
| ID | Requirement | Priority |
|----|-------------|----------|
| NFR-007.1 | Graceful handling of network errors | High |
| NFR-007.2 | Automatic retry for failed payments | Medium |
| NFR-007.3 | Queue persistence during outages | High |
| NFR-007.4 | Data synchronization after reconnection | High |

---

### 3.5 Security Requirements (Mahitaji ya Usalama)

#### NFR-008: Authentication & Authorization (Uthibitishaji na Ruhusa)
| ID | Requirement | Priority |
|----|-------------|----------|
| NFR-008.1 | Password encryption (bcrypt) | High |
| NFR-008.2 | Session timeout after 30 minutes | High |
| NFR-008.3 | Role-based access control | High |
| NFR-008.4 | Login attempt limiting | Medium |
| NFR-008.5 | HTTPS for all communications | High |

#### NFR-009: Data Protection (Ulinzi wa Data)
| ID | Requirement | Priority |
|----|-------------|----------|
| NFR-009.1 | SQL injection prevention | High |
| NFR-009.2 | XSS (Cross-Site Scripting) protection | High |
| NFR-009.3 | CSRF (Cross-Site Request Forgery) tokens | Medium |
| NFR-009.4 | Input validation and sanitization | High |
| NFR-009.5 | Phone number privacy (masked) | Medium |

---

### 3.6 Scalability Requirements (Mahitaji ya Uwezo wa Kupanuka)

#### NFR-010: Capacity Planning (Mipango ya Uwezo)
| ID | Requirement | Target | Priority |
|----|-------------|--------|----------|
| NFR-010.1 | Support menu items | Up to 100 items | Medium |
| NFR-010.2 | Daily order storage | 10,000+ orders | High |
| NFR-010.3 | Concurrent user sessions | 100+ users | High |
| NFR-010.4 | Database growth handling | 1GB+ per year | Medium |

---

### 3.7 Maintainability Requirements (Mahitaji ya Utunzaji)

#### NFR-011: Code Quality (Ubora wa Code)
| ID | Requirement | Priority |
|----|-------------|----------|
| NFR-011.1 | Modular architecture | High |
| NFR-011.2 | Well-commented code | Medium |
| NFR-011.3 | Standard coding conventions | High |
| NFR-011.4 | Separation of concerns | High |

#### NFR-012: Documentation (Nyaraka)
| ID | Requirement | Priority |
|----|-------------|----------|
| NFR-012.1 | Technical documentation | High |
| NFR-012.2 | User manual (English & Swahili) | High |
| NFR-012.3 | API documentation (if applicable) | Medium |
| NFR-012.4 | Database schema documentation | High |

---

### 3.8 Portability Requirements (Mahitaji ya Uwezo wa Kusafirishwa)

#### NFR-013: Platform Support (Usaidizi wa Jukwaa)
| ID | Requirement | Priority |
|----|-------------|----------|
| NFR-013.1 | Web browser compatibility (Chrome, Firefox, Safari, Edge) | High |
| NFR-013.2 | Mobile browser compatibility | High |
| NFR-013.3 | Android device support | High |
| NFR-013.4 | iOS device support | High |
| NFR-013.5 | XAMPP/WAMP server compatibility | High |

---

### 3.9 Network Requirements (Mahitaji ya Mtandao)

#### NFR-014: Connectivity (Uunganisho)
| ID | Requirement | Target | Priority |
|----|-------------|--------|----------|
| NFR-014.1 | Minimum bandwidth required | 256 kbps | High |
| NFR-014.2 | Offline menu viewing capability | Yes | Medium |
| NFR-014.3 | Cache static assets | Yes | High |
| NFR-014.4 | Retry failed requests | 3 attempts | Medium |

---

### 3.10 Compliance Requirements (Mahitaji ya Kufuata Kanuni)

#### NFR-015: Standards Compliance
| ID | Requirement | Priority |
|----|-------------|----------|
| NFR-015.1 | WCAG 2.1 Level AA accessibility (where applicable) | Medium |
| NFR-015.2 | UTF-8 character encoding support | High |
| NFR-015.3 | GDPR compliance for data privacy | Medium |
| NFR-015.4 | University IT security policies | High |

---

### 3.11 Summary Matrix

```
┌────────────────────────────────────────────────────────────────────┐
│           NON-FUNCTIONAL REQUIREMENTS SUMMARY                    │
├────────────────────────────────────────────────────────────────────┤
│                                                                    │
│  Performance      ████████████████████░░░░  80% Critical          │
│  Usability        ████████████████████████░░  90% Critical          │
│  Reliability      ████████████████████████░░  90% Critical          │
│  Security         █████████████████████████░  95% Critical          │
│  Scalability      ████████████████████░░░░░░  70% Important         │
│  Maintainability  ████████████████████░░░░░░  70% Important         │
│  Portability      ██████████████████████░░  80% Critical          │
│  Network          ████████████████████████░░  90% Critical          │
│  Compliance       ████████████████████░░░░░░  70% Important         │
│                                                                    │
└────────────────────────────────────────────────────────────────────┘
```

---

**Document Version:** 1.0  
**Date:** June 2026
