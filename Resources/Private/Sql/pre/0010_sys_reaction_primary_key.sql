-- TYPO3 v14 (EXT:reactions) needs sys_reaction.uid as primary key.
-- Projects with EXT:external_import (v12) already have the table, without uid: external_import only adds its own
-- column. The SchemaMigrator splits AUTO_INCREMENT and PRIMARY KEY into two statements, which both fail on their own.
-- Idempotent (MariaDB): IF EXISTS / IF NOT EXISTS.
ALTER TABLE IF EXISTS sys_reaction ADD COLUMN IF NOT EXISTS uid INT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY FIRST;
