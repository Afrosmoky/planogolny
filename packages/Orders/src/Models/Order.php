<?php

namespace Planogolny\Orders\Models;

use App\Models\Analysis;
use Illuminate\Database\Eloquent\Model;
use Planogolny\Orders\Enums\OrderStatus;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'analysis_id',
        'report_number',
        'email',
        'address_text',
        'amount',
        'currency',
        'payment_provider',
        'external_payment_id',
        'status',
        'invoice_data',
        'invoice_type',
    ];

    public function hasInvoiceData(): bool
    {
        return ! empty($this->invoice_data);
    }

    public function isPaid(): bool
    {
        return in_array($this->status, [
            OrderStatus::PAID,
            OrderStatus::COMPLETED,
        ], true);
    }

    protected $casts = [
        'invoice_data' => 'array',
    ];

    public function analysis()
    {
        return $this->belongsTo(Analysis::class);
    }
}
