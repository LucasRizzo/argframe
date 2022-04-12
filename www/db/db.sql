CREATE TABLE IF NOT EXISTS `arguments` (
  `id` int(11) NOT NULL,
  `argument` text NOT NULL,
  `x` decimal(10,0) NOT NULL,
  `y` decimal(10,0) NOT NULL,
  `label` varchar(40) NOT NULL,
  `model` varchar(40) NOT NULL,
  `dataset` varchar(40) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=latin1;

ALTER TABLE `arguments`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `arguments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=43;

CREATE TABLE IF NOT EXISTS `attributes` (
  `attribute` varchar(30) NOT NULL,
  `dataset` varchar(30) NOT NULL,
  `a_level` varchar(30) NOT NULL,
  `a_from` decimal(10,0) NOT NULL,
  `a_to` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `attributes`
  ADD PRIMARY KEY (`attribute`,`dataset`,`a_level`);

CREATE TABLE IF NOT EXISTS `models` (
  `dataset` varchar(40) NOT NULL,
  `name` varchar(40) NOT NULL,
  `semantic` varchar(40) NOT NULL,
  `edges` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `models`
  ADD PRIMARY KEY (`dataset`,`name`);