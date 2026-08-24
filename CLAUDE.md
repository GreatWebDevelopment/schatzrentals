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

## Deployment (GWD preview)

- **Live preview:** https://schatz-rentals-imfjjk.p.gwd.dev (https://schatz-rentals.p.gwd.dev 301s to it). GWD site ID 71 (slug `schatz-rentals-imfjjk`, from the project slug); sites 68–70 are stale duplicates from setup attempts.
- **GitHub:** https://github.com/GreatWebDevelopment/schatzrentals (must stay PUBLIC — the platform clones anonymously). `site_setup_wizard` needs the `.git`-suffixed URL.
- **Preview host:** `ubuntu@3.146.165.75`, container `gwd-preview-schatz-rentals-imfjjk` on port 10077, Caddy config in `/etc/caddy/sites/`. Containers idle-sleep; first request 502s, then hit `/wake` (or let the wake page do it) and retry.
- **CRITICAL — template overlay:** the platform's Laravel builder overlays its own starter-kit template over the repo, which **overwrites `routes/auth.php` and `bootstrap/app.php`** (restores `/register` routes, adds a 419→redirect-back handler that masks CSRF errors as silent 302s to `/`). That's why `RegisteredUserController` 404s unconditionally — never rely on routes/auth.php alone for auth lockdown.
- **Database:** SQLite inside the container — **data is lost on redeploy**. `provision_database` (Neon Postgres) has returned 500 since April 2026; switch when fixed, or use the `gwd-postgres` container on the preview host.
- **Admin accounts:** created manually via `docker exec ... php artisan tinker` (registration is disabled). The platform smoke test may create `smoke-test-*@example.com` users — safe to delete.

## Conventions

- **Public registration is disabled** (routes/auth.php). Create admin users via tinker/seeder only.
- Public pages (`apply/*`) use a standalone warm editorial design (Fraunces + Instrument Sans via bunny.net, pine `#1c352a` / paper `#f6f1e7` / amber `#c97f2b`). Admin pages use the starter kit's AppLayout/shadcn components.
- `score_total` is computed server-side in `Admin\ApplicantController::update()` from the submitted scores; don't set it directly.
- Empty reference rows are stripped in `ApplicationController::store()`.
