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
        
        // Register namespaces
        $xml = new SimpleXMLElement($xmlContent);
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
            return $xml->getName() === 'Comprobante';
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Extract UUID from TimbreFiscalDigital
     */
    private function extractUuid(SimpleXMLElement $xml): ?string
    {
        // Try CFDI 4.0 namespace
        $tfd = $xml->xpath('//tfd:TimbreFiscalDigital');
        
        if (!empty($tfd)) {
            $attributes = $tfd[0]->attributes();
            return (string) $attributes['UUID'];
        }

        return null;
    }

    /**
     * Extract emission date
     */
    private function extractEmissionDate(SimpleXMLElement $xml): ?string
    {
        $attributes = $xml->attributes();
        return isset($attributes['Fecha']) ? (string) $attributes['Fecha'] : null;
    }

    /**
     * Extract issuer RFC
     */
    private function extractIssuerRfc(SimpleXMLElement $xml): ?string
    {
        // Try CFDI 4.0
        $xml->registerXPathNamespace('cfdi', 'http://www.sat.gob.mx/cfd/4');
        $emisor = $xml->xpath('//cfdi:Emisor');
        
        if (!empty($emisor)) {
            $attributes = $emisor[0]->attributes();
            return (string) $attributes['Rfc'];
        }

        // Try CFDI 3.3
        $xml->registerXPathNamespace('cfdi3', 'http://www.sat.gob.mx/cfd/3');
        $emisor = $xml->xpath('//cfdi3:Emisor');
        
        if (!empty($emisor)) {
            $attributes = $emisor[0]->attributes();
            return (string) $attributes['Rfc'];
        }

        return null;
    }

    /**
     * Extract receiver RFC
     */
    private function extractReceiverRfc(SimpleXMLElement $xml): ?string
    {
        // Try CFDI 4.0
        $xml->registerXPathNamespace('cfdi', 'http://www.sat.gob.mx/cfd/4');
        $receptor = $xml->xpath('//cfdi:Receptor');
        
        if (!empty($receptor)) {
            $attributes = $receptor[0]->attributes();
            return (string) $attributes['Rfc'];
        }

        // Try CFDI 3.3
        $xml->registerXPathNamespace('cfdi3', 'http://www.sat.gob.mx/cfd/3');
        $receptor = $xml->xpath('//cfdi3:Receptor');
        
        if (!empty($receptor)) {
            $attributes = $receptor[0]->attributes();
            return (string) $attributes['Rfc'];
        }

        return null;
    }

    /**
     * Extract total amount
     */
    private function extractTotal(SimpleXMLElement $xml): ?float
    {
        $attributes = $xml->attributes();
        return isset($attributes['Total']) ? (float) $attributes['Total'] : null;
    }

    /**
     * Extract tax amount
     */
    private function extractTaxAmount(SimpleXMLElement $xml): float
    {
        // Try CFDI 4.0
        $xml->registerXPathNamespace('cfdi', 'http://www.sat.gob.mx/cfd/4');
        $impuestos = $xml->xpath('//cfdi:Impuestos');
        
        if (!empty($impuestos)) {
            $attributes = $impuestos[0]->attributes();
            if (isset($attributes['TotalImpuestosTrasladados'])) {
                return (float) $attributes['TotalImpuestosTrasladados'];
            }
        }

        // Try CFDI 3.3
        $xml->registerXPathNamespace('cfdi3', 'http://www.sat.gob.mx/cfd/3');
        $impuestos = $xml->xpath('//cfdi3:Impuestos');
        
        if (!empty($impuestos)) {
            $attributes = $impuestos[0]->attributes();
            if (isset($attributes['TotalImpuestosTrasladados'])) {
                return (float) $attributes['TotalImpuestosTrasladados'];
            }
        }

        return 0.0;
    }

    /**
     * Extract CFDI type
     */
    private function extractType(SimpleXMLElement $xml): ?string
    {
        $attributes = $xml->attributes();
        if (!isset($attributes['TipoDeComprobante'])) {
            return null;
        }

        $tipo = (string) $attributes['TipoDeComprobante'];
        
        // Map SAT codes to readable names
        return match($tipo) {
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
        return isset($attributes['MetodoPago']) ? (string) $attributes['MetodoPago'] : null;
    }

    /**
     * Extract payment form
     */
    private function extractPaymentForm(SimpleXMLElement $xml): ?string
    {
        $attributes = $xml->attributes();
        return isset($attributes['FormaPago']) ? (string) $attributes['FormaPago'] : null;
    }

    /**
     * Extract currency
     */
    private function extractCurrency(SimpleXMLElement $xml): string
    {
        $attributes = $xml->attributes();
        return isset($attributes['Moneda']) ? (string) $attributes['Moneda'] : 'MXN';
    }
}
