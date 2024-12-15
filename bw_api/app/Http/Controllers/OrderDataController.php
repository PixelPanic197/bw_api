<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // Подключаем HTTP-класс для работы с запросами
use App\Models\Order; // Подключаем модель Order

class OrderDataController extends Controller
{
    private $host = 'http://89.108.115.241:6969';
    private $apiKey = 'E6kUTYrYwZq2tN4QEtyzsbEBk3ie';

    public function index(Request $request)
    {
        // Получаем параметры из запроса
        $dateFrom = $request->input('dateFrom');
        $dateTo = $request->input('dateTo');
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 500);

        // Выполняем HTTP-запрос к API
        try {
            $response = Http::get("{$this->host}/api/orders", [
                'dateFrom' => $dateFrom,
                'dateTo' => $dateTo,
                'page' => $page,
                'limit' => $limit,
                'key' => $this->apiKey,
            ]);

            // Проверяем успешность ответа
            if ($response->ok()) {
                $data = $response->json()['data'];

                // Сохраняем или обновляем данные о заказах в базе данных
                foreach ($data as $order) {
                    Order::updateOrCreate(
                        ['g_number' => $order['g_number']], // Уникальный идентификатор
                        [
                            'date' => $order['date'],
                            'last_change_date' => $order['last_change_date'],
                            'supplier_article' => $order['supplier_article'],
                            'tech_size' => $order['tech_size'],
                            'barcode' => $order['barcode'],
                            'total_price' => $order['total_price'],
                            'discount_percent' => $order['discount_percent'],
                            'warehouse_name' => $order['warehouse_name'],
                            'oblast' => $order['oblast'],
                            'income_id' => $order['income_id'],
                            'odid' => $order['odid'],
                            'nm_id' => $order['nm_id'],
                            'subject' => $order['subject'],
                            'category' => $order['category'],
                            'brand' => $order['brand'],
                            'is_cancel' => $order['is_cancel'],
                            'cancel_dt' => $order['cancel_dt'],
                        ]
                    );
                }

                return response()->json(['message' => 'Orders data fetched and stored successfully.']);
            }

            return response()->json(['error' => 'Failed to fetch orders data.'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching data: ' . $e->getMessage()], 500);
        }
    }
}
