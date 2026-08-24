# Schatz Rentals — Cleaner Applicant App

Laravel 12 + Vue 3 + Inertia (TypeScript, Tailwind v3) app for hiring turnover cleaners for Schatz Rentals units.

- **GWD Project ID:** 96 (client: Brandon Schatz, ID 16)
- **Local dev:** SQLite (`database/database.sqlite`). Production: PostgreSQL.
- **Tests:** PHPUnit (`php artisan test`). Pest is NOT installed despite the `tests/Pest.php` stub.

## Structure

- `/apply` — public application form (`pages/apply/Form.vue`), no auth. `/` redirects here.
- `/applicants` — admin review dashboard (`pages/applicants/Index.vue`, `Show.vue`), auth required. `/dashboard` redirects here.
- `app/Models/Applicant.php` — holds `STATUSES` and `SCORE_CRITERIA` (the 6-criterion interview scorecard, 1–5 each, max 30).
- Shared frontend types/labels: `resources/js/types/applicant.ts`.

## Conventions

- **Public registration is disabled** (routes/auth.php). Create admin users via tinker/seeder only.
- Public pages (`apply/*`) use a standalone warm editorial design (Fraunces + Instrument Sans via bunny.net, pine `#1c352a` / paper `#f6f1e7` / amber `#c97f2b`). Admin pages use the starter kit's AppLayout/shadcn components.
- `score_total` is computed server-side in `Admin\ApplicantController::update()` from the submitted scores; don't set it directly.
- Empty reference rows are stripped in `ApplicationController::store()`.
