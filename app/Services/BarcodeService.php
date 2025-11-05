<?php

namespace App\Services;

use Picqer\Barcode\BarcodeGeneratorPNG;
use Picqer\Barcode\BarcodeGeneratorHTML;
use Picqer\Barcode\BarcodeGeneratorSVG;

class BarcodeService
{
    /**
     * Generate barcode as PNG image
     *
     * @param string $code
     * @param string $type (TYPE_CODE_128, TYPE_EAN_13, etc)
     * @param int $width
     * @param int $height
     * @return string Base64 encoded image
     */
    public function generatePNG(string $code, string $type = 'TYPE_CODE_128', int $width = 2, int $height = 50): string
    {
        $generator = new BarcodeGeneratorPNG();
        
        // Map string type to constant
        $barcodeType = constant('Picqer\\Barcode\\BarcodeGenerator::' . $type);
        
        $barcode = $generator->getBarcode($code, $barcodeType, $width, $height);
        
        return 'data:image/png;base64,' . base64_encode($barcode);
    }

    /**
     * Generate barcode as SVG
     *
     * @param string $code
     * @param string $type
     * @param int $width
     * @param int $height
     * @param string $color
     * @return string SVG string
     */
    public function generateSVG(string $code, string $type = 'TYPE_CODE_128', int $width = 2, int $height = 50, string $color = 'black'): string
    {
        $generator = new BarcodeGeneratorSVG();
        
        // Map string type to constant
        $barcodeType = constant('Picqer\\Barcode\\BarcodeGenerator::' . $type);
        
        return $generator->getBarcode($code, $barcodeType, $width, $height, $color);
    }

    /**
     * Generate barcode as HTML
     *
     * @param string $code
     * @param string $type
     * @param int $width
     * @param int $height
     * @return string HTML string
     */
    public function generateHTML(string $code, string $type = 'TYPE_CODE_128', int $width = 2, int $height = 50): string
    {
        $generator = new BarcodeGeneratorHTML();
        
        // Map string type to constant
        $barcodeType = constant('Picqer\\Barcode\\BarcodeGenerator::' . $type);
        
        return $generator->getBarcode($code, $barcodeType, $width, $height);
    }

    /**
     * Generate barcode for product
     * Auto-generate barcode if product doesn't have one
     *
     * @param \App\Models\Product $product
     * @param string $format (png, svg, html)
     * @return string
     */
    public function generateForProduct($product, string $format = 'png'): string
    {
        // Use existing barcode or generate one
        $code = $product->barcode ?: $this->generateProductBarcode($product);
        
        // Update product if barcode was generated
        if (!$product->barcode) {
            $product->update(['barcode' => $code]);
        }
        
        return match($format) {
            'svg' => $this->generateSVG($code),
            'html' => $this->generateHTML($code),
            default => $this->generatePNG($code),
        };
    }

    /**
     * Generate unique barcode for product
     * Format: 89 + product_id (padded to 11 digits with checksum)
     *
     * @param \App\Models\Product $product
     * @return string
     */
    private function generateProductBarcode($product): string
    {
        // Generate EAN-13 compatible barcode
        // Format: 89 (country code for custom) + 10 digits product id + 1 checksum
        $productId = str_pad($product->id, 10, '0', STR_PAD_LEFT);
        $code = '89' . $productId;
        
        // Calculate EAN-13 checksum
        $checksum = $this->calculateEAN13Checksum($code);
        
        return $code . $checksum;
    }

    /**
     * Calculate EAN-13 checksum digit
     *
     * @param string $code (12 digits)
     * @return int
     */
    private function calculateEAN13Checksum(string $code): int
    {
        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $digit = (int) $code[$i];
            $sum += ($i % 2 === 0) ? $digit : $digit * 3;
        }
        $checksum = (10 - ($sum % 10)) % 10;
        return $checksum;
    }

    /**
     * Validate EAN-13 barcode
     *
     * @param string $barcode
     * @return bool
     */
    public function validateEAN13(string $barcode): bool
    {
        if (strlen($barcode) !== 13 || !ctype_digit($barcode)) {
            return false;
        }
        
        $code = substr($barcode, 0, 12);
        $checksum = (int) substr($barcode, 12, 1);
        
        return $this->calculateEAN13Checksum($code) === $checksum;
    }

    /**
     * Get available barcode types
     *
     * @return array
     */
    public function getAvailableTypes(): array
    {
        return [
            'TYPE_CODE_128' => 'Code 128 (Recommended)',
            'TYPE_EAN_13' => 'EAN-13 (International)',
            'TYPE_CODE_39' => 'Code 39',
            'TYPE_CODE_93' => 'Code 93',
            'TYPE_UPC_A' => 'UPC-A',
        ];
    }
}
