# GEMINI.md

@AGENTS.md

## Gemini CLI specifics

- Project rules, commands, and architecture are in `AGENTS.md` (imported above). Add shared rules there, and only Gemini-specific notes here.
- Run shell commands PowerShell-safe (Windows dev machine). Use `./vendor/bin/pint`, not `vendor/bin/pint.bat`.
- `.gemini/settings.json` hooks graphify's `hook-guard`. Don't commit machine-specific absolute paths in it.

## graphify

This project has a knowledge graph at `graphify-out/` with god nodes, community structure, and cross-file relationships.

- For codebase questions, first run `graphify query "<question>"` when `graphify-out/graph.json` exists. Use `graphify path "<A>" "<B>"` for relationships and `graphify explain "<concept>"` for focused concepts. These return a scoped subgraph, usually much smaller than `GRAPH_REPORT.md` or raw grep output.
- If `graphify-out/wiki/index.md` exists, use it for broad navigation instead of raw source browsing.
- Read `graphify-out/GRAPH_REPORT.md` only for broad architecture review or when query/path/explain don't surface enough context.
- After modifying code, run `graphify update .` to keep the graph current (AST-only, no API cost).