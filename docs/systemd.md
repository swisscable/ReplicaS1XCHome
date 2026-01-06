# systemd Setup

Use systemd to keep the API server running on a headless Debian 13 host.

## Example Service Unit

Create `/etc/systemd/system/iptv-server.service`:

```ini
[Unit]
Description=IPTV Server
After=network.target mariadb.service

[Service]
Type=simple
User=www-data
WorkingDirectory=/opt/iptv-server
EnvironmentFile=/opt/iptv-server/.env
ExecStart=/usr/bin/php -S 0.0.0.0:8080 -t /opt/iptv-server/public
Restart=always
RestartSec=5

[Install]
WantedBy=multi-user.target
```

## Enable

```bash
sudo systemctl daemon-reload
sudo systemctl enable --now iptv-server.service
```
