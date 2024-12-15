<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Stock;

class StockDataController extends Controller
{
    private $host = 'http://89.108.115.241:6969';
    private $apiKey = 'E6kUTYrYwZq2tN4QEtyzsbEBk3ie';

    public function index(Request $request)
    {
        $dateFrom = $request->input('dateFrom');
        $dateTo = $request->input('dateTo');
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 500);

        try {
            // Выполняем HTTP-запрос к API
            $response = Http::get("{$this->host}/api/stocks", [
                'dateFrom' => $dateFrom,
                'dateTo' => $dateTo,
                'page' => $page,
                'limit' => $limit,
                'key' => $this->apiKey,
            ]);

            // Проверяем успешность ответа
            if ($response->ok()) {
                $data = $response->json()['data'];

                foreach ($data as $stock) {
                    // Если g_number отсутствует, создаем уникальное значение для него
                    $g_number = $stock['g_number'] ?? uniqid('g_num_', true); // Генерация уникального ID

                    // Проверяем уникальность g_number, чтобы избежать дублирования
                    Stock::updateOrCreate(
                        ['g_number' => $g_number], // Используем g_number (или уникальное значение)
                        [
                            'date' => $stock['date'] ?? null,
                            'last_change_date' => $stock['last_change_date'] ?? null,
                            'supplier_article' => $stock['supplier_article'] ?? null,
                            'tech_size' => $stock['tech_size'] ?? null,
                            'barcode' => $stock['barcode'] ?? null,
                            'quantity' => $stock['quantity'] ?? 0,
                            'is_supply' => $stock['is_supply'] ?? false,
                            'is_realization' => $stock['is_realization'] ?? false,
                            'quantity_full' => $stock['quantity_full'] ?? 0,
                            'warehouse_name' => $stock['warehouse_name'] ?? null,
                            'in_way_to_client' => $stock['in_way_to_client'] ?? 0,
                            'in_way_from_client' => $stock['in_way_from_client'] ?? 0,
                            'nm_id' => $stock['nm_id'] ?? null,
                            'subject' => $stock['subject'] ?? null,
                            'category' => $stock['category'] ?? null,
                            'brand' => $stock['brand'] ?? null,
                            'sc_code' => $stock['sc_code'] ?? null,
                            'price' => $stock['price'] ?? 0,
                            'discount' => $stock['discount'] ?? 0,
                        ]
                    );
                }

                return response()->json(['message' => 'Stocks data fetched and stored successfully.']);
            }

            return response()->json(['error' => 'Failed to fetch stocks data.'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching data: ' . $e->getMessage()], 500);
        }
    }
}
