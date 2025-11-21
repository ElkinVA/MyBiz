<?php
return [
    // Frontend routes
    '/' => ['HomeController', 'index'],
    '/catalog' => ['HomeController', 'catalog'],
    '/product/(\d+)' => ['HomeController', 'product'],
    '/page/([a-z0-9-]+)' => ['PageController', 'show'],
    
    // Admin routes
    '/admin' => ['AdminController', 'dashboard'],
    '/admin/login' => ['AdminController', 'login'],
    '/admin/logout' => ['AdminController', 'logout'],
    '/admin/dashboard' => ['AdminController', 'dashboard'],
    
    // Settings
    '/admin/settings' => ['AdminController', 'settings'],
    '/admin/settings/header' => ['AdminController', 'headerSettings'],
    '/admin/settings/design' => ['AdminController', 'designSettings'],
    
    // Sliders
    '/admin/sliders' => ['AdminController', 'sliders'],
    '/admin/sliders/create' => ['AdminController', 'createSlider'],
    '/admin/sliders/edit/(\d+)' => ['AdminController', 'editSlider'],
    '/admin/sliders/delete/(\d+)' => ['AdminController', 'deleteSlider'],
    '/admin/sliders/toggle/(\d+)' => ['AdminController', 'toggleSlider'],
    
    // Categories
    '/admin/categories' => ['AdminController', 'categories'],
    '/admin/categories/create' => ['AdminController', 'createCategory'],
    '/admin/categories/edit/(\d+)' => ['AdminController', 'editCategory'],
    '/admin/categories/delete/(\d+)' => ['AdminController', 'deleteCategory'],
    '/admin/categories/toggle/(\d+)' => ['AdminController', 'toggleCategory'],
    
    // Products
    '/admin/products' => ['AdminController', 'products'],
    '/admin/products/create' => ['AdminController', 'createProduct'],
    '/admin/products/edit/(\d+)' => ['AdminController', 'editProduct'],
    '/admin/products/delete/(\d+)' => ['AdminController', 'deleteProduct'],
    '/admin/products/toggle/(\d+)' => ['AdminController', 'toggleProduct'],
    
    // Pages
    '/admin/pages' => ['AdminController', 'pages'],
    '/admin/pages/create' => ['AdminController', 'createPage'],
    '/admin/pages/edit/(\d+)' => ['AdminController', 'editPage'],
    '/admin/pages/delete/(\d+)' => ['AdminController', 'deletePage'],
    '/admin/pages/toggle/(\d+)' => ['AdminController', 'togglePage'],
    
    // API routes
    '/api/products' => ['ApiController', 'products'],
    '/api/categories' => ['ApiController', 'categories'],
    '/about' => 'PageController@about',
'/contacts' => 'PageController@contacts', 
'/guarantee' => 'PageController@guarantee',
'/delivery' => 'PageController@delivery',
'/faq' => 'PageController@faq',

// Динамические страницы по slug
'/page/{slug}' => 'PageController@show',
'GET' => [
        '/' => 'HomeController@index',
        '/admin' => 'AdminController@dashboard',
        '/admin/login' => 'AdminController@login',
        '/admin/categories' => 'AdminController@categories',
        '/admin/products' => 'AdminController@products',
        '/admin/pages' => 'AdminController@pages',
        '/admin/settings' => 'AdminController@settings',
        '/page/{slug}' => 'PageController@show',
        '/api/products' => 'ApiController@products'
    ],
    'POST' => [
        '/admin/login' => 'AdminController@loginPost',
        '/admin/categories' => 'AdminController@saveCategory',
        '/admin/products' => 'AdminController@saveProduct',
        '/admin/pages' => 'AdminController@savePage',
        '/admin/settings' => 'AdminController@saveSettings'
    ]
    ];