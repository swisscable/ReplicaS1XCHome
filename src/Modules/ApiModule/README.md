# ApiModule

## Purpose

Exposes **Xtream Codes-compatible** HTTP endpoints for client integrations (e.g., TiviMate). It converts incoming requests to domain service calls and formats responses according to Xtream Codes API behavior.

## Key Responsibilities

- Route registration (`ApiRoutes`)
- Request validation and rate limiting
- Response formatting for Xtream Codes endpoints

## Public Endpoints

| Endpoint | Method | Description |
| --- | --- | --- |
| `/player_api.php` | GET | Authentication and metadata (actions include `get_live_streams`, `get_vod_streams`, `get_series`). |
| `/get.php` | GET | M3U playlist generation. |
| `/xmltv.php` | GET | XMLTV EPG output. |
| `/health` | GET | Health check. |
| `/metrics` | GET | Prometheus-compatible metrics (optional). |

## Inputs/Outputs

- **Inputs:** query string parameters like `username`, `password`, `action`, and `output`.
- **Outputs:** JSON for API responses, M3U for playlists, XML for EPG.

## Dependencies

- `AuthService` (AuthModule)
- `UserService` (UserModule)
- `MediaService` (MediaModule)
- `RateLimiter` (Infrastructure)
- `InputValidator` (Infrastructure)

## Example Usage

```http
GET /player_api.php?username=demo&password=demo
GET /get.php?username=demo&password=demo&type=m3u_plus&output=ts
```

## Integration Notes

The router is initialized in `public/index.php` and routes are registered via `ApiRoutes::routes()`.
