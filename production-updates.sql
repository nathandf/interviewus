
CREATE TABLE `industry` ( `id` BIGINT NOT NULL AUTO_INCREMENT , `name` VARCHAR(256) NOT NULL , `display_name` VARCHAR(256) NOT NULL , PRIMARY KEY (`id`)) engine = InnoDB;
CREATE TABLE `country` ( `id` BIGINT NOT NULL AUTO_INCREMENT , `iso` VARCHAR(256) NOT NULL , `nicename` VARCHAR(256) NOT NULL , `iso3` VARCHAR(256) NOT NULL , `numcode` BIGINT NOT NULL , `phone_code` BIGINT NOT NULL , PRIMARY KEY (`id`)) engine = InnoDB;
CREATE TABLE `address` ( `id` BIGINT NOT NULL AUTO_INCREMENT , `address_1` VARCHAR(256) NOT NULL , `address_2` VARCHAR(256) NOT NULL , `city` VARCHAR(128) NOT NULL , `postal_code` VARCHAR(64) NOT NULL , `region` VARCHAR(128) NOT NULL , `country_id` BIGINT NOT NULL , PRIMARY KEY (`id`)) engine = InnoDB;
CREATE TABLE `phone` ( `id` BIGINT NOT NULL AUTO_INCREMENT , `country_code` BIGINT NOT NULL , `national_number` BIGINT NOT NULL , PRIMARY KEY (`id`)) engine = InnoDB;
CREATE TABLE `image` ( `id` BIGINT NOT NULL AUTO_INCREMENT , `filename` VARCHAR(512) NOT NULL , `file_type` VARCHAR(128) NOT NULL , PRIMARY KEY (`id`)) engine = InnoDB;
CREATE TABLE `video` ( `id` BIGINT NOT NULL AUTO_INCREMENT , `filename` VARCHAR(512) NOT NULL , `file_type` VARCHAR(128) NOT NULL , PRIMARY KEY (`id`)) engine = InnoDB;
CREATE TABLE `file` ( `id` BIGINT NOT NULL AUTO_INCREMENT , `filename` VARCHAR(512) NOT NULL , `file_type` VARCHAR(128) NOT NULL , PRIMARY KEY (`id`)) engine = InnoDB;
CREATE TABLE `account` ( `id` BIGINT NOT NULL AUTO_INCREMENT , `account_type_id` BIGINT NOT NULL , PRIMARY KEY (`id`)) engine = InnoDB;
CREATE TABLE `user` ( `id` BIGINT NOT NULL AUTO_INCREMENT , `role` VARCHAR(256) NOT NULL , `first_name` VARCHAR(256) NOT NULL , `last_name` VARCHAR(256) NULL , `email` VARCHAR(256) NOT NULL , `phone_id` BIGINT NULL , `address_id` BIGINT NULL , `password` VARCHAR(512) NOT NULL , `image_id` BIGINT NULL , PRIMARY KEY (`id`)) engine = InnoDB;
CREATE TABLE `account_user` ( `id` BIGINT NOT NULL AUTO_INCREMENT , `account_id` BIGINT NOT NULL , `user_id` BIGINT NOT NULL , PRIMARY KEY (`id`)) engine = InnoDB;
CREATE TABLE `organization` ( `id` BIGINT NOT NULL AUTO_INCREMENT , `account_id` BIGINT NOT NULL , `industry_id` BIGINT NULL , `name` VARCHAR(256) NOT NULL , `phone_id` BIGINT NULL , `address_id` BIGINT NULL , `user_id` BIGINT NOT NULL , PRIMARY KEY (`id`)) engine = InnoDB;
CREATE TABLE `position` ( `id` BIGINT NOT NULL AUTO_INCREMENT , `name` VARCHAR(256) NOT NULL , `description` VARCHAR(512) NULL , `organization_id` BIGINT NOT NULL , PRIMARY KEY (`id`)) engine = InnoDB;
CREATE TABLE `organization_user` ( `id` BIGINT NOT NULL AUTO_INCREMENT , `organization_id` BIGINT NOT NULL , `user_id` BIGINT NOT NULL , PRIMARY KEY (`id`)) engine = InnoDB;
CREATE TABLE `interview` ( `id` BIGINT NOT NULL AUTO_INCREMENT , `organization_id` BIGINT NOT NULL , `name` VARCHAR(256) NOT NULL , `description` VARCHAR(512) NULL , `position_id` BIGINT NOT NULL , `status` VARCHAR(128) NOT NULL , `scheduled_time` VARCHAR(256) NULL , `start_time` VARCHAR(256) NOT NULL , `end_time` VARCHAR(256) NOT NULL , `token` VARCHAR(256) NOT NULL , PRIMARY KEY (`id`)) engine = InnoDB;
CREATE TABLE `interview_question` ( `id` BIGINT NOT NULL AUTO_INCREMENT , `interview_id` BIGINT NOT NULL , `placement` BIGINT NOT NULL , `body` VARCHAR(1024) NOT NULL , PRIMARY KEY (`id`)) engine = InnoDB;
CREATE TABLE `interviewee` ( `id` BIGINT NOT NULL AUTO_INCREMENT , `first_name` VARCHAR(256) NOT NULL , `last_name` VARCHAR(256) NULL , `email` VARCHAR(256) NOT NULL , `phone_id` BIGINT NOT NULL , `address_id` BIGINT NULL , `image_id` BIGINT NULL , PRIMARY KEY (`id`)) engine = InnoDB;
CREATE TABLE `interviewee_answer` ( `id` BIGINT NOT NULL AUTO_INCREMENT , `interview_question_id` BIGINT NOT NULL , `body` VARCHAR(1024) NOT NULL , PRIMARY KEY (`id`)) engine = InnoDB;
CREATE TABLE `interview_template` ( `id` BIGINT NOT NULL AUTO_INCREMENT , `organization_id` BIGINT NOT NULL , `industry_id` BIGINT NULL , PRIMARY KEY (`id`)) engine = InnoDB;
CREATE TABLE `question` ( `id` BIGINT NOT NULL AUTO_INCREMENT , `question_type_id` BIGINT NOT NULL , `placement` BIGINT NOT NULL , `body` VARCHAR(1024) NOT NULL , PRIMARY KEY (`id`)) engine = InnoDB;
CREATE TABLE `question_choice` ( `id` BIGINT NOT NULL AUTO_INCREMENT , `question_id` BIGINT NOT NULL , `placement` BIGINT NOT NULL , `body` VARCHAR(1024) NOT NULL , PRIMARY KEY (`id`)) engine = InnoDB;
CREATE TABLE `question_type` ( `id` BIGINT NOT NULL AUTO_INCREMENT , `name` VARCHAR(256) NOT NULL , PRIMARY KEY (`id`)) engine = InnoDB;
ALTER TABLE `country` ADD `name` VARCHAR(256) NOT NULL AFTER `iso`;
ALTER TABLE `country` CHANGE `phone_code` `phonecode` BIGINT(20) NOT NULL;
INSERT INTO `country` (`id`, `iso`, `name`, `nicename`, `iso3`, `numcode`, `phonecode`) VALUES
(1, 'AF', 'AFGHANISTAN', 'Afghanistan', 'AFG', 4, 93),
(2, 'AL', 'ALBANIA', 'Albania', 'ALB', 8, 355),
(3, 'DZ', 'ALGERIA', 'Algeria', 'DZA', 12, 213),
(4, 'AS', 'AMERICAN SAMOA', 'American Samoa', 'ASM', 16, 1684),
(5, 'AD', 'ANDORRA', 'Andorra', 'AND', 20, 376),
(6, 'AO', 'ANGOLA', 'Angola', 'AGO', 24, 244),
(7, 'AI', 'ANGUILLA', 'Anguilla', 'AIA', 660, 1264),
(8, 'AQ', 'ANTARCTICA', 'Antarctica', NULL, NULL, 0),
(9, 'AG', 'ANTIGUA AND BARBUDA', 'Antigua and Barbuda', 'ATG', 28, 1268),
(10, 'AR', 'ARGENTINA', 'Argentina', 'ARG', 32, 54),
(11, 'AM', 'ARMENIA', 'Armenia', 'ARM', 51, 374),
(12, 'AW', 'ARUBA', 'Aruba', 'ABW', 533, 297),
(13, 'AU', 'AUSTRALIA', 'Australia', 'AUS', 36, 61),
(14, 'AT', 'AUSTRIA', 'Austria', 'AUT', 40, 43),
(15, 'AZ', 'AZERBAIJAN', 'Azerbaijan', 'AZE', 31, 994),
(16, 'BS', 'BAHAMAS', 'Bahamas', 'BHS', 44, 1242),
(17, 'BH', 'BAHRAIN', 'Bahrain', 'BHR', 48, 973),
(18, 'BD', 'BANGLADESH', 'Bangladesh', 'BGD', 50, 880),
(19, 'BB', 'BARBADOS', 'Barbados', 'BRB', 52, 1246),
(20, 'BY', 'BELARUS', 'Belarus', 'BLR', 112, 375),
(21, 'BE', 'BELGIUM', 'Belgium', 'BEL', 56, 32),
(22, 'BZ', 'BELIZE', 'Belize', 'BLZ', 84, 501),
(23, 'BJ', 'BENIN', 'Benin', 'BEN', 204, 229),
(24, 'BM', 'BERMUDA', 'Bermuda', 'BMU', 60, 1441),
(25, 'BT', 'BHUTAN', 'Bhutan', 'BTN', 64, 975),
(26, 'BO', 'BOLIVIA', 'Bolivia', 'BOL', 68, 591),
(27, 'BA', 'BOSNIA AND HERZEGOVINA', 'Bosnia and Herzegovina', 'BIH', 70, 387),
(28, 'BW', 'BOTSWANA', 'Botswana', 'BWA', 72, 267),
(29, 'BV', 'BOUVET ISLAND', 'Bouvet Island', NULL, NULL, 0),
(30, 'BR', 'BRAZIL', 'Brazil', 'BRA', 76, 55),
(31, 'IO', 'BRITISH INDIAN OCEAN TERRITORY', 'British Indian Ocean Territory', NULL, NULL, 246),
(32, 'BN', 'BRUNEI DARUSSALAM', 'Brunei Darussalam', 'BRN', 96, 673),
(33, 'BG', 'BULGARIA', 'Bulgaria', 'BGR', 100, 359),
(34, 'BF', 'BURKINA FASO', 'Burkina Faso', 'BFA', 854, 226),
(35, 'BI', 'BURUNDI', 'Burundi', 'BDI', 108, 257),
(36, 'KH', 'CAMBODIA', 'Cambodia', 'KHM', 116, 855),
(37, 'CM', 'CAMEROON', 'Cameroon', 'CMR', 120, 237),
(38, 'CA', 'CANADA', 'Canada', 'CAN', 124, 1),
(39, 'CV', 'CAPE VERDE', 'Cape Verde', 'CPV', 132, 238),
(40, 'KY', 'CAYMAN ISLANDS', 'Cayman Islands', 'CYM', 136, 1345),
(41, 'CF', 'CENTRAL AFRICAN REPUBLIC', 'Central African Republic', 'CAF', 140, 236),
(42, 'TD', 'CHAD', 'Chad', 'TCD', 148, 235),
(43, 'CL', 'CHILE', 'Chile', 'CHL', 152, 56),
(44, 'CN', 'CHINA', 'China', 'CHN', 156, 86),
(45, 'CX', 'CHRISTMAS ISLAND', 'Christmas Island', NULL, NULL, 61),
(46, 'CC', 'COCOS (KEELING) ISLANDS', 'Cocos (Keeling) Islands', NULL, NULL, 672),
(47, 'CO', 'COLOMBIA', 'Colombia', 'COL', 170, 57),
(48, 'KM', 'COMOROS', 'Comoros', 'COM', 174, 269),
(49, 'CG', 'CONGO', 'Congo', 'COG', 178, 242),
(50, 'CD', 'CONGO, THE DEMOCRATIC REPUBLIC OF THE', 'Congo, the Democratic Republic of the', 'COD', 180, 242),
(51, 'CK', 'COOK ISLANDS', 'Cook Islands', 'COK', 184, 682),
(52, 'CR', 'COSTA RICA', 'Costa Rica', 'CRI', 188, 506),
(53, 'CI', 'COTE D\'IVOIRE', 'Cote D\'Ivoire', 'CIV', 384, 225),
(54, 'HR', 'CROATIA', 'Croatia', 'HRV', 191, 385),
(55, 'CU', 'CUBA', 'Cuba', 'CUB', 192, 53),
(56, 'CY', 'CYPRUS', 'Cyprus', 'CYP', 196, 357),
(57, 'CZ', 'CZECH REPUBLIC', 'Czech Republic', 'CZE', 203, 420),
(58, 'DK', 'DENMARK', 'Denmark', 'DNK', 208, 45),
(59, 'DJ', 'DJIBOUTI', 'Djibouti', 'DJI', 262, 253),
(60, 'DM', 'DOMINICA', 'Dominica', 'DMA', 212, 1767),
(61, 'DO', 'DOMINICAN REPUBLIC', 'Dominican Republic', 'DOM', 214, 1809),
(62, 'EC', 'ECUADOR', 'Ecuador', 'ECU', 218, 593),
(63, 'EG', 'EGYPT', 'Egypt', 'EGY', 818, 20),
(64, 'SV', 'EL SALVADOR', 'El Salvador', 'SLV', 222, 503),
(65, 'GQ', 'EQUATORIAL GUINEA', 'Equatorial Guinea', 'GNQ', 226, 240),
(66, 'ER', 'ERITREA', 'Eritrea', 'ERI', 232, 291),
(67, 'EE', 'ESTONIA', 'Estonia', 'EST', 233, 372),
(68, 'ET', 'ETHIOPIA', 'Ethiopia', 'ETH', 231, 251),
(69, 'FK', 'FALKLAND ISLANDS (MALVINAS)', 'Falkland Islands (Malvinas)', 'FLK', 238, 500),
(70, 'FO', 'FAROE ISLANDS', 'Faroe Islands', 'FRO', 234, 298),
(71, 'FJ', 'FIJI', 'Fiji', 'FJI', 242, 679),
(72, 'FI', 'FINLAND', 'Finland', 'FIN', 246, 358),
(73, 'FR', 'FRANCE', 'France', 'FRA', 250, 33),
(74, 'GF', 'FRENCH GUIANA', 'French Guiana', 'GUF', 254, 594),
(75, 'PF', 'FRENCH POLYNESIA', 'French Polynesia', 'PYF', 258, 689),
(76, 'TF', 'FRENCH SOUTHERN TERRITORIES', 'French Southern Territories', NULL, NULL, 0),
(77, 'GA', 'GABON', 'Gabon', 'GAB', 266, 241),
(78, 'GM', 'GAMBIA', 'Gambia', 'GMB', 270, 220),
(79, 'GE', 'GEORGIA', 'Georgia', 'GEO', 268, 995),
(80, 'DE', 'GERMANY', 'Germany', 'DEU', 276, 49),
(81, 'GH', 'GHANA', 'Ghana', 'GHA', 288, 233),
(82, 'GI', 'GIBRALTAR', 'Gibraltar', 'GIB', 292, 350),
(83, 'GR', 'GREECE', 'Greece', 'GRC', 300, 30),
(84, 'GL', 'GREENLAND', 'Greenland', 'GRL', 304, 299),
(85, 'GD', 'GRENADA', 'Grenada', 'GRD', 308, 1473),
(86, 'GP', 'GUADELOUPE', 'Guadeloupe', 'GLP', 312, 590),
(87, 'GU', 'GUAM', 'Guam', 'GUM', 316, 1671),
(88, 'GT', 'GUATEMALA', 'Guatemala', 'GTM', 320, 502),
(89, 'GN', 'GUINEA', 'Guinea', 'GIN', 324, 224),
(90, 'GW', 'GUINEA-BISSAU', 'Guinea-Bissau', 'GNB', 624, 245),
(91, 'GY', 'GUYANA', 'Guyana', 'GUY', 328, 592),
(92, 'HT', 'HAITI', 'Haiti', 'HTI', 332, 509),
(93, 'HM', 'HEARD ISLAND AND MCDONALD ISLANDS', 'Heard Island and Mcdonald Islands', NULL, NULL, 0),
(94, 'VA', 'HOLY SEE (VATICAN CITY STATE)', 'Holy See (Vatican City State)', 'VAT', 336, 39),
(95, 'HN', 'HONDURAS', 'Honduras', 'HND', 340, 504),
(96, 'HK', 'HONG KONG', 'Hong Kong', 'HKG', 344, 852),
(97, 'HU', 'HUNGARY', 'Hungary', 'HUN', 348, 36),
(98, 'IS', 'ICELAND', 'Iceland', 'ISL', 352, 354),
(99, 'IN', 'INDIA', 'India', 'IND', 356, 91),
(100, 'ID', 'INDONESIA', 'Indonesia', 'IDN', 360, 62),
(101, 'IR', 'IRAN, ISLAMIC REPUBLIC OF', 'Iran, Islamic Republic of', 'IRN', 364, 98),
(102, 'IQ', 'IRAQ', 'Iraq', 'IRQ', 368, 964),
(103, 'IE', 'IRELAND', 'Ireland', 'IRL', 372, 353),
(104, 'IL', 'ISRAEL', 'Israel', 'ISR', 376, 972),
(105, 'IT', 'ITALY', 'Italy', 'ITA', 380, 39),
(106, 'JM', 'JAMAICA', 'Jamaica', 'JAM', 388, 1876),
(107, 'JP', 'JAPAN', 'Japan', 'JPN', 392, 81),
(108, 'JO', 'JORDAN', 'Jordan', 'JOR', 400, 962),
(109, 'KZ', 'KAZAKHSTAN', 'Kazakhstan', 'KAZ', 398, 7),
(110, 'KE', 'KENYA', 'Kenya', 'KEN', 404, 254),
(111, 'KI', 'KIRIBATI', 'Kiribati', 'KIR', 296, 686),
(112, 'KP', 'KOREA, DEMOCRATIC PEOPLE\'S REPUBLIC OF', 'Korea, Democratic People\'s Republic of', 'PRK', 408, 850),
(113, 'KR', 'KOREA, REPUBLIC OF', 'Korea, Republic of', 'KOR', 410, 82),
(114, 'KW', 'KUWAIT', 'Kuwait', 'KWT', 414, 965),
(115, 'KG', 'KYRGYZSTAN', 'Kyrgyzstan', 'KGZ', 417, 996),
(116, 'LA', 'LAO PEOPLE\'S DEMOCRATIC REPUBLIC', 'Lao People\'s Democratic Republic', 'LAO', 418, 856),
(117, 'LV', 'LATVIA', 'Latvia', 'LVA', 428, 371),
(118, 'LB', 'LEBANON', 'Lebanon', 'LBN', 422, 961),
(119, 'LS', 'LESOTHO', 'Lesotho', 'LSO', 426, 266),
(120, 'LR', 'LIBERIA', 'Liberia', 'LBR', 430, 231),
(121, 'LY', 'LIBYAN ARAB JAMAHIRIYA', 'Libyan Arab Jamahiriya', 'LBY', 434, 218),
(122, 'LI', 'LIECHTENSTEIN', 'Liechtenstein', 'LIE', 438, 423),
(123, 'LT', 'LITHUANIA', 'Lithuania', 'LTU', 440, 370),
(124, 'LU', 'LUXEMBOURG', 'Luxembourg', 'LUX', 442, 352),
(125, 'MO', 'MACAO', 'Macao', 'MAC', 446, 853),
(126, 'MK', 'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF', 'Macedonia, the Former Yugoslav Republic of', 'MKD', 807, 389),
(127, 'MG', 'MADAGASCAR', 'Madagascar', 'MDG', 450, 261),
(128, 'MW', 'MALAWI', 'Malawi', 'MWI', 454, 265),
(129, 'MY', 'MALAYSIA', 'Malaysia', 'MYS', 458, 60),
(130, 'MV', 'MALDIVES', 'Maldives', 'MDV', 462, 960),
(131, 'ML', 'MALI', 'Mali', 'MLI', 466, 223),
(132, 'MT', 'MALTA', 'Malta', 'MLT', 470, 356),
(133, 'MH', 'MARSHALL ISLANDS', 'Marshall Islands', 'MHL', 584, 692),
(134, 'MQ', 'MARTINIQUE', 'Martinique', 'MTQ', 474, 596),
(135, 'MR', 'MAURITANIA', 'Mauritania', 'MRT', 478, 222),
(136, 'MU', 'MAURITIUS', 'Mauritius', 'MUS', 480, 230),
(137, 'YT', 'MAYOTTE', 'Mayotte', NULL, NULL, 269),
(138, 'MX', 'MEXICO', 'Mexico', 'MEX', 484, 52),
(139, 'FM', 'MICRONESIA, FEDERATED STATES OF', 'Micronesia, Federated States of', 'FSM', 583, 691),
(140, 'MD', 'MOLDOVA, REPUBLIC OF', 'Moldova, Republic of', 'MDA', 498, 373),
(141, 'MC', 'MONACO', 'Monaco', 'MCO', 492, 377),
(142, 'MN', 'MONGOLIA', 'Mongolia', 'MNG', 496, 976),
(143, 'MS', 'MONTSERRAT', 'Montserrat', 'MSR', 500, 1664),
(144, 'MA', 'MOROCCO', 'Morocco', 'MAR', 504, 212),
(145, 'MZ', 'MOZAMBIQUE', 'Mozambique', 'MOZ', 508, 258),
(146, 'MM', 'MYANMAR', 'Myanmar', 'MMR', 104, 95),
(147, 'NA', 'NAMIBIA', 'Namibia', 'NAM', 516, 264),
(148, 'NR', 'NAURU', 'Nauru', 'NRU', 520, 674),
(149, 'NP', 'NEPAL', 'Nepal', 'NPL', 524, 977),
(150, 'NL', 'NETHERLANDS', 'Netherlands', 'NLD', 528, 31),
(151, 'AN', 'NETHERLANDS ANTILLES', 'Netherlands Antilles', 'ANT', 530, 599),
(152, 'NC', 'NEW CALEDONIA', 'New Caledonia', 'NCL', 540, 687),
(153, 'NZ', 'NEW ZEALAND', 'New Zealand', 'NZL', 554, 64),
(154, 'NI', 'NICARAGUA', 'Nicaragua', 'NIC', 558, 505),
(155, 'NE', 'NIGER', 'Niger', 'NER', 562, 227),
(156, 'NG', 'NIGERIA', 'Nigeria', 'NGA', 566, 234),
(157, 'NU', 'NIUE', 'Niue', 'NIU', 570, 683),
(158, 'NF', 'NORFOLK ISLAND', 'Norfolk Island', 'NFK', 574, 672),
(159, 'MP', 'NORTHERN MARIANA ISLANDS', 'Northern Mariana Islands', 'MNP', 580, 1670),
(160, 'NO', 'NORWAY', 'Norway', 'NOR', 578, 47),
(161, 'OM', 'OMAN', 'Oman', 'OMN', 512, 968),
(162, 'PK', 'PAKISTAN', 'Pakistan', 'PAK', 586, 92),
(163, 'PW', 'PALAU', 'Palau', 'PLW', 585, 680),
(164, 'PS', 'PALESTINIAN TERRITORY, OCCUPIED', 'Palestinian Territory, Occupied', NULL, NULL, 970),
(165, 'PA', 'PANAMA', 'Panama', 'PAN', 591, 507),
(166, 'PG', 'PAPUA NEW GUINEA', 'Papua New Guinea', 'PNG', 598, 675),
(167, 'PY', 'PARAGUAY', 'Paraguay', 'PRY', 600, 595),
(168, 'PE', 'PERU', 'Peru', 'PER', 604, 51),
(169, 'PH', 'PHILIPPINES', 'Philippines', 'PHL', 608, 63),
(170, 'PN', 'PITCAIRN', 'Pitcairn', 'PCN', 612, 0),
(171, 'PL', 'POLAND', 'Poland', 'POL', 616, 48),
(172, 'PT', 'PORTUGAL', 'Portugal', 'PRT', 620, 351),
(173, 'PR', 'PUERTO RICO', 'Puerto Rico', 'PRI', 630, 1787),
(174, 'QA', 'QATAR', 'Qatar', 'QAT', 634, 974),
(175, 'RE', 'REUNION', 'Reunion', 'REU', 638, 262),
(176, 'RO', 'ROMANIA', 'Romania', 'ROM', 642, 40),
(177, 'RU', 'RUSSIAN FEDERATION', 'Russian Federation', 'RUS', 643, 70),
(178, 'RW', 'RWANDA', 'Rwanda', 'RWA', 646, 250),
(179, 'SH', 'SAINT HELENA', 'Saint Helena', 'SHN', 654, 290),
(180, 'KN', 'SAINT KITTS AND NEVIS', 'Saint Kitts and Nevis', 'KNA', 659, 1869),
(181, 'LC', 'SAINT LUCIA', 'Saint Lucia', 'LCA', 662, 1758),
(182, 'PM', 'SAINT PIERRE AND MIQUELON', 'Saint Pierre and Miquelon', 'SPM', 666, 508),
(183, 'VC', 'SAINT VINCENT AND THE GRENADINES', 'Saint Vincent and the Grenadines', 'VCT', 670, 1784),
(184, 'WS', 'SAMOA', 'Samoa', 'WSM', 882, 684),
(185, 'SM', 'SAN MARINO', 'San Marino', 'SMR', 674, 378),
(186, 'ST', 'SAO TOME AND PRINCIPE', 'Sao Tome and Principe', 'STP', 678, 239),
(187, 'SA', 'SAUDI ARABIA', 'Saudi Arabia', 'SAU', 682, 966),
(188, 'SN', 'SENEGAL', 'Senegal', 'SEN', 686, 221),
(189, 'CS', 'SERBIA AND MONTENEGRO', 'Serbia and Montenegro', NULL, NULL, 381),
(190, 'SC', 'SEYCHELLES', 'Seychelles', 'SYC', 690, 248),
(191, 'SL', 'SIERRA LEONE', 'Sierra Leone', 'SLE', 694, 232),
(192, 'SG', 'SINGAPORE', 'Singapore', 'SGP', 702, 65),
(193, 'SK', 'SLOVAKIA', 'Slovakia', 'SVK', 703, 421),
(194, 'SI', 'SLOVENIA', 'Slovenia', 'SVN', 705, 386),
(195, 'SB', 'SOLOMON ISLANDS', 'Solomon Islands', 'SLB', 90, 677),
(196, 'SO', 'SOMALIA', 'Somalia', 'SOM', 706, 252),
(197, 'ZA', 'SOUTH AFRICA', 'South Africa', 'ZAF', 710, 27),
(198, 'GS', 'SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS', 'South Georgia and the South Sandwich Islands', NULL, NULL, 0),
(199, 'ES', 'SPAIN', 'Spain', 'ESP', 724, 34),
(200, 'LK', 'SRI LANKA', 'Sri Lanka', 'LKA', 144, 94),
(201, 'SD', 'SUDAN', 'Sudan', 'SDN', 736, 249),
(202, 'SR', 'SURINAME', 'Suriname', 'SUR', 740, 597),
(203, 'SJ', 'SVALBARD AND JAN MAYEN', 'Svalbard and Jan Mayen', 'SJM', 744, 47),
(204, 'SZ', 'SWAZILAND', 'Swaziland', 'SWZ', 748, 268),
(205, 'SE', 'SWEDEN', 'Sweden', 'SWE', 752, 46),
(206, 'CH', 'SWITZERLAND', 'Switzerland', 'CHE', 756, 41),
(207, 'SY', 'SYRIAN ARAB REPUBLIC', 'Syrian Arab Republic', 'SYR', 760, 963),
(208, 'TW', 'TAIWAN, PROVINCE OF CHINA', 'Taiwan, Province of China', 'TWN', 158, 886),
(209, 'TJ', 'TAJIKISTAN', 'Tajikistan', 'TJK', 762, 992),
(210, 'TZ', 'TANZANIA, UNITED REPUBLIC OF', 'Tanzania, United Republic of', 'TZA', 834, 255),
(211, 'TH', 'THAILAND', 'Thailand', 'THA', 764, 66),
(212, 'TL', 'TIMOR-LESTE', 'Timor-Leste', NULL, NULL, 670),
(213, 'TG', 'TOGO', 'Togo', 'TGO', 768, 228),
(214, 'TK', 'TOKELAU', 'Tokelau', 'TKL', 772, 690),
(215, 'TO', 'TONGA', 'Tonga', 'TON', 776, 676),
(216, 'TT', 'TRINIDAD AND TOBAGO', 'Trinidad and Tobago', 'TTO', 780, 1868),
(217, 'TN', 'TUNISIA', 'Tunisia', 'TUN', 788, 216),
(218, 'TR', 'TURKEY', 'Turkey', 'TUR', 792, 90),
(219, 'TM', 'TURKMENISTAN', 'Turkmenistan', 'TKM', 795, 7370),
(220, 'TC', 'TURKS AND CAICOS ISLANDS', 'Turks and Caicos Islands', 'TCA', 796, 1649),
(221, 'TV', 'TUVALU', 'Tuvalu', 'TUV', 798, 688),
(222, 'UG', 'UGANDA', 'Uganda', 'UGA', 800, 256),
(223, 'UA', 'UKRAINE', 'Ukraine', 'UKR', 804, 380),
(224, 'AE', 'UNITED ARAB EMIRATES', 'United Arab Emirates', 'ARE', 784, 971),
(225, 'GB', 'UNITED KINGDOM', 'United Kingdom', 'GBR', 826, 44),
(226, 'US', 'UNITED STATES', 'United States', 'USA', 840, 1),
(227, 'UM', 'UNITED STATES MINOR OUTLYING ISLANDS', 'United States Minor Outlying Islands', '', NULL, 1),
(228, 'UY', 'URUGUAY', 'Uruguay', 'URY', 858, 598),
(229, 'UZ', 'UZBEKISTAN', 'Uzbekistan', 'UZB', 860, 998),
(230, 'VU', 'VANUATU', 'Vanuatu', 'VUT', 548, 678),
(231, 'VE', 'VENEZUELA', 'Venezuela', 'VEN', 862, 58),
(232, 'VN', 'VIET NAM', 'Viet Nam', 'VNM', 704, 84),
(233, 'VG', 'VIRGIN ISLANDS, BRITISH', 'Virgin Islands, British', 'VGB', 92, 1284),
(234, 'VI', 'VIRGIN ISLANDS, U.S.', 'Virgin Islands, U.s.', 'VIR', 850, 1340),
(235, 'WF', 'WALLIS AND FUTUNA', 'Wallis and Futuna', 'WLF', 876, 681),
(236, 'EH', 'WESTERN SAHARA', 'Western Sahara', 'ESH', 732, 212),
(237, 'YE', 'YEMEN', 'Yemen', 'YEM', 887, 967),
(238, 'ZM', 'ZAMBIA', 'Zambia', 'ZMB', 894, 260),
(239, 'ZW', 'ZIMBABWE', 'Zimbabwe', 'ZWE', 716, 263);
ALTER TABLE `interviewee` ADD `organization_id` BIGINT NOT NULL AFTER `id`;
ALTER TABLE `question` ADD `interview_template_id` BIGINT NOT NULL AFTER `id`;
ALTER TABLE `interview_template` ADD `name` VARCHAR(256) NOT NULL AFTER `id`, ADD `description` VARCHAR(1024) NULL DEFAULT NULL AFTER `name`;

CREATE TABLE `tag` ( `id` BIGINT NOT NULL AUTO_INCREMENT , `string` VARCHAR(64) NOT NULL , PRIMARY KEY (`id`)) engine = InnoDB;
CREATE TABLE `question_tag` ( `id` BIGINT NOT NULL AUTO_INCREMENT , `question_id` BIGINT NOT NULL , `tag_id` BIGINT NOT NULL , PRIMARY KEY (`id`)) engine = InnoDB;
ALTER TABLE `interview` DROP `name`, DROP `description`;
ALTER TABLE `interview` ADD `interviewee_id` BIGINT NOT NULL AFTER `organization_id`, ADD `interview_template_id` BIGINT NOT NULL AFTER `interviewee_id`;
ALTER TABLE `interview` ADD `deployment_type_id` BIGINT NOT NULL AFTER `id`;

CREATE TABLE `twilio_phone_number` ( `id` BIGINT NOT NULL AUTO_INCREMENT , `organization_id` BIGINT NOT NULL , `sid` VARCHAR(256) NOT NULL , `phone_number` VARCHAR(256) NOT NULL , `friendly_number` VARCHAR(256) NOT NULL , PRIMARY KEY (`id`)) engine = InnoDB;
ALTER TABLE `interview_question` ADD `dispatched` TINYINT NOT NULL DEFAULT '0' AFTER `body`;
ALTER TABLE `phone` ADD `e164_phone_number` VARCHAR(256) NOT NULL AFTER `national_number`;

CREATE TABLE `plan` ( `id` bigint(20) NOT NULL, `name` varchar(256) NOT NULL, `description` varchar(512) DEFAULT NULL, `price` bigint(20) NOT NULL, `featured` tinyint(4) NOT NULL DEFAULT '0', `braintree_plan_id` varchar(256) DEFAULT NULL ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
INSERT INTO `plan` (`id`, `name`, `description`, `price`, `featured`, `braintree_plan_id`) VALUES (1, 'basic', NULL, 19, 0, 's2kr'), (2, 'standard', NULL, 49, 1, '3n82'), (3, 'pro', NULL, 78, 0, 'hkmb'), (4, 'business', NULL, 124, 0, '9mf6'), (5, 'enterprise', NULL, 198, 0, 'qjsg'), (6, 'basic', NULL, 24, 0, '84mw'), (7, 'standard', NULL, 62, 1, 'p2z6'), (8, 'pro', NULL, 98, 0, '552b'), (9, 'business', NULL, 155, 0, 'k3dm'), (10, 'enterprise', NULL, 248, 0, 'c47g'), (11, 'free', NULL, 0, 0, NULL);
ALTER TABLE `plan` ADD PRIMARY KEY (`id`);
ALTER TABLE `plan` MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
CREATE TABLE `plan_details` ( `id` bigint(20) NOT NULL, `plan_id` bigint(20) NOT NULL, `sms_interviews` bigint(20) NOT NULL, `web_interviews` bigint(20) NOT NULL, `max_questions` bigint(20) NOT NULL, `storage` varchar(64) NOT NULL, `users` bigint(20) NOT NULL ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
INSERT INTO `plan_details` (`id`, `plan_id`, `sms_interviews`, `web_interviews`, `max_questions`, `storage`, `users`) VALUES (1, 1, 9, 9, 10, '5GB', 1), (2, 2, 25, 25, 10, '25GB', 2), (3, 3, 50, 50, 15, '50GB', 5), (4, 4, 100, 100, 25, 'Unlimited', 10), (5, 5, 175, -1, 25, 'Unlimited', 25), (6, 6, 9, 9, 10, '5GB', 1), (7, 7, 25, 25, 10, '25GB', 2), (8, 8, 50, 50, 15, '50GB', 5), (9, 9, 100, 100, 25, 'Unlimited', 10), (10, 10, 175, -1, 25, 'Unlimited', 25), (11, 11, 1, 1, 10, '1GB', 1);
ALTER TABLE `plan_details` ADD PRIMARY KEY (`id`);
ALTER TABLE `plan_details` MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

ALTER TABLE `user` ADD `current_account_id` BIGINT NULL DEFAULT NULL AFTER `image_id`, ADD `current_organization_id` BIGINT NULL DEFAULT NULL AFTER `current_account_id`;
ALTER TABLE `user` ADD `token` VARCHAR(256) NULL DEFAULT NULL AFTER `password`;

CREATE TABLE `cart` ( `id` BIGINT NOT NULL AUTO_INCREMENT , `account_id` BIGINT NOT NULL , PRIMARY KEY (`id`)) engine = InnoDB;
CREATE TABLE `product` ( `id` BIGINT NOT NULL AUTO_INCREMENT , `cart_id` BIGINT NOT NULL , `plan_id` BIGINT NULL , `billing_frequency` VARCHAR(256) NULL , PRIMARY KEY (`id`)) engine = InnoDB;
ALTER TABLE `account` ADD `sms_interviews` BIGINT NOT NULL DEFAULT '1' AFTER `account_type_id`, ADD `web_interviews` BIGINT NOT NULL DEFAULT '1' AFTER `sms_interviews`, ADD `users` BIGINT NOT NULL DEFAULT '1' AFTER `web_interviews`, ADD `plan_id` BIGINT NULL AFTER `users`, ADD `recurs_on` BIGINT NULL AFTER `plan_id`, ADD `status` TINYINT NOT NULL DEFAULT '1' AFTER `recurs_on`;
ALTER TABLE `account` CHANGE `plan_id` `plan_id` BIGINT(20) NULL DEFAULT '11';
ALTER TABLE `account` CHANGE `account_type_id` `account_type_id` BIGINT(20) NULL DEFAULT '0';

ALTER TABLE `account` ADD `braintree_customer_id` VARCHAR(256) NULL AFTER `status`;
ALTER TABLE `account` ADD `braintree_subscription_id` VARCHAR(256) NULL DEFAULT NULL AFTER `braintree_customer_id`;

CREATE TABLE `payment_method` ( `id` BIGINT NOT NULL AUTO_INCREMENT , `braintree_payment_method_token` VARCHAR(256) NOT NULL , `is_default` TINYINT NOT NULL , PRIMARY KEY (`id`)) engine = InnoDB;
ALTER TABLE `payment_method` CHANGE `is_default` `is_default` TINYINT(4) NOT NULL DEFAULT '0';
ALTER TABLE `payment_method` ADD `account_id` BIGINT NOT NULL AFTER `id`;
INSERT INTO `twilio_phone_number` (`id`, `sid`, `phone_number`, `friendly_number`) VALUES (NULL, 'PNb3e9c12b31f5a9923eb9befb32bcef32', '+18327694054', '(832) 769-4054');
CREATE TABLE `conversation` ( `id` BIGINT NOT NULL AUTO_INCREMENT , `twilio_phone_number_id` BIGINT NOT NULL , `e164_phone_number` VARCHAR(256) NOT NULL , PRIMARY KEY (`id`)) engine = InnoDB;
ALTER TABLE `interview` ADD `conversation_id` BIGINT NULL DEFAULT NULL AFTER `organization_id`;

CREATE TABLE `deployment_type` ( `id` BIGINT NOT NULL AUTO_INCREMENT , `name` VARCHAR(128) NOT NULL , PRIMARY KEY (`id`)) engine = InnoDB;
INSERT INTO `deployment_type` (`id`, `name`) VALUES (NULL, 'sms'), (NULL, 'web');
INSERT INTO `twilio_phone_number` (`id`, `sid`, `phone_number`, `friendly_number`) VALUES (NULL, 'PN159a6b15c933f1ceec13c9cbc20084a9', '+19147757270', '(914) 775-7270');
ALTER TABLE `interview_question` ADD `sms_sid` VARCHAR(256) NULL DEFAULT NULL AFTER `dispatched`, ADD `sms_status` VARCHAR(256) NULL DEFAULT NULL AFTER `sms_sid`;

CREATE TABLE `inbound_sms` ( `id` BIGINT NOT NULL AUTO_INCREMENT , `conversation_id` BIGINT NOT NULL , `body` VARCHAR(2056) NOT NULL , `recieved_at` VARCHAR(256) NOT NULL , PRIMARY KEY (`id`)) engine = InnoDB;
CREATE TABLE `concatenated_sms` ( `id` BIGINT NOT NULL AUTO_INCREMENT , `conversation_id` BIGINT NOT NULL , `body` VARCHAR(2048) NOT NULL , `updated_at` VARCHAR(256) NOT NULL , PRIMARY KEY (`id`)) engine = InnoDB;
ALTER TABLE `concatenated_sms` ADD `created_at` VARCHAR(256) NOT NULL AFTER `body`;

CREATE TABLE `unsubscribe` ( `id` BIGINT NOT NULL AUTO_INCREMENT , `email` VARCHAR(512) NOT NULL , PRIMARY KEY (`id`)) engine = InnoDB;
ALTER TABLE `interview` ADD `user_id` BIGINT NOT NULL AFTER `position_id`;
ALTER TABLE `account` ADD `user_id` BIGINT NOT NULL AFTER `account_type_id`;
ALTER TABLE `interview` ADD `mode` VARCHAR(16) NOT NULL DEFAULT 'visible' AFTER `status`;
INSERT INTO `question_type` (`id`, `name`) VALUES (NULL, 'open'), (NULL, 'single'), (NULL, 'multiple');

CREATE TABLE `facebook_pixel` ( `id` BIGINT NOT NULL AUTO_INCREMENT , `pixel_id` VARCHAR(256) NOT NULL , `name` VARCHAR(256) NOT NULL , PRIMARY KEY (`id`)) engine = InnoDB;

CREATE TABLE `timezone` ( `country_code` VARCHAR(5) NULL , `timezone` VARCHAR(125) NULL , `gmt_offset` BIGINT NULL , `dst_offset` BIGINT NULL , `raw_offset` BIGINT NULL ) engine = InnoDB;
ALTER TABLE `timezone` CHANGE `gmt_offset` `gmt_offset` FLOAT(10,2) NULL DEFAULT NULL, CHANGE `dst_offset` `dst_offset` FLOAT(10,2) NULL DEFAULT NULL, CHANGE `raw_offset` `raw_offset` FLOAT(10,2) NULL DEFAULT NULL;
ALTER TABLE `timezone` CHANGE `timezone` `timezone` VARCHAR(125) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '';
ALTER TABLE `timezone` ADD `id` BIGINT NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`id`);
ALTER TABLE `account` ADD `timezone` VARCHAR(128) NOT NULL AFTER `braintree_subscription_id`;
ALTER TABLE `organization` ADD `timezone` VARCHAR(256) NULL DEFAULT 'America/Chicago' AFTER `user_id`;

CREATE TABLE `password_reset_token` ( `id` BIGINT NOT NULL AUTO_INCREMENT , `token` VARCHAR(256) NOT NULL , `email` VARCHAR(256) NOT NULL , `expiration` VARCHAR(256) NOT NULL , PRIMARY KEY (`id`)) engine = InnoDB;