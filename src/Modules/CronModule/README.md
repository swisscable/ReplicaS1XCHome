# CronModule

## Purpose

Coordinates automated jobs (cron/systemd timers) for maintenance, cache cleanup, EPG refresh, and backups.

## Key Components

- `CronService`
  - Entry point for scheduled routines.

## Inputs/Outputs

- **Inputs:** None (invoked by CLI scripts).
- **Outputs:** Log entries.

## Dependencies

- `LoggerInterface` (Infrastructure)

## Example

```bash
php bin/cron.php daily
```

## Integration Notes

`bin/cron.php` invokes `CronService` based on CLI arguments.
