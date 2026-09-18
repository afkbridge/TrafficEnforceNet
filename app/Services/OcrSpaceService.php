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

        $response = Http::timeout(60)
            ->withHeaders([
                'apikey' => $apiKey,
            ])
            ->attach(
                'file',
                file_get_contents($image->getRealPath()),
                $image->getClientOriginalName()
            )
            ->post('https://api.ocr.space/parse/image', [
                'language' => 'eng',
                'isOverlayRequired' => 'false',
                'OCREngine' => '2',
            ]);

        if (!$response->successful()) {
            throw new RuntimeException(
                'OCR.space request failed: ' . $response->status()
            );
        }

        $data = $response->json();

        if (!empty($data['IsErroredOnProcessing'])) {
            $message = $data['ErrorMessage']
                ?? 'Unknown OCR.space error.';

            if (is_array($message)) {
                $message = implode(', ', $message);
            }

            throw new RuntimeException($message);
        }

        return trim(
            $data['ParsedResults'][0]['ParsedText'] ?? ''
        );
    }
}