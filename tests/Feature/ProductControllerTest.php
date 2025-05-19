<?php

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('dapat menyimpan produk baru', function () {
    $category = Category::factory()->create();

    $response = $this->post('/products', [
        'name' => 'Produk A',
        'description' => 'Deskripsi Produk A',
        'category_id' => $category->id,
        'price' => 10000,
        'stock' => 5
    ]);

    $response->assertRedirect(); 
    $this->assertDatabaseHas('products', [
        'name' => 'Produk A',
        'description' => 'Deskripsi Produk A',
        'category_id' => $category->id,
        'price' => 10000,
        'stock' => 5
    ]);
});


test('tidak dapat menyimpan produk dengan data tidak valid', function () {
    // Kirim request POST dengan data kosong
    $response = $this->post(route('products.store'), []);

    // Pastikan ada error validasi
    $response->assertInvalid([
        'name' => 'The name field is required.',
        'description' => 'The description field is required.',
        'price' => 'The price field is required.',
        'stock' => 'The stock field is required.',
        'category_id' => 'The category id field is required.'
    ]);
});

test('tidak dapat menyimpan produk dengan kategori yang tidak ada', function () {
    $productData = [
        'name' => 'Produk Test',
        'description' => 'Deskripsi produk test',
        'price' => 100000,
        'stock' => 10,
        'category_id' => 999 // ID kategori yang tidak ada
    ];

    $response = $this->post(route('products.store'), $productData);

    $response->assertInvalid([
        'category_id' => 'The selected category id is invalid.'
    ]);
});
