<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class orderdata extends Controller
{
 
public function index($dateFrom, $dateTo)
{
    $response = Http::get("{$this->host}/api/orders", [
        'dateFrom' => $dateFrom,
        'dateTo' => $dateTo,
        'page' => 1,
        'limit' => 500,
        'key' => $this->apiKey,
    ]);

    if ($response->ok()) {
        $data = $response->json()['data'];

        foreach ($data as $order) {
            Order::updateOrCreate(
                ['g_number' => $order['g_number']], // Уникальный ключ
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
}
}
