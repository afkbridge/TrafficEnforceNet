<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class OcrSpaceService
{
    public function extractText(UploadedFile $image): string
    {
        $apiKey = config('services.ocr_space.api_key');

        if (empty($apiKey)) {
            throw new RuntimeException(
                'OCR.space API key is missing.'
            );
        }

        if (!$image->isValid()) {
            throw new RuntimeException(
                'The uploaded image is invalid.'
            );
        }

        $response = Http::timeout(90)
            ->withHeaders([
                'apikey' => trim($apiKey),
                'Accept' => 'application/json',
            ])
            ->attach(
                'file',
                file_get_contents($image->getRealPath()),
                $image->getClientOriginalName()
            )
            ->post('https://api.ocr.space/parse/image', [
                'language' => 'eng',
                'isOverlayRequired' => 'false',
                'OCREngine' => '1',
                'scale' => 'true',
            ]);

        if (!$response->successful()) {
            $body = trim($response->body());

            throw new RuntimeException(
                'OCR.space request failed: HTTP ' .
                $response->status() .
                ($body !== '' ? ' - ' . $body : '')
            );
        }

        $data = $response->json();

        if (!is_array($data)) {
            throw new RuntimeException(
                'OCR.space returned an invalid response.'
            );
        }

        if (!empty($data['IsErroredOnProcessing'])) {
            $message = $data['ErrorMessage']
                ?? $data['ErrorDetails']
                ?? 'Unknown OCR.space processing error.';

            if (is_array($message)) {
                $message = implode(', ', $message);
            }

            throw new RuntimeException(
                'OCR.space processing error: ' . $message
            );
        }

        if (empty($data['ParsedResults'])) {
            throw new RuntimeException(
                'OCR.space returned no OCR results.'
            );
        }

        $text = $data['ParsedResults'][0]['ParsedText'] ?? '';

        return trim($text);
    }
}