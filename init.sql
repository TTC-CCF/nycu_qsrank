DROP DATABASE IF EXISTS QS_list;

CREATE DATABASE IF NOT EXISTS QS_list;
USE QS_list;
--
-- Table structure for table `Academy`
--

CREATE TABLE IF NOT EXISTS `Academy` (
  `Academy_No` varchar(20) NOT NULL,
  `Academy_Name` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Academy`
--

INSERT INTO `Academy` (`Academy_No`, `Academy_Name`) VALUES
('01', '人文社會學院'),
('02', '人文與社會科學院'),
('03', '工學院'),
('04', '牙醫學院'),
('05', '生命科學院'),
('06', '生物科技學院'),
('07', '生醫工程學院'),
('08', '光電學院'),
('09', '客家文化學院'),
('10', '科技法律學院'),
('11', '前瞻系統工程教育院'),
('12', '理學院'),
('13', '國際半導體學院'),
('14', '智慧科學暨綠能學院'),
('15', '電機學院'),
('16', '資訊學院'),
('17', '管理學院'),
('18', '醫學院'),
('19', '藥物科學院'),
('20', '護理學院'),
('21', '產學創新學院');
--
-- Indexes for table `Academy`
--
ALTER TABLE `Academy`
  ADD PRIMARY KEY (`Academy_No`);
COMMIT;

-- --------------------------------------------------------

--
-- Table structure for table `Employer_list`
--

CREATE TABLE  IF NOT EXISTS `Employer_list` (
  `SN` int(200) NOT NULL,
  `year` int(10) DEFAULT NULL,
  `資料提供單位編號` varchar(20) DEFAULT NULL,
  `資料提供單位` varchar(50) DEFAULT NULL,
  `資料提供單位Email` varchar(200) DEFAULT NULL,
  `Title` varchar(20) DEFAULT NULL,
  `First_name` varchar(50) DEFAULT NULL,
  `Last_name` varchar(50) DEFAULT NULL,
  `Chinese_name` varchar(200) DEFAULT NULL,
  `Position` varchar(200) DEFAULT NULL,
  `Industry` varchar(200) DEFAULT NULL,
  `CompanyName` varchar(200) DEFAULT NULL,
  `BroadSubjectArea` varchar(100) DEFAULT NULL,
  `MainSubject` varchar(100) DEFAULT NULL,
  `Location` varchar(100) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Phone` varchar(50) DEFAULT NULL,
  `去年是否同意參與QS` varchar(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO Employer_list (`SN`, `year`, `資料提供單位編號`, `資料提供單位`, `資料提供單位Email`, `Title`, `First_name`, `Last_name`, `Chinese_name`, `Position`, `Industry`, `CompanyName`, `BroadSubjectArea`, `MainSubject`, `Location`, `Email`, `Phone`, `去年是否同意參與QS`) VALUES
(0, 2023, 1, '01人文社會學院', 'human_socialty@nycu.edu.tw', 'Mr.', 'Hsuu Yun', 'Hsu', '', '', '', '', '', '', 'Taiwan', 'hsuyun@email.com', '', 'V'),
(1, 2023, 1, '01人文社會學院', 'human_socialty@nycu.edu.tw', 'Mr.', 'suu Yun', 'Hsu', '', '', '', '', '', '', 'Taiwan', 'suyun@email.com', '', 'V'),
(2, 2023, 1, '01人文社會學院', 'human_socialty@nycu.edu.tw', 'Mr.', 'uu Yun', 'Hsu', '', '', '', '', '', '', 'Taiwan', 'uyun@email.com', '', 'V'),
(3, 2023, 1, '01人文社會學院', 'human_socialty@nycu.edu.tw', 'Mr.', 'Yun', 'Hsu', '', '', '', '', '', '', 'Taiwan', 'yun@email.com', '', 'V'),
(4, 2023, 1, '01人文社會學院', 'human_socialty@nycu.edu.tw', 'Mr.', 'un', 'Hsu', '', '', '', '', '', '', 'Taiwan', 'un@email.com', '', 'V'),
(5, 2023, 1, '01人文社會學院', 'human_socialty@nycu.edu.tw', 'Mr.', 'ddHsuu Yun', 'Hsu', '', '', '', '', '', '', 'Taiwan', 'ddhsuyun@email.com', '', 'V'),
(6, 2023, 1, '01人文社會學院', 'human_socialty@nycu.edu.tw', 'Mr.', 'sHsuu Yun', 'Hsu', '', '', '', '', '', '', 'Taiwan', 'shsuyun@email.com', '', 'V'),
(7, 2023, 1, '03工學院', 'engineering@nycu.edu.tw', 'Mr.', 'eeHsuu Yun', 'Hsu', '', '', '', '', '', '', 'Taiwan', 'eedhsuyun@email.com', '', 'V'),
(8, 2023, 1, '03工學院', 'engineering@nycu.edu.tw', 'Mr.', 'eeHsuu Yun', 'Hsu', '', '', '', '', '', '', 'Taiwan', 'eedshsuyun@email.com', '', 'V'),
(9, 2023, 1, '03工學院', 'engineering@nycu.edu.tw', 'Mr.', 'eeHsuu Yun', 'Hsu', '', '', '', '', '', '', 'Taiwan', 'eedhssuyun@email.com', '', 'V'),
(10, 2023, 1, '03工學院', 'engineering@nycu.edu.tw', 'Mr.', 'eeHsuu Yun', 'Hsu', '', '', '', '', '', '', 'Taiwan', 'eehsudfyun@email.com', '', 'V'),
(11, 2023, 1, '03工學院', 'engineering@nycu.edu.tw', 'Mr.', 'eeHsuu Yun', 'Hsu', '', '', '', '', '', '', 'Taiwan', 'eehcvcsuyun@email.com', '', 'V'),
(12, 2023, 1, '03工學院', 'engineering@nycu.edu.tw', 'Mr.', 'eeHsuu Yun', 'Hsu', '', '', '', '', '', '', 'Taiwan', 'eehssuyun@email.com', '', 'V'),
(13, 2023, 1, '03工學院', 'engineering@nycu.edu.tw', 'Mr.', 'eeHsuu Yun', 'Hsu', '', '', '', '', '', '', 'Taiwan', 'eehsuyudfdn@email.com', '', 'V');

--
-- Indexes for table `Employer_list`
--
ALTER TABLE `Employer_list`
  ADD PRIMARY KEY (`SN`);
COMMIT;

-- --------------------------------------------------------

--
-- Table structure for table `Scholar_list`
--

CREATE TABLE IF NOT EXISTS  `Scholar_list` (
  `SN` int(200) NOT NULL,
  `year` int(10) DEFAULT NULL,
  `資料提供單位編號` varchar(20) DEFAULT NULL,
  `資料提供單位` varchar(50) DEFAULT NULL,
  `資料提供單位Email` varchar(200) DEFAULT NULL,
  `Title` varchar(20) DEFAULT NULL,
  `First_name` varchar(50) DEFAULT NULL,
  `Last_name` varchar(50) DEFAULT NULL,
  `Chinese_name` varchar(200) DEFAULT NULL,
  `Job_title` varchar(200) DEFAULT NULL,
  `Department` varchar(200) DEFAULT NULL,
  `Institution` varchar(200) DEFAULT NULL,
  `BroadSubjectArea` varchar(100) DEFAULT NULL,
  `MainSubject` varchar(100) DEFAULT NULL,
  `Country` varchar(100) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Phone` varchar(50) DEFAULT NULL,
  `去年是否同意參與QS` varchar(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Scholar_list`
--

INSERT INTO `Scholar_list` (`SN`, `year`, `資料提供單位編號`, `資料提供單位`, `資料提供單位Email`, `Title`, `First_name`, `Last_name`, `Chinese_name`, `Job_title`, `Department`, `Institution`, `BroadSubjectArea`, `MainSubject`, `Country`, `Email`, `Phone`, `去年是否同意參與QS`) VALUES
(1, 2022, '01', '01人文社會學院', NULL, 'Dr.', 'Arnab Roy', 'Chowdhury', NULL, 'Assistant Professor', 'School of Sociology', 'HSE University', NULL, NULL, 'Russia', 'achowdhury@hse.ru', NULL, 'V'),
(2, 2022, '01', '01人文社會學院', NULL, 'Dr.', 'Barbara', 'Oakley', '', 'Professor', 'School of Engineering and Computer Science', 'Oakland University', '', '', 'United States', 'oakley@oakland.edu', '', ''),
(3, 2022, '01', '01人文社會學院', NULL, 'Dr.', 'Bjørn Enge', 'Bertelsen', NULL, 'Professor', 'Department of Social Anthropology', 'University of Bergen', NULL, NULL, 'Norway', 'Bjorn.Bertelsen@uib.no', NULL, 'V'),
(4, 2022, '01', '01人文社會學院', NULL, 'Dr.', 'Brett', 'Neilson', NULL, 'Professor', 'Institute for Culture and Society', 'Western Sydney University', NULL, NULL, 'Australia', 'b.neilson@westernsydney.edu.au', NULL, 'V'),
(5, 2022, '01', '01人文社會學院', NULL, 'Dr.', 'C.-T. James', 'Huang', NULL, 'Professor', 'Department of Linguistics', 'Harvard University', NULL, NULL, 'United States', 'ctjhuang@fas.harvard.edu', NULL, ''),
(6, 2022, '01', '01人文社會學院', NULL, 'Dr.', 'Carlos', 'Gussenhoven', NULL, 'Professor Emeritus', 'Centre for Language Studies', 'Radboud University', NULL, NULL, 'The Netherlands', 'carlos.gussenhoven@ru.nl', NULL, ''),
(7, 2022, '01', '01人文社會學院', NULL, 'Dr.', 'Chen-Pang', 'Yeang', NULL, 'Assistant Professor', 'School of Sociology', 'HSE University', NULL, NULL, 'Canada', 'test@hse.ru', NULL, ''),
(8, 2022, '01', '01人文社會學院', NULL, 'Dr.', 'Chingching', 'Chang', NULL, 'Distinguished researcher', 'School of Sociology', 'HSE University', NULL, NULL, 'Taiwan', 'tes1@hse.ru', NULL, ''),
(9, 2022, '01', '01人文社會學院', NULL, 'Dr.', 'Chin-Teng', 'Lin', NULL, 'Distinguished Professor', 'School of Sociology', 'HSE University', NULL, NULL, 'Australia', 'ttrr@hse.ru', NULL, 'V'),
(10, 2022, '01', '01人文社會學院', NULL, 'Mr.', '東熙', '李', NULL, 'musician', 'freelancer', 'Paekche Institute Of The Arts', NULL, NULL, 'South Korea', 'howtoeatsun@gmail.com', NULL, ''),
(11, 2022, '01', '01人文社會學院', NULL, 'Dr.', 'Eun-Ju', 'Lee', NULL, 'Professor', 'Department of Communication', 'Seoul National University', NULL, NULL, 'South Korea', 'eunju0204@snu.ac.kr', NULL, ''),
(12, 2022, '01', '01人文社會學院', NULL, 'Dr.', 'Fabian', 'Januszewski', '', 'Professor', '', 'Paderborn University', '', '', 'Germany', 'drjanosch@gmail.com', '', ''),
(13, 2022, '01', '01人文社會學院', NULL, 'Dr.', 'Gert', 'Cauwenberghs', '', 'Professor', 'Institute for Neural Computation', 'University of California San Diego', '', '', 'United States', 'gert@ucsd.edu', '', ''),
(14, 2022, '01', '01人文社會學院', NULL, 'Dr.', 'Heong Hong', 'Por', NULL, 'Lecturer', 'School of Sociology', 'HSE University', NULL, NULL, 'Malaysia', 'howdhury@hse.ru', NULL, ''),
(15, 2022, '01', '01人文社會學院', NULL, 'Dr.', 'Herng', 'Su', NULL, 'Professor', 'School of Sociology', 'HSE University', NULL, NULL, 'Taiwan', 'achdhury@hse.ru', NULL, 'V'),
(16, 2022, '01', '01人文社會學院', NULL, 'Dr.', 'Hua-Hua', 'Chang', NULL, 'Charles R. Hicks Chair Professor', 'School of Sociology', 'HSE University', NULL, NULL, 'United States', 'achowdhu@hse.ru', NULL, 'V'),
(17, 2022, '01', '01人文社會學院', NULL, 'Dr.', 'Ja Ian', 'Chong', NULL, 'Assistant Professor', 'School of Sociology', 'HSE University', NULL, NULL, 'Singapore', 'chong.jaian@gmail.com', NULL, 'V'),
(33, 2023, '03', '03工學院', '', 'Ms.', 'Liu', 'yujiun', '劉育君', '', '', '', '', '', '', 'A1@ii.com', NULL, ''),
(34, 2023, '03', '03工學院', '', 'Ms.', 'Liu', 'yujiun', '劉育君', '', '', '', '', '', '', 'A2@ii.com', NULL, ''),
(35, 2023, '03', '03工學院', '', 'Ms.', 'Liu', 'yujiun', '劉育君', '', '', '', '', '', '', 'A3@ii.com', NULL, '');

--
-- Indexes for table `Scholar_list`
--
ALTER TABLE `Scholar_list`
  ADD PRIMARY KEY (`SN`);
COMMIT;

-- --------------------------------------------------------

--
-- Table structure for table `Subject`
--

CREATE TABLE IF NOT EXISTS  `Subject` (
  `BroadSubjectArea` varchar(200) DEFAULT NULL,
  `MainSubject` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Subject`
--

INSERT INTO `Subject` (`BroadSubjectArea`, `MainSubject`) VALUES
('Arts and Humanities', 'Linguistics'),
('Arts and Humanities', 'Theology, Divinity and Religious Studies'),
('Arts and Humanities', 'Archaeology'),
('Arts and Humanities', 'Architecture and Built Environment'),
('Arts and Humanities', 'Art and Design'),
('Arts and Humanities', 'Classics and Ancient History'),
('Arts and Humanities', 'English Language and Literature'),
('Arts and Humanities', 'History'),
('Arts and Humanities', 'Art History'),
('Arts and Humanities', 'Modern Languages'),
('Arts and Humanities', 'Performing Arts'),
('Arts and Humanities', 'Philosophy'),
('Engineering and Technology', 'Engineering - Chemical'),
('Engineering and Technology', 'Engineering - Civil and Structural'),
('Engineering and Technology', 'Computer Science and Information Systems'),
('Engineering and Technology', 'Engineering - Electrical and Electronic'),
('Engineering and Technology', 'Engineering - Petroleum'),
('Engineering and Technology', 'Engineering - Mechanical'),
('Engineering and Technology', 'Engineering - Mineral and Mining'),
('Engineering and Technology', 'Data Science'),
('Life Sciences and Medicine', 'Agriculture and Forestry'),
('Life Sciences and Medicine', 'Anatomy and Physiology'),
('Life Sciences and Medicine', 'Biological Sciences'),
('Life Sciences and Medicine', 'Dentistry'),
('Life Sciences and Medicine', 'Medicine'),
('Life Sciences and Medicine', 'Nursing'),
('Life Sciences and Medicine', 'Pharmacy and Pharmacology'),
('Life Sciences and Medicine', 'Psychology'),
('Life Sciences and Medicine', 'Veterinary Science'),
('Natural Sciences', 'Chemistry'),
('Natural Sciences', 'Earth and Marine Sciences'),
('Natural Sciences', 'Environmental Sciences'),
('Natural Sciences', 'Geography'),
('Natural Sciences', 'Geology'),
('Natural Sciences', 'Geophysics'),
('Natural Sciences', 'Materials Sciences'),
('Natural Sciences', 'Mathematics'),
('Natural Sciences', 'Physics and Astronomy'),
('Social Sciences and Management', 'Marketing'),
('Social Sciences and Management', 'Accounting and Finance'),
('Social Sciences and Management', 'Anthropology'),
('Social Sciences and Management', 'Business and Management Studies'),
('Social Sciences and Management', 'Communication and Media Studies'),
('Social Sciences and Management', 'Development Studies'),
('Social Sciences and Management', 'Economics and Econometrics'),
('Social Sciences and Management', 'Education and Training'),
('Social Sciences and Management', 'Hospitality and Leisure Management'),
('Social Sciences and Management', 'Law and Legal Studies'),
('Social Sciences and Management', 'Library and Information Management'),
('Social Sciences and Management', 'Politics'),
('Social Sciences and Management', 'Social Policy and Administration'),
('Social Sciences and Management', 'Sociology'),
('Social Sciences and Management', 'Sports-Related Subjects'),
('Social Sciences and Management', 'Statistics and Operational Research');
COMMIT;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS  `users` (
  `SN` int(100) NOT NULL,
  `account` varchar(20) DEFAULT NULL,
  `password` varchar(200) DEFAULT NULL,
  `unit` char(20) DEFAULT NULL,
  `unitno` int(11) NOT NULL,
  `email` varchar(200) DEFAULT NULL,
  `role` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` 
(`SN`, `account`, `password`, `unit`, `unitno`, `email`, `role`) VALUES
(0, 'Admin', 'test', 'Cirda', 0, 'Cirda', 0);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`SN`);
COMMIT;
