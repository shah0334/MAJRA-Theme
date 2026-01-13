-- Database Migration and Seeding Script
-- Usage: mysql -h <host> -u <user> -p <pass> -D <db> --force < this_file.sql

-- 1. Schema Updates (Adding name_ar column)
-- Using --force flag ensures that if these fail (column exists), the script continues.
ALTER TABLE sic_impact_areas ADD COLUMN name_ar VARCHAR(255) NULL AFTER name;
ALTER TABLE sic_beneficiary_types ADD COLUMN name_ar VARCHAR(255) NULL AFTER name;
ALTER TABLE sic_sdgs ADD COLUMN name_ar VARCHAR(255) NULL AFTER name;

-- 2. Seed Data (Upsert Logic)

-- Impact Areas
INSERT INTO sic_impact_areas (impact_area_id, name, name_ar) VALUES
(1, 'Art, Culture & Heritage', 'الفن والثقافة والتراث'),
(2, 'Environment', 'البيئة'),
(3, 'Technology', 'التكنولوجيا'),
(4, 'Health', 'الصحة'),
(5, 'Sports', 'الرياضة'),
(6, 'Education', 'التعليم')
ON DUPLICATE KEY UPDATE name_ar = VALUES(name_ar);

-- Beneficiaries
INSERT INTO sic_beneficiary_types (beneficiary_type_id, name, name_ar) VALUES
(1, 'Youth & Students', 'الشباب والطلاب'),
(2, 'Women & Girls', 'النساء والفتيات'),
(3, 'Elderly', 'كبار السن'),
(4, 'People of Determination', 'أصحاب الهمم'),
(5, 'Families', 'الأسر'),
(6, 'Small Businesses & Entrepreneurs', 'الشركات الصغيرة ورواد الأعمال'),
(7, 'Creative Professionals & Innovators', 'المهنيون المبدعون والمبتكرون'),
(8, 'Third Sector Organizations', 'مؤسسات القطاع الثالث'),
(9, 'General Public', 'الجمهور العام')
ON DUPLICATE KEY UPDATE name_ar = VALUES(name_ar);

-- SDGs
INSERT INTO sic_sdgs (sdg_id, name, name_ar) VALUES
(1, 'No Poverty', 'القضاء على الفقر'),
(2, 'Zero Hunger', 'القضاء التام على الجوع'),
(3, 'Good Health and Well-being', 'الصحة الجيدة والرفاه'),
(4, 'Quality Education', 'التعليم الجيد'),
(5, 'Gender Equality', 'المساواة بين الجنسين'),
(6, 'Clean Water and Sanitation', 'المياه النظيفة والنظافة الصحية'),
(7, 'Affordable and Clean Energy', 'طاقة نظيفة وبسعر معقول'),
(8, 'Decent Work and Economic Growth', 'العمل اللائق ونمو الاقتصاد'),
(9, 'Industry, Innovation and Infrastructure', 'الصناعة والابتكار والهياكل الأساسية'),
(10, 'Reduced Inequalities', 'الحد من أوجه عدم المساواة'),
(11, 'Sustainable Cities and Communities', 'مدن ومجتمعات محلية مستدامة'),
(12, 'Responsible Consumption and Production', 'الاستهلاك والإنتاج المسؤولان'),
(13, 'Climate Action', 'العمل المناخي'),
(14, 'Life Below Water', 'الحياة تحت الماء'),
(15, 'Life on Land', 'الحياة في البر'),
(16, 'Peace, Justice and Strong Institutions', 'السلام والعدل والمؤسسات القوية'),
(17, 'Partnerships for the Goals', 'عقد الشراكات لتحقيق الأهداف')
ON DUPLICATE KEY UPDATE name_ar = VALUES(name_ar);
