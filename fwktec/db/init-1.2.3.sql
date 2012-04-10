--
-- Table structure for table sessions
--

CREATE TABLE `##sessions` (
  `ID` varchar(32) NOT NULL,
  `ACCESS` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `DATA` text,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

