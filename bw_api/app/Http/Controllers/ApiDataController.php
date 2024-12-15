<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // Для работы с HTTP запросами
use App\Models\Sale; // Подключаем модель Sale

class ApiDataController extends Controller
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
            $response = Http::get("{$this->host}/api/sales", [
                'dateFrom' => $dateFrom,
                'dateTo' => $dateTo,
                'page' => $page,
                'limit' => $limit,
                'key' => $this->apiKey,
            ]);

            // Проверяем успешность ответа
            if ($response->ok()) {
                $data = $response->json()['data'];

                foreach ($data as $sale) {
                    // Сохраняем или обновляем данные о продаже в базе данных
                    Sale::updateOrCreate(
                        ['g_number' => $sale['g_number']], // Уникальный идентификатор (g_number)
                        [
                            'date' => $sale['date'],
                            'supplier_article' => $sale['supplier_article'],
                            'tech_size' => $sale['tech_size'],
                            'barcode' => $sale['barcode'],
                            'total_price' => $sale['total_price'],
                            'discount_percent' => $sale['discount_percent'],
                            'is_supply' => $sale['is_supply'],
                            'is_realization' => $sale['is_realization'],
                            'warehouse_name' => $sale['warehouse_name'],
                            'country_name' => $sale['country_name'],
                            'region_name' => $sale['region_name'],
                            'income_id' => $sale['income_id'],
                            'sale_id' => $sale['sale_id'],
                            'subject' => $sale['subject'],
                            'brand' => $sale['brand']
                        ]
                    );
                }

                return response()->json(['message' => 'Sales data fetched and stored successfully.']);
            }

            return response()->json(['error' => 'Failed to fetch sales data.'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching data: ' . $e->getMessage()], 500);
        }
    }
    
}
