<?php

use App\Models\Product;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    Sanctum::actingAs(
        User::factory()->create()
    );
});

it('get all products', function () {
    $count = Product::count();
    $response = $this->get('/api/products');
    $response->assertStatus(200)
        ->assertJson(['message' => 'Products successfully fetched.'])
        ->assertJsonCount($count, 'data');

})->group('product');

it('has product page', function () {
    $response = $this->get('/api/products');
    $response->assertStatus(200);
})->group('product');

it('creates a product', function () {
    $sample = [
        'title' => fake()->word(),
        'description' => fake()->text(200),
        'price' => fake()->randomNumber(2, false)
    ];

    $response = $this->post('/api/products', $sample);
    $response->assertStatus(201);

    expect(Product::latest()->first())
        ->title->toBeString()->not->toBeEmpty()
        ->description->toBeString()->not->toBeEmpty()
        ->price->toBeNumeric()
        ->title->toBe($sample['title']);
})->group('product');


it('response is a valid eloquent resource', function () {
    $sample = [
        'title' => fake()->word(),
        'description' => fake()->text(200),
        'price' => fake()->randomNumber(2, false)
    ];

    $response = $this->post('/api/products', $sample);
    $response->assertStatus(201)
        ->assertJson(['message' => 'Product successfully created.'])
        ->assertJson(['data' => $sample]);
})->group('product');
