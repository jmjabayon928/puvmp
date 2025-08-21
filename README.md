# 🚍 Department of Transportation – PUVMP Systems (Legacy PHP/MySQL)

A legacy system I designed and developed while part of the **Public Utility Vehicle Modernization Program (PUVMP)** of the Philippines Department of Transportation (DOTr).  

It supported nationwide **transport cooperatives, HRIS & Payroll, procurement, inventory, franchises, reporting, and communications** — all in one integrated platform.

> ⚠️ **Note**: This is a legacy codebase (PHP + MySQLi, procedural style). The repository is shared for **portfolio and historical value only**. All data has been stripped; only schema and code remain.

---

## ✨ Features

**1. Home**
- Home Page
- Dashboard
- Verified Transport Cooperatives
- Employees Locator

**2. Transactions Management**

**3. Communications and Documentations**
- Communications Routing
  - All / Incoming / Outgoing / Internal
- Documents Management

**4. Notifications and Calendar**
- Notifications (Past, Current/Upcoming)
- Calendar of Activities (Past, Current/Upcoming)

**5. Planning and Evaluation**
- Accomplishment Reports
- Reports Monitoring
- Types of Reports

**6. Operations Division**
- SMS: Certification of Good Standing
- Transport Cooperatives Directory  
  - Accredited / Registered / Applying / Federations / Delisted
- Transport Cooperatives Reports  
  - Units, Members, Assets/Liabilities, Capitalizations, Net Surplus, By Unit Type, CGS Validity
- Transport Cooperatives Incomplete Records

**7. PMO Reports**

**8. LTFRB Franchises**
- Franchises
- Import Franchise

**9. HRIS & Payroll**
- HRIS: Employees, Hirings, Promotions, Separations, Performance, L&D, Records
- Internal Comms: Special/Office/Memo Orders, Circulars, Travel Orders
- Payroll: Payrolls, Reports, Leave Credits, Leave Apps, Overtime
- Attendance: By Employee/Date/Type, Import, Mission Slips

**10. Procurement System**
- Procurement Types, Modes, Requirements
- Browse Procurements

**11. Inventory Management**
- By Category
- By Division/Department
- By Employee

**12. Settings**
- Agency Settings: Divisions, Positions, Employee Types, Salary Grades, Suppliers
- System Settings: Regional Areas, File Types, Item Categories
- HR Settings: Salary, Periods, Groups, Tax/SSS/PhilHealth Brackets
- User Settings: Users, Access, Online Users
- Transactions Log

---

## 🗄 Database (Schema-Only)

The repo includes **`otcsystems.sql`** with `CREATE TABLE` statements only.  
No production data is included.

---

## 🚀 Quick Start (with Docker)

Clone the repo and run:

```bash
docker-compose up --build
```

Open:

- **App** → http://localhost:8080  
- **Adminer** → http://localhost:8081  
  (Server: `mysql`, User: `otc_user`, Pass: `change_me`, DB: `otcsystems`)

Since no data is provided, the app pages requiring login/data will show empty states.  
This repo focuses on **architecture and schema**.

---

## 🏅 Recognitions

**From Mr. Manny Camagay, Project Manager - PUVMP - DOTr:**

> “Mr. Abayon is a very trustworthy and efficient team player.  
> He developed the systems design for data governance and data sharing not only for the PUVMP but the whole sector in public transport. The system resolved many long-standing issues and bottlenecks in mobility, cooperative operations, and simplified digital finance processes nationwide. His departure was a huge loss and his role remains irreplaceable.”  

**From Mr. Paul Visaya, Director of MIS, DOTr:**  
> Recognized the system for adding **great value to the department’s Management Information Service**, strengthening digital transformation and cross-agency coordination.

---

## 🔐 Security & Data Notes

- No production data is included.  
- All credentials/secrets/uploads/vendors are removed or gitignored.  
- Code reflects legacy practices from its time.  
- In modern projects I use `password_hash`, prepared statements/ORM, CI/CD, and cloud-native services.

---

## 🛠 Vendors

- `tcpdf/` (PDF generation) and `vendors/` (3rd-party libs) are excluded to keep the repo slim.  
- They can be reinstalled via Composer or added manually if needed.

---

## 🔮 Modernization Notes (what I’d do today)

- **API**: Node.js + TypeScript (Nest/Express), RBAC, audited endpoints.  
- **Frontend**: React/Next.js with form validation and role-based routing.  
- **DB layer**: Prisma/TypeORM; unify logs into one table with date column or keep monthly partitions behind views.  
- **Cloud**: AWS RDS, S3 for docs, CloudFront, containerized deploy (ECS/EKS).  
- **CI/CD**: GitHub Actions (lint/test/build/deploy).  
- **Observability**: metrics, logs, error tracking.

---

## 👤 Author

**Jeff Martin Abayon**  
[LinkedIn](https://www.linkedin.com/in/jeff-martin-abayon-calgary/) | [GitHub](https://github.com/jmjabayon928)

---
