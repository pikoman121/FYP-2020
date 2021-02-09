-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 15, 2020 at 04:00 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `present_app_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `assessments`
--

CREATE TABLE `assessments` (
  `ass_id` int(11) NOT NULL,
  `ass_title` varchar(25) NOT NULL,
  `ass_description` text NOT NULL,
  `ass_filepath` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `assessments`
--

INSERT INTO `assessments` (`ass_id`, `ass_title`, `ass_description`, `ass_filepath`) VALUES
(1, 'Individual (default)', 'Present\'s default individual assessment.', 'includes/assessments/ass1.txt'),
(2, 'Group (default)', 'Present\'s default group assessment.', 'includes/assessments/ass2.txt'),
(3, 'Cole\'s Assessment', 'An custom assessment designed by Peter Cole as he wandered the nether realms.', 'includes/assessments/ass3.txt'),
(4, 'Mickey\'s Assessment', 'A custom assessment made by Mickey Mouse as he sat on Donald\'s face and farted.', 'includes/assessments/ass4.txt'),
(5, 'Joyce\'s Assessment', 'An assessment written by Joyce Mills while she sat at the pub.', 'includes/assessments/ass5.txt'),
(12, 'My Ass', '12345', 'includes/assessments/ass6.txt'),
(14, 'Wei Bin Matrix', 'Bin boy', 'includes/assessments/ass7.txt');

-- --------------------------------------------------------

--
-- Table structure for table `attendees`
--

CREATE TABLE `attendees` (
  `a_id` int(11) NOT NULL,
  `a_first_name` varchar(100) NOT NULL,
  `a_last_name` varchar(100) NOT NULL,
  `a_affiliation` varchar(100) NOT NULL,
  `a_position` varchar(100) NOT NULL,
  `a_email` varchar(255) NOT NULL,
  `a_signup_date` date NOT NULL,
  `a_presentation_id` int(11) NOT NULL,
  `a_feedback` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `attendees`
--

INSERT INTO `attendees` (`a_id`, `a_first_name`, `a_last_name`, `a_affiliation`, `a_position`, `a_email`, `a_signup_date`, `a_presentation_id`, `a_feedback`) VALUES
(52, 'Gordon', 'Ramsay', 'Gordon Ramsay Group', 'CEO', 'lord.ngweibin@hotmail.com', '2020-07-15', 48, 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `e_id` int(11) NOT NULL,
  `e_acad_year` year(4) NOT NULL,
  `e_unit_code` varchar(20) NOT NULL,
  `e_unit_title` varchar(50) NOT NULL,
  `e_unit_description` text NOT NULL,
  `e_teaching_period` varchar(50) NOT NULL,
  `e_campus` varchar(50) NOT NULL,
  `e_uc_id` int(11) NOT NULL,
  `e_uc_name` varchar(100) NOT NULL,
  `e_uc_email` varchar(100) NOT NULL,
  `e_uc_telephone` varchar(100) NOT NULL,
  `e_date_created` date NOT NULL,
  `e_start_date` date NOT NULL,
  `e_end_date` date NOT NULL,
  `e_num_presentations` int(11) NOT NULL,
  `e_event_closed` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`e_id`, `e_acad_year`, `e_unit_code`, `e_unit_title`, `e_unit_description`, `e_teaching_period`, `e_campus`, `e_uc_id`, `e_uc_name`, `e_uc_email`, `e_uc_telephone`, `e_date_created`, `e_start_date`, `e_end_date`, `e_num_presentations`, `e_event_closed`) VALUES
(31, 2020, 'ICT286', 'Cooking Pasta', 'Carbonara', 'MONDAY ', 'Murdoch', 92, 'Ng Weibin', 'lord.ngweibin@gmail.com', '97520006', '2020-07-15', '2020-07-15', '2020-07-17', 0, 'no');

-- --------------------------------------------------------

--
-- Table structure for table `presentations`
--

CREATE TABLE `presentations` (
  `p_id` int(11) NOT NULL,
  `p_title` varchar(100) NOT NULL,
  `p_details` text NOT NULL,
  `p_video` varchar(255) DEFAULT NULL,
  `p_unique_id` varchar(20) NOT NULL,
  `p_date` date NOT NULL,
  `p_time` time NOT NULL,
  `p_room` varchar(50) NOT NULL,
  `p_published` varchar(3) NOT NULL,
  `p_date_published` date DEFAULT NULL,
  `p_mailer_sent` varchar(3) NOT NULL,
  `p_num_signedup` int(11) NOT NULL,
  `p_num_responses` int(11) NOT NULL,
  `p_rep_id` int(11) NOT NULL,
  `p_rep_name` varchar(100) NOT NULL,
  `p_rep_email` varchar(255) NOT NULL,
  `p_event_id` int(11) NOT NULL,
  `p_assessment_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `resetpassword`
--

CREATE TABLE `resetpassword` (
  `id` int(11) NOT NULL,
  `code` varchar(300) NOT NULL,
  `email` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `results`
--

CREATE TABLE `results` (
  `r_id` int(11) NOT NULL,
  `r_presentation_id` int(11) NOT NULL,
  `r_attendee_id` int(11) NOT NULL,
  `r_ass_id` int(11) NOT NULL,
  `n1` int(1) NOT NULL,
  `n2` int(1) NOT NULL,
  `n3` int(1) NOT NULL,
  `n4` int(1) NOT NULL,
  `n5` int(1) NOT NULL,
  `n6` int(1) NOT NULL,
  `n7` int(1) NOT NULL,
  `n8` int(1) NOT NULL,
  `n9` int(1) NOT NULL,
  `n10` int(1) NOT NULL,
  `t1` text NOT NULL,
  `t2` text NOT NULL,
  `t3` text NOT NULL,
  `t4` text NOT NULL,
  `t5` text NOT NULL,
  `t6` text NOT NULL,
  `t7` text NOT NULL,
  `t8` text NOT NULL,
  `t9` text NOT NULL,
  `t10` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `results`
--

INSERT INTO `results` (`r_id`, `r_presentation_id`, `r_attendee_id`, `r_ass_id`, `n1`, `n2`, `n3`, `n4`, `n5`, `n6`, `n7`, `n8`, `n9`, `n10`, `t1`, `t2`, `t3`, `t4`, `t5`, `t6`, `t7`, `t8`, `t9`, `t10`) VALUES
(27, 48, 52, 2, 5, 5, 5, 5, 5, 5, 5, 5, 0, 0, 'Chio bu', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none');

-- --------------------------------------------------------

--
-- Table structure for table `speakers`
--

CREATE TABLE `speakers` (
  `s_id` int(11) NOT NULL,
  `s_first_name` varchar(100) NOT NULL,
  `s_last_name` varchar(100) NOT NULL,
  `s_course` varchar(100) NOT NULL,
  `s_profile_pic` varchar(25) NOT NULL,
  `s_presentation_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `u_id` int(11) NOT NULL,
  `u_acc_type` varchar(3) NOT NULL,
  `u_first_name` varchar(25) NOT NULL,
  `u_last_name` varchar(25) NOT NULL,
  `u_telephone` varchar(20) NOT NULL,
  `u_username` varchar(255) NOT NULL,
  `u_email` varchar(100) NOT NULL,
  `u_password` varchar(255) NOT NULL,
  `u_signup_date` date NOT NULL,
  `u_profile_pic` varchar(255) NOT NULL,
  `u_num_events` int(11) NOT NULL,
  `u_num_presentations` int(11) NOT NULL,
  `u_acc_closed` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`u_id`, `u_acc_type`, `u_first_name`, `u_last_name`, `u_telephone`, `u_username`, `u_email`, `u_password`, `u_signup_date`, `u_profile_pic`, `u_num_events`, `u_num_presentations`, `u_acc_closed`) VALUES
(92, 'uc', 'Ng', 'Weibin', '97520006', 'ng_weibin', 'lord.ngweibin@gmail.com', '706cd7ce252961e9db43243cdd0bec14', '2020-07-15', 'assets/images/profile_pictures/uc_profile.png', 2, 0, 'no');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assessments`
--
ALTER TABLE `assessments`
  ADD PRIMARY KEY (`ass_id`);

--
-- Indexes for table `attendees`
--
ALTER TABLE `attendees`
  ADD PRIMARY KEY (`a_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`e_id`);

--
-- Indexes for table `presentations`
--
ALTER TABLE `presentations`
  ADD PRIMARY KEY (`p_id`);

--
-- Indexes for table `resetpassword`
--
ALTER TABLE `resetpassword`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `results`
--
ALTER TABLE `results`
  ADD PRIMARY KEY (`r_id`);

--
-- Indexes for table `speakers`
--
ALTER TABLE `speakers`
  ADD PRIMARY KEY (`s_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`u_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assessments`
--
ALTER TABLE `assessments`
  MODIFY `ass_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `attendees`
--
ALTER TABLE `attendees`
  MODIFY `a_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `e_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `presentations`
--
ALTER TABLE `presentations`
  MODIFY `p_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `resetpassword`
--
ALTER TABLE `resetpassword`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `results`
--
ALTER TABLE `results`
  MODIFY `r_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `speakers`
--
ALTER TABLE `speakers`
  MODIFY `s_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `u_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
