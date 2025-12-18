<?php

namespace App\Services;

use InvalidArgumentException;

class OrderService
{
    /**
     * Calcule le total d'une commande
     *
     * @param array<array{price: float, quantity: int}> $items
     * @return float
     * @throws InvalidArgumentException
     */
    public function calculateTotal(array $items): float
    {
        $total = 0;

        foreach ($items as $index => $item) {
            // Validation de la structure des données
            if (!is_array($item)) {
                throw new InvalidArgumentException("L'item à l'index $index n'est pas un tableau valide");
            }

            // Validation des clés requises
            if (!isset($item['price']) || !isset($item['quantity'])) {
                throw new InvalidArgumentException("L'item à l'index $index doit contenir 'price' et 'quantity'");
            }

            // Validation des types
            if (!is_numeric($item['price'])) {
                throw new InvalidArgumentException("Le prix de l'item à l'index $index doit être un nombre");
            }

            if (!is_numeric($item['quantity'])) {
                throw new InvalidArgumentException("La quantité de l'item à l'index $index doit être un nombre");
            }

            $price = (float) $item['price'];
            $quantity = (int) $item['quantity'];

            // Validation métier : quantité non négative
            if ($quantity < 0) {
                throw new InvalidArgumentException("La quantité de l'item à l'index $index ne peut pas être négative");
            }

            // Validation métier : prix non négatif
            if ($price < 0) {
                throw new InvalidArgumentException("Le prix de l'item à l'index $index ne peut pas être négatif");
            }

            $total += $price * $quantity;
        }

        return round($total, 2);
    }

    /**
     * Calcule le total avec taxe
     *
     * @param array $items
     * @param float $taxRate Taux de taxe en pourcentage (ex: 20 pour 20%)
     * @return float
     */
    public function calculateTotalWithTax(array $items, float $taxRate = 0): float
    {
        $subtotal = $this->calculateTotal($items);

        if ($taxRate < 0) {
            throw new InvalidArgumentException("Le taux de taxe ne peut pas être négatif");
        }

        $tax = $subtotal * ($taxRate / 100);

        return round($subtotal + $tax, 2);
    }
}
