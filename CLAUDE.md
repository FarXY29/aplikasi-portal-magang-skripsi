# CLAUDE.md

@AGENTS.md

## Claude Code specifics

- Project rules, commands, and architecture are in `AGENTS.md` (imported above). Add shared rules there, and only Claude-specific notes here.
- For fast feedback, run the single relevant test first (`php artisan test --compact --filter=...`), then the full suite before finishing.
- `.claude/settings.local.json` holds personal permissions. Keep it out of git.