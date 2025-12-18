<?php

namespace Tests\Unit;

use App\Services\OrderService;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class OrderServiceTest extends TestCase
{
    private OrderService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new OrderService();
    }

    #[Test]
    public function test_calculate_total_of_order(): void
    {
        $items = [
            ['price' => 100, 'quantity' => 2],
            ['price' => 50, 'quantity' => 1]
        ];

        $total = $this->service->calculateTotal($items);

        $this->assertEquals(250, $total);
    }

    #[Test]
    public function test_calculate_total_with_single_item(): void
    {
        $items = [
            ['price' => 75.50, 'quantity' => 3]
        ];

        $total = $this->service->calculateTotal($items);

        $this->assertEquals(226.5, $total);
    }

    #[Test]
    public function test_calculate_total_with_zero_quantity(): void
    {
        $items = [
            ['price' => 100, 'quantity' => 0],
            ['price' => 50, 'quantity' => 2]
        ];

        $total = $this->service->calculateTotal($items);

        $this->assertEquals(100, $total);
    }

    #[Test]
    public function test_throws_exception_for_negative_quantity(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('La quantité de l\'item à l\'index 0 ne peut pas être négative');

        $items = [
            ['price' => 100, 'quantity' => -1]
        ];

        $this->service->calculateTotal($items);
    }

    #[Test]
    public function test_throws_exception_for_negative_price(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Le prix de l\'item à l\'index 0 ne peut pas être négatif');

        $items = [
            ['price' => -50, 'quantity' => 2]
        ];

        $this->service->calculateTotal($items);
    }

    #[Test]
    public function test_calculate_total_with_empty_array(): void
    {
        $items = [];

        $total = $this->service->calculateTotal($items);

        $this->assertEquals(0, $total);
    }

    #[Test]
    public function test_calculate_total_with_decimal_values(): void
    {
        $items = [
            ['price' => 19.99, 'quantity' => 2],
            ['price' => 5.50, 'quantity' => 3]
        ];

        $total = $this->service->calculateTotal($items);
        $expected = (19.99 * 2) + (5.50 * 3);

        $this->assertEquals($expected, $total);
    }

    #[Test]
    public function test_throws_exception_for_missing_price(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("L'item à l'index 0 doit contenir 'price' et 'quantity'");

        $items = [
            ['quantity' => 2]
        ];

        $this->service->calculateTotal($items);
    }

    #[Test]
    public function test_throws_exception_for_missing_quantity(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("L'item à l'index 0 doit contenir 'price' et 'quantity'");

        $items = [
            ['price' => 100]
        ];

        $this->service->calculateTotal($items);
    }

    #[Test]
    public function test_throws_exception_for_invalid_price_type(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Le prix de l'item à l'index 0 doit être un nombre");

        $items = [
            ['price' => 'not a number', 'quantity' => 2]
        ];

        $this->service->calculateTotal($items);
    }

    #[Test]
    public function test_throws_exception_for_invalid_quantity_type(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("La quantité de l'item à l'index 0 doit être un nombre");

        $items = [
            ['price' => 100, 'quantity' => 'two']
        ];

        $this->service->calculateTotal($items);
    }

    #[Test]
    public function test_calculate_total_with_tax(): void
    {
        $items = [
            ['price' => 100, 'quantity' => 2]
        ];

        $total = $this->service->calculateTotalWithTax($items, 20);

        $this->assertEquals(240, $total); // 200 + 20% = 240
    }

    #[Test]
    public function test_calculate_total_with_zero_tax(): void
    {
        $items = [
            ['price' => 100, 'quantity' => 2]
        ];

        $total = $this->service->calculateTotalWithTax($items, 0);

        $this->assertEquals(200, $total);
    }

    #[Test]
    public function test_throws_exception_for_negative_tax_rate(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Le taux de taxe ne peut pas être négatif");

        $items = [
            ['price' => 100, 'quantity' => 2]
        ];

        $this->service->calculateTotalWithTax($items, -10);
    }

    #[Test]
    public function test_large_dataset_performance(): void
    {
        $items = [];
        for ($i = 0; $i < 1000; $i++) {
            $items[] = ['price' => rand(1, 100), 'quantity' => rand(1, 10)];
        }

        $start = microtime(true);
        $total = $this->service->calculateTotal($items);
        $end = microtime(true);

        $executionTime = $end - $start;

        // Le test doit être rapide
        $this->assertLessThan(0.05, $executionTime,
            "Le calcul devrait prendre moins de 50ms, pris {$executionTime}s");

        // Vérifier que le total est un nombre
        $this->assertIsFloat($total);
        $this->assertGreaterThan(0, $total);
    }
}
