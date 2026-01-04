# Security Overview

- **No external references**: All endpoints are locally configurable.
- **Prepared statements**: Used for all database access.
- **JWT tokens**: Signed with `JWT_SECRET`.
- **HTTPS enforcement**: Enable `ENABLE_HTTPS_REDIRECT=true` to redirect HTTP to HTTPS.
- **Rate limiting**: Configurable via `RATE_LIMIT_PER_MINUTE`.
- **CSRF**: State-changing endpoints should include CSRF validation (future UI extensions).
