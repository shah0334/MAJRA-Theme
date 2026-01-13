-- SIC Submission Portal Schema (submission scope only)
-- Target: MySQL 8.0+ (utf8mb4, InnoDB)

CREATE DATABASE IF NOT EXISTS sic_portal
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE sic_portal;

-- =========================
-- Program and Cycle (Year)
-- =========================
CREATE TABLE sic_programs (
  program_id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
  program_code VARCHAR(20) NOT NULL,      -- 'SIC'
  program_name VARCHAR(100) NOT NULL,     -- 'SIC'
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (program_id),
  UNIQUE KEY uq_program_code (program_code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE sic_program_cycles (
  cycle_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  program_id SMALLINT UNSIGNED NOT NULL,

  cycle_year SMALLINT UNSIGNED NOT NULL,  -- 2026, 2027, etc
  cycle_label VARCHAR(50) NOT NULL,       -- 'SIC 2026'

  is_active TINYINT(1) NOT NULL DEFAULT 0,

  submission_open_at  DATETIME NULL,
  submission_close_at DATETIME NULL,

  terms_version   VARCHAR(50) NULL,
  privacy_version VARCHAR(50) NULL,

  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY (cycle_id),
  UNIQUE KEY uq_program_year (program_id, cycle_year),
  KEY idx_cycle_active (program_id, is_active),

  CONSTRAINT fk_cycles_program
    FOREIGN KEY (program_id)
    REFERENCES sic_programs(program_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- =========================
-- Applicants (linked to WP)
-- =========================
CREATE TABLE sic_applicants (
  applicant_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  wp_user_id BIGINT UNSIGNED NULL,

  email VARCHAR(320) NOT NULL,
  first_name VARCHAR(100) NULL,
  last_name  VARCHAR(100) NULL,
  phone      VARCHAR(50)  NULL,

  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY (applicant_id),
  UNIQUE KEY uq_applicant_email (email),
  UNIQUE KEY uq_applicant_wp_user (wp_user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- =========================
-- Organizations (cross-year container)
-- =========================
CREATE TABLE sic_organizations (
  organization_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  created_by_applicant_id BIGINT UNSIGNED NOT NULL,

  canonical_name VARCHAR(255) NULL,

  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY (organization_id),
  KEY idx_org_created_by (created_by_applicant_id),

  CONSTRAINT fk_org_created_by
    FOREIGN KEY (created_by_applicant_id)
    REFERENCES sic_applicants(applicant_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE sic_organization_members (
  organization_id BIGINT UNSIGNED NOT NULL,
  applicant_id    BIGINT UNSIGNED NOT NULL,

  member_role ENUM('owner','editor','viewer') NOT NULL DEFAULT 'owner',
  added_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

  PRIMARY KEY (organization_id, applicant_id),

  CONSTRAINT fk_org_members_org
    FOREIGN KEY (organization_id)
    REFERENCES sic_organizations(organization_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,

  CONSTRAINT fk_org_members_app
    FOREIGN KEY (applicant_id)
    REFERENCES sic_applicants(applicant_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- =========================
-- Organization Profile per Cycle (the form data)
-- =========================
CREATE TABLE sic_organization_profiles (
  org_profile_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,

  cycle_id BIGINT UNSIGNED NOT NULL,
  organization_id BIGINT UNSIGNED NOT NULL,
  created_by_applicant_id BIGINT UNSIGNED NOT NULL,

  organization_name      VARCHAR(255) NOT NULL,
  trade_license_number   VARCHAR(100) NULL,
  website_url            VARCHAR(2048) NULL,

  emirate_of_registration VARCHAR(100) NULL,
  legal_entity_type       VARCHAR(150) NULL,
  industry                VARCHAR(150) NULL,
  is_freezone             TINYINT(1) NOT NULL DEFAULT 0,

  business_activity_type  VARCHAR(150) NULL,
  number_of_employees     INT UNSIGNED NULL,
  annual_turnover_band    VARCHAR(150) NULL,

  csr_implemented TINYINT(1) NULL,

  status ENUM('draft','finalized') NOT NULL DEFAULT 'draft',
  finalized_at TIMESTAMP NULL,

  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY (org_profile_id),

  UNIQUE KEY uq_org_cycle (cycle_id, organization_id),
  UNIQUE KEY uq_org_profile_cycle (org_profile_id, cycle_id),

  KEY idx_org_profile_cycle (cycle_id),
  KEY idx_org_profile_org (organization_id),
  KEY idx_org_profile_created_by (created_by_applicant_id),

  CONSTRAINT fk_org_profile_cycle
    FOREIGN KEY (cycle_id)
    REFERENCES sic_program_cycles(cycle_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,

  CONSTRAINT fk_org_profile_org
    FOREIGN KEY (organization_id)
    REFERENCES sic_organizations(organization_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,

  CONSTRAINT fk_org_profile_created_by
    FOREIGN KEY (created_by_applicant_id)
    REFERENCES sic_applicants(applicant_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- =========================
-- Files (stored externally, linked to a Cycle)
-- =========================
CREATE TABLE sic_files (
  file_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  cycle_id BIGINT UNSIGNED NOT NULL,

  storage_provider VARCHAR(30) NOT NULL,      -- s3, azure, gcs
  storage_url      VARCHAR(2048) NOT NULL,
  storage_key      VARCHAR(512) NULL,

  original_filename VARCHAR(255) NULL,
  mime_type         VARCHAR(127) NULL,
  size_bytes        BIGINT UNSIGNED NULL,
  sha256            CHAR(64) NULL,

  uploaded_by_applicant_id BIGINT UNSIGNED NULL,

  meta JSON NULL,

  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

  PRIMARY KEY (file_id),
  KEY idx_files_cycle (cycle_id),
  KEY idx_files_uploaded_by (uploaded_by_applicant_id),

  CONSTRAINT fk_files_cycle
    FOREIGN KEY (cycle_id)
    REFERENCES sic_program_cycles(cycle_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,

  CONSTRAINT fk_files_uploaded_by
    FOREIGN KEY (uploaded_by_applicant_id)
    REFERENCES sic_applicants(applicant_id)
    ON DELETE SET NULL
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE sic_organization_profile_files (
  org_profile_id BIGINT UNSIGNED NOT NULL,
  file_role ENUM('logo','trade_license_certificate') NOT NULL,
  file_id BIGINT UNSIGNED NOT NULL,

  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

  PRIMARY KEY (org_profile_id, file_role),
  UNIQUE KEY uq_org_profile_file_fileid (file_id),

  CONSTRAINT fk_org_profile_files_profile
    FOREIGN KEY (org_profile_id)
    REFERENCES sic_organization_profiles(org_profile_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,

  CONSTRAINT fk_org_profile_files_file
    FOREIGN KEY (file_id)
    REFERENCES sic_files(file_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE sic_org_csr_activities (
  csr_activity_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  org_profile_id BIGINT UNSIGNED NOT NULL,

  program_name VARCHAR(255) NOT NULL,
  allocated_amount_aed DECIMAL(15,2) NULL,

  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

  PRIMARY KEY (csr_activity_id),
  KEY idx_csr_profile (org_profile_id),

  CONSTRAINT fk_csr_profile
    FOREIGN KEY (org_profile_id)
    REFERENCES sic_organization_profiles(org_profile_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- =========================
-- Projects (cycle-specific submissions)
-- =========================
CREATE TABLE sic_projects (
  project_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,

  cycle_id BIGINT UNSIGNED NOT NULL,
  org_profile_id BIGINT UNSIGNED NOT NULL,
  created_by_applicant_id BIGINT UNSIGNED NOT NULL,

  project_name VARCHAR(255) NOT NULL,
  project_stage VARCHAR(150) NULL,
  project_description TEXT NULL,

  start_date DATE NULL,
  end_date   DATE NULL,

  total_beneficiaries_targeted INT UNSIGNED NULL,
  total_beneficiaries_reached  INT UNSIGNED NULL,

  contributes_env_social ENUM('yes','no','unknown') NULL,
  has_governance_monitoring ENUM('yes','no','unknown') NULL,

  location_search_text VARCHAR(255) NULL,
  location_address     VARCHAR(512) NULL,
  location_place_id    VARCHAR(255) NULL,
  location_provider    VARCHAR(50)  NULL,
  latitude  DECIMAL(10,7) NULL,
  longitude DECIMAL(10,7) NULL,

  leadership_women_pct DECIMAL(5,2) NULL,
  team_women_pct       DECIMAL(5,2) NULL,
  leadership_pod_pct   DECIMAL(5,2) NULL,
  team_pod_pct         DECIMAL(5,2) NULL,
  team_youth_pct       DECIMAL(5,2) NULL,
  engages_youth        ENUM('yes','no','unknown') NULL,
  involves_influencers ENUM('yes','no','unknown') NULL,

  submission_status ENUM('draft','submitted','locked') NOT NULL DEFAULT 'draft',

  profile_completed      TINYINT(1) NOT NULL DEFAULT 0,
  details_completed      TINYINT(1) NOT NULL DEFAULT 0,
  evidence_completed     TINYINT(1) NOT NULL DEFAULT 0,
  pinpoint_completed     TINYINT(1) NOT NULL DEFAULT 0,
  demographics_completed TINYINT(1) NOT NULL DEFAULT 0,

  disclaimer_accepted     TINYINT(1) NOT NULL DEFAULT 0,
  disclaimer_accepted_at  TIMESTAMP NULL,
  terms_version           VARCHAR(50) NULL,
  privacy_version         VARCHAR(50) NULL,

  submitted_at TIMESTAMP NULL,
  locked_at    TIMESTAMP NULL,

  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY (project_id),
  KEY idx_projects_cycle (cycle_id),
  KEY idx_projects_profile (org_profile_id),
  KEY idx_projects_status (submission_status),

  CONSTRAINT fk_projects_cycle
    FOREIGN KEY (cycle_id)
    REFERENCES sic_program_cycles(cycle_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,

  CONSTRAINT fk_projects_profile_cycle
    FOREIGN KEY (org_profile_id, cycle_id)
    REFERENCES sic_organization_profiles(org_profile_id, cycle_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,

  CONSTRAINT fk_projects_created_by
    FOREIGN KEY (created_by_applicant_id)
    REFERENCES sic_applicants(applicant_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE sic_project_files (
  project_id BIGINT UNSIGNED NOT NULL,
  file_role ENUM(
    'profile_image',
    'photos',
    'impact_report',
    'sustainable_impact_plan',
    'testimonials_file'
  ) NOT NULL,
  file_id BIGINT UNSIGNED NOT NULL,

  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

  PRIMARY KEY (project_id, file_role),
  UNIQUE KEY uq_project_file_fileid (file_id),

  CONSTRAINT fk_project_files_project
    FOREIGN KEY (project_id)
    REFERENCES sic_projects(project_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,

  CONSTRAINT fk_project_files_file
    FOREIGN KEY (file_id)
    REFERENCES sic_files(file_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE sic_project_links (
  link_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  project_id BIGINT UNSIGNED NOT NULL,

  link_role ENUM('testimonials_media_coverage') NOT NULL,
  url VARCHAR(2048) NOT NULL,

  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

  PRIMARY KEY (link_id),
  KEY idx_links_project (project_id),

  CONSTRAINT fk_links_project
    FOREIGN KEY (project_id)
    REFERENCES sic_projects(project_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- =========================
-- Lookups and multi-select joins
-- =========================
CREATE TABLE sic_impact_areas (
  impact_area_id TINYINT UNSIGNED NOT NULL,
  name VARCHAR(100) NOT NULL,
  name_ar VARCHAR(255) NULL,
  PRIMARY KEY (impact_area_id),
  UNIQUE KEY uq_impact_area_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE sic_project_impact_areas (
  project_id BIGINT UNSIGNED NOT NULL,
  impact_area_id TINYINT UNSIGNED NOT NULL,
  PRIMARY KEY (project_id, impact_area_id),

  CONSTRAINT fk_pia_project
    FOREIGN KEY (project_id)
    REFERENCES sic_projects(project_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,

  CONSTRAINT fk_pia_area
    FOREIGN KEY (impact_area_id)
    REFERENCES sic_impact_areas(impact_area_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE sic_beneficiary_types (
  beneficiary_type_id TINYINT UNSIGNED NOT NULL,
  name VARCHAR(150) NOT NULL,
  name_ar VARCHAR(255) NULL,
  PRIMARY KEY (beneficiary_type_id),
  UNIQUE KEY uq_beneficiary_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE sic_project_beneficiaries (
  project_id BIGINT UNSIGNED NOT NULL,
  beneficiary_type_id TINYINT UNSIGNED NOT NULL,
  PRIMARY KEY (project_id, beneficiary_type_id),

  CONSTRAINT fk_pb_project
    FOREIGN KEY (project_id)
    REFERENCES sic_projects(project_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,

  CONSTRAINT fk_pb_beneficiary
    FOREIGN KEY (beneficiary_type_id)
    REFERENCES sic_beneficiary_types(beneficiary_type_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE sic_sdgs (
  sdg_id TINYINT UNSIGNED NOT NULL,
  name VARCHAR(150) NOT NULL,
  name_ar VARCHAR(255) NULL,
  PRIMARY KEY (sdg_id),
  UNIQUE KEY uq_sdg_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE sic_project_sdgs (
  project_id BIGINT UNSIGNED NOT NULL,
  sdg_id TINYINT UNSIGNED NOT NULL,
  PRIMARY KEY (project_id, sdg_id),

  CONSTRAINT fk_psdg_project
    FOREIGN KEY (project_id)
    REFERENCES sic_projects(project_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,

  CONSTRAINT fk_psdg_sdg
    FOREIGN KEY (sdg_id)
    REFERENCES sic_sdgs(sdg_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
