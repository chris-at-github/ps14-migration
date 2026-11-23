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
