SIC Submission Portal - Developer Package

Contents
1) schema.sql
   - Creates all MySQL tables required for SIC project submission (submission scope only).
2) seed_cycles.sql
   - Seeds SIC program and SIC 2026 cycle and includes a script to switch to SIC 2027 next year.
3) seed_lookups_template.sql
   - Templates for seeding lookup values (impact areas, beneficiary types, SDGs).
4) SIC_Submission_Portal_DB_Schema_and_Implementation_Plan.docx
   - Human-readable specification, relationships, and WordPress implementation plan.

Execution order
1) Run schema.sql
2) Run seed_cycles.sql
3) Fill in and run seed_lookups_template.sql

Notes
- MySQL: InnoDB is required for foreign keys and transactions.
- WordPress: connect to the external DB with a dedicated wpdb instance and use prepare() for dynamic SQL.
- Files: upload to external storage and store only URLs and metadata in sic_files.

References (official docs)
- MySQL foreign keys: https://dev.mysql.com/doc/refman/8.0/en/create-table-foreign-keys.html
- MySQL JSON: https://dev.mysql.com/doc/refman/8.0/en/json.html
- WordPress wpdb: https://developer.wordpress.org/reference/classes/wpdb/
- WordPress nonces: https://developer.wordpress.org/apis/security/nonces/
