<?php

namespace Planogolny\Invoicing\Services;

use Illuminate\Support\Facades\Http;
use Planogolny\Invoicing\DTO\InvoiceRequestDTO;
use Planogolny\Invoicing\DTO\InvoiceDTO;

final class IngInvoiceApi
{
    private function client()
    {
        return Http::withHeaders([
            'ApiUserCompanyRoleKey' => config('ing.api_key'),
            'Accept' => 'application/json',
        ]);
    }

    /**
     * CREATE INVOICE (B2C)
     */
    public function createInvoice(InvoiceRequestDTO $dto): string
    {
        if (config('ing.env') === 'dev') {
            return 'ING-INVOICE-' . $dto->orderId;
        }
        $payload = [
            'positions' => [
                'name' => $dto->description,
                'code' => 'string',
                'quantity' => 1,
                'unit' => 'string',
                'net' => $dto->amount / 1,23,
                'tax' => round(($dto->amount / 100)*23, 0),
                'gross' => $dto->amount,
                'taxStake' => 'TAX_23',
            ],
            'payment' => [
                'deadlineDate' => now()->toDateString(),
                'method' => 'TRANSFER',
                'bankAccounts'=> [
                    'accountNumber' => config('ing.account_number'),
                ]
            ],
            'buyer' => [
                "email" => $dto->buyerEmail,
                "fullName" => $dto->buyerName,
                "addressStreet" => $dto->buyerAddressStreet,
                "city" => $dto->buyerCity,
                "postCode" => $dto->buyerPostCode,
                "countryCode" => 'PL',
                "taxNumber" => $dto->buyerTaxNumber,
                "taxCountryCode" => 'PL'
            ]
        ];
        info('ING INVOICE PAYLOAD', [
            'payload' => $payload,
        ]);
        $response = $this->client()->post(
            config('ing.base_url') . '/create-invoice',
            [
                'positions' => [
                    'name' => $dto->description,
                    'code' => 'string',
                    'quantity' => 1,
                    'unit' => 'string',
                    'net' => $dto->amount / 1,23,
                    'tax' => round(($dto->amount / 100)*23, 0),
                    'gross' => $dto->amount,
                    'taxStake' => 'TAX_23',
                ],
                'payment' => [
                    'deadlineDate' => now()->toDateString(),
                    'method' => 'TRANSFER',
                    'bankAccounts'=> [
                        'accountNumber' => config('ing.account_number'),
                    ]
                ],
                'buyer' => [
                    "email" => $dto->buyerEmail,
                    "fullName" => $dto->buyerName,
                    "addressStreet" => $dto->buyerAddressStreet,
                    "city" => $dto->buyerCity,
                    "postCode" => $dto->buyerPostCode,
                    "countryCode" => 'PL',
                    "taxNumber" => $dto->buyerTaxNumber,
                    "taxCountryCode" => 'PL'
                ]
            ]
        );

        info('ING INVOICE RESPONSE', [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        if (! $response->successful()) {
            throw new \RuntimeException(
                'ING create invoice failed: ' . $response->body()
            );
        }

        return $response->json('invoiceNumber');
    }

    /**
     * DOWNLOAD INVOICE PDF
     */
    public function downloadInvoicePdf(string $invoiceId): InvoiceDTO
    {
        if (config('ing.env') === 'dev') {
            return new InvoiceDTO(
                invoiceNumber: $invoiceId,
                pdfBinary: '%PDF-1.4 FAKE PDF CONTENT%'
            );
        }

        $response = $this->client()
            ->accept('application/pdf')
            ->get(
                config('ing.base_url') . "/download-invoice/{$invoiceId}/pdf"
            );

        if (! $response->successful()) {
            throw new \RuntimeException(
                'ING invoice PDF download failed: ' . $response->body()
            );
        }

        return new InvoiceDTO(
            invoiceNumber: $invoiceId,
            pdfBinary: $response->body()
        );
    }
}
