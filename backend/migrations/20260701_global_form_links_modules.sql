-- Operation: publish-bridge global module pools
-- Forms and Linktree modules are shared across all departments.

ALTER TABLE apps
  ADD COLUMN IF NOT EXISTS is_global_module TINYINT(1) DEFAULT 0;

UPDATE apps
SET is_global_module = 1
WHERE module_type IN ('form', 'links', 'event', 'bootcamp');
