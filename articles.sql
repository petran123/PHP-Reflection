-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 21, 2019 at 05:36 PM
-- Server version: 10.4.6-MariaDB
-- PHP Version: 7.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `id` int(255) NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` varchar(4000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_posted` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`id`, `title`, `content`, `date_posted`) VALUES
(1, 'My first post!', 'This is my first article post. Made directly from the SQL database as an example to work with.', '2019-10-15'),
(2, 'Second Post', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Arcu dictum varius duis at consectetur lorem. Diam donec adipiscing tristique risus nec feugiat in fermentum posuere. Et netus et malesuada fames ac turpis. Mi eget mauris pharetra et. Nulla facilisi nullam vehicula ipsum a arcu cursus vitae congue. Sed felis eget velit aliquet sagittis id consectetur purus ut. Amet consectetur adipiscing elit ut. Cras fermentum odio eu feugiat. Eu consequat ac felis donec et odio pellentesque diam volutpat. Imperdiet proin fermentum leo vel. Amet risus nullam eget felis eget. Turpis massa sed elementum tempus egestas sed sed risus. Nunc consequat interdum varius sit. Natoque penatibus et magnis dis parturient montes nascetur ridiculus mus. Erat velit scelerisque in dictum non consectetur a.', '2019-10-16'),
(3, 'Post number three', 'Pellentesque nec nam aliquam sem et tortor consequat id porta. Turpis egestas pretium aenean pharetra magna ac placerat vestibulum. Pellentesque nec nam aliquam sem. Consectetur lorem donec massa sapien faucibus et molestie ac. Odio facilisis mauris sit amet massa vitae. Posuere sollicitudin aliquam ultrices sagittis. In tellus integer feugiat scelerisque varius. Nunc congue nisi vitae suscipit. Tortor at risus viverra adipiscing at in tellus integer. Nec sagittis aliquam malesuada bibendum arcu vitae. Magna eget est lorem ipsum dolor sit. Pharetra convallis posuere morbi leo urna molestie at elementum. Porttitor massa id neque aliquam vestibulum morbi blandit cursus. Netus et malesuada fames ac turpis egestas sed tempus. Quis varius quam quisque id diam vel quam elementum. Rhoncus dolor purus non enim praesent elementum facilisis.', '2019-10-17'),
(4, 'Four', 'Bibendum est ultricies integer quis auctor elit sed. Amet volutpat consequat mauris nunc congue nisi. In dictum non consectetur a. Nisl nisi scelerisque eu ultrices vitae. Congue quisque egestas diam in arcu cursus euismod quis viverra. Placerat orci nulla pellentesque dignissim enim sit amet venenatis urna. Fringilla urna porttitor rhoncus dolor purus. Malesuada nunc vel risus commodo. Pulvinar etiam non quam lacus suspendisse faucibus interdum. Lectus urna duis convallis convallis. Euismod nisi porta lorem mollis aliquam ut porttitor. In aliquam sem fringilla ut morbi. Sit amet justo donec enim. Amet tellus cras adipiscing enim eu turpis egestas pretium. Eu volutpat odio facilisis mauris sit. Tellus rutrum tellus pellentesque eu tincidunt tortor aliquam nulla. Adipiscing vitae proin sagittis nisl rhoncus. Dignissim enim sit amet venenatis urna cursus eget.', '2019-10-18'),
(5, 'Five', 'Metus dictum at tempor commodo ullamcorper a. Ut enim blandit volutpat maecenas volutpat blandit aliquam. Orci eu lobortis elementum nibh tellus molestie nunc non blandit. Lectus magna fringilla urna porttitor rhoncus dolor purus non enim. Enim sit amet venenatis urna cursus eget nunc scelerisque. Nam libero justo laoreet sit amet cursus. Nulla facilisi cras fermentum odio eu feugiat pretium nibh. Ac odio tempor orci dapibus ultrices. Aenean vel elit scelerisque mauris pellentesque pulvinar. Egestas erat imperdiet sed euismod nisi porta lorem mollis aliquam. Nullam vehicula ipsum a arcu cursus vitae congue mauris. Et sollicitudin ac orci phasellus egestas tellus rutrum. Id consectetur purus ut faucibus pulvinar elementum integer enim. Pharetra convallis posuere morbi leo.', '2019-10-21'),
(6, 'First entry by the website', 'Success! i hope', '2019-10-21'),
(7, 'Yet another one', 'This one was allowed in by a mistake. Should be fixed now.', '2019-10-21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `content` (`content`(768)),
  ADD KEY `title` (`title`),
  ADD KEY `date_posted` (`date_posted`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
