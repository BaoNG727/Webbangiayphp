<?php

require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Core/Database.php';

class HomeController extends Controller
{
    public function index()
    {
        $productModel = $this->model('Product');
        
        $data = [
            'title' => 'Nike Shoe Store - Home',
            'featured_products' => $productModel->getFeatured(4),
            'sale_products' => $productModel->getSaleProducts(4)
        ];

        $this->view('layouts/header', $data);
        $this->view('home/index', $data);
        $this->view('layouts/footer');
    }
}
