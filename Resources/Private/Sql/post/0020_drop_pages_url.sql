-- Post (after the wizards): the core wizard "pageDoktypeLinkMigration" (doktype 3) and
-- ps14Migration_pageTypeLinkMigration (doktype 120) move pages.url to pages.link. TYPO3 v14 no longer has
-- the field "url" (Breaking #17406).
-- Idempotent: IF EXISTS (MariaDB).
ALTER TABLE pages DROP COLUMN IF EXISTS url;
