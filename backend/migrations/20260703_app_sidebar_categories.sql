ALTER TABLE apps
    ADD COLUMN sidebar_category_key VARCHAR(64) NULL AFTER director_only,
    ADD COLUMN sidebar_category_label VARCHAR(120) NULL AFTER sidebar_category_key,
    ADD COLUMN sidebar_order INT DEFAULT 100 AFTER sidebar_category_label,
    ADD COLUMN is_primary TINYINT(1) DEFAULT 0 AFTER sidebar_order;

CREATE INDEX idx_apps_sidebar_category
    ON apps (department_id, is_active, sidebar_category_key, is_primary, sidebar_order);
