# UserModule

## Purpose

Handles user authentication and user lookup against the database.

## Key Components

- `UserRepository`
  - Uses prepared statements to fetch user records.
  - Enforces active status checks.
- `UserService`
  - Orchestrates authentication logic.

## Inputs/Outputs

- **Inputs:** `username` and `password`.
- **Outputs:** User record array or `null`.

## Dependencies

- PDO database connection (Infrastructure)

## Example

```php
$user = $userService->authenticate($username, $password);
```

## Integration Notes

`ApiController` relies on `UserService` for all authentication flows.
