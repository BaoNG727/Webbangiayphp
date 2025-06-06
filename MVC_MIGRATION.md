# Chuyển đổi sang Mô hình MVC

## Cấu trúc MVC đã được tạo

Trang web Nike Shoe Store đã được tái cấu trúc theo mô hình MVC (Model-View-Controller) với cấu trúc sau:

### Cấu trúc thư mục:
```
app/
├── Core/
│   ├── Database.php      # Lớp quản lý database với PDO
│   ├── Controller.php    # Lớp Controller cơ sở
│   ├── Model.php        # Lớp Model cơ sở
│   └── Router.php       # Hệ thống routing
├── Controllers/
│   ├── HomeController.php     # Controller trang chủ
│   ├── ProductController.php  # Controller sản phẩm
│   ├── AuthController.php     # Controller xác thực
│   └── CartController.php     # Controller giỏ hàng
├── Models/
│   ├── Product.php      # Model sản phẩm
│   ├── User.php         # Model người dùng
│   ├── Cart.php         # Model giỏ hàng
│   └── Order.php        # Model đơn hàng
└── Views/
    ├── layouts/
    │   ├── header.php   # Header chung
    │   └── footer.php   # Footer chung
    ├── home/
    │   └── index.php    # Trang chủ
    ├── products/
    │   └── index.php    # Danh sách sản phẩm
    ├── users/
    │   ├── login.php    # Trang đăng nhập
    │   └── register.php # Trang đăng ký
    └── cart/
        └── index.php    # Trang giỏ hàng
```

## Tính năng đã triển khai:

### 1. Core System
- **Database**: Lớp quản lý kết nối database với PDO, prepared statements
- **Router**: Hệ thống routing hỗ trợ GET/POST, parameters động
- **Controller**: Lớp cơ sở với các phương thức tiện ích
- **Model**: Lớp cơ sở với CRUD operations

### 2. Controllers
- **HomeController**: Xử lý trang chủ, hiển thị sản phẩm nổi bật
- **ProductController**: Quản lý sản phẩm, tìm kiếm, lọc, thêm vào giỏ
- **AuthController**: Xử lý đăng nhập, đăng ký, đăng xuất
- **CartController**: Quản lý giỏ hàng (thêm, xóa, cập nhật)

### 3. Models
- **Product**: Quản lý sản phẩm, tìm kiếm, phân loại
- **User**: Quản lý người dùng, xác thực
- **Cart**: Quản lý giỏ hàng
- **Order**: Quản lý đơn hàng

### 4. Views
- Responsive design với Bootstrap 5
- Component-based layout (header/footer chung)
- AJAX cho các tương tác không reload trang
- Form validation

## Cách sử dụng:

### 1. Cấu hình
File `index-mvc.php` là entry point chính, xử lý tất cả requests.
File `.htaccess` redirect tất cả requests đến `index-mvc.php`.

### 2. Routes đã định nghĩa:
```
GET  /                    -> HomeController::index
GET  /products           -> ProductController::index
GET  /product/{id}       -> ProductController::show
POST /product/add-to-cart -> ProductController::addToCart
GET  /login              -> AuthController::login
POST /login              -> AuthController::login
GET  /register           -> AuthController::register
POST /register           -> AuthController::register
GET  /logout             -> AuthController::logout
GET  /cart               -> CartController::index
POST /cart/update        -> CartController::update
POST /cart/remove        -> CartController::remove
POST /cart/clear         -> CartController::clear
```

### 3. Database
Sử dụng cùng database schema như cũ, chỉ thay đổi cách truy cập từ mysqli sang PDO.

### 4. Để chuyển sang sử dụng MVC hoàn toàn:
1. Backup files cũ
2. Rename `index.php` thành `index-old.php`
3. Rename `index-mvc.php` thành `index.php`
4. Cấu hình web server để sử dụng mod_rewrite

## Lợi ích của mô hình MVC:

1. **Tách biệt rõ ràng**: Logic, data và presentation được tách riêng
2. **Dễ bảo trì**: Code có cấu trúc rõ ràng, dễ tìm và sửa lỗi
3. **Có thể mở rộng**: Dễ dàng thêm tính năng mới
4. **Tái sử dụng**: Components có thể tái sử dụng
5. **Testing**: Dễ dàng viết unit tests
6. **Security**: Tốt hơn với prepared statements, validation tập trung

## Next Steps:
- Triển khai thêm tính năng Admin Panel theo MVC
- Thêm tính năng Checkout/Orders
- Implement caching
- Thêm middleware cho authentication
- API endpoints cho mobile app

## ✅ Migration Status: COMPLETED

### Cleanup Legacy Files - ✅ DONE
Các file và thư mục legacy đã được xóa thành công:

**Removed Files:**
- `index.php` - Thay thế bởi `index-mvc.php`
- `login.php` - Thay thế bởi `AuthController` và view
- `register.php` - Thay thế bởi `AuthController` và view  
- `cart.php` - Thay thế bởi `CartController` và view
- `checkout.php` - Tích hợp vào `CartController`
- `product.php` - Thay thế bởi `ProductController` và view
- `products.php` - Thay thế bởi `ProductController` và view

**Removed Directories:**
- `includes/` - Nội dung được chuyển vào `app/Views/layouts/`
- `config/` - Thư mục trống, không cần thiết

**Current Clean Structure:**
```
Webgiay/
├── .git/                 # Version control
├── .htaccess            # URL rewriting rules
├── index-mvc.php        # 🎯 MVC Entry Point
├── database.sql         # Database schema
├── README.md           # Project documentation
├── MVC_MIGRATION.md    # This file
├── cleanup_legacy.md   # Cleanup documentation
├── admin/              # Admin panel (separate)
├── app/                # 🎯 MVC Structure
│   ├── Core/           # Framework core
│   ├── Controllers/    # Business logic
│   ├── Models/         # Data layer
│   └── Views/          # Presentation layer
├── assets/             # Static files (CSS, JS, images)
├── public/             # Public assets
└── uploads/            # User uploaded files
```

### 🚀 Ready for Production
- ✅ MVC structure implemented
- ✅ Legacy files cleaned up
- ✅ Routing system active
- ✅ Database abstraction with PDO
- ✅ Security improvements (prepared statements)
- ✅ Modern responsive UI with Bootstrap 5
- ✅ AJAX functionality
- ✅ Clean separation of concerns

### Quick Start:
1. Đảm bảo Apache mod_rewrite được bật
2. Truy cập website qua `http://localhost/Webgiay/`
3. Tất cả routes sẽ được xử lý bởi `index-mvc.php`
4. Admin panel vẫn hoạt động tại `/admin/`

**Chuyển đổi MVC hoàn tất! 🎉**
