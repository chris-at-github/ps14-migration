# Ps14 Migration

Migration älterer TYPO3-Projekte auf die ps14-Vorlage (TYPO3 v14):
Schema-Update, SQL-Skripte und Upgrade-Wizards. Das Paket wird nur für die
Dauer einer Migration installiert und danach wieder entfernt; es gelangt nie
auf einen Server (`require-dev`, Deploy-Artefakt mit `--no-dev`).

Plan und Entscheidungen: `ps14-typo3/docs/plans/migration-tool.md`.

## Verwendung

```bash
ddev composer require --dev ps14/ps14_migration
ddev exec vendor/bin/ps14-migrate
ddev composer remove --dev ps14/ps14_migration
```

## Aufbau

| Pfad | Inhalt |
|---|---|
| `bin/ps14-migrate` | Ablaufsteuerung (Bash), läuft auch, wenn TYPO3 noch nicht startet |
| `Classes/Command/` | `ps14:migration:update-schema`: Schema-Update (add/change) ohne `_cli_`-Anmeldung |
| `Classes/Upgrades/` | generische Upgrade-Wizards für ps14-Pakete |
| `Resources/Private/Sql/pre/` | generische SQL-Skripte vor dem Schema-Update |
| `Resources/Private/Sql/post/` | generische SQL-Skripte nach den Wizards |

## Enthaltene Schritte

| Schritt | Inhalt |
|---|---|
| Pre-SQL `0010_sys_reaction_primary_key.sql` | `sys_reaction.uid` als Primärschlüssel (Tabelle aus EXT:external_import ohne `uid`) |
| `ps14:migration:update-schema` | Schema add/change ohne `_cli_`-Anmeldung; `--skip=<tabelle>.<spalte>` für Änderungen, die Daten zerstören würden |
| `ps14Migration_navigationContentMigration` | ps14_foundation 2.x: MM-Tabelle → UID-Liste in `pages.tx_foundation_navigation_content` |
| `ps14Migration_navigationLayoutMigration` | ps14_foundation 2.x: `tx_foundation_navigation_layout` `'0'` → `''` |
| `ps14Migration_pageTypeLinkMigration` | ps14_foundation 2.x: Seitentyp 120 → Core-Seitentyp „Link“ (3), `url` → `link` |
| `ps14Migration_containerRatioMigration` | ps14_container 2.x: Spaltenbreiten → `ratio`; nicht exakte Fälle werden ausgegeben |
| `ps14Migration_accordionElementsMigration`, `…_tabsElementsMigration`, `…_timelineElementsMigration` | ps14_foundation 2.x Elements → Collections der Content Blocks aus `ps14_modules` (`AbstractElementsMigration`: gleiche UIDs, Dateireferenzen, Zähler, verschachtelte Inhalte) |
| `ps14Migration_downloadsMigration` | ps14_downloads 12.x: CType `ps14_downloads` → `uploads` (ps14_downloads v14), Dateien `tx_foundation_file` → `media`, Beschreibung und Größe eingeschaltet |
| Post-SQL `0010`, `0020` | entfernt `tx_foundation_pages_content_mm` und `pages.url` (nach den Wizards) |

Nach einem erfolgreichen Lauf steht ein Eintrag in `sys_registry`
(`ps14_migration`/`completed`); ein zweiter Lauf bricht ab (`--force` zum
Übergehen). `extension:setup` wird bewusst nicht aufgerufen, weil es auch
die mit `--skip` ausgenommenen Spaltenänderungen anwendet.

## Projektspezifisches

Liegt im Projekt und wird nach der letzten Datenübernahme (Go-Live) gelöscht:

| Pfad | Inhalt |
|---|---|
| `config/migration/migration.env` | Quelle (DDEV-Provider), Quellversion, Optionen |
| `config/migration/sql/pre/*.sql` | vor dem Schema-Update, nach den generischen Skripten |
| `config/migration/sql/post/*.sql` | nach den Wizards, Begründung als Kopfkommentar |
| `packages/ps14_site/Classes/Upgrades/` | projektspezifische Upgrade-Wizards |

## Regeln für SQL-Skripte

- Ausführung in Dateinamen-Reihenfolge (`0010_…sql`, `0020_…sql`).
- Idempotent: ein zweiter Lauf ändert nichts.
- Präfix `DEV`: nur in der Entwicklung, beim Go-Live übersprungen.
- `post/`: nur, was vor den Wizards nicht laufen kann; die Begründung steht
  als Kopfkommentar in der Datei.
- Entfernt werden nur Altquellen, die im selben Lauf migriert wurden.
