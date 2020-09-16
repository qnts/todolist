SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


CREATE TABLE `todos` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'planning'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


INSERT INTO `todos` (`id`, `name`, `start_date`, `end_date`, `status`) VALUES
(1, 'Writing a simple MVC PHP application', '2018-05-15', '2018-05-17', 'done'),
(2, 'A long event', '2018-05-14', '2018-05-19', 'done'),
(3, 'Ongoing task', '2018-05-22', '2018-05-24', 'doing'),
(4, 'Test creating task', '2018-05-26', '2018-05-30', 'planning');

ALTER TABLE `todos`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `todos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;
