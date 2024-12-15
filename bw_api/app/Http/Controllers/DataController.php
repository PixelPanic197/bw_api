<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Order;
use App\Models\Stock;

class DataController extends Controller
{
    /**
     * Получить записи из таблицы Sales с фильтрацией по дате.
     */
    public function getSales(Request $request)
    {
        return $this->fetchData(Sale::query(), $request);
    }

    /**
     * Получить записи из таблицы Orders с фильтрацией по дате.
     */
    public function getOrders(Request $request)
    {
        return $this->fetchData(Order::query(), $request);
    }

    /**
     * Получить записи из таблицы Stock с фильтрацией по дате.
     */
    public function getStock(Request $request)
    {
        return $this->fetchData(Stock::query(), $request);
    }

    /**
     * Универсальный метод для фильтрации по дате и пагинации.
     */
    private function fetchData($query, Request $request)
    {
        $dateFrom = $request->input('dateFrom'); // Дата начала
        $dateTo = $request->input('dateTo');     // Дата конца
        $page = $request->input('page', 1);      // Номер страницы
        $limit = $request->input('limit', 10);   // Лимит записей на страницу

        try {
            // Фильтрация по дате, если переданы параметры
            if ($dateFrom) {
                $query->where('created_at', '>=', $dateFrom);
            }
            if ($dateTo) {
                $query->where('created_at', '<=', $dateTo);
            }

            // Пагинация
            $data = $query->paginate($limit, ['*'], 'page', $page);

            // Возвращаем результат
            return response()->json([
                'data' => $data->items(),
                'meta' => [
                    'current_page' => $data->currentPage(),
                    'last_page' => $data->lastPage(),
                    'total' => $data->total(),
                    'per_page' => $data->perPage(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
