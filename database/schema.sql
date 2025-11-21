-- Создание базы данных для SQL Server
IF NOT EXISTS(SELECT * FROM sys.databases WHERE name = 'mybiz')
BEGIN
    CREATE DATABASE mybiz;
END
GO

USE mybiz;
GO

-- Таблица администраторов
IF NOT EXISTS (SELECT * FROM sysobjects WHERE name='admins' AND xtype='U')
CREATE TABLE admins (
    id INT IDENTITY(1,1) PRIMARY KEY,
    username NVARCHAR(100) NOT NULL UNIQUE,
    email NVARCHAR(255) NOT NULL UNIQUE,
    password NVARCHAR(255) NOT NULL,
    created_at DATETIME2 DEFAULT GETDATE(),
    updated_at DATETIME2 DEFAULT GETDATE()
);
GO

-- Таблица настроек
IF NOT EXISTS (SELECT * FROM sysobjects WHERE name='settings' AND xtype='U')
CREATE TABLE settings (
    id INT IDENTITY(1,1) PRIMARY KEY,
    [key] NVARCHAR(255) NOT NULL UNIQUE,
    value NVARCHAR(MAX),
    created_at DATETIME2 DEFAULT GETDATE(),
    updated_at DATETIME2 DEFAULT GETDATE()
);
GO

-- Таблица слайдера
IF NOT EXISTS (SELECT * FROM sysobjects WHERE name='sliders' AND xtype='U')
CREATE TABLE sliders (
    id INT IDENTITY(1,1) PRIMARY KEY,
    title NVARCHAR(255) NOT NULL,
    subtitle NVARCHAR(500),
    image NVARCHAR(500),
    button_text NVARCHAR(100),
    button_link NVARCHAR(255),
    position NVARCHAR(50) DEFAULT 'top',
    is_active BIT DEFAULT 1,
    created_at DATETIME2 DEFAULT GETDATE(),
    updated_at DATETIME2 DEFAULT GETDATE()
);
GO

-- Таблица категорий
IF NOT EXISTS (SELECT * FROM sysobjects WHERE name='categories' AND xtype='U')
CREATE TABLE categories (
    id INT IDENTITY(1,1) PRIMARY KEY,
    name NVARCHAR(255) NOT NULL,
    slug NVARCHAR(255) UNIQUE,
    description NVARCHAR(MAX),
    image NVARCHAR(500),
    parent_id INT NULL,
    is_active BIT DEFAULT 1,
    created_at DATETIME2 DEFAULT GETDATE(),
    updated_at DATETIME2 DEFAULT GETDATE()
);
GO

-- Внешний ключ для категорий
IF NOT EXISTS (SELECT * FROM sys.foreign_keys WHERE name = 'FK_categories_parent')
ALTER TABLE categories ADD CONSTRAINT FK_categories_parent 
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL;
GO

-- Таблица продуктов
IF NOT EXISTS (SELECT * FROM sysobjects WHERE name='products' AND xtype='U')
CREATE TABLE products (
    id INT IDENTITY(1,1) PRIMARY KEY,
    name NVARCHAR(255) NOT NULL,
    slug NVARCHAR(255) UNIQUE,
    description NVARCHAR(MAX),
    price DECIMAL(10,2) NOT NULL,
    compare_price DECIMAL(10,2),
    sku NVARCHAR(100),
    barcode NVARCHAR(255),
    quantity INT DEFAULT 0,
    category_id INT,
    images NVARCHAR(MAX),
    is_featured BIT DEFAULT 0,
    is_published BIT DEFAULT 1,
    status NVARCHAR(50) DEFAULT 'active',
    meta_title NVARCHAR(255),
    meta_description NVARCHAR(MAX),
    created_at DATETIME2 DEFAULT GETDATE(),
    updated_at DATETIME2 DEFAULT GETDATE()
);
GO

-- Внешний ключ для продуктов
IF NOT EXISTS (SELECT * FROM sys.foreign_keys WHERE name = 'FK_products_category')
ALTER TABLE products ADD CONSTRAINT FK_products_category 
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL;
GO

-- Таблица пользователей
IF NOT EXISTS (SELECT * FROM sysobjects WHERE name='users' AND xtype='U')
CREATE TABLE users (
    id INT IDENTITY(1,1) PRIMARY KEY,
    name NVARCHAR(255) NOT NULL,
    email NVARCHAR(255) NOT NULL UNIQUE,
    password NVARCHAR(255) NOT NULL,
    phone NVARCHAR(50),
    address NVARCHAR(MAX),
    is_active BIT DEFAULT 1,
    created_at DATETIME2 DEFAULT GETDATE(),
    updated_at DATETIME2 DEFAULT GETDATE()
);
GO

-- Вставка тестового администратора
IF NOT EXISTS (SELECT * FROM admins WHERE email = 'admin@mybiz.com')
INSERT INTO admins (username, email, password) VALUES 
('admin', 'admin@mybiz.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');
GO