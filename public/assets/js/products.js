// public/assets/js/products.js

class ProductsManager {
    constructor() {
        this.currentPage = 1;
        this.hasMore = true;
        this.isLoading = false;
        
        this.initEvents();
    }

    initEvents() {
        // Load more products
        const loadMoreBtn = document.getElementById('loadMoreBtn');
        if (loadMoreBtn) {
            loadMoreBtn.addEventListener('click', () => this.loadMoreProducts());
        }

        // Filter form
        const filterForm = document.getElementById('filterForm');
        if (filterForm) {
            filterForm.addEventListener('submit', (e) => this.handleFilter(e));
        }

        // Real-time search
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput) {
            searchInput.addEventListener('input', 
                this.debounce(() => this.handleFilter(), 500)
            );
        }
    }

    async loadMoreProducts() {
        if (this.isLoading || !this.hasMore) return;

        this.isLoading = true;
        const loadMoreBtn = document.getElementById('loadMoreBtn');
        loadMoreBtn.disabled = true;
        loadMoreBtn.textContent = 'Загрузка...';

        try {
            const formData = new FormData();
            formData.append('page', this.currentPage + 1);
            
            // Добавляем параметры фильтрации
            const search = document.querySelector('input[name="search"]').value;
            const category = document.querySelector('select[name="category"]').value;
            
            if (search) formData.append('search', search);
            if (category) formData.append('category', category);

            const response = await fetch('/home/load-more', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                this.currentPage++;
                this.hasMore = data.hasMore;
                this.appendProducts(data.products);
                
                if (!data.hasMore) {
                    loadMoreBtn.remove();
                } else {
                    loadMoreBtn.dataset.page = this.currentPage + 1;
                }
            }
        } catch (error) {
            console.error('Error loading more products:', error);
        } finally {
            this.isLoading = false;
            loadMoreBtn.disabled = false;
            loadMoreBtn.textContent = 'Показать ещё';
        }
    }

    appendProducts(products) {
        const productsGrid = document.getElementById('productsGrid');
        
        products.forEach(product => {
            const productHTML = this.createProductHTML(product);
            productsGrid.insertAdjacentHTML('beforeend', productHTML);
        });
    }

    createProductHTML(product) {
        return `
            <div class="product-card">
                ${product.image ? `
                    <div class="product-image">
                        <img src="/assets/images/uploads/products/${product.image}" 
                             alt="${product.name}">
                    </div>
                ` : ''}
                <div class="product-info">
                    <h3 class="product-title">${this.escapeHtml(product.name)}</h3>
                    <p class="product-description">${this.escapeHtml(product.description)}</p>
                    <div class="product-price">${parseFloat(product.price).toFixed(2)} ₽</div>
                </div>
            </div>
        `;
    }

    handleFilter(e) {
        if (e) e.preventDefault();
        
        // Сбрасываем пагинацию при новом фильтре
        this.currentPage = 1;
        this.hasMore = true;
        
        // Отправляем форму фильтрации
        const filterForm = document.getElementById('filterForm');
        if (filterForm) {
            filterForm.submit();
        }
    }

    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    escapeHtml(unsafe) {
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }
}

// Инициализация при загрузке страницы
document.addEventListener('DOMContentLoaded', () => {
    new ProductsManager();
});