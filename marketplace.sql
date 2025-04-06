-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 07, 2025 at 12:05 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `marketplace`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `number` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'user',
  `status` enum('active','blocked') NOT NULL DEFAULT 'active',
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `name`, `number`, `email`, `username`, `password`, `role`, `status`, `registration_date`) VALUES
(1, 'Huy', '0978476946', 'phamquangnamhuy1908@gmail.com', 'quanghuy', '52be5ff91284c65bac56f280df55f797a5c505f7ef66317ff358e34791507027', 'admin', 'active', '2024-05-14 04:17:25'),
(2, 'seller', '+998997733999', 'seller@gmail.com', 'seller', '52be5ff91284c65bac56f280df55f797a5c505f7ef66317ff358e34791507027', 'seller', 'active', '2024-05-14 04:17:25'),
(3, 'user', '+998993399777', 'user@gmail.com', 'user', '52be5ff91284c65bac56f280df55f797a5c505f7ef66317ff358e34791507027', 'user', 'active', '2024-05-14 04:17:25'),
(4, 'userAKA', '+998993399177', 'userAKA@gmail.com', 'userAKA', '52be5ff91284c65bac56f280df55f797a5c505f7ef66317ff358e34791507027', 'user', 'active', '2024-05-14 04:17:25'),
(5, 'Huy', '0978476946', 'phamquangnamhuy@gmail.com', 'usertest', '$2y$10$/r270OgdgyWw6ol3Ha7wc.JucDhSiBM0m1gZLMZSanT87uDuBIrNe', 'user', 'active', '2025-04-06 21:10:14'),
(6, 'phamquanghuy', '0978476945', 'phamquangnam@gmail.com', 'usertest2', '$2y$10$hamomBG6SYucRM8laDEP7.EdOR/cQYCVsbRUkFJ3kHIC5TNUt9H/G', 'user', 'active', '2025-04-06 21:16:19'),
(7, 'quanghuyadmin', '0978476947', 'phamquanghuy@gmail.com', 'quanghuyadmin', 'lolijolpp123', 'admin', 'active', '2025-04-06 21:22:08'),
(8, 'huypham', '0978476949', 'phamquangnam1@gmail.com', 'huy', 'a71db4fedef8ea4a4522321b54b7600b37f77a57c2e83677bb87e644e574f5fd', 'user', 'active', '2025-04-06 21:54:12'),
(9, 'phong', '0978476940', 'phamquangphong1908@gmail.com', 'phong', 'a71db4fedef8ea4a4522321b54b7600b37f77a57c2e83677bb87e644e574f5fd', 'seller', 'active', '2025-04-06 21:54:53');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `number_of_products` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `number_of_products`) VALUES
(3, 3, 43, 1);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_name`) VALUES
(1, 'Game Mobile'),
(2, 'Tài Khoản Tiện Ích'),
(3, 'Game PC & Tài Khoản Game'),
(4, 'Game Console');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(30) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `seller_id` int(11) DEFAULT NULL,
  `price_old` int(11) DEFAULT NULL,
  `price_current` int(11) DEFAULT NULL,
  `description` varchar(2000) DEFAULT NULL,
  `rating` decimal(2,1) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `added_to_site` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `category_id`, `seller_id`, `price_old`, `price_current`, `description`, `rating`, `quantity`, `added_to_site`) VALUES
(43, 'Dead Cells', 1, 2, 10, 10, 'Trò chơi kết hợp giữa Roguelike và Metroidvania, nơi người chơi điều khiển một khối tế bào khám phá một lâu đài rộng lớn, đánh bại quái vật và thu thập vật phẩm để tăng cường sức mạnh.', 5.0, 10, '2025-04-06 20:01:40'),
(44, 'Little Nightmares 1', 1, 2, 7, 7, 'Little Nightmares là một trò chơi kinh dị với lối chơi giải đố, nơi người chơi vào vai một cô bé tên Six đang trốn thoát khỏi một nơi gọi là The Maw, một khu vực đầy những sinh vật kỳ lạ và đáng sợ. Trong suốt hành trình, bạn sẽ phải giải quyết các câu đố, tránh né những kẻ thù khổng lồ và khám phá bí ẩn đằng sau The Maw. Trò chơi gây ấn tượng mạnh với bầu không khí u ám, âm thanh đáng sợ và các tình huống đầy kịch tính. Lối chơi không hề dễ dàng, đòi hỏi người chơi phải kiên nhẫn và tinh mắt để vượt qua các thử thách. Little Nightmares là một trò chơi hấp dẫn và đầy thử thách cho những ai yêu thích thể loại kinh dị và phiêu lưu.', 5.0, 10, '2025-04-06 20:04:58'),
(45, 'Little Nightmares 2', 1, 2, 10, 7, 'Little Nightmares 2 là một trò chơi kinh dị với lối chơi giải đố, nơi người chơi vào vai một cô bé tên Six đang trốn thoát khỏi một nơi gọi là The Maw, một khu vực đầy những sinh vật kỳ lạ và đáng sợ. Trong suốt hành trình, bạn sẽ phải giải quyết các câu đố, tránh né những kẻ thù khổng lồ và khám phá bí ẩn đằng sau The Maw. Trò chơi gây ấn tượng mạnh với bầu không khí u ám, âm thanh đáng sợ và các tình huống đầy kịch tính. Lối chơi không hề dễ dàng, đòi hỏi người chơi phải kiên nhẫn và tinh mắt để vượt qua các thử thách. Little Nightmares là một trò chơi hấp dẫn và đầy thử thách cho những ai yêu thích thể loại kinh dị và phiêu lưu.', 5.0, 10, '2025-04-06 20:05:40'),
(46, 'Endling – Extinction is Foreve', 1, 2, 7, 5, 'Endling – Extinction is Forever là một trò chơi sinh tồn đầy cảm xúc, nơi người chơi vào vai một chú cáo mẹ, là loài động vật cuối cùng còn sống sót trên hành tinh đang đối mặt với sự tàn phá môi trường. Trò chơi đặt người chơi vào một thế giới u ám, nơi con người và sự biến đổi môi trường đã đẩy các loài động vật vào bờ vực tuyệt chủng. Bạn phải dẫn dắt chú cáo mẹ bảo vệ và chăm sóc các con của mình, tìm kiếm thức ăn và tránh khỏi các nguy hiểm từ tự nhiên cũng như con người. Trò chơi không chỉ là một hành trình sinh tồn mà còn là một câu chuyện cảm động về tình mẫu tử và sự hy sinh. Với hình ảnh chân thật và âm nhạc đầy cảm xúc, Endling sẽ khiến người chơi phải suy ngẫm về tình trạng của môi trường hiện nay.', 5.0, 5, '2025-04-06 20:07:25'),
(47, 'Death&#039;s Door', 1, 2, 10, 8, 'Death’s Door là một trò chơi hành động nhập vai nơi người chơi sẽ vào vai một chú quạ đen chuyên thu thập linh hồn. Trong game, bạn sẽ phải đối mặt với những kẻ thù nguy hiểm và khám phá những thế giới huyền bí để thu thập linh hồn và mở các cánh cửa dẫn đến các thế giới khác. Trò chơi mang đậm phong cách hành động với lối chiến đấu nhanh nhẹn, cho phép người chơi sử dụng nhiều loại vũ khí và kỹ năng khác nhau. Các câu đố cũng được tích hợp vào gameplay, tạo ra một sự kết hợp thú vị giữa hành động và suy nghĩ chiến thuật. Cùng với đồ họa hoạt hình ấn tượng và âm nhạc tuyệt vời, Death’s Door sẽ mang đến cho bạn một trải nghiệm hành động khó quên.', 5.0, 5, '2025-04-06 20:08:05'),
(48, 'ARIDA: Backland&#039;s Awakeni', 1, 2, 5, 5, 'ARIDA: Backland&#039;s Awakening là một trò chơi phiêu lưu sinh tồn với bối cảnh lấy cảm hứng từ lịch sử Brazil vào thế kỷ 19. Người chơi sẽ vào vai Cícera, một cô gái trẻ phải đối mặt với những điều kiện sống khắc nghiệt trong một vùng đất bị tàn phá bởi hạn hán và nạn đói. Trò chơi không chỉ tập trung vào việc sinh tồn mà còn khắc họa câu chuyện sâu sắc về cuộc sống, gia đình và sự hy sinh. Trong suốt hành trình, Cícera sẽ khám phá môi trường xung quanh, thu thập tài nguyên, chế tạo đồ dùng và tìm cách đối phó với những thử thách mà tự nhiên và con người đặt ra. Với lối chơi sinh tồn độc đáo và cốt truyện đầy cảm động, ARIDA là một trải nghiệm đầy thử thách và ý nghĩa.', 5.0, 4, '2025-04-06 20:08:45'),
(49, 'Monument Valley 2', 1, 2, 5, 5, 'Là phần tiếp theo của Monument Valley, Monument Valley 2 tiếp tục mang đến những câu đố đầy thử thách nhưng cũng không kém phần hấp dẫn. Lần này, người chơi sẽ điều khiển nhân vật Ro, một người mẹ đang dẫn dắt con mình vượt qua những thế giới hình học kỳ lạ. Trò chơi vẫn giữ vững sự kế thừa của phần trước với đồ họa đẹp mắt và những câu đố thông minh. Monument Valley 2 không chỉ mang lại thử thách về logic mà còn kể một câu chuyện cảm động về tình mẫu tử. Những chi tiết như việc tạo ra những con đường mới từ các khối hình học sẽ luôn khiến bạn bất ngờ, tạo ra cảm giác hứng thú suốt hành trình.', 5.0, 2, '2025-04-06 20:09:48'),
(50, 'Monument Valley', 1, 2, 4, 4, 'Monument Valley là một trò chơi giải đố với đồ họa 3D tuyệt đẹp và lối chơi đầy tính nghệ thuật. Người chơi sẽ nhập vai vào công chúa Ida, người phải vượt qua những cấu trúc hình học kỳ quái, xoay chuyển các phần của môi trường để tạo ra những con đường dẫn đến mục tiêu. Mỗi cấp độ trong Monument Valley mang đến một thử thách mới, đòi hỏi người chơi phải tìm cách giải quyết các câu đố thông qua sự sáng tạo và tư duy không gian. Trò chơi nổi bật với thiết kế nghệ thuật độc đáo, không gian yên tĩnh, và âm nhạc nền du dương, tạo nên một trải nghiệm thư giãn và thỏa mãn.', 5.0, 2, '2025-04-06 20:10:22'),
(51, 'The Witcher 3: Wild Hunt', 3, 2, 40, 20, 'Giới thiệu: Trò chơi đưa người chơi vào vai Geralt of Rivia, một thợ săn quái vật trong một thế giới mở rộng lớn. Với cốt truyện sâu sắc, đồ họa tuyệt đẹp và hệ thống chiến đấu phong phú, The Witcher 3 đã nhận được nhiều giải thưởng Game of the Year.', 5.0, 2, '2025-04-06 20:17:54'),
(52, 'Red Dead Redemption 2', 3, 2, 40, 20, 'Giới thiệu: Trò chơi diễn ra vào cuối thế kỷ 19, người chơi vào vai Arthur Morgan, một thành viên của băng đảng Van der Linde. Với thế giới mở chi tiết, cốt truyện cảm động và gameplay đa dạng, Red Dead Redemption 2 được xem là một kiệt tác trong ngành', 5.0, 2, '2025-04-06 20:18:21'),
(53, 'Cyberpunk 2077', 3, 2, 50, 19, 'Giới thiệu: Đặt trong một tương lai dystopian, người chơi vào vai V, một mercenary tìm kiếm một cấy ghép công nghệ có thể mang lại sự sống vĩnh cửu. Mặc dù ra mắt với nhiều vấn đề kỹ thuật, game đã nhận được nhiều bản cập nhật để cải thiện trải nghiệm.', 5.0, 2, '2025-04-06 20:18:49'),
(54, 'Assassin&#039;s Creed Valhalla', 3, 2, 60, 30, 'Người chơi vào vai Eivor, một chiến binh Viking, trong hành trình chinh phục nước Anh. Với thế giới mở rộng lớn, hệ thống chiến đấu cải tiến và nhiều hoạt động phụ, Valhalla tiếp tục duy trì chất lượng của series Assassin&#039;s Creed.​', 5.0, 5, '2025-04-06 20:19:20'),
(55, 'DOOM Eternal', 3, 2, 40, 15, 'Trò chơi diễn ra trong thế giới Tamriel, nơi người chơi vào vai Dragonborn, người có khả năng sử dụng tiếng thét huyền bí để chống lại rồng và các thế lực đen tối. Với thế giới mở rộng lớn và nhiều nhiệm vụ phụ, Skyrim đã trở thành một biểu tượng trong làng game nhập vai.​', 5.0, 6, '2025-04-06 20:20:09'),
(56, 'Far Cry 5', 3, 2, 29, 18, 'Trò chơi diễn ra tại Montana, Mỹ, nơi người chơi đối đầu với một giáo phái vũ trang. Với gameplay tự do, môi trường mở rộng lớn và nhiều hoạt động phụ, Far Cry 5 mang đến trải nghiệm hành động thú vị.', 5.0, 19, '2025-04-06 20:20:43'),
(57, 'Battlefield V', 3, 2, 49, 32, 'Trò chơi lấy bối cảnh Thế chiến II, với các trận đấu quy mô lớn, môi trường phá', 5.0, 5, '2025-04-06 20:21:10'),
(58, 'YouTube Premium', 2, 2, 20, 10, 'YouTube Premium là dịch vụ trả phí của YouTube, mang đến cho người dùng trải nghiệm xem video không có quảng cáo trên toàn bộ nền tảng YouTube, bao gồm cả YouTube, YouTube Music và YouTube Kids. Một điểm nổi bật khác của YouTube Premium là khả năng tải video về xem offline, giúp người dùng tận hưởng các video yêu thích ngay cả khi không có kết nối internet. Ngoài ra, người dùng cũng có quyền truy cập vào các chương trình và video độc quyền mà chỉ có trên YouTube Premium. Nếu bạn là người thường xuyên xem video trên YouTube, YouTube Premium sẽ mang đến trải nghiệm mượt mà và thú vị hơn bao giờ hết.', 5.0, 100, '2025-04-06 20:24:18'),
(59, 'Spotify Premium', 2, 2, 10, 10, 'Spotify Premium là dịch vụ nghe nhạc trả phí từ Spotify, mang đến nhiều tiện ích vượt trội so với phiên bản miễn phí. Với Spotify Premium, bạn sẽ không phải nghe quảng cáo, có thể nghe nhạc offline và tùy chỉnh danh sách phát yêu thích mà không bị gián đoạn. Dịch vụ này còn cho phép bạn bỏ qua các bài hát không muốn nghe và có thể nghe bất kỳ bài hát nào trong thư viện khổng lồ của Spotify mà không phải chịu sự hạn chế của quảng cáo. Đặc biệt, Spotify Premium còn hỗ trợ nghe nhạc chất lượng cao, mang đến trải nghiệm âm nhạc tuyệt vời cho người dùng yêu thích âm thanh sống động.', 5.0, 20, '2025-04-06 20:24:42'),
(60, 'Netflix', 2, 2, 10, 10, 'Netflix Premium là dịch vụ truyền hình trực tuyến trả phí, cho phép người dùng xem hàng nghìn bộ phim, chương trình truyền hình, phim tài liệu và nội dung gốc độc quyền của Netflix. Với gói Premium, người dùng có thể stream trên 4 thiết bị cùng lúc với chất lượng video lên đến 4K Ultra HD và HDR. Netflix nổi bật với các sản phẩm nội dung gốc chất lượng cao như các series &quot;Stranger Things&quot;, &quot;The Witcher&quot;, và các bộ phim độc quyền. Đây là một lựa chọn tuyệt vời cho những ai đam mê xem phim và chương trình truyền hình chất lượng cao mà không bị gián đoạn quảng cáo.', 5.0, 100, '2025-04-06 20:25:07'),
(61, 'ChatGPT Plus', 2, 2, 25, 20, 'ChatGPT Plus là dịch vụ trả phí của OpenAI dành cho người dùng ChatGPT, mang lại nhiều lợi ích vượt trội so với phiên bản miễn phí. Người dùng ChatGPT Plus sẽ được truy cập vào các phiên bản mới nhất của mô hình GPT, cho phép họ tận hưởng khả năng trả lời nhanh chóng và chính xác hơn. ChatGPT Plus giúp người dùng có trải nghiệm mượt mà, đặc biệt là trong các giờ cao điểm khi nhu cầu sử dụng ChatGPT rất cao. Dịch vụ này cũng cung cấp quyền truy cập vào các tính năng cao cấp như khả năng xử lý các yêu cầu phức tạp và nhanh chóng. Đây là một lựa chọn lý tưởng cho những người muốn khai thác tối đa tiềm năng của ChatGPT trong công việc hoặc học tập.', 5.0, 100, '2025-04-06 20:25:31'),
(62, 'Amazon Prime', 2, 2, 30, 10, 'Amazon Prime là một dịch vụ đăng ký trả phí của Amazon, mang đến cho người dùng nhiều ưu đãi độc quyền. Khi đăng ký Amazon Prime, bạn sẽ nhận được ưu đãi giao hàng miễn phí cho các sản phẩm trên Amazon, quyền truy cập vào dịch vụ stream phim và chương trình TV qua Prime Video, cũng như các trò chơi và gói nội dung độc quyền qua Twitch Prime. Người dùng còn có thể truy cập vào dịch vụ nhạc miễn phí và có thể đọc sách miễn phí qua Prime Reading. Amazon Prime là dịch vụ rất hữu ích cho những ai thường xuyên mua sắm trực tuyến và yêu thích xem phim hoặc chương trình truyền hình.', 5.0, 20, '2025-04-06 20:25:51'),
(63, 'Microsoft Office 365', 2, 2, 100, 65, 'Microsoft Office 365 là một dịch vụ đăng ký trả phí giúp người dùng truy cập vào bộ công cụ văn phòng mạnh mẽ của Microsoft, bao gồm Word, Excel, PowerPoint, OneNote, và Outlook. Với Office 365, người dùng có thể làm việc trên các tài liệu trực tuyến, cộng tác với nhóm qua Microsoft Teams, và lưu trữ tệp tin trên OneDrive với dung lượng lên đến 1TB. Office 365 giúp các cá nhân và doanh nghiệp tăng cường hiệu suất công việc, đồng thời mang đến một giải pháp an toàn và tiện lợi cho việc quản lý tài liệu và dữ liệu. Đây là lựa chọn lý tưởng cho những ai cần sử dụng các công cụ văn phòng mạnh mẽ với sự linh hoạt và khả năng truy cập mọi lúc mọi nơi.', 5.0, 100, '2025-04-06 20:26:17'),
(64, 'Disney+', 2, 2, 30, 20, 'Disney+ là dịch vụ phát trực tuyến của Disney, cung cấp một kho phim và chương trình truyền hình khổng lồ từ Disney, Pixar, Marvel, Star Wars, National Geographic và 21st Century Fox. Với Disney+, người dùng có thể tận hưởng các bộ phim bom tấn như &amp;quot;The Mandalorian&amp;quot;, &amp;quot;Avengers&amp;quot;, &amp;quot;Frozen&amp;quot;, và hàng loạt bộ phim hoạt hình nổi tiếng của Disney. Dịch vụ này cho phép người dùng xem không giới hạn các nội dung yêu thích trên nhiều thiết bị, mang lại trải nghiệm giải trí tuyệt vời cho cả gia đình. Disney+ cũng hỗ trợ tải về để xem offline, giúp người dùng xem các bộ phim yêu thích mọi lúc mọi nơi.', 5.0, 100, '2025-04-06 20:26:37'),
(65, 'The Last of Us Part II', 4, 2, 180, 80, '&quot;The Last of Us Part II&quot; là một trò chơi hành động nhập vai từ Naughty Dog, tiếp nối câu chuyện của Ellie trong một thế giới hậu tận thế. Trò chơi mang đến một cốt truyện sâu sắc, đầy cảm xúc, với những tình huống khó khăn mà nhân vật chính phải đối mặt. Với đồ họa tuyệt đẹp và hệ thống chiến đấu mượt mà, người chơi sẽ tham gia vào hành trình tìm kiếm báo thù trong một thế giới đầy rẫy hiểm nguy. Trò chơi được đánh giá cao vì tính chân thực trong việc xây dựng nhân vật và các mối quan hệ, đặc biệt là các yếu tố về đạo đức và lựa chọn trong game.', 5.0, 10, '2025-04-06 20:43:02'),
(66, 'God of War (2018)', 4, 2, 20, 16, '&quot;God of War&quot; là một trong những tựa game hành động nhập vai hay nhất trong lịch sử, kể về hành trình của Kratos và con trai Atreus trong một thế giới thần thoại Bắc Âu. Trò chơi nổi bật với hệ thống chiến đấu độc đáo, đồ họa tuyệt đẹp và một cốt truyện đầy sâu sắc về tình cha con và sự trưởng thành. Người chơi sẽ chiến đấu với các vị thần, quái vật và khám phá những bí mật trong thế giới Norse, với sự kết hợp hoàn hảo giữa hành động, giải đố và khám phá.', 5.0, 20, '2025-04-06 20:45:52'),
(67, 'Spider-Man: Miles Morales', 4, 2, 40, 40, '&quot;Spider-Man: Miles Morales&quot; là phần tiếp theo của tựa game &quot;Spider-Man&quot; từ Insomniac Games, đưa người chơi vào vai Miles Morales, một thanh niên mới trở thành Spider-Man. Trò chơi cung cấp một thế giới mở đầy tự do, nơi bạn có thể leo tường, chiến đấu và khám phá thành phố New York dưới góc nhìn của Miles. Với đồ họa ấn tượng và hệ thống chiến đấu mượt mà, người chơi sẽ trải nghiệm hành trình của một siêu anh hùng trẻ tuổi đầy khao khát và thử thách.', 5.0, 20, '2025-04-06 20:46:19'),
(68, 'Halo Infinite', 4, 2, 80, 60, '&quot;Halo Infinite&quot; là tựa game bắn súng góc nhìn thứ nhất mới nhất trong series nổi tiếng của Microsoft. Game tiếp tục câu chuyện của Master Chief trong cuộc chiến chống lại Covenant và các thế lực ngoài hành tinh khác. Trò chơi có một thế giới mở rộng lớn, chế độ chơi multiplayer miễn phí và chế độ chiến dịch hấp dẫn. Với đồ họa tuyệt đẹp và gameplay chiến đấu mượt mà, &quot;Halo Infinite&quot; hứa hẹn mang đến cho người chơi một trải nghiệm hành động hấp dẫn và đầy thử thách.', 5.0, 20, '2025-04-06 20:46:49'),
(69, 'Super Mario Odyssey', 4, 2, 80, 60, '&quot;Super Mario Odyssey&quot; là tựa game phiêu lưu hành động nổi tiếng của Nintendo, nơi người chơi sẽ vào vai Mario trong cuộc hành trình tìm kiếm công chúa Peach và cứu cô khỏi bàn tay của Bowser. Trò chơi mang đến một thế giới mở đầy màu sắc và sáng tạo, với những môi trường phong phú và các thế giới kỳ diệu để khám phá. Sử dụng chiếc mũ đặc biệt, Mario có thể chiếm hữu các sinh vật và vật thể khác để giải quyết các câu đố, mang đến một trải nghiệm vui nhộn và sáng tạo.', 5.0, 100, '2025-04-06 20:47:11'),
(70, 'The Legend of Zelda', 4, 2, 60, 58, '&quot;The Legend of Zelda: Breath of the Wild&quot; là một trong những trò chơi hành động phiêu lưu hay nhất từng được phát hành. Với thế giới mở rộng lớn, bạn sẽ vào vai Link trong cuộc hành trình cứu vương quốc Hyrule khỏi sự tàn phá của Calamity Ganon. Trò chơi mang đến gameplay tự do, nơi người chơi có thể khám phá, chiến đấu, giải đố và thu thập tài nguyên để nâng cấp sức mạnh. Breath of the Wild đã nhận được nhiều giải thưởng và được coi là một trong những tựa game vĩ đại nhất mọi thời đại.', 5.0, 100, '2025-04-06 20:47:50');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image_url`) VALUES
(202, 43, '7c34fdc9afad8358cb98270c012fbc74.jpg'),
(203, 44, '15f6094a5c16af98e59f5ca5b145da2d.jpg'),
(204, 45, 'e337be4c01f5ea4d24476bd5c45fe23e.jpg'),
(205, 46, '2f0b96e00214592a14cb0bbb802b40b3.jpg'),
(206, 47, '82d95025e43e266d2ac8f70b2480c647.jpg'),
(207, 48, '21f29868af9f073b26821cd5086a5e5c.jpg'),
(208, 49, '0067c5eccff582d7d8e249f139462ac3.jpg'),
(209, 50, 'a3f8bd908fbbc5fdb31c1ee56ad9b6a2.jpg'),
(210, 51, '4703b9b448f0287cae690ed2aefbf714.jpg'),
(211, 52, 'ab650991f3e3464524e3be78a14c17c6.jpg'),
(212, 53, '2a9d164051f8f2a5f131d6292fc1815a.jpg'),
(213, 54, '8a4b1fe3426c5f0e516c0045db31021f.jpg'),
(214, 55, '67048f9611af90fce375cbc2d44bcec1.jpg'),
(215, 56, '421462e395a18c068b6a52330604c232.jpg'),
(216, 57, 'b20a7c74e1cdea5b8c3715f51e9061d8.jpg'),
(217, 58, '20ad56d1cec5bd58dbe3d78ce03f7710.png'),
(218, 59, '5658a625fb1058449ba02e6eb8be5d8e.jpg'),
(219, 60, 'b5ebd7e58644321b641ab109bbadff41.png'),
(220, 61, '3e639d5690a876ea24ec6d7c8c26054b.jpg'),
(221, 62, '558925cce725b17e57b731853c927801.png'),
(222, 63, 'c06d45f119970fb3b893cd707e93c1c9.png'),
(223, 64, '2e967e65d8d8a5ebe30c386fd9fc3f25.jpg'),
(224, 65, '48f8f0b296e53489c02a823a6c651958.jpg'),
(225, 66, 'ce534bb2b8e940ad2a5fa1e313bc248b.jpg'),
(226, 67, 'b9cabae79b69995ec6488a97c849bc24.jpg'),
(227, 68, '75df61992cc0fa5ec52ad8ef07e1a649.jpg'),
(228, 69, 'bf3c5a4cbf73354be2b8f7f1ae3e6c60.jpg'),
(229, 70, '4418ee46ab48f4dad6b07fa14dfc851b.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `wishes`
--

CREATE TABLE `wishes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `seller_id` (`seller_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `wishes`
--
ALTER TABLE `wishes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=230;

--
-- AUTO_INCREMENT for table `wishes`
--
ALTER TABLE `wishes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`seller_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wishes`
--
ALTER TABLE `wishes`
  ADD CONSTRAINT `wishes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishes_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
