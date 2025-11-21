    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Навигация</h3>
                    <ul>
                        <li><a href="/">Главная</a></li>
                        <li><a href="/page/about">О нас</a></li>
                        <li><a href="/page/contacts">Контакты</a></li>
                        <li><a href="/page/guarantee">Гарантия</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Контакты</h3>
                    <p>Телефон: <?= $settings['contact_phone'] ?? '+7 (999) 999-99-99' ?></p>
                    <p>Email: <?= $settings['contact_email'] ?? 'info@mybiz.ru' ?></p>
                    <p>Адрес: <?= $settings['contact_address'] ?? 'г. Москва' ?></p>
                </div>
                
                <div class="footer-section">
                    <h3>Юридическая информация</h3>
                    <p>ИНН: <?= $settings['legal_inn'] ?? '0000000000' ?></p>
                    <p>ОГРН: <?= $settings['legal_ogrn'] ?? '0000000000000' ?></p>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <?= date('Y') ?> <?= $settings['site_name'] ?? 'MyBiz' ?>. Все права защищены.</p>
            </div>
        </div>
    </footer>

    <script src="/assets/js/main.js"></script>
    <script src="/assets/js/slider.js"></script>
    <script src="/assets/js/products.js"></script>
</body>
</html>