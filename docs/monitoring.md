# Monitoring & Metrics

## Prometheus Metrics

Enable metrics in `.env`:

```
METRICS_ENABLED=true
```

Then scrape:

```
GET /metrics
```

## Logging

Logs are written to `LOG_PATH` and can be shipped using standard log forwarders.

## Health Endpoint

`GET /health` returns the server status and timestamp.
