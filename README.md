# Nike Shoes E-commerce Website

![Nike Logo](assets/images/nike-logo.png)

## Giới thiệu

Đây là một website thương mại điện tử chuyên về giày Nike, được xây dựng bằng PHP và MySQL. Website cung cấp đầy đủ các chức năng của một cửa hàng trực tuyến hiện đại, với giao diện đẹp mắt và trải nghiệm người dùng thân thiện.

## Các tính năng đã triển khai

### Người dùng
- **Đăng ký và đăng nhập**: Hệ thống tài khoản với xác thực an toàn
- **Trang chủ**: Hiển thị slider quảng cáo, sản phẩm nổi bật, sản phẩm đang giảm giá
- **Danh mục sản phẩm**: Hiển thị sản phẩm theo danh mục (Running, Basketball, Training, Casual)
- **Tìm kiếm**: Tìm kiếm sản phẩm theo tên, mô tả và danh mục
- **Lọc sản phẩm**: Lọc theo danh mục, giá, sắp xếp theo nhiều tiêu chí
- **Chi tiết sản phẩm**: Hiển thị thông tin chi tiết của sản phẩm với hình ảnh, kích cỡ, mô tả
- **Gallery sản phẩm**: Xem nhiều hình ảnh của sản phẩm qua thumbnails
- **Size Guide**: Hướng dẫn chọn kích cỡ giày phù hợp
- **Giỏ hàng**: Thêm, xóa, cập nhật số lượng sản phẩm trong giỏ hàng
- **Wishlist**: Lưu sản phẩm yêu thích để xem sau
- **Thanh toán**: Quy trình thanh toán đơn giản và rõ ràng
- **Theo dõi đơn hàng**: Xem trạng thái và lịch sử đơn hàng
- **Đánh giá sản phẩm**: Cho phép người dùng đánh giá và bình luận về sản phẩm
- **Newsletter**: Đăng ký nhận thông tin khuyến mãi qua email

### Admin
- **Quản lý sản phẩm**: Thêm, xóa, sửa sản phẩm và thông tin liên quan
- **Quản lý danh mục**: Thêm, xóa, sửa danh mục sản phẩm
- **Quản lý đơn hàng**: Xem và cập nhật trạng thái đơn hàng
- **Quản lý người dùng**: Quản lý thông tin và quyền hạn của người dùng
- **Thống kê**: Báo cáo về doanh thu, sản phẩm bán chạy, khách hàng thân thiết

### UI/UX
- **Responsive design**: Hiển thị tốt trên tất cả các thiết bị (desktop, tablet, mobile)
- **Hero Slider**: Trình chiếu hình ảnh quảng cáo tự động
- **Quick View**: Xem nhanh thông tin sản phẩm mà không cần chuyển trang
- **Back to Top**: Nút quay lại đầu trang khi cuộn xuống
- **Thông báo**: Hiển thị thông báo khi thêm sản phẩm vào giỏ hàng hoặc wishlist
- **Testimonials**: Phần hiển thị đánh giá của khách hàng
- **Features Banner**: Hiển thị các dịch vụ và lợi ích khi mua hàng

## Công nghệ sử dụng

### Front-end
- **HTML5**: Cấu trúc và nội dung trang web
- **CSS3**: Tạo kiểu và hiệu ứng giao diện
- **Bootstrap 5**: Framework CSS cho responsive design
- **JavaScript/jQuery**: Xử lý tương tác người dùng, hiệu ứng, và AJAX
- **Font Awesome**: Thư viện biểu tượng
- **Local Storage**: Lưu trữ dữ liệu của wishlist và trạng thái người dùng

### Back-end
- **PHP**: Ngôn ngữ lập trình chính cho phía máy chủ
- **MySQL**: Hệ quản trị cơ sở dữ liệu
- **XAMPP**: Môi trường phát triển tích hợp cho PHP và MySQL
- **Sessions**: Quản lý phiên đăng nhập và giỏ hàng

### Bảo mật
- **Password Hashing**: Mã hóa mật khẩu người dùng
- **Input Sanitization**: Xử lý dữ liệu đầu vào để ngăn chặn SQL Injection
- **CSRF Protection**: Bảo vệ chống lại tấn công giả mạo yêu cầu

## Cài đặt & Sử dụng

1. **Yêu cầu hệ thống**:
   - PHP 7.4 hoặc cao hơn
   - MySQL 5.7 hoặc cao hơn
   - Web server (Apache, Nginx)

2. **Cài đặt**:
   ```
   git clone https://github.com/yourname/Webgiay.git
   ```

3. **Cấu hình cơ sở dữ liệu**:
   - Tạo cơ sở dữ liệu mới trong MySQL
   - Import file database.sql vào cơ sở dữ liệu đã tạo
   - Cập nhật file includes/config.php với thông tin kết nối cơ sở dữ liệu

4. **Chạy ứng dụng**:
   - Truy cập: http://localhost/Webgiay

## Tài khoản demo
- **Admin**: admin@example.com / password: admin123
- **User**: user@example.com / password: user123

## Cấu trúc thư mục
- **admin**: Chứa các file quản trị website
- **assets**: Chứa CSS, JavaScript, và hình ảnh
- **includes**: Chứa các file PHP được include
- **uploads**: Chứa hình ảnh sản phẩm uploaded

## Kế hoạch phát triển tương lai
- Tích hợp thanh toán trực tuyến (PayPal, Stripe)
- Thêm hệ thống chat trực tuyến hỗ trợ khách hàng
- Tối ưu hóa SEO
- Thêm tính năng so sánh sản phẩm
- Xây dựng ứng dụng di động

## Cấp phép
© 2025 Nike Shoe Store. Mọi quyền được bảo lưu.
