-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 01, 2025 at 12:54 PM
-- Server version: 8.0.42-0ubuntu0.24.04.1
-- PHP Version: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `BazoGRYzarka`
--

-- --------------------------------------------------------

--
-- Table structure for table `Gra`
--

CREATE TABLE `Gra` (
  `IdGry` int NOT NULL,
  `Tytul` varchar(255) DEFAULT NULL,
  `Wydawca` varchar(255) DEFAULT NULL,
  `Producent` varchar(255) DEFAULT NULL,
  `Id_JakieSystemy` int DEFAULT NULL,
  `Cena` decimal(10,2) DEFAULT NULL,
  `Id_Pegi` int DEFAULT NULL,
  `DataWydania` date DEFAULT NULL,
  `krotkiOpis` text,
  `dlugiOpis` text,
  `zdjGlowne` text,
  `WebGLFolderName` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Gra`
--

INSERT INTO `Gra` (`IdGry`, `Tytul`, `Wydawca`, `Producent`, `Id_JakieSystemy`, `Cena`, `Id_Pegi`, `DataWydania`, `krotkiOpis`, `dlugiOpis`, `zdjGlowne`, `WebGLFolderName`) VALUES
(1, 'Minecraft', 'MOJANG', 'MOJANG', 7, 75.00, NULL, '2011-05-05', 'Minecraft to popularna gra sandboxowa, w której gracze mogą budować, eksplorować i przetrwać w generowanym proceduralnie świecie złożonym z bloków. Dostępna jest w trybach kreatywnym i survivalowym, zarówno solo, jak i w trybie wieloosobowym.', 'Minecraft to kultowa gra komputerowa stworzona przez Markusa Perssona (Notch), a obecnie rozwijana przez Mojang Studios. Zadebiutowała w 2011 roku i od tamtej pory zdobyła ogromną popularność na całym świecie.\r\n\r\nGra toczy się w otwartym, trójwymiarowym świecie zbudowanym z sześciennych bloków reprezentujących różne materiały – ziemię, kamień, wodę, drewno i inne. Gracze mogą swobodnie eksplorować świat, wydobywać surowce, wytwarzać przedmioty (crafting), konstruować budowle, a także walczyć z potworami i przetrwać w trybie survival.\r\n\r\nDostępny jest również tryb kreatywny, który pozwala na nieograniczone budowanie bez zagrożenia ze strony przeciwników czy konieczności zdobywania surowców. Minecraft oferuje także bogaty system modyfikacji, własne serwery multiplayer, redstone (system logiczny do tworzenia mechanizmów) oraz możliwość tworzenia map i przygód przez społeczność.\r\n\r\nDzięki swojej prostocie, ogromnym możliwościom i wsparciu społeczności, Minecraft jest idealną grą zarówno dla dzieci, jak i dorosłych. To nie tylko gra, ale i narzędzie edukacyjne, wykorzystywane również w szkołach do nauki programowania, matematyki i współpracy.', 'minecraft.jpg', NULL),
(21, 'Race around the World', 'Riot Games', 'Riot Games', 6, 29.99, 1, '2025-05-07', 'Race Around the World to dynamiczna gra wyścigowa, w której ścigasz się przez najbardziej kultowe zakątki świata. Od autostrad USA po piaski Sahary – sprawdź swoje umiejętności za kierownicą i zostań globalnym mistrzem wyścigów!', 'Race Around the World przenosi Cię w ekscytującą podróż przez kontynenty, gdzie każdy wyścig to nowe wyzwanie. Wybierz swój samochód, dostosuj go do ekstremalnych warunków i ścigaj się w najpiękniejszych, ale i najbardziej niebezpiecznych miejscach na Ziemi – od zakorkowanych ulic Tokio, przez kręte serpentyny Alp, aż po burzliwe bezdroża Amazonii.\r\n\r\nZróżnicowane tryby gry, realistyczna fizyka jazdy, dynamiczna pogoda i system dnia i nocy sprawiają, że każda trasa to unikalne przeżycie. Rywalizuj w trybie kariery, stwórz własną ligę ze znajomymi lub weź udział w międzynarodowych turniejach online.\r\n\r\nCzy potrafisz wygrać wyścig, który nie kończy się na jednej mapie? Pora objechać świat... na pełnym gazie.', 'motoryzacja.jpg', 'game1'),
(43, 'Minecraft 2', 'Paesano Corporation', 'Paesano Corporation', 7, 98.99, 8, '2025-03-03', 'Witamy w Minecraft 2 – nowej erze budowania, przetrwania i odkrywania!', 'Po ponad dekadzie od premiery kultowego Minecrafta, nadchodzi kontynuacja, która wynosi klasyczną rozgrywkę na zupełnie nowy poziom. W Minecraft 2 znajdziesz jeszcze większy, dynamiczny świat z realistyczną fizyką, nowymi biomami, potworami i technologiami, a wszystko to wciąż w duchu kreatywnej wolności.  🧱 Nowe możliwości budowania – setki nowych bloków i narzędzi pozwalają tworzyć jeszcze bardziej zaawansowane struktury. Nowy system redstone przypomina prawdziwą elektronikę!  🌍 Żyjący świat – biome reagują na Twoje działania, a fauna i flora rozwijają się z czasem. Każda rozgrywka to unikalna historia.  🤖 Inteligentni NPC – mieszkańcy wiosek uczą się, rozmawiają i tworzą własne społeczności. Możesz z nimi handlować, współpracować lub... rywalizować.  💥 Tryb przygody 2.0 – gotowy na kampanię z fabułą? Minecraft 2 oferuje tryb narracyjny z zadaniami, bossami i wątkami pobocznymi.  🎮 Multiplayer z prawdziwego zdarzenia – dedykowane serwery, kooperacja z przyjaciółmi, tryby PvP i wspólna budowa ogromnych projektów.  🎨 Nowa grafika, ten sam klimat – styl voxelowy został odświeżony dzięki nowemu silnikowi graficznemu, wspierającemu dynamiczne oświetlenie, cienie i efekty pogodowe – ale bez utraty charakteru klasycznego Minecrafta.', '683c1822e1831_zabawki.jpg', NULL),
(44, 'Global Offensive: Reloaded', 'BlackEcho Studios', 'Tactical Pixel Games', 4, 0.00, 9, '2025-03-04', 'Global Offensive: Reloaded to taktyczna strzelanka FPS, w której liczy się refleks, precyzja i współpraca. Dołącz do elitarnego oddziału antyterrorystycznego lub stań po stronie rebeliantów i walcz w szybkich, emocjonujących potyczkach online!', 'Global Offensive: Reloaded to duchowy spadkobierca klasycznych taktycznych strzelanek. Gra stawia na szybkie tempo, krótkie rundy i pełne napięcia decyzje drużynowe. Wciel się w rolę żołnierza jednostki specjalnej lub bojownika rebeliantów i weź udział w globalnym konflikcie rozgrywającym się na kultowych mapach – od zrujnowanych fabryk po miejskie dzielnice i pustynne forty.  Gra oferuje szeroki wachlarz broni, realistyczną balistykę, system ekonomii zakupów w trakcie meczu oraz rankingowe rozgrywki, które sprawdzą Twoje umiejętności w starciach z graczami z całego świata.  Global Offensive: Reloaded to więcej niż tylko strzelanie – to gra, gdzie każdy krok, każdy granat i każda decyzja mogą przesądzić o zwycięstwie.  Zoptymalizowana grafika, serwery dedykowane oraz wsparcie dla modów czynią z niej idealne pole bitwy dla nowych i doświadczonych graczy.', '683c1a3408131_Counter-Strike.jpg', NULL),
(45, 'TurboTrack Legends', 'Nitrobyte Works', 'FlashPoint Interactive', 3, 12.99, 8, '2025-05-01', 'TurboTrack Legends to ekstremalna gra wyścigowa, w której liczy się prędkość, precyzja i perfekcyjne linie przejazdu. Pokonuj szalone pętle, skocznie i ostre zakręty, walcząc o najlepszy czas na globalnych rankingach!', 'Przygotuj się na najbardziej zakręcone wyścigi w historii. TurboTrack Legends zabiera Cię do świata, w którym trasy łamią prawa fizyki, a każda sekunda ma znaczenie. Gra stawia na czystą rywalizację – bez zderzeń, bez broni, tylko Ty, tor i licznik czasu.  🚀 Kluczowe cechy:      Setki oficjalnych i społecznościowych tras – od szybkich sprintów po techniczne labirynty      Edytor torów – twórz własne trasy, dziel się nimi i rywalizuj na nich z graczami z całego świata      Tryb duchów i powtórek – śledź własne rekordy lub ucz się od najlepszych      Ranking globalny i lokalny – wspinaj się na szczyt tabeli najlepszych kierowców      Stylowa grafika arcade 3D z efektami turbo, neonami i klimatem rodem z lat 2000  Niezależnie, czy chcesz zagrać 5 minut, czy 5 godzin – TurboTrack Legends uzależnia jak prawdziwa dawka adrenaliny.  Wciśnij gaz do dechy, zapnij pasy i pokaż, kto rządzi na torze!', '683c1a8a0adea_trackmania.jpg', NULL),
(46, 'Blockadia: Depths of Creation', 'RetroSpark Studios', 'RetroSpark Studios', 7, 53.99, 10, '2024-09-10', 'Blockadia: Depths of Creation to dwuwymiarowa gra sandbox, w której kopiesz, tworzysz i walczysz w generowanym losowo świecie pełnym tajemnic, skarbów i potworów. Buduj swoją bazę, pokonuj bossów i odkrywaj podziemia pełne niebezpieczeństw!', 'Witaj w Blockadii – świecie, gdzie jedynym ograniczeniem jest Twoja wyobraźnia (i poziom zdrowia). W tej 2D sandboxowej przygodzie wyruszysz w podróż przez jaskinie, lasy, pustynie i podziemia, zbierając surowce, tworząc przedmioty i walcząc z przerażającymi przeciwnikami.  Gra oferuje:      Nieliniowy świat z proceduralnym generowaniem map      Rozbudowany system craftingu i alchemii      Walki z epickimi bossami oraz unikalnymi minibossami      Możliwość gry solo lub w trybie kooperacji online      Personalizację postaci, NPC-ów i budynków  Czy stworzysz ogromną twierdzę w chmurach, a może odkryjesz tajemnice starożytnych podziemi? W Blockadii to Ty tworzysz swoją przygodę – blok po bloku, metr po metrze.', '683c1ac401cde_nieruchomosci.jpg', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `JakieSystemy`
--

CREATE TABLE `JakieSystemy` (
  `Id_JakieSystemy` int NOT NULL,
  `CzyMac` tinyint(1) DEFAULT NULL,
  `CzyWindows` tinyint(1) DEFAULT NULL,
  `CzyLinux` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `JakieSystemy`
--

INSERT INTO `JakieSystemy` (`Id_JakieSystemy`, `CzyMac`, `CzyWindows`, `CzyLinux`) VALUES
(1, 0, 0, 0),
(2, 0, 0, 1),
(3, 0, 1, 0),
(4, 0, 1, 1),
(5, 1, 0, 0),
(6, 1, 0, 1),
(7, 1, 1, 1),
(8, 1, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `Pegi`
--

CREATE TABLE `Pegi` (
  `Id_Pegi` int NOT NULL,
  `Rating` varchar(50) DEFAULT NULL,
  `CzyPrzemoc` tinyint(1) DEFAULT NULL,
  `CzyNarkotyki` tinyint(1) DEFAULT NULL,
  `CzyTresciSeksualne` tinyint(1) DEFAULT NULL,
  `CzyWulgarnyJezyk` tinyint(1) DEFAULT NULL,
  `CzyZakupyWGrze` tinyint(1) DEFAULT NULL,
  `CzyStrach` tinyint(1) DEFAULT NULL,
  `CzyDyskryminacja` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Pegi`
--

INSERT INTO `Pegi` (`Id_Pegi`, `Rating`, `CzyPrzemoc`, `CzyNarkotyki`, `CzyTresciSeksualne`, `CzyWulgarnyJezyk`, `CzyZakupyWGrze`, `CzyStrach`, `CzyDyskryminacja`) VALUES
(1, '18', 0, 1, 1, 0, 0, 0, 0),
(2, '0', 0, 0, 0, 0, 0, 0, 0),
(3, '1', 0, 0, 0, 0, 0, 0, 0),
(4, '17', 0, 0, 1, 0, 0, 0, 0),
(5, '18', 1, 1, 1, 1, 0, 0, 1),
(6, '1', 0, 1, 1, 1, 1, 0, 0),
(7, '12', 0, 0, 1, 1, 0, 0, 0),
(8, '7', 0, 0, 0, 0, 1, 0, 0),
(9, '18', 1, 0, 0, 0, 1, 0, 1),
(10, '18', 1, 1, 1, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `Recenzje`
--

CREATE TABLE `Recenzje` (
  `Id_Recenzje` int NOT NULL,
  `Id_Gry` int DEFAULT NULL,
  `Typ` varchar(100) DEFAULT NULL,
  `Tresc` text,
  `Id_Uzytkownika` int DEFAULT NULL,
  `Data_wystawienie` date DEFAULT NULL,
  `Czas_gry` float(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Recenzje`
--

INSERT INTO `Recenzje` (`Id_Recenzje`, `Id_Gry`, `Typ`, `Tresc`, `Id_Uzytkownika`, `Data_wystawienie`, `Czas_gry`) VALUES
(2, 21, '4', 'Fajne choć nie jest to gra na dłuzszą rozgrywke, jedynie jedno podejscie!!!', 6, '2025-06-01', 5.00),
(3, 43, '5', 'xd\r\nxd\r\nxd\r\nxd\r\nxd\r\nxd\r\nxd\r\nxd\r\nxd\r\nxd\r\nxd\r\nxd\r\nxd\r\nxd\r\nxd\r\nxd\r\nxd\r\nxd\r\nxd\r\nxd\r\nxd\r\nxd\r\nxd\r\nxd\r\nxd\r\nxd\r\nxd\r\nxd\r\nxd\r\nxd\r\nxd\r\nxd\r\nxd\r\nxd\r\nxd\r\nxd\r\nxd\r\nxd\r\nxd\r\nxd\r\nxd\r\nv', 6, '2025-06-01', 2.00),
(4, 45, '5', '⣿⣿⣿⣿⣿⣿⣿⣿⣿⠿⣿⣛⣛⣭⣩⣭⣙⣛⣛⠿⢿⣿⣿⣿⣿⣿⣿⣿⣿  ⣿⣿⣿⣿⣿⣿⢟⣭⣶⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣷⣦⣍⡻⢿⣿⣿⣿⣿ ⣿⣿⣿⣿⢟⣴⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣮⡻⣿⣿⣿ ⣿⣿⡿⢡⣾⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⡜⢿⣿ ⣿⣿⢁⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⡿⠿⠿⠿⠿⣿⣿⣿⣿⣿⣿⣿⡌⣿ ⣿⡇⣼⣿⣿⣿⣿⣿⣿⣿⣷⣶⣶⣶⡿⢋⣁⣹⣿⣿⣿⣷⣷⣾⣿⣿⣿⣷⢹ ⣿⢁⣿⣿⣿⣿⣿⣯⡤⢀⣀⣠⣄⣀⢀⣤⣿⣿⣿⣤⠀⣀⣤⣤⣄⣽⣿⣿⡇ ⡏⢸⣿⣿⣿⣿⣿⠿⠁⠨⣙⣿⣿⣷⢀⣿⣿⠃⣿⣿⠀⢿⣿⣯⡻⣹⣿⣿⣇ ⡇⣿⣿⣿⣿⣿⣿⣿⡆⠀⠈⠉⠋⡡⣪⣿⣿⠆⢹⣿⣦⠀⠈⠁⢠⣿⣿⣿⢿ ⡇⢿⣿⠿⣛⣿⣻⣿⣿⣦⣠⣴⡾⠛⠃⠺⠉⠁⢀⠉⢉⡰⣶⣶⣿⣯⣝⣿⢸ ⣷⡘⣏⣾⣿⠿⡟⢫⣭⡁⣀⡀⠀⠀⢀⣒⣒⢒⣙⣙⣒⣀⡀⢉⣹⠟⠿⡟⣼ ⣿⣷⡹⣿⣿⡰⢶⣿⣿⣿⣿⣿⣷⣶⣤⡤⢀⣐⣒⣦⣤⣶⣾⣿⣿⢛⣣⢣⣿ ⣿⣿⣿⣌⠻⣿⣾⣿⣿⣿⣿⣿⣿⣿⣿⣾⣽⣬⢻⣿⣿⣿⣿⣿⣿⡿⣱⣿⣿ ⣿⣿⣿⣿⣷⣌⡙⢿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣼⣿⣿⣿⣿⡿⢛⣾⣿⣿⣿ ⣿⣿⣿⣿⣿⣿⣿⣷⣮⣝⣛⠿⢿⣿⣿⣿⣿⣿⣿⣿⠿⢛⣭⣶⣿⣿⣿⣿⣿ ⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣶⣶⣦⣭⣭⣭⣴⣶⣿⣿⣿⣿⣿⣿⣿⣿⣿', 6, '2025-06-01', 744.00);

-- --------------------------------------------------------

--
-- Table structure for table `TagGry`
--

CREATE TABLE `TagGry` (
  `Id_tagu` int NOT NULL,
  `Id_Gry` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `TagGry`
--

INSERT INTO `TagGry` (`Id_tagu`, `Id_Gry`) VALUES
(1, 43),
(6, 43),
(7, 43),
(1, 44),
(8, 44),
(9, 45),
(10, 45),
(1, 46),
(4, 46),
(7, 46);

-- --------------------------------------------------------

--
-- Table structure for table `Tagi`
--

CREATE TABLE `Tagi` (
  `Id_tagu` int NOT NULL,
  `Nazwa` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Tagi`
--

INSERT INTO `Tagi` (`Id_tagu`, `Nazwa`) VALUES
(1, 'Akcja'),
(2, 'Przygodowa'),
(3, 'Dla dzieci'),
(4, 'Horror'),
(5, 'RPG'),
(6, 'Puzzle'),
(7, 'Sandbox'),
(8, 'Bomb Defusal'),
(9, 'Wyścigi'),
(10, 'Multiplayer');

-- --------------------------------------------------------

--
-- Table structure for table `Uzytkownik`
--

CREATE TABLE `Uzytkownik` (
  `Id_Uzytkownika` int NOT NULL,
  `Nick` varchar(50) DEFAULT NULL,
  `Imie` varchar(50) DEFAULT NULL,
  `Nazwisko` varchar(50) DEFAULT NULL,
  `Kraj` varchar(50) DEFAULT NULL,
  `LinkDoProfilu` varchar(255) DEFAULT NULL,
  `ZdjecieProfilowe` varchar(50) DEFAULT NULL,
  `Opis` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `password_hash` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Uzytkownik`
--

INSERT INTO `Uzytkownik` (`Id_Uzytkownika`, `Nick`, `Imie`, `Nazwisko`, `Kraj`, `LinkDoProfilu`, `ZdjecieProfilowe`, `Opis`, `password_hash`, `created_at`, `email`) VALUES
(1, 'Kojkek', 'Patryk', 'Koc', 'Polska', 'kojkek', NULL, NULL, NULL, '2025-05-18 13:32:45', NULL),
(2, 'GrubSon', 'Kacper', 'Kucharski', 'Polska', 'GrubSon', NULL, NULL, NULL, '2025-05-18 13:32:45', NULL),
(4, 'Test12345', NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$EOrljM1E3HMoxAzSt963Wu.2nti4PYH/TrO0WAJH6bV4jabKnIEom', '2025-05-31 08:55:05', 'kefo321@gmail.com'),
(6, 'test123', NULL, NULL, 'POLSKA', NULL, 'avatar_6.jpg', 'TEAYETEAYETEAYETEAYETEAYETEAYETEAYETEAYETEAYETEAYETEAYETEAYETEAYETEAYETEAYETEAYETEAYETEAYETEAYETEAYETEAYETEAYETEAYETEAYETEAYETEAYETEAYETEAYETEAYEv', '$2y$10$H2GgXetOLWZlffwn6Thr5e92BE0bYCjym38xASl2/cMsQydnRf0aC', '2025-06-01 11:55:20', 'test123@wp.pl');

-- --------------------------------------------------------

--
-- Table structure for table `UzytkownikGra`
--

CREATE TABLE `UzytkownikGra` (
  `Id_Gry` int NOT NULL,
  `Id_Uzytkownika` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `UzytkownikGra`
--

INSERT INTO `UzytkownikGra` (`Id_Gry`, `Id_Uzytkownika`) VALUES
(21, 6),
(43, 6),
(44, 6),
(45, 6),
(46, 6);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Gra`
--
ALTER TABLE `Gra`
  ADD PRIMARY KEY (`IdGry`),
  ADD KEY `Id_JakieSystemy` (`Id_JakieSystemy`),
  ADD KEY `Id_Pegi` (`Id_Pegi`);

--
-- Indexes for table `JakieSystemy`
--
ALTER TABLE `JakieSystemy`
  ADD PRIMARY KEY (`Id_JakieSystemy`);

--
-- Indexes for table `Pegi`
--
ALTER TABLE `Pegi`
  ADD PRIMARY KEY (`Id_Pegi`);

--
-- Indexes for table `Recenzje`
--
ALTER TABLE `Recenzje`
  ADD PRIMARY KEY (`Id_Recenzje`),
  ADD UNIQUE KEY `Id_Gry` (`Id_Gry`,`Id_Uzytkownika`),
  ADD KEY `Id_Uzytkownika` (`Id_Uzytkownika`);

--
-- Indexes for table `TagGry`
--
ALTER TABLE `TagGry`
  ADD PRIMARY KEY (`Id_tagu`,`Id_Gry`),
  ADD KEY `Id_Gry` (`Id_Gry`);

--
-- Indexes for table `Tagi`
--
ALTER TABLE `Tagi`
  ADD PRIMARY KEY (`Id_tagu`);

--
-- Indexes for table `Uzytkownik`
--
ALTER TABLE `Uzytkownik`
  ADD PRIMARY KEY (`Id_Uzytkownika`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `UzytkownikGra`
--
ALTER TABLE `UzytkownikGra`
  ADD PRIMARY KEY (`Id_Gry`,`Id_Uzytkownika`),
  ADD KEY `Id_Uzytkownika` (`Id_Uzytkownika`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Gra`
--
ALTER TABLE `Gra`
  MODIFY `IdGry` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `JakieSystemy`
--
ALTER TABLE `JakieSystemy`
  MODIFY `Id_JakieSystemy` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `Pegi`
--
ALTER TABLE `Pegi`
  MODIFY `Id_Pegi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `Recenzje`
--
ALTER TABLE `Recenzje`
  MODIFY `Id_Recenzje` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `Tagi`
--
ALTER TABLE `Tagi`
  MODIFY `Id_tagu` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `Uzytkownik`
--
ALTER TABLE `Uzytkownik`
  MODIFY `Id_Uzytkownika` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Gra`
--
ALTER TABLE `Gra`
  ADD CONSTRAINT `Gra_ibfk_1` FOREIGN KEY (`Id_JakieSystemy`) REFERENCES `JakieSystemy` (`Id_JakieSystemy`),
  ADD CONSTRAINT `Gra_ibfk_2` FOREIGN KEY (`Id_Pegi`) REFERENCES `Pegi` (`Id_Pegi`);

--
-- Constraints for table `Recenzje`
--
ALTER TABLE `Recenzje`
  ADD CONSTRAINT `Recenzje_ibfk_1` FOREIGN KEY (`Id_Gry`) REFERENCES `Gra` (`IdGry`),
  ADD CONSTRAINT `Recenzje_ibfk_2` FOREIGN KEY (`Id_Uzytkownika`) REFERENCES `Uzytkownik` (`Id_Uzytkownika`);

--
-- Constraints for table `TagGry`
--
ALTER TABLE `TagGry`
  ADD CONSTRAINT `TagGry_ibfk_1` FOREIGN KEY (`Id_tagu`) REFERENCES `Tagi` (`Id_tagu`),
  ADD CONSTRAINT `TagGry_ibfk_2` FOREIGN KEY (`Id_Gry`) REFERENCES `Gra` (`IdGry`);

--
-- Constraints for table `UzytkownikGra`
--
ALTER TABLE `UzytkownikGra`
  ADD CONSTRAINT `UzytkownikGra_ibfk_1` FOREIGN KEY (`Id_Gry`) REFERENCES `Gra` (`IdGry`),
  ADD CONSTRAINT `UzytkownikGra_ibfk_2` FOREIGN KEY (`Id_Uzytkownika`) REFERENCES `Uzytkownik` (`Id_Uzytkownika`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
