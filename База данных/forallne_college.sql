-- phpMyAdmin SQL Dump
-- version 4.4.15.10
-- https://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Фев 07 2018 г., 14:44
-- Версия сервера: 10.0.33-MariaDB
-- Версия PHP: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `forallne_college`
--

-- --------------------------------------------------------

--
-- Структура таблицы `attend`
--

CREATE TABLE IF NOT EXISTS `attend` (
  `date` varchar(10) NOT NULL,
  `student_ID` int(9) NOT NULL,
  `curator_ID` int(3) NOT NULL,
  `P` int(2) NOT NULL,
  `U` int(2) NOT NULL,
  `B` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `attend`
--

INSERT INTO `attend` (`date`, `student_ID`, `curator_ID`, `P`, `U`, `B`) VALUES
('301117', 39, 1, 0, 1, 0),
('301117', 31, 1, 0, 3, 0),
('301117', 30, 1, 0, 3, 0),
('301117', 40, 1, 1, 0, 0),
('301117', 36, 1, 0, 0, 3),
('301117', 33, 1, 0, 5, 0),
('170118', 39, 1, 0, 3, 0),
('170118', 41, 1, 4, 0, 0),
('170118', 31, 1, 0, 1, 0),
('300118', 39, 1, 4, 0, 0),
('300118', 31, 1, 0, 1, 0),
('300118', 32, 1, 0, 1, 0),
('300118', 41, 1, 0, 2, 0),
('010218', 33, 1, 0, 8, 0),
('010218', 36, 1, 0, 5, 0),
('010218', 37, 1, 0, 2, 0),
('010218', 35, 1, 0, 1, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `curators`
--

CREATE TABLE IF NOT EXISTS `curators` (
  `curator_ID` int(3) unsigned NOT NULL,
  `c_name` varchar(15) NOT NULL,
  `c_surname` varchar(15) NOT NULL,
  `c_father` varchar(15) NOT NULL,
  `nickname` varchar(15) NOT NULL,
  `password` varchar(32) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `curators`
--

INSERT INTO `curators` (`curator_ID`, `c_name`, `c_surname`, `c_father`, `nickname`, `password`) VALUES
(1, 'Евгений', 'Старосветский', 'Дмитриевич', 'admin', '21232f297a57a5a743894a0e4a801fc3');

-- --------------------------------------------------------

--
-- Структура таблицы `curator_group`
--

CREATE TABLE IF NOT EXISTS `curator_group` (
  `curator_ID` int(3) unsigned NOT NULL,
  `group_ID` varchar(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `curator_group`
--

INSERT INTO `curator_group` (`curator_ID`, `group_ID`) VALUES
(1, '312');

-- --------------------------------------------------------

--
-- Структура таблицы `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `group_ID` varchar(6) NOT NULL,
  `course` varchar(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `groups`
--

INSERT INTO `groups` (`group_ID`, `course`) VALUES
('212', '1'),
('312', '1');

-- --------------------------------------------------------

--
-- Структура таблицы `students`
--

CREATE TABLE IF NOT EXISTS `students` (
  `student_ID` int(9) NOT NULL,
  `s_name` varchar(44) NOT NULL,
  `s_group` varchar(6) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `students`
--

INSERT INTO `students` (`student_ID`, `s_name`, `s_group`) VALUES
(24, 'Каков Ива', '212'),
(30, 'Рюрик Иван Кириллович', '312'),
(31, 'Кондратенков Борис Лохинович', '312'),
(32, 'Корчагин Никита Павлович', '312'),
(33, 'Манкевич Павел Владимирович', '312'),
(35, 'Рыхова Александра Николаевна', '312'),
(36, 'Попов Юрий Анатольевич', '312'),
(37, 'Россий Лаврентий Парфеньевич', '312'),
(39, 'Казаков Якун Эдуардович', '312'),
(40, 'Ширяева Фёкла Пантелеймоновна', '312'),
(41, 'Кивач Николай Терентьевич', '312'),
(43, 'Кузнецов Павел Александорович', '312'),
(45, 'Новый', '312');

-- --------------------------------------------------------

--
-- Структура таблицы `weekends`
--

CREATE TABLE IF NOT EXISTS `weekends` (
  `date` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `weekends`
--

INSERT INTO `weekends` (`date`) VALUES
('010117'),
('010318'),
('010417'),
('010617'),
('010717'),
('010817'),
('010818'),
('011017'),
('020218'),
('020417'),
('020418'),
('020617'),
('020717'),
('020817'),
('020818'),
('020917'),
('030318'),
('030617'),
('030717'),
('030817'),
('030818'),
('030917'),
('031217'),
('040217'),
('040218'),
('040317'),
('040418'),
('040617'),
('040717'),
('040817'),
('040818'),
('041117'),
('050217'),
('050317'),
('050617'),
('050717'),
('050817'),
('050818'),
('051117'),
('060517'),
('060617'),
('060717'),
('060817'),
('060818'),
('070117'),
('070517'),
('070617'),
('070717'),
('070817'),
('070818'),
('071017'),
('080117'),
('080417'),
('080617'),
('080717'),
('080817'),
('080818'),
('081017'),
('090318'),
('090417'),
('090418'),
('090617'),
('090717'),
('090817'),
('090818'),
('090917'),
('091217'),
('100218'),
('100418'),
('100617'),
('100717'),
('100817'),
('100818'),
('100917'),
('101217'),
('110217'),
('110317'),
('110617'),
('110717'),
('110817'),
('110818'),
('111117'),
('120217'),
('120317'),
('120617'),
('120717'),
('120817'),
('120818'),
('121117'),
('130517'),
('130617'),
('130717'),
('130817'),
('130818'),
('140117'),
('140517'),
('140617'),
('140717'),
('140817'),
('140818'),
('141017'),
('150117'),
('150118'),
('150318'),
('150417'),
('150617'),
('150717'),
('150817'),
('150818'),
('151017'),
('160118'),
('160218'),
('160417'),
('160418'),
('160617'),
('160717'),
('160817'),
('160818'),
('160917'),
('161217'),
('170617'),
('170717'),
('170817'),
('170818'),
('170917'),
('171217'),
('180217'),
('180218'),
('180317'),
('180418'),
('180617'),
('180717'),
('180817'),
('180818'),
('181117'),
('190217'),
('190317'),
('190617'),
('190717'),
('190817'),
('190818'),
('191117'),
('200517'),
('200617'),
('200717'),
('200817'),
('200818'),
('210117'),
('210517'),
('210617'),
('210717'),
('210817'),
('210818'),
('211017'),
('220117'),
('220417'),
('220617'),
('220717'),
('220817'),
('220818'),
('221017'),
('230417'),
('230617'),
('230717'),
('230817'),
('230818'),
('230917'),
('231217'),
('240617'),
('240717'),
('240817'),
('240818'),
('240917'),
('241217'),
('250217'),
('250317'),
('250617'),
('250717'),
('250817'),
('250818'),
('251117'),
('260217'),
('260317'),
('260617'),
('260717'),
('260817'),
('260818'),
('261117'),
('270517'),
('270617'),
('270717'),
('270817'),
('270818'),
('280117'),
('280517'),
('280617'),
('280717'),
('280817'),
('280818'),
('281017'),
('290117'),
('290417'),
('290617'),
('290717'),
('290817'),
('290818'),
('291017'),
('300417'),
('300617'),
('300717'),
('300817'),
('300818'),
('300917'),
('301217'),
('310717'),
('310817'),
('310818'),
('311217'),
('undefined');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `attend`
--
ALTER TABLE `attend`
  ADD KEY `student_ID` (`student_ID`);

--
-- Индексы таблицы `curators`
--
ALTER TABLE `curators`
  ADD PRIMARY KEY (`curator_ID`),
  ADD KEY `curator_ID` (`curator_ID`);

--
-- Индексы таблицы `curator_group`
--
ALTER TABLE `curator_group`
  ADD KEY `curator_ID` (`curator_ID`),
  ADD KEY `group_ID` (`group_ID`);

--
-- Индексы таблицы `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`group_ID`),
  ADD KEY `group_ID` (`group_ID`);

--
-- Индексы таблицы `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_ID`),
  ADD KEY `s_group` (`s_group`),
  ADD KEY `student_ID` (`student_ID`);

--
-- Индексы таблицы `weekends`
--
ALTER TABLE `weekends`
  ADD PRIMARY KEY (`date`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `curators`
--
ALTER TABLE `curators`
  MODIFY `curator_ID` int(3) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `students`
--
ALTER TABLE `students`
  MODIFY `student_ID` int(9) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=46;
--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `attend`
--
ALTER TABLE `attend`
  ADD CONSTRAINT `attend_ibfk_1` FOREIGN KEY (`student_ID`) REFERENCES `students` (`student_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `curator_group`
--
ALTER TABLE `curator_group`
  ADD CONSTRAINT `curator_group_ibfk_1` FOREIGN KEY (`curator_ID`) REFERENCES `curators` (`curator_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `curator_group_ibfk_2` FOREIGN KEY (`group_ID`) REFERENCES `groups` (`group_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`s_group`) REFERENCES `groups` (`group_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
