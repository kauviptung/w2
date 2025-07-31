# Project Technical Overview

## 1. High-Level Architecture
The application acts as a traditional PHP web platform utilizing a single front controller (`index.php`). Incoming HTTP requests are parsed by `index.php` where session handling, configuration loading, and controller dispatching occur.

1. **Front Controller**  
   - `index.php` sets up environment, loads `vendor/autoload.php` (Composer packages) and `app/init.php`.  
   - URL segments are parsed into a `$route` array via `route()` helper (`app/helper/app.php`).  
   - Based on `route(0)` the corresponding script from `app/controller` or `admin/controller` is included.

2. **Views & Templating**  
   - Twig (v2) is used for rendering. The template directory is chosen by the active theme stored in settings (`app/init.php` lines 112-116).  
   - Theme assets live in `app/views/<Theme>` and `public/<Theme>`.

3. **Database Layer**  
   - Uses PDO with helper wrappers (`getRow`, `getRows`, `countRow` in `app/helper/data_control.php`) for common queries.  
   - Queries are mostly raw SQL; no ORM is in place.

4. **Authentication**  
   - Standard login/registration logic resides in `app/controller/auth.php`. Sessions (`msmbilisim_userlogin`) and cookies (`u_id`, `u_password`) persist logins (see `app/init.php` lines 26‑74).  
   - Optional Google OAuth sign‑in is integrated using `google/apiclient` (composer).  
   - Admin area uses similar session checks with access control flags.

5. **Configuration & Settings**  
   - Database connection and base constants defined in `app/config.php`.  
   - Runtime settings loaded from multiple tables (`settings`, `general_options`, `decoration`, etc.) inside `app/init.php`.

6. **Error Handling**  
   - Errors are generally suppressed (`error_reporting(0)` in config). Controllers set `$error/$success` flags displayed in views; there is no centralized exception handler.

7. **External Libraries**
   - **Twig** – templating engine.
   - **PHPMailer** – transactional email.
   - **PragmaRX Google2FA** – 2‑factor authentication.
   - **Stripe/Mollie/2Checkout** – payment integrations.
   - **Bacon QR Code** – QR generation for payment links.
   - **Google API Client** – OAuth for Google login.

## 2. Directory & File Structure Walkthrough
| Path | Purpose |
| --- | --- |
| `index.php` | Main entry point and router. |
| `app/` | Application core: controllers, views, helpers, config. |
| `admin/` | Admin controllers and Twig views. |
| `public/` | Assets grouped by theme (CSS, JS, images). |
| `cronjobs/` | Scheduled scripts for processing orders, payments, etc. |
| `lib/` | Third‑party payment libraries. |
| `vendor/` | Composer dependencies. |
| `smm247.sql` | Full database dump. |

Key PHP files:
1. **`index.php`** – bootstrap & route dispatcher.
2. **`app/init.php`** – starts session, connects to DB, loads helpers/classes, and loads settings.
3. **`app/controller/auth.php`** – login, registration and Google OAuth logic.
4. **`app/controller/order.php`** – handles new orders and related validations.
5. **`app/helper/data_control.php`** – large helper library including DB helpers, currency utils and more.
6. **`admin/controller/*`** – admin dashboard controllers (e.g., `orders.php`, `services.php`).

## 3. Database Schema Mapping
Below is a condensed list of the main tables. Primary keys are usually the first `id`/`*_id` column. Foreign keys are implicit (no constraints), referenced in controllers.

| Table | Key Columns | Purpose |
| --- | --- | --- |
| `admins` | `admin_id` PK | Admin user accounts & permissions. |
| `admin_constants` | `id` PK | Global admin constants (brand logo, rent status). |
| `article` | `id` PK | Blog or article posts. |
| `bank_accounts` | `id` PK | Bank transfer details. |
| `blogs` | `id` PK | Blog posts with status field. |
| `bulkedit` | `id` PK, `service_id` | Temporary service editing list. |
| `categories` | `category_id` PK | Service categories. |
| `childpanels` | `id` PK, `client_id` FK | Child reseller panels provisioned for clients. |
| `clients` | `client_id` PK | End‑user accounts. References many other tables. |
| `clients_category` | composite `id` | Mapping between clients and categories. |
| `clients_price` | `id` PK | Per‑client service pricing overrides. |
| `clients_service` | `id` PK | Services allowed for a client. |
| `client_report` | `id` PK, `client_id` | Log of user actions. |
| `currencies` | `id` PK | Supported currencies and rates. |
| `custom_settings` | `id` PK | Misc custom options. |
| `decoration` | `id` PK | Theme/UX decoration settings. |
| `earn` | `earn_id` PK, `client_id` | Referral/earning requests. |
| `files` | `id` PK | Uploaded file references. |
| `general_options` | `id` PK | Misc global options. |
| `integrations` | `id` PK | HTML/JS snippets to include on pages. |
| `invoices` | `id` PK | Generated invoices. |
| `kuponlar` | `id` PK | Discount coupons. |
| `kupon_kullananlar` | `id` PK | Coupon usage history. |
| `languages` | `id` PK | Available languages. |
| `mailforms` | `id` PK | Email templates. |
| `menus` | `id` PK | Navigation menu definitions. |
| `news` | `id` PK | News/announcement items. |
| `notifications_popup` | `id` PK | Popup notifications configuration. |
| `orders` | `order_id` PK, `client_id`, `service_id` | Customer orders. |
| `pages` | `page_id` PK | Static page content and SEO fields. |
| `panel_categories` | `id` PK | Categories for panel features. |
| `panel_info` | `panel_id` PK | Main panel metadata (domain, API key). |
| `paymentmethods` | `methodId` PK | Payment gateway definitions. |
| `payments` | `payment_id` PK, `client_id` FK | Deposit records. |
| `referral` | `referral_id` PK, `client_id` FK | Referral statistics per client. |
| `referral_payouts` | `r_p_id` PK, `client_id` FK | Referral withdrawal requests. |
| `serviceapi_alert` | `id` PK, `service_id` | Alerts for service/API failures. |
| `services` | `service_id` PK, `category_id` FK | All purchasable services. |
| `service_api` | `id` PK | External provider credentials. |
| `settings` | `id` PK | Core site settings (theme, mail, payment, etc). |
| `sync_logs` | `id` PK, `service_id`, `api_id` | Logs for provider sync tasks. |
| `tasks` | `task_id` PK | Background task records. |
| `themes` | `id` PK | Available UI themes. |
| `tickets` | `ticket_id` PK, `client_id` FK | Support tickets. |
| `ticket_reply` | `id` PK, `ticket_id` FK | Ticket messages. |
| `ticket_subjects` | `subject_id` PK | Predefined ticket subjects. |
| `units_per_page` | `id` PK | Pagination settings. |
| `updates` | `u_id` PK, `service_id` FK | Service update logs. |

**Relationship highlights** (textual ER diagram):
- `clients` ←→ `orders` (one‑to‑many).
- `services` ←→ `orders` (one‑to‑many).
- `categories` ←→ `services` (one‑to‑many).
- `paymentmethods` ←→ `payments` (gateway used).
- `tickets` ←→ `ticket_reply` (ticket conversation).
- `clients` ←→ `referral` / `referral_payouts` (affiliate system).
- No explicit foreign keys are enforced; relationships are maintained in application logic.

## 4. Execution Trace Example
### Home Page (`/`)
1. `index.php` parses the request and builds `$route`. If no route is provided and the user isn’t logged in, `$route[0]` becomes `auth` (lines 81‑89 in `index.php`).
2. `app/init.php` is included; session starts and DB connection established (lines 1‑10). Helpers and classes are loaded dynamically via `glob()` (lines 197‑203).  
3. Controller resolved: for home page this typically maps to `app/controller/neworder.php` or `auth.php` depending on login state.  
4. Controller sets up variables (`$title`, `$content`, etc.) and eventually calls `$twig->render()` within `index.php` (around lines 324‑432) to output the theme template.

### Form Submission Example – Add Funds (`/addfunds` POST)
1. User accesses `/addfunds`. `index.php` loads `app/controller/addfunds.php` which may further dispatch to specific initiators (e.g., `app/controller/addfunds/Initiators/paytm.php`).
2. On POST, the controller validates amount, selects payment method, and inserts a row into `payments` table (`payment_status` pending).  
3. Depending on gateway, additional libraries from `lib/` or `vendor/` are invoked (e.g., Stripe API client).  
4. After processing, user is redirected back with success/error message which the Twig template displays.

## 5. Security & Performance Notes
- **SQL Injection**: Most queries use prepared statements, but certain dynamic constructions in helpers (`app/helper/data_control.php`) concatenate columns directly which might be risky if not sanitized.
- **XSS**: Output escaping is mostly disabled (`autoescape => false` in Twig). User‑supplied content should be sanitized before rendering to avoid XSS.
- **Sessions**: PHP sessions store login state. Cookies persist credentials in plaintext hashes—no HttpOnly/secure flags shown, so session hijacking risk exists.
- **CSRF**: No universal CSRF tokens were spotted in forms, making POST routes vulnerable.
- **Password Hashing**: `md5` is used for storing passwords. This is insecure by modern standards.
- **Caching**: None evident; each request hits the database directly which could be optimized.
- **Autoloading**: Custom autoload uses simple `glob`; Composer’s PSR‑4 autoloading could improve performance and structure.

## 6. Coding Standards & Style
- The code base does not strictly follow PSR‑12; indentation and naming are inconsistent (~40‑50% compliance).
- Docblocks and inline comments are sparse. Many controllers have minimal comments making it harder to follow logic.
- Recommended improvements:
  - Adopt PSR‑12 and namespaces.
  - Introduce a front‑controller framework (Laravel/Symfony) or at least a routing library.
  - Add PHPDoc blocks for functions and classes.
  - Implement centralized error/exception handling.

## 7. Suggested Improvements
1. **API Integration**
   - Abstract external service calls (payment gateways, service providers) into dedicated classes with clear interfaces.
   - Consider using Guzzle for HTTP requests and handling retries/logging uniformly.
2. **UX/UI**
   - Consolidate and modernize the themes. Use a frontend framework (e.g., Bootstrap 5) and ensure responsive design.
   - Improve accessibility and add client-side validation.
3. **Notification Module**
   - Implement webhook/notification integration (Telegram, Slack) for critical events like new orders or low balance.
   - Use a job queue (e.g., Redis based) for sending notifications asynchronously.

---
**Analysis Performed**
- Explored repository structure and key files.
- Parsed `smm247.sql` to extract table schemas.
- Reviewed major controllers and helpers for application flow.
- Identified external libraries from `composer.json`.
