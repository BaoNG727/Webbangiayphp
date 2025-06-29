# Nike Shoes E-commerce Website

![Nike Logo](assets/images/nike-logo.png)

## 🚀 Giới thiệu

Đây là một website thương mại điện tử chuyên về giày Nike, được xây dựng bằng PHP thuần và MySQL. Website cung cấp đầy đủ các chức năng của một cửa hàng trực tuyến hiện đại, với giao diện đẹp mắt và trải nghiệm người dùng thân thiện.

### 🎯 Mục tiêu dự án
- Xây dựng một nền tảng bán giày Nike hoàn chỉnh
- Cung cấp trải nghiệm mua sắm trực tuyến tối ưu
- Quản lý sản phẩm và đơn hàng hiệu quả
- Giao diện responsive tương thích mọi thiết bị

## ✨ Các tính năng chi tiết

### 👤 Chức năng Người dùng (Customer)
- **🔐 Xác thực & Tài khoản**
  - Đăng ký tài khoản mới với email verification
  - Đăng nhập/đăng xuất an toàn
  - Quản lý thông tin cá nhân
  - Đổi mật khẩu và cập nhật profile

- **🏠 Trang chủ & Điều hướng**
  - Hero slider với hình ảnh quảng cáo tự động
  - Hiển thị sản phẩm nổi bật và mới nhất
  - Sản phẩm đang giảm giá và khuyến mãi
  - Menu điều hướng responsive
  - Breadcrumb navigation

- **👟 Danh mục & Sản phẩm**
  - Browse sản phẩm theo danh mục: Running, Basketball, Training, Casual
  - Tìm kiếm thông minh theo tên, mô tả, thương hiệu
  - Lọc sản phẩm theo: giá, danh mục, thương hiệu, kích cỡ
  - Sắp xếp theo: giá tăng/giảm dần, tên A-Z, mới nhất
  - Pagination với lazy loading

- **🔍 Chi tiết sản phẩm**
  - Gallery hình ảnh với zoom và thumbnails
  - Thông tin chi tiết: mô tả, thông số kỹ thuật
  - Chọn kích cỡ và màu sắc
  - Size guide tương tác
  - Đánh giá và bình luận từ khách hàng
  - Sản phẩm liên quan và gợi ý

- **🛒 Giỏ hàng & Mua sắm**
  - Thêm sản phẩm vào giỏ hàng với AJAX
  - Quick add to cart từ danh sách sản phẩm
  - Cập nhật số lượng và xóa sản phẩm
  - Lưu giỏ hàng trong session
  - Tính toán tự động tổng tiền và thuế

- **💝 Wishlist (Danh sách yêu thích)**
  - Lưu sản phẩm yêu thích với Local Storage
  - Quick add/remove từ mọi trang
  - Quản lý và xem lại wishlist
  - Chuyển từ wishlist sang giỏ hàng

- **💳 Thanh toán & Đơn hàng**
  - Quy trình checkout đơn giản 3 bước
  - Nhập thông tin giao hàng và thanh toán
  - Áp dụng mã giảm giá và khuyến mãi
  - Xác nhận đơn hàng qua email
  - Theo dõi trạng thái đơn hàng realtime

- **📊 Quản lý đơn hàng cá nhân**
  - Xem lịch sử đơn hàng
  - Chi tiết từng đơn hàng
  - Tracking number và trạng thái giao hàng
  - Hủy đơn hàng (nếu chưa xử lý)
  - Download invoice/receipt

### 👨‍💼 Chức năng Admin
- **📈 Dashboard & Thống kê**
  - Tổng quan doanh thu theo ngày/tháng/năm
  - Biểu đồ sản phẩm bán chạy nhất
  - Thống kê người dùng mới
  - Top khách hàng thân thiết
  - Báo cáo inventory và stock

- **👟 Quản lý sản phẩm**
  - CRUD sản phẩm với upload hình ảnh
  - Quản lý nhiều hình ảnh cho một sản phẩm
  - Bulk operations (xóa nhiều, cập nhật giá)
  - Import/Export sản phẩm từ CSV/Excel
  - Quản lý kho hàng và stock
  - SEO-friendly URLs

- **📂 Quản lý danh mục**
  - Tạo và quản lý categories
  - Hierarchical categories (danh mục con)
  - Upload banner cho từng danh mục
  - Sắp xếp thứ tự hiển thị

- **📋 Quản lý đơn hàng**
  - Xem tất cả đơn hàng với filter
  - Cập nhật trạng thái đơn hàng
  - In hóa đơn và shipping label
  - Quản lý hoàn trả và refund
  - Gửi email thông báo cho khách hàng

- **👥 Quản lý người dùng**
  - Danh sách tất cả users
  - Phân quyền admin/customer
  - Block/unblock users
  - Xem lịch sử mua hàng của customer
  - Quản lý thông tin cá nhân users

- **🎫 Quản lý khuyến mãi**
  - Tạo mã giảm giá và coupons
  - Thiết lập điều kiện và thời hạn
  - Theo dõi usage và hiệu quả
  - Flash sales và time-limited offers

### 🎨 Tính năng UI/UX
- **📱 Responsive Design**
  - Mobile-first approach
  - Tối ưu cho tất cả screen sizes
  - Touch-friendly interactions
  - Progressive Web App ready

- **⚡ Performance**
  - Image lazy loading
  - CSS/JS minification
  - Browser caching
  - Optimized database queries

- **🎯 User Experience**
  - Quick View modal cho sản phẩm
  - Infinite scroll pagination
  - Real-time search suggestions
  - Breadcrumb navigation
  - Back to top button smooth scroll

- **🔔 Notifications**
  - Toast notifications cho actions
  - Real-time cart updates
  - Email notifications cho orders
  - Admin alerts cho low stock

## 🛠️ Công nghệ sử dụng

### 🎨 Front-end Technologies
- **HTML5**: 
  - Semantic markup cho SEO tốt hơn
  - Web accessibility standards (WCAG)
  - Structured data cho rich snippets
  
- **CSS3 & Styling**:
  - **Bootstrap 5.3.0**: Framework responsive chính
  - **Custom CSS**: Tùy chỉnh theme Nike brand colors
  - **CSS Grid & Flexbox**: Layout hiện đại
  - **CSS Animations**: Hover effects, transitions
  - **Media Queries**: Responsive breakpoints
  - **SCSS/SASS**: Preprocessing (optional)

- **JavaScript & Libraries**:
  - **Vanilla JavaScript**: Core functionality
  - **jQuery 3.6.0**: DOM manipulation và AJAX
  - **Bootstrap JS**: Interactive components
  - **Local Storage API**: Wishlist persistence
  - **Fetch API**: Modern HTTP requests
  - **IntersectionObserver**: Lazy loading images

- **Icons & Fonts**:
  - **Font Awesome 6.4.0**: Icon library
  - **Google Fonts**: Typography (Roboto, Open Sans)
  - **Custom SVG icons**: Brand-specific icons

### ⚙️ Back-end Technologies
- **PHP 8.0+**:
  - **Object-Oriented Programming**: Clean code structure
  - **MVC Pattern**: Model-View-Controller architecture
  - **Namespaces**: Organized code structure
  - **Composer**: Dependency management (future)
  - **Error Handling**: Try-catch blocks
  - **Type Declarations**: Strong typing

- **Database Management**:
  - **MySQL 8.0**: Primary database
  - **PDO (PHP Data Objects)**: Database abstraction
  - **Prepared Statements**: SQL injection protection
  - **Foreign Key Constraints**: Data integrity
  - **Indexing**: Query optimization
  - **Views**: Complex query simplification

- **Server Environment**:
  - **Apache 2.4**: Web server
  - **XAMPP**: Development environment
  - **mod_rewrite**: SEO-friendly URLs
  - **.htaccess**: Server configuration

### 🔒 Security & Performance
- **Security Measures**:
  - **Password Hashing**: PHP `password_hash()` with bcrypt
  - **Input Sanitization**: `htmlspecialchars()`, `filter_var()`
  - **SQL Injection Prevention**: PDO prepared statements
  - **XSS Protection**: Output escaping
  - **CSRF Tokens**: Form security (implementation ready)
  - **Session Security**: Secure session management

- **Performance Optimization**:
  - **Database Optimization**: Proper indexing
  - **Image Optimization**: WebP format support
  - **Caching**: Browser caching headers
  - **Minification**: CSS/JS compression
  - **Lazy Loading**: Images and content
  - **CDN Ready**: External resource loading

### 📁 Architecture & Patterns
- **MVC Architecture**:
  ```
  app/
  ├── Controllers/     # Business logic
  ├── Models/         # Data layer
  ├── Views/          # Presentation layer
  └── Core/           # Framework components
  ```

- **Design Patterns**:
  - **Singleton Pattern**: Database connection
  - **Factory Pattern**: Object creation
  - **Repository Pattern**: Data access
  - **Observer Pattern**: Event handling

- **File Structure**:
  ```
  Webgiay/
  ├── admin/              # Admin panel
  ├── app/               # Application logic
  │   ├── Controllers/   # Request handlers
  │   ├── Models/        # Database models
  │   ├── Views/         # Templates
  │   └── Core/          # Core classes
  ├── assets/            # Static assets
  │   ├── css/          # Stylesheets
  │   ├── js/           # JavaScript files
  │   └── images/       # UI images
  ├── includes/          # Configuration files
  ├── uploads/           # User uploads
  └── database.sql       # Database schema
  ```

## 📊 Database Schema

### 🗃️ Cấu trúc Database
Dự án sử dụng 8 bảng chính được thiết kế tối ưu:

```sql
nike_store/
├── users           # Thông tin người dùng
├── categories      # Danh mục sản phẩm  
├── shoes          # Sản phẩm giày Nike
├── products       # Thông tin sản phẩm chung
├── cart           # Giỏ hàng
├── orders         # Đơn hàng
├── order_items    # Chi tiết đơn hàng
└── reviews        # Đánh giá sản phẩm
```

### 🔗 Relationships
- **users** ↔ **cart** (1:n)
- **users** ↔ **orders** (1:n)  
- **users** ↔ **reviews** (1:n)
- **categories** ↔ **products** (1:n)
- **products** ↔ **shoes** (1:1)
- **products** ↔ **cart** (1:n)
- **orders** ↔ **order_items** (1:n)
- **products** ↔ **order_items** (1:n)
- **products** ↔ **reviews** (1:n)

## 🚀 Cài đặt & Sử dụng

### ⚙️ Yêu cầu hệ thống
- **PHP**: 8.0+ (khuyến nghị 8.1+)
- **MySQL**: 8.0+ hoặc MariaDB 10.4+
- **Apache**: 2.4+ với mod_rewrite enabled
- **Extensions**: mysqli, pdo_mysql, gd, curl
- **Memory**: Tối thiểu 512MB RAM
- **Disk Space**: 500MB trống

### 📥 Hướng dẫn cài đặt chi tiết

#### Bước 1: Clone project
```bash
git clone https://github.com/yourname/Webgiay.git
cd Webgiay
```

#### Bước 2: Cấu hình Web Server
**Với XAMPP:**
1. Copy thư mục `Webgiay` vào `C:\xampp\htdocs\`
2. Start Apache và MySQL trong XAMPP Control Panel

**Với WAMP:**
1. Copy thư mục vào `C:\wamp64\www\`
2. Start All Services

#### Bước 3: Thiết lập Database
```sql
-- Tạo database
CREATE DATABASE nike_store CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Import schema và data
mysql -u root -p nike_store < database.sql
```

#### Bước 4: Cấu hình kết nối
Cập nhật file `includes/config.php`:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'nike_store');
define('DB_USER', 'root');
define('DB_PASS', ''); // XAMPP mặc định không có password
```

Cập nhật file `app/Core/Config.php`:
```php
private const DB_NAME = 'nike_store';
```

#### Bước 5: Phân quyền thư mục
```bash
# Trên Linux/macOS
chmod 755 uploads/
chmod 644 uploads/*

# Trên Windows với XAMPP
# Đảm bảo thư mục uploads có quyền ghi
```

#### Bước 6: Khởi chạy
1. Truy cập: `http://localhost/Webgiay`
2. Hoặc với virtual host: `http://nikestore.local`

### 🔧 Cấu hình nâng cao

#### Virtual Host (Apache)
Thêm vào `httpd-vhosts.conf`:
```apache
<VirtualHost *:80>
    DocumentRoot "C:/xampp/htdocs/Webgiay"
    ServerName nikestore.local
    <Directory "C:/xampp/htdocs/Webgiay">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

#### Tối ưu hóa PHP
Cập nhật `php.ini`:
```ini
upload_max_filesize = 10M
post_max_size = 10M
max_execution_time = 300
memory_limit = 512M
```

## 👥 Tài khoản Demo

### 🔑 Admin Access
- **Email**: `admin@nike.com`
- **Password**: `admin123`
- **Quyền**: Full access to admin panel

### 👤 Customer Account  
- **Email**: `customer@nike.com`
- **Password**: `customer123`
- **Quyền**: Shopping and order management

### 🛒 Test Data
Database đã được populate với:
- **8 sản phẩm Nike** với hình ảnh và mô tả chi tiết
- **4 danh mục chính**: Running, Basketball, Training, Casual
- **Sample reviews** cho các sản phẩm
- **Test orders** để demo admin functionality

## 🧪 Testing & Development

### 🔍 Debug Mode
Enable debug trong `includes/config.php`:
```php
define('DEBUG_MODE', true);
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

### 📝 Logging
Logs được lưu trong `logs/` directory:
- `error.log`: PHP errors
- `access.log`: Page access logs
- `sql.log`: Database queries (debug mode)

### 🧪 Unit Testing
```bash
# Chạy tests (future implementation)
composer test

# Check code quality
composer cs-check
```

## 📁 Cấu trúc Project chi tiết

```
Webgiay/
├── 📂 admin/                    # Admin Panel
│   ├── dashboard.php            # Trang tổng quan admin
│   ├── products.php             # Quản lý sản phẩm
│   ├── orders.php               # Quản lý đơn hàng
│   ├── users.php                # Quản lý người dùng
│   ├── categories.php           # Quản lý danh mục
│   └── settings.php             # Cài đặt hệ thống
│
├── 📂 app/                      # Application Logic (MVC)
│   ├── 📂 Controllers/          # Controllers Layer
│   │   ├── HomeController.php   # Trang chủ logic
│   │   ├── ProductController.php # Sản phẩm logic
│   │   ├── CartController.php   # Giỏ hàng logic
│   │   └── UserController.php   # User logic
│   │
│   ├── 📂 Models/               # Models Layer
│   │   ├── User.php             # User model
│   │   ├── Product.php          # Product model
│   │   ├── Cart.php             # Cart model
│   │   └── Order.php            # Order model
│   │
│   ├── 📂 Views/                # Views Layer
│   │   ├── 📂 layouts/          # Layout templates
│   │   ├── 📂 products/         # Product views
│   │   ├── 📂 user/             # User views
│   │   └── 📂 cart/             # Cart views
│   │
│   └── 📂 Core/                 # Core Framework
│       ├── Database.php         # Database connection
│       ├── Router.php           # URL routing
│       ├── Controller.php       # Base controller
│       └── Config.php           # Configuration
│
├── 📂 assets/                   # Static Assets
│   ├── 📂 css/                  # Stylesheets
│   │   ├── bootstrap.min.css    # Bootstrap framework
│   │   ├── style.css            # Custom styles
│   │   ├── admin.css            # Admin styles
│   │   └── responsive.css       # Mobile responsive
│   │
│   ├── 📂 js/                   # JavaScript Files
│   │   ├── bootstrap.min.js     # Bootstrap JS
│   │   ├── jquery.min.js        # jQuery library
│   │   ├── cart.js              # Cart functionality
│   │   ├── product.js           # Product interactions
│   │   └── admin.js             # Admin panel JS
│   │
│   ├── 📂 images/               # UI Images
│   │   ├── logo.png             # Brand logo
│   │   ├── hero-bg.jpg          # Hero background
│   │   ├── icons/               # UI icons
│   │   └── banners/             # Promotional banners
│   │
│   └── 📂 fonts/                # Custom Fonts
│       └── nike-fonts/          # Nike brand fonts
│
├── 📂 includes/                 # Configuration & Utilities
│   ├── config.php               # Database config
│   ├── functions.php            # Helper functions
│   ├── auth.php                 # Authentication logic
│   └── header.php               # Common header
│   └── footer.php               # Common footer
│
├── 📂 uploads/                  # User Uploads
│   ├── 📂 products/             # Product images
│   ├── 📂 avatars/              # User avatars
│   └── 📂 temp/                 # Temporary files
│
├── 📂 logs/                     # Application Logs
│   ├── error.log                # Error logs
│   ├── access.log               # Access logs
│   └── sql.log                  # Database queries
│
├── 📂 docs/                     # Documentation
│   ├── api.md                   # API documentation
│   ├── setup.md                 # Setup guide
│   └── deployment.md            # Deployment guide
│
├── 📄 index.php                 # Entry point
├── 📄 login.php                 # Login page
├── 📄 register.php              # Registration
├── 📄 product.php               # Product details
├── 📄 cart.php                  # Shopping cart
├── 📄 checkout.php              # Checkout process
├── 📄 profile.php               # User profile
├── 📄 search.php                # Search results
├── 📄 contact.php               # Contact page
├── 📄 database.sql              # Database schema
├── 📄 .htaccess                 # Apache configuration
├── 📄 composer.json             # Dependencies (future)
└── 📄 README.md                 # Project documentation
```

### 📋 File Descriptions

#### 🏠 Core Pages
- **index.php**: Trang chủ với hero slider và sản phẩm nổi bật
- **login.php**: Form đăng nhập với validation
- **register.php**: Đăng ký tài khoản mới
- **product.php**: Chi tiết sản phẩm với gallery và reviews
- **cart.php**: Giỏ hàng với tính năng cập nhật
- **checkout.php**: Quy trình thanh toán 3 bước

#### ⚙️ Configuration Files
- **config.php**: Cấu hình database và constants
- **functions.php**: Helper functions tái sử dụng
- **auth.php**: Xử lý authentication và sessions
- **.htaccess**: URL rewriting và security headers

#### 🎨 Asset Organization
- **CSS**: Organized by functionality (layout, components, responsive)
- **JavaScript**: Modular approach với separate files
- **Images**: Structured folders cho different types
- **Uploads**: Dynamic content với proper permissions

## 🔄 API Endpoints (Future Implementation)

### 🛒 Cart API
```php
GET    /api/cart                 # Lấy giỏ hàng hiện tại
POST   /api/cart/add             # Thêm sản phẩm vào giỏ
PUT    /api/cart/update          # Cập nhật số lượng
DELETE /api/cart/remove          # Xóa sản phẩm
```

### 👟 Products API
```php
GET    /api/products             # Danh sách sản phẩm
GET    /api/products/{id}        # Chi tiết sản phẩm
GET    /api/products/search      # Tìm kiếm sản phẩm
GET    /api/products/category/{id} # Sản phẩm theo danh mục
```

### 📦 Orders API
```php
GET    /api/orders               # Lịch sử đơn hàng
GET    /api/orders/{id}          # Chi tiết đơn hàng
POST   /api/orders/create        # Tạo đơn hàng mới
PUT    /api/orders/{id}/status   # Cập nhật trạng thái
```

## 🚀 Kế hoạch phát triển tương lai

### 🎯 Phase 1: Performance & Security
- [ ] **Caching System**: Redis/Memcached implementation
- [ ] **CDN Integration**: CloudFlare hoặc AWS CloudFront
- [ ] **HTTPS/SSL**: Security certificate setup
- [ ] **Rate Limiting**: API protection
- [ ] **CSRF Protection**: Complete implementation
- [ ] **Input Validation**: Enhanced security measures

### 🎯 Phase 2: Advanced Features
- [ ] **Payment Gateway**: 
  - PayPal integration
  - Stripe payment processing
  - VNPay cho khách hàng Việt Nam
  - Installment payment options
- [ ] **Inventory Management**:
  - Real-time stock updates
  - Low stock alerts
  - Automatic reorder points
- [ ] **Advanced Search**:
  - Elasticsearch integration
  - AI-powered recommendations
  - Voice search capability

### 🎯 Phase 3: User Experience
- [ ] **Progressive Web App (PWA)**:
  - Offline functionality
  - Push notifications
  - App-like experience
- [ ] **Customer Support**:
  - Live chat integration
  - Ticket system
  - FAQ with search
  - Video call support
- [ ] **Social Features**:
  - Product reviews with photos
  - User-generated content
  - Social media sharing
  - Influencer partnerships

### 🎯 Phase 4: Analytics & Intelligence
- [ ] **Business Intelligence**:
  - Advanced analytics dashboard
  - Sales forecasting
  - Customer behavior analysis
  - A/B testing framework
- [ ] **Machine Learning**:
  - Personalized recommendations
  - Price optimization
  - Fraud detection
  - Demand prediction

### 🎯 Phase 5: Mobile & International
- [ ] **Mobile Application**:
  - React Native app
  - Push notifications
  - Mobile-specific features
  - App Store deployment
- [ ] **Internationalization**:
  - Multi-language support
  - Currency conversion
  - Local payment methods
  - Regional shipping

## 🛡️ Security Measures

### 🔒 Current Implementation
- ✅ **Password Hashing**: bcrypt with salt
- ✅ **SQL Injection Protection**: PDO prepared statements  
- ✅ **XSS Prevention**: Input sanitization
- ✅ **Session Security**: Secure session handling
- ✅ **File Upload Security**: Type and size validation

### 🔒 Planned Enhancements
- [ ] **Two-Factor Authentication (2FA)**
- [ ] **OAuth Integration** (Google, Facebook, Apple)
- [ ] **API Rate Limiting**
- [ ] **CSRF Token Implementation**
- [ ] **Content Security Policy (CSP)**
- [ ] **Security Headers** (HSTS, X-Frame-Options)

## 📊 Performance Optimization

### ⚡ Current Optimizations
- ✅ **Image Lazy Loading**: Improved page load times
- ✅ **CSS/JS Minification**: Reduced file sizes
- ✅ **Database Indexing**: Optimized queries
- ✅ **Browser Caching**: Static asset caching

### ⚡ Planned Improvements
- [ ] **Server-Side Caching**: Redis implementation
- [ ] **Database Query Optimization**: Advanced indexing
- [ ] **Image Optimization**: WebP format, compression
- [ ] **Code Splitting**: JavaScript optimization
- [ ] **CDN Implementation**: Global content delivery

## 🧪 Testing Strategy

### 🔍 Testing Levels
- [ ] **Unit Testing**: PHPUnit implementation
- [ ] **Integration Testing**: API endpoint testing
- [ ] **Functional Testing**: User workflow testing
- [ ] **Performance Testing**: Load testing with tools
- [ ] **Security Testing**: Vulnerability assessment

### 🔍 Testing Tools
- [ ] **PHPUnit**: Unit testing framework
- [ ] **Selenium**: Browser automation
- [ ] **PostMan**: API testing
- [ ] **JMeter**: Performance testing
- [ ] **OWASP ZAP**: Security testing

## 📈 Monitoring & Maintenance

### 📊 Monitoring Tools
- [ ] **Application Monitoring**: Error tracking
- [ ] **Performance Monitoring**: Response time tracking
- [ ] **Uptime Monitoring**: Server availability
- [ ] **Security Monitoring**: Intrusion detection

### 🔧 Maintenance Tasks
- [ ] **Regular Backups**: Automated database backups
- [ ] **Security Updates**: Framework và dependency updates
- [ ] **Performance Reviews**: Monthly optimization reviews
- [ ] **Log Analysis**: Regular log review và cleanup

## 📞 Liên hệ & Hỗ trợ

### 👨‍💻 Developer Contact
- **Email**: developer@nikestore.com
- **GitHub**: [@nikestore-dev](https://github.com/nikestore-dev)
- **LinkedIn**: [Nike Store Developer](https://linkedin.com/in/nikestore-dev)

### 🆘 Support Channels
- **Technical Issues**: [GitHub Issues](https://github.com/yourname/Webgiay/issues)
- **Feature Requests**: [Feature Request Form](https://forms.gle/nikestore-features)
- **Documentation**: [Wiki Pages](https://github.com/yourname/Webgiay/wiki)
- **Community**: [Discord Channel](https://discord.gg/nikestore)

### 📚 Resources
- **API Documentation**: [docs/api.md](docs/api.md)
- **Setup Guide**: [docs/setup.md](docs/setup.md)
- **Deployment Guide**: [docs/deployment.md](docs/deployment.md)
- **Contributing Guide**: [CONTRIBUTING.md](CONTRIBUTING.md)

## 🤝 Contributing

Chúng tôi rất hoan nghênh mọi đóng góp cho dự án! 

### 📋 How to Contribute
1. **Fork** the repository
2. **Create** a feature branch (`git checkout -b feature/AmazingFeature`)
3. **Commit** your changes (`git commit -m 'Add some AmazingFeature'`)
4. **Push** to the branch (`git push origin feature/AmazingFeature`)
5. **Open** a Pull Request

### 🎯 Areas for Contribution
- 🐛 **Bug Fixes**: Help us identify và fix issues
- ✨ **New Features**: Implement new functionality
- 📖 **Documentation**: Improve documentation
- 🎨 **UI/UX**: Enhance user interface
- ⚡ **Performance**: Optimize code performance
- 🧪 **Testing**: Add test coverage
- 🌐 **Translation**: Add multi-language support

### 📝 Coding Standards
- Follow **PSR-12** coding standards for PHP
- Use **meaningful variable names** và comments
- Write **clean, readable code**
- Include **proper error handling**
- Add **unit tests** for new features

## 📜 Changelog

### Version 2.0.0 (2025-01-20)
#### 🆕 Added
- ✅ Complete database restructure với 8 core tables
- ✅ Enhanced README with comprehensive documentation
- ✅ Sample data với Vietnamese product descriptions
- ✅ Admin và customer demo accounts
- ✅ Improved MVC architecture documentation

#### 🔧 Changed
- ✅ Database name from `nike_shoe_store` to `nike_store`
- ✅ Simplified database schema removing unnecessary complexity
- ✅ Updated configuration files for consistency

#### 🐛 Fixed
- ✅ Database connection issues
- ✅ Configuration file inconsistencies
- ✅ Documentation outdated information

### Version 1.5.0 (Previous)
#### 🆕 Added
- Basic e-commerce functionality
- Admin panel for product management
- User authentication system
- Shopping cart implementation

## 🔗 Related Projects

### 🛠️ Tools & Extensions
- **Nike Store API**: RESTful API wrapper (planned)
- **Nike Store Mobile**: React Native app (planned)
- **Nike Store Analytics**: Business intelligence dashboard (planned)

### 🎨 Themes & Templates
- **Nike Store Admin**: Advanced admin theme
- **Nike Store Mobile**: Mobile-optimized theme
- **Nike Store Dark**: Dark mode theme

## 🏆 Acknowledgments

### 💝 Special Thanks
- **Nike Inc.** for brand inspiration (educational use only)
- **Bootstrap Team** for the amazing CSS framework
- **jQuery Team** for JavaScript simplification
- **PHP Community** for continuous improvements
- **MySQL Team** for reliable database system

### 📚 Libraries & Frameworks Used
- [Bootstrap 5.3.0](https://getbootstrap.com/) - CSS Framework
- [jQuery 3.6.0](https://jquery.com/) - JavaScript Library
- [Font Awesome 6.4.0](https://fontawesome.com/) - Icon Library
- [Google Fonts](https://fonts.google.com/) - Typography

### 🎓 Educational Purpose
This project được tạo ra cho mục đích học tập và demonstration. Logo Nike và brand elements được sử dụng chỉ cho educational purpose và không intended for commercial use.

## ⚖️ Legal Notice

### 📄 License
Dự án này được phân phối dưới **MIT License**. Xem file [LICENSE](LICENSE) để biết thêm chi tiết.

```
MIT License

Copyright (c) 2025 Nike Shoe Store Project

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```

### ⚠️ Disclaimer
- Nike brand và logos are trademarks của Nike, Inc.
- Project này chỉ for educational purposes
- Không intended for commercial use
- All Nike-related assets used under fair use

### 🔐 Privacy Policy
- User data được bảo vệ theo GDPR standards
- No personal information được share với third parties  
- Session data được encrypted và secured
- Password được hash với bcrypt algorithm

---

## 🌟 Star History

[![Star History Chart](https://api.star-history.com/svg?repos=yourname/Webgiay&type=Date)](https://star-history.com/#yourname/Webgiay&Date)

---

<div align="center">
  <h3>🙏 Cảm ơn bạn đã quan tâm đến dự án Nike Shoe Store!</h3>
  <p>Nếu dự án hữu ích, đừng quên ⭐ <strong>Star</strong> repo này!</p>
  
  <br>
  
  **Made with ❤️ by [Your Name](https://github.com/yourname)**
  
  <br>
  
  ![Profile Views](https://komarev.com/ghpvc/?username=yourname&color=blue)
  ![GitHub followers](https://img.shields.io/github/followers/yourname?style=social)
  ![GitHub stars](https://img.shields.io/github/stars/yourname/Webgiay?style=social)
</div>
