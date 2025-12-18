<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use InvalidArgumentException;

class OrderController extends Controller
{
    /**
     * Calcule le total d'une commande
     */
    public function total(Request $request, OrderService $orderService): JsonResponse
    {
        try {
            // Valider les données d'entrée
            $validated = $request->validate([
                'items' => 'required|array',
                'items.*.price' => 'required|numeric|min:0',
                'items.*.quantity' => 'required|integer|min:0',
                'tax_rate' => 'numeric|min:0|max:100|nullable'
            ]);

            $items = $validated['items'];
            $taxRate = $validated['tax_rate'] ?? 0;

            if ($taxRate > 0) {
                $total = $orderService->calculateTotalWithTax($items, $taxRate);
                $calculationType = 'with_tax';
            } else {
                $total = $orderService->calculateTotal($items);
                $calculationType = 'without_tax';
            }

            return response()->json([
                'success' => true,
                'total' => $total,
                'calculation_type' => $calculationType,
                'tax_rate' => $taxRate,
                'currency' => 'EUR'
            ]);

        } catch (InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        }
    }
}
