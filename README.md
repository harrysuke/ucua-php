# UCUA PHP Framework

A comprehensive, full-featured PHP-based Web Application Boilerplate and Administrative Dashboard template. This repository provides a robust foundation for building modern web applications with built-in user management, role-based access control, analytics, content management, gamification, and interactive chat features.

## ðŸš€ Key Features

* **Advanced User Management:** Complete control over user roles, permissions, impersonation capabilities, password management, and registration workflows.
* **Administrative Control Panel:** Robust dashboard features including activity tracking, system metrics, audit logs, and system backups.
* **Interactive Chat System:** Includes components for building automated or manual live chat features, managing unanswered queries, and configuring pre-set responses.
* **Content Management System (CMS):** In-built capabilities to manage dynamic pages, feeds, comments, and application copy.
* **Analytics & Insights:** Interactive analytical tools featuring custom charts (pie charts, line charts, KPIs) to visually track platform usage and performance.
* **Gamification Engine:** Engagement features including leaderboards and customized gamification rule management to keep users motivated.
* **API Integration:** Built-in endpoints and a dedicated `ucua_api` directory for building or consuming external services easily.
* **Laragon Ready:** Optimized file structures and documentation tailored for immediate deployment on local Laragon server setups.

## ðŸ“ Repository Structure

* `.vscode/` â€” Pre-configured environment workflows for Visual Studio Code.
* `Use Case/` â€” Documentation and blueprints mapping framework use cases.
* `ai/` â€” Script logic for artificial intelligence features or integrations.
* `css/` & `js/` â€” Core assets powering the administrative interface presentation and responsiveness.
* `landing_files/` & `register_files/` & `simple_files/` â€” Modular code views separating frontend onboarding from system backends.
* `ucua_api/` â€” Structured directory for core API development.
* `blank_files/` & `generated_documents/` â€” Utilities for automated template generation and exportable report structures.

## ðŸ› ï¸ Getting Started

### Prerequisites

* **PHP:** Version 7.4 or later recommended.
* **Web Server:** Apache or Nginx (Pre-configured setup instructions included for **Laragon** environments).
* **Database:** MySQL / MariaDB.

### Installation

1. Clone the repository into your web root directory:
   ```bash
   git clone https://github.com/harrysuke/ucua-php.git
   ```
2. Import the initial database structures from the queries provided in `SCRIPT-BANK.txt`.
3. Configure your local server configuration using the guidance provided in `LARAGON.txt`.
4. Launch your local stack and navigate to `index.php` or `admLogin.php` to access the administrative system.

## ðŸ”’ Security & Roles

Access controls are heavily integrated into the core architecture:
* Access permissions are managed natively via `admin_page_permissions.php` and `admin_user_rights.php`.
* Full accountability logs can be audited inside `audit-log.php` and `audit-login.php`.
