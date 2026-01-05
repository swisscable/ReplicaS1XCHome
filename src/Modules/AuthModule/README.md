# AuthModule

## Purpose

Provides JWT-based authentication helpers and builds Xtream Codes-compatible response payloads.

## Key Components

- `AuthService`
  - Issues JWT tokens for authenticated users.
  - Formats `user_info` and `server_info` payloads.

## Inputs/Outputs

- **Inputs:** User array with `id`, `username`, `password`, `status`, `expires_at`.
- **Outputs:** Array formatted for `/player_api.php` responses.

## Dependencies

- `JwtService` (Infrastructure)
- `Config` (Infrastructure)

## Example

```php
$payload = $authService->buildAuthPayload($user);
```

## Integration Notes

`ApiController::playerApi()` uses AuthService to build authentication responses.
