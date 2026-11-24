-- Post (after the wizards): ps14Migration_navigationContentMigration reads this table and writes the uid lists
-- into pages.tx_foundation_navigation_content. Afterwards the MM table of ps14_foundation 2.x is no longer used.
-- Idempotent: IF EXISTS.
DROP TABLE IF EXISTS tx_foundation_pages_content_mm;
