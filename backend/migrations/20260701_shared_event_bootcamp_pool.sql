-- Operation: publish-bridge shared pools
-- Event and bootcamp module records are shared across all departments.

ALTER TABLE app_events
  ADD COLUMN IF NOT EXISTS is_global TINYINT(1) DEFAULT 1;

UPDATE app_events
SET is_global = 1
WHERE is_global IS NULL OR is_global = 0;
