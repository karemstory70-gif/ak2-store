<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return DB::transaction(function () use ($record, $data): Order {
            $order = Order::query()->whereKey($record->getKey())->lockForUpdate()->firstOrFail();

            if ($order->status === 'cancelled' && ($data['status'] ?? null) !== 'cancelled') {
                throw new UnprocessableEntityHttpException('A cancelled order cannot be reopened.');
            }

            if ($order->status !== 'cancelled' && ($data['status'] ?? null) === 'cancelled') {
                $order->load('items');

                foreach ($order->items as $item) {
                    if ($item->product_variant_id) {
                        $variant = ProductVariant::query()
                            ->whereKey($item->product_variant_id)
                            ->lockForUpdate()
                            ->first();

                        $variant?->increment('stock_quantity', $item->quantity);
                    } elseif ($item->product_id) {
                        $product = Product::query()
                            ->whereKey($item->product_id)
                            ->lockForUpdate()
                            ->first();

                        $product?->increment('stock_quantity', $item->quantity);
                    }
                }
            }

            $order->update(Arr::only($data, ['status', 'payment_status', 'admin_notes']));

            return $order->refresh();
        });
    }
}
