# MediaModule

## Purpose

Manages streams, playlists, and EPG output for IPTV clients.

## Key Components

- `MediaRepository`
  - Retrieves live, VOD, and series metadata from the database.
- `MediaService`
  - Builds M3U playlists with Xtream Codes URLs.
  - Generates XMLTV-compatible EPG output.

## Inputs/Outputs

- **Inputs:** User identity and requested output format.
- **Outputs:** List of stream metadata or formatted playlist strings.

## Dependencies

- PDO database connection (Infrastructure)
- `Config` for base URL

## Example

```php
$playlist = $mediaService->buildPlaylist($user, 'ts');
```

## Integration Notes

`ApiController` uses MediaService to satisfy `/get.php`, `/xmltv.php`, and stream listing actions.
