# Legacy Files Cleanup Documentation

## Files to be Removed After MVC Migration

### Legacy PHP Files (Root Directory)
- `index.php` - Replaced by `index-mvc.php` and routing system
- `login.php` - Replaced by `AuthController` and `users/login.php` view
- `register.php` - Replaced by `AuthController` and `users/register.php` view
- `cart.php` - Replaced by `CartController` and `cart/index.php` view
- `checkout.php` - Functionality moved to `CartController`
- `product.php` - Replaced by `ProductController` and `products/show.php` view
- `products.php` - Replaced by `ProductController` and `products/index.php` view
- `about.php` - Can be replaced by static page controller
- `contact.php` - Can be replaced by static page controller
- `logout.php` - Replaced by `AuthController::logout()` method

### Legacy Includes Directory
- `includes/header.php` - Replaced by `app/Views/layouts/header.php`
- `includes/footer.php` - Replaced by `app/Views/layouts/footer.php`
- `includes/config.php` - Replaced by `app/Core/Database.php` configuration
- `includes/cart_actions.php` - Functionality moved to `CartController`

### Empty Directories
- `config/` - Empty directory, can be removed

## Files to Keep
- `admin/` - Admin functionality (may need separate MVC migration)
- `assets/` - CSS, JS, images
- `uploads/` - User uploaded files
- `database.sql` - Database schema
- `README.md` - Project documentation
- `MVC_MIGRATION.md` - Migration documentation
- `.htaccess` - URL rewriting rules
- `.git/` and git files - Version control
- `index-mvc.php` - New MVC entry point
- `app/` - Complete MVC structure

## Cleanup Process
1. Backup important legacy files (if needed)
2. Remove legacy PHP files
3. Remove legacy includes directory
4. Remove empty config directory
5. Update documentation

Date: June 6, 2025
Status: ✅ COMPLETED - All legacy files cleaned up successfully
