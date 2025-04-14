@echo off
ECHO ===================================
ECHO    Nike Shoes E-commerce Website
ECHO    Auto Git Commit and Push Tool
ECHO ===================================
ECHO.

:: Kiểm tra xem Git đã được cài đặt chưa
WHERE git >nul 2>nul
IF %ERRORLEVEL% NEQ 0 (
    ECHO [ERROR] Git chưa được cài đặt. Vui lòng cài đặt Git từ https://git-scm.com/downloads
    PAUSE
    EXIT /B
)

:: Kiểm tra xem đã khởi tạo Git repository chưa
IF NOT EXIST .git (
    ECHO [INFO] Khởi tạo Git repository mới...
    git init
    
    ECHO.
    ECHO [INPUT] Nhập tên người dùng GitHub của bạn:
    SET /P GIT_USERNAME=
    
    ECHO [INPUT] Nhập email GitHub của bạn:
    SET /P GIT_EMAIL=
    
    git config --local user.name "%GIT_USERNAME%"
    git config --local user.email "%GIT_EMAIL%"
    
    ECHO.
    ECHO [INPUT] Nhập URL repository GitHub (ví dụ: https://github.com/username/Webgiay.git):
    SET /P REPO_URL=
    
    git remote add origin %REPO_URL%
) ELSE (
    ECHO [INFO] Git repository đã tồn tại.
    
    :: Hiển thị thông tin remote repository
    ECHO.
    ECHO [INFO] Thông tin remote repository:
    git remote -v
)

:: Tạo file .gitignore để loại trừ một số file không cần thiết
IF NOT EXIST .gitignore (
    ECHO [INFO] Tạo file .gitignore...
    ECHO vendor/ > .gitignore
    ECHO node_modules/ >> .gitignore
    ECHO .env >> .gitignore
    ECHO .DS_Store >> .gitignore
    ECHO Thumbs.db >> .gitignore
    ECHO *.log >> .gitignore
)

:: Thêm tất cả các file
ECHO.
ECHO [INFO] Thêm tất cả các file vào Git...
git add .

:: Tạo commit
ECHO.
ECHO [INPUT] Nhập mô tả commit (mặc định: "Update Nike Shoes E-commerce Website"):
SET COMMIT_MSG=Update Nike Shoes E-commerce Website
SET /P COMMIT_MSG_INPUT=
IF NOT "%COMMIT_MSG_INPUT%"=="" SET COMMIT_MSG=%COMMIT_MSG_INPUT%

git commit -m "%COMMIT_MSG%"

:: Push lên GitHub
ECHO.
ECHO [INFO] Đang đẩy code lên GitHub...
git push -u origin master

IF %ERRORLEVEL% NEQ 0 (
    ECHO.
    ECHO [WARNING] Có thể bạn cần phải đăng nhập GitHub. 
    ECHO [INFO] Nếu đây là lần đầu tiên bạn push, GitHub sẽ yêu cầu xác thực.
    ECHO [INFO] Bạn có thể được yêu cầu nhập username và password GitHub hoặc sử dụng token.
    PAUSE
    
    :: Thử push lại
    git push -u origin master
    
    IF %ERRORLEVEL% NEQ 0 (
        ECHO.
        ECHO [ERROR] Không thể push lên GitHub. 
        ECHO [INFO] Vui lòng kiểm tra thông tin đăng nhập GitHub của bạn.
        ECHO [INFO] Bạn có thể thử tạo và sử dụng Personal Access Token: https://github.com/settings/tokens
    ) ELSE (
        ECHO.
        ECHO [SUCCESS] Push thành công!
    )
) ELSE (
    ECHO.
    ECHO [SUCCESS] Push thành công!
)

ECHO.
ECHO ===================================
ECHO    Hoàn tất quá trình!
ECHO ===================================
PAUSE