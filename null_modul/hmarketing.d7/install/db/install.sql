CREATE TABLE `hmarketing_test` (
    `ID` INT(11) NOT NULL AUTO_INCREMENT,
    `TITLE` VARCHAR(65536) NOT NULL,
    `SORT` INT(11) DEFAULT 500,
    PRIMARY KEY(ID)
);
insert  into `hmarketing_test` (`ID`, `TITLE`, `SORT`) values (1, 'Заготовка модуля Эйч Маркетинг', 500);