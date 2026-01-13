<?php

namespace Planogolny\Orders\Models;

use Illuminate\Database\Eloquent\Model;
class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
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

    protected $casts = [
        'amount' => 'int',
        'invoice_data' => 'array',
    ];
}
