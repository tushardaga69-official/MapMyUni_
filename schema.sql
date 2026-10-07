CREATE DATABASE IF NOT EXISTS mapmyuni
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
USE mapmyuni;

CREATE TABLE IF NOT EXISTS faculty (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(150) NOT NULL,
  email VARCHAR(190) NOT NULL DEFAULT '',
  department VARCHAR(190) NOT NULL DEFAULT '',
  location VARCHAR(255) NOT NULL DEFAULT '',
  record_key CHAR(64) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_faculty_record (record_key),
  KEY idx_faculty_name (name),
  KEY idx_faculty_department (department)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS campus_places (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(190) NOT NULL,
  block VARCHAR(10) NOT NULL DEFAULT '',
  floor VARCHAR(60) NOT NULL DEFAULT '',
  room_code VARCHAR(60) NOT NULL DEFAULT '',
  record_key CHAR(64) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_place_record (record_key),
  KEY idx_place_name (name),
  KEY idx_place_block (block)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS contact_messages (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(190) NOT NULL,
  topic VARCHAR(100) NOT NULL,
  message TEXT NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_contact_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO campus_places (name, block, floor, room_code, record_key) VALUES
('Academic Office for English & Economics, School of Sciences, BBA, English', 'A', '', '', SHA2('place:academic-office-english-economics-sciences-bba-english:A', 256)),
('Academic Support Department of Psychology', 'B', '', '', SHA2('place:academic-support-psychology:B', 256)),
('Academic Support: School of Law', 'B', '', '', SHA2('place:academic-support-law:B', 256)),
('Activity Centre (CSA / SWO)', 'B', '', '', SHA2('place:activity-centre-csa-swo:B', 256)),
('Administrative Office', 'B', '', '', SHA2('place:administrative-office:B', 256)),
('Anti-Ragging & Anti-Sexual Harassment Cell (ICC)', 'A', '', '', SHA2('place:anti-ragging-icc:A', 256)),
('Banquet Hall', 'A', '', '', SHA2('place:banquet-hall:A', 256)),
('Board Room', 'B', '', '', SHA2('place:board-room:B', 256)),
('Business Lab / Conference Room', 'A', '', '', SHA2('place:business-lab-conference-room:A', 256)),
('Campus Store', 'A', '', '', SHA2('place:campus-store:A', 256)),
('CAPS', 'B', '', '', SHA2('place:caps:B', 256)),
('Central Store', 'B', '', '', SHA2('place:central-store:B', 256)),
('Centre for Placement and Career Counseling', 'A', '', '', SHA2('place:placement-career-counseling:A', 256)),
('Centre for Research and CEDBEG', 'A', '', '', SHA2('place:research-cedbeg:A', 256)),
('Common Room for Staff', 'B', '', '', SHA2('place:staff-common-room:B', 256)),
('Computer Lab', 'B', '', '', SHA2('place:computer-lab:B', 256)),
('Conference Hall', 'B', '', '', SHA2('place:conference-hall:B', 256)),
('CSA', 'B', '', '', SHA2('place:csa:B', 256)),
('Department of Economics Faculty Room', 'A', '', '', SHA2('place:economics-faculty-room:A', 256)),
('Department of English, Cultural Studies & Languages Faculty Room', 'A', '', '', SHA2('place:english-cultural-studies-languages-room:A', 256)),
('Main Auditorium', 'A', '4th Floor', '', SHA2('place:main-auditorium:A:4th-floor', 256)),
('Mini Auditorium', 'B', '4th Floor', '', SHA2('place:mini-auditorium:B:4th-floor', 256))
ON DUPLICATE KEY UPDATE
  name = VALUES(name), block = VALUES(block), floor = VALUES(floor), room_code = VALUES(room_code);
