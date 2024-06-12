-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2023. Nov 25. 14:54
-- Kiszolgáló verziója: 10.4.27-MariaDB
-- PHP verzió: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `csapatsport`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `csapat`
--

CREATE TABLE `csapat` (
  `csapatid` int(5) NOT NULL,
  `nev` varchar(100) NOT NULL,
  `varos` varchar(100) NOT NULL,
  `alapitaseve` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- A tábla adatainak kiíratása `csapat`
--

INSERT INTO `csapat` (`csapatid`, `nev`, `varos`, `alapitaseve`) VALUES
(11111, 'Szegedi Cápák', 'Szeged', 1996),
(11112, 'Pécsi Párducok', 'Pécs', 1998),
(11113, 'Győri Viperák', 'Győr', 2001),
(11114, 'Budapesti Medvék', 'Budapest', 1990),
(11115, 'Debreceni Sárkányok', 'Debrecen', 1994);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `felhasznalo`
--

CREATE TABLE `felhasznalo` (
  `felhasznalonev` varchar(120) NOT NULL,
  `nev` varchar(120) NOT NULL,
  `jelszo` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- A tábla adatainak kiíratása `felhasznalo`
--

INSERT INTO `felhasznalo` (`felhasznalonev`, `nev`, `jelszo`) VALUES
('biro', 'Bíró Győző', '$2y$10$V6bhjXzgSNvLB0I01j09VemKHgRHMMTMoJ1iY/JCE/JWk1zRhB2wm'),
('koordinator', 'Koordinátor Vera', '$2y$10$WDbXCJn6hNcYbVpBEu5PDOYmnvaRIQqe3RQAsv5xkDQN5IvCHm5s2'),
('megegy', 'Mégegy', '$2y$10$oeAxHHMFQOSSmfSSdR1Oc.2XTQXgGQZ5OWoZ1brKGMVU5PifN6pVi'),
('proba', 'Proba', '$2y$10$.jWuwsECexmlO8aj3.uFhuMxtZd5srnFdCWS.9UjerdKrUukvtwXu'),
('szervezo', 'Szervező Sándor', '$2y$10$83sxVUgSYJWvG9u128.i5e4d9ffbbL9PU5gn9Vu2GAekTViOU0pHa');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `merkozes`
--

CREATE TABLE `merkozes` (
  `csapat1id` int(5) NOT NULL,
  `csapat2id` int(5) NOT NULL,
  `datum` date NOT NULL,
  `helyszin` varchar(100) NOT NULL,
  `nyertes` varchar(100) DEFAULT NULL,
  `nyertespont` int(2) DEFAULT NULL,
  `vesztespont` int(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- A tábla adatainak kiíratása `merkozes`
--

INSERT INTO `merkozes` (`csapat1id`, `csapat2id`, `datum`, `helyszin`, `nyertes`, `nyertespont`, `vesztespont`) VALUES
(11111, 11115, '2023-12-01', 'Debrecen Főnix Arána', NULL, NULL, NULL),
(11112, 11111, '2023-11-07', 'Szeged Pick Aréna', 'Szegedi Cápák', 5, 2),
(11112, 11113, '2023-10-02', 'Győr Audi Aréna', 'Győri Viperák', 1, 0),
(11113, 11111, '2023-11-30', 'Győr Audi Aréna', NULL, NULL, NULL),
(11113, 11114, '2023-10-09', 'Budapest Papp László Aréna', 'Győri Viperák', 4, 2),
(11114, 11115, '2023-09-12', 'Budapest Papp László Sportaréna', 'Budapesti Medvék', 4, 3),
(11114, 11115, '2023-09-23', 'Debreceni Főnix Aréna', 'Debreceni Sárkányok', 3, 1),
(11114, 11115, '2023-12-28', 'Budapest Papp László Sportaréna', NULL, NULL, NULL),
(11115, 11112, '2023-09-03', 'Debrecen Főnix Aréna', 'Pécsi Párducok', 3, 2);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `tag`
--

CREATE TABLE `tag` (
  `sportoloid` int(6) NOT NULL,
  `nev` varchar(100) NOT NULL,
  `szuletesidatum` date NOT NULL,
  `allampolgarsag` varchar(50) NOT NULL,
  `poszt` varchar(50) NOT NULL,
  `csapatid` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- A tábla adatainak kiíratása `tag`
--

INSERT INTO `tag` (`sportoloid`, `nev`, `szuletesidatum`, `allampolgarsag`, `poszt`, `csapatid`) VALUES
(111111, 'Kovács András', '1998-05-12', 'magyar', 'csatár', 11111),
(111112, 'Tóth Sándor', '1990-01-02', 'magyar', 'kapus', 11111),
(111113, 'Széll István', '1981-04-01', 'magyar', 'hátvéd', 11111),
(111114, 'Magyar Béla', '1996-11-07', 'magyar', 'szél', 11111),
(111115, 'Alex Müller', '1997-06-24', 'német', 'középpályás', 11111),
(111116, 'Dragan Jovic', '2001-03-21', 'szerb', 'középpályás', 11111),
(111117, 'Nicola Jovanovic', '2000-08-11', 'szerb', 'csatár', 11111),
(222221, 'Asztalos Balázs', '1987-09-06', 'magyar', 'kapus', 11112),
(222222, 'Kovács Péter', '1991-04-26', 'magyar', 'szél', 11112),
(222223, 'Nagy Botond', '2002-02-02', 'magyar', 'középpályás', 11112),
(222224, 'Luka Horvat', '1997-06-21', 'horvát', 'hátvéd', 11112),
(222225, 'Marco Kovac', '1998-01-22', 'horvát', 'csatár', 11112),
(222226, 'Emre Yilmaz', '1989-12-23', 'török', 'középpályás', 11112),
(222227, 'Kaan Demir', '1992-07-11', 'török', 'szél', 11112),
(333331, 'Horváth Ilona', '1986-06-06', 'magyar', 'kapus', 11113),
(333332, 'Németh Virág', '1996-12-01', 'magyar', 'csatár', 11113),
(333333, 'Sánta Klaudia', '1997-11-03', 'magyar', 'szél', 11113),
(333334, 'Tóth Emília', '1995-05-25', 'magyar', 'hátvéd', 11113),
(333335, 'Eva Nováková', '1993-03-05', 'szlovák', 'csatár', 11113),
(333336, 'Zuzana Kovácova', '1992-02-06', 'szlovák', 'középpályás', 11113),
(333337, 'Anna Weber', '2000-06-01', 'oszták', 'szél', 11113),
(444441, 'Magyar Sára', '1992-09-09', 'magyar', 'hátvéd', 11114),
(444442, 'Szabó Tünde', '1994-12-12', 'magyar', 'középpályás', 11114),
(444443, 'Kovács Nikoletta', '2002-05-23', 'magyar', 'kapus', 11114),
(444444, 'Sofia Ferrari', '1985-04-08', 'olasz', 'csatár', 11114),
(444445, 'Giulia Bianchi', '1998-08-12', 'olasz', 'középpályás', 11114),
(444446, 'Lucía García', '1994-05-16', 'spanyol', 'csatár', 11114),
(444447, 'Marta López', '1994-01-21', 'spanyol', 'hátvéd', 11114),
(555551, 'Lengyel Csaba', '1989-05-01', 'magyar', 'kapus', 11115),
(555552, 'Kovács Lehel', '1996-02-15', 'magyar', 'hátvéd', 11115),
(555553, 'Nagy Tamás', '1995-11-21', 'magyar', 'szél', 11115),
(555554, 'Faragó Bence', '1999-11-01', 'magyar', 'középpályás', 11115),
(555555, 'Dmitry Ivanov', '1991-03-17', 'orosz', 'csatár', 11115),
(555556, 'Lukas Müller', '1990-06-28', 'német', 'szél', 11115),
(555557, 'Felix Schäfer', '1993-12-07', 'német', 'középpályás', 11115);

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `csapat`
--
ALTER TABLE `csapat`
  ADD PRIMARY KEY (`csapatid`);

--
-- A tábla indexei `felhasznalo`
--
ALTER TABLE `felhasznalo`
  ADD PRIMARY KEY (`felhasznalonev`);

--
-- A tábla indexei `merkozes`
--
ALTER TABLE `merkozes`
  ADD PRIMARY KEY (`csapat1id`,`csapat2id`,`datum`),
  ADD KEY `csapat2id` (`csapat2id`);

--
-- A tábla indexei `tag`
--
ALTER TABLE `tag`
  ADD PRIMARY KEY (`sportoloid`),
  ADD KEY `tag_ibfk_1` (`csapatid`);

--
-- Megkötések a kiírt táblákhoz
--

--
-- Megkötések a táblához `merkozes`
--
ALTER TABLE `merkozes`
  ADD CONSTRAINT `merkozes_ibfk_1` FOREIGN KEY (`csapat1id`) REFERENCES `csapat` (`csapatid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `merkozes_ibfk_2` FOREIGN KEY (`csapat2id`) REFERENCES `csapat` (`csapatid`) ON UPDATE CASCADE;

--
-- Megkötések a táblához `tag`
--
ALTER TABLE `tag`
  ADD CONSTRAINT `tag_ibfk_1` FOREIGN KEY (`csapatid`) REFERENCES `csapat` (`csapatid`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
