-- Начальные настройки сайта
INSERT INTO settings (setting_key, setting_value, setting_type) VALUES 
('site_name', 'MyBiz', 'string'),
('site_logo', '', 'image'),
('show_site_logo', '1', 'boolean'),
('header_background_type', 'color', 'string'),
('header_background_value', '#ffffff', 'color'),
('header_text_color', '#000000', 'color'),
('header_font_family', 'Arial, sans-serif', 'font'),
('show_top_slider', '1', 'boolean'),
('show_bottom_slider', '1', 'boolean'),
('show_categories', '1', 'boolean'),
('show_products', '1', 'boolean'),
('contact_phone', '+7 (999) 999-99-99', 'string'),
('contact_email', 'info@mybiz.ru', 'string'),
('contact_address', 'г. Москва', 'string'),
('legal_inn', '0000000000', 'string'),
('legal_ogrn', '0000000000000', 'string'),
('seo_text', '<p>Добро пожаловать в наш интернет-магазин MyBiz! Мы предлагаем широкий ассортимент качественных товаров по доступным ценам. Наша компания работает на рынке более 5 лет и заслужила доверие тысяч клиентов.</p>', 'string');

-- Начальные слайды
INSERT INTO sliders (title, description, background_type, background_value, text_color, font_family, slider_position, sort_order) VALUES 
('Добро пожаловать в MyBiz', 'Лучшие товары по доступным ценам', 'color', '#007bff', '#ffffff', 'Arial, sans-serif', 'top', 1),
('Скидки до 50%', 'Только этой недели специальные предложения', 'gradient', 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)', '#ffffff', 'Arial, sans-serif', 'top', 2),
('Бесплатная доставка', 'При заказе от 3000 рублей', 'color', '#28a745', '#ffffff', 'Arial, sans-serif', 'bottom', 1);

-- Начальные категории
INSERT INTO categories (name, description, background_type, background_value, text_color, font_family, sort_order) VALUES 
('Электроника', 'Современная электроника и гаджеты', 'color', '#007bff', '#ffffff', 'Arial, sans-serif', 1),
('Одежда', 'Модная одежда для всей семьи', 'color', '#28a745', '#ffffff', 'Arial, sans-serif', 2),
('Книги', 'Художественная и образовательная литература', 'color', '#ffc107', '#000000', 'Arial, sans-serif', 3),
('Спорт', 'Спортивные товары и инвентарь', 'color', '#dc3545', '#ffffff', 'Arial, sans-serif', 4);

-- Начальные товары
INSERT INTO products (category_id, name, description, price, image, background_type, background_value, text_color, font_family) VALUES 
(1, 'Смартфон Samsung Galaxy', 'Новый флагманский смартфон с отличной камерой', 29999.00, '', 'color', '#ffffff', '#000000', 'Arial, sans-serif'),
(1, 'Ноутбук ASUS', 'Мощный ноутбук для работы и игр', 54999.00, '', 'color', '#ffffff', '#000000', 'Arial, sans-serif'),
(2, 'Джинсы классические', 'Качественные джинсы прямого покроя', 2999.00, '', 'color', '#ffffff', '#000000', 'Arial, sans-serif'),
(3, 'Книга "Искусство программирования"', 'Фундаментальный труд Дональда Кнута', 4599.00, '', 'color', '#ffffff', '#000000', 'Arial, sans-serif'),
(4, 'Беговая дорожка', 'Профессиональная беговая дорожка для дома', 25999.00, '', 'color', '#ffffff', '#000000', 'Arial, sans-serif');

-- Начальные страницы
INSERT INTO pages (title, content, slug, meta_title, meta_description, is_active) VALUES 
('О нас', '<h2>О нашей компании</h2><p>MyBiz - это современный интернет-магазин, который работает на рынке более 5 лет. Мы стремимся предоставлять нашим клиентам только качественные товары по доступным ценам.</p>', 'about', 'О компании MyBiz', 'Узнайте больше о нашей компании и нашей миссии', 1),
('Контакты', '<h2>Наши контакты</h2><p>Телефон: +7 (999) 999-99-99</p><p>Email: info@mybiz.ru</p><p>Адрес: г. Москва</p>', 'contacts', 'Контакты MyBiz', 'Свяжитесь с нами по телефону, email или посетите наш офис', 1),
('Гарантия качества', '<h2>Гарантия качества</h2><p>Мы гарантируем качество всех наших товаров. На все товары предоставляется гарантия от производителя.</p>', 'guarantee', 'Гарантия качества MyBiz', 'Информация о гарантии качества на все товары', 1);

-- Начальный администратор (логин: admin, пароль: admin123)
INSERT INTO admins (username, password_hash, email) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@mybiz.ru');