<?php

namespace App\Services;

use SimpleXMLElement;
use Exception;

class CfdiParserService
{
    /**
     * Parse CFDI XML file and extract data
     *
     * @param string $xmlPath Path to the XML file
     * @return array Extracted CFDI data
     * @throws Exception
     */
    public function parseXml(string $xmlPath): array
    {
        if (!file_exists($xmlPath)) {
            throw new Exception("XML file not found: {$xmlPath}");
        }

        $xmlContent = file_get_contents($xmlPath);
        
        try {
            $xml = new SimpleXMLElement($xmlContent);
        } catch (Exception $e) {
            throw new Exception("Error parsing XML: " . $e->getMessage());
        }

        // Register namespaces for all supported versions
        $namespaces = $xml->getDocNamespaces(true);
        foreach ($namespaces as $prefix => $ns) {
            $xml->registerXPathNamespace($prefix ?: 'cfdi', $ns);
        }
        
        // Ensure standard prefixes are available even if not in root
        $xml->registerXPathNamespace('cfdi', 'http://www.sat.gob.mx/cfd/4');
        $xml->registerXPathNamespace('cfdi3', 'http://www.sat.gob.mx/cfd/3');
        $xml->registerXPathNamespace('tfd', 'http://www.sat.gob.mx/TimbreFiscalDigital');

        return [
            'uuid' => $this->extractUuid($xml),
            'emission_date' => $this->extractEmissionDate($xml),
            'issuer_rfc' => $this->extractIssuerRfc($xml),
            'receiver_rfc' => $this->extractReceiverRfc($xml),
            'total' => $this->extractTotal($xml),
            'tax_amount' => $this->extractTaxAmount($xml),
            'type' => $this->extractType($xml),
            'payment_method' => $this->extractPaymentMethod($xml),
            'payment_form' => $this->extractPaymentForm($xml),
            'currency' => $this->extractCurrency($xml),
        ];
    }

    /**
     * Validate XML structure
     *
     * @param string $xmlPath
     * @return bool
     */
    public function validateXml(string $xmlPath): bool
    {
        try {
            if (!file_exists($xmlPath)) {
                return false;
            }

            $xmlContent = file_get_contents($xmlPath);
            $xml = new SimpleXMLElement($xmlContent);
            
            // Check if it's a valid CFDI (has Comprobante root element)
            return str_contains($xml->getName(), 'Comprobante');
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Extract UUID from TimbreFiscalDigital
     */
    private function extractUuid(SimpleXMLElement $xml): ?string
    {
        $tfd = $xml->xpath('//tfd:TimbreFiscalDigital');
        
        if (!empty($tfd)) {
            $attributes = $tfd[0]->attributes();
            return (string) ($attributes['UUID'] ?? $attributes['uuid'] ?? null);
        }

        return null;
    }

    /**
     * Extract emission date
     */
    private function extractEmissionDate(SimpleXMLElement $xml): ?string
    {
        $attributes = $xml->attributes();
        return (string) ($attributes['Fecha'] ?? $attributes['fecha'] ?? null);
    }

    /**
     * Extract issuer RFC
     */
    private function extractIssuerRfc(SimpleXMLElement $xml): ?string
    {
        $emisor = $xml->xpath('//cfdi:Emisor | //cfdi3:Emisor');
        
        if (!empty($emisor)) {
            $attributes = $emisor[0]->attributes();
            return (string) ($attributes['Rfc'] ?? $attributes['rfc'] ?? null);
        }

        return null;
    }

    /**
     * Extract receiver RFC
     */
    private function extractReceiverRfc(SimpleXMLElement $xml): ?string
    {
        $receptor = $xml->xpath('//cfdi:Receptor | //cfdi3:Receptor');
        
        if (!empty($receptor)) {
            $attributes = $receptor[0]->attributes();
            return (string) ($attributes['Rfc'] ?? $attributes['rfc'] ?? null);
        }

        return null;
    }

    /**
     * Extract total amount
     */
    private function extractTotal(SimpleXMLElement $xml): ?float
    {
        $attributes = $xml->attributes();
        return isset($attributes['Total']) ? (float) $attributes['Total'] : (isset($attributes['total']) ? (float) $attributes['total'] : null);
    }

    /**
     * Extract tax amount
     */
    private function extractTaxAmount(SimpleXMLElement $xml): float
    {
        // Try to find the summary total first
        $impuestosFull = $xml->xpath('//cfdi:Impuestos[@TotalImpuestosTrasladados] | //cfdi3:Impuestos[@TotalImpuestosTrasladados]');
        
        if (!empty($impuestosFull)) {
            $attributes = $impuestosFull[0]->attributes();
            return (float) $attributes['TotalImpuestosTrasladados'];
        }

        // If not found, sum up individual taxes
        $traslados = $xml->xpath('//cfdi:Traslado[@Importe] | //cfdi3:Traslado[@Importe]');
        $total = 0.0;
        foreach ($traslados as $traslado) {
            $total += (float) $traslado->attributes()['Importe'];
        }

        return $total;
    }

    /**
     * Extract CFDI type
     */
    private function extractType(SimpleXMLElement $xml): ?string
    {
        $attributes = $xml->attributes();
        $tipo = (string) ($attributes['TipoDeComprobante'] ?? $attributes['tipoDeComprobante'] ?? null);
        
        if (!$tipo) {
            return null;
        }

        // Map SAT codes to readable names
        return match(strtoupper($tipo)) {
            'I' => 'Ingreso',
            'E' => 'Egreso',
            'T' => 'Traslado',
            'N' => 'Nomina',
            'P' => 'Pago',
            default => $tipo,
        };
    }

    /**
     * Extract payment method
     */
    private function extractPaymentMethod(SimpleXMLElement $xml): ?string
    {
        $attributes = $xml->attributes();
        return (string) ($attributes['MetodoPago'] ?? $attributes['metodoPago'] ?? null);
    }

    /**
     * Extract payment form
     */
    private function extractPaymentForm(SimpleXMLElement $xml): ?string
    {
        $attributes = $xml->attributes();
        return (string) ($attributes['FormaPago'] ?? $attributes['formaPago'] ?? null);
    }

    /**
     * Extract currency
     */
    private function extractCurrency(SimpleXMLElement $xml): string
    {
        $attributes = $xml->attributes();
        return (string) ($attributes['Moneda'] ?? $attributes['moneda'] ?? 'MXN');
    }
}

