-- Seed SIC program and SIC 2026 cycle
-- Run this after the schema is created.

INSERT INTO sic_programs (program_code, program_name)
VALUES ('SIC', 'SIC')
ON DUPLICATE KEY UPDATE program_name = VALUES(program_name);

-- Create or update the 2026 cycle and mark it active
INSERT INTO sic_program_cycles (program_id, cycle_year, cycle_label, is_active)
SELECT program_id, 2026, 'SIC 2026', 1
FROM sic_programs
WHERE program_code = 'SIC'
ON DUPLICATE KEY UPDATE
  cycle_label = VALUES(cycle_label),
  is_active = VALUES(is_active);

-- Optional: set submission window and legal version strings
-- UPDATE sic_program_cycles pc
-- JOIN sic_programs p ON p.program_id = pc.program_id
-- SET pc.submission_open_at  = '2026-01-01 00:00:00',
--     pc.submission_close_at = '2026-12-31 23:59:59',
--     pc.terms_version = 'v1',
--     pc.privacy_version = 'v1'
-- WHERE p.program_code = 'SIC' AND pc.cycle_year = 2026;

-- Create SIC 2027 and switch active cycle to 2027
-- Use this next year.

-- Deactivate all cycles for SIC
UPDATE sic_program_cycles pc
JOIN sic_programs p ON p.program_id = pc.program_id
SET pc.is_active = 0
WHERE p.program_code = 'SIC';

-- Create or update SIC 2027 and mark it active
INSERT INTO sic_program_cycles (program_id, cycle_year, cycle_label, is_active)
SELECT program_id, 2027, 'SIC 2027', 1
FROM sic_programs
WHERE program_code = 'SIC'
ON DUPLICATE KEY UPDATE
  cycle_label = VALUES(cycle_label),
  is_active = VALUES(is_active);
