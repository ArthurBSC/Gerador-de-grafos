<?php

namespace App\Utils;

/**
 * Utilitário para geração e manipulação de cores
 * 
 * Responsabilidades:
 * - Gerar cores aleatórias harmoniosas
 * - Paletas de cores predefinidas
 * - Conversões entre formatos de cor
 * - Validação de cores
 */
class GeradorCores
{
    /**
     * Paleta principal de cores para nós
     */
    private const CORES_PRINCIPAIS = [
        '#3498db', // Azul
        '#e74c3c', // Vermelho
        '#2ecc71', // Verde
        '#f39c12', // Laranja
        '#9b59b6', // Roxo
        '#1abc9c', // Verde-azulado
        '#34495e', // Azul escuro
        '#16a085', // Verde-azulado escuro
        '#27ae60', // Verde escuro
        '#2980b9', // Azul médio
        '#8e44ad', // Roxo escuro
        '#f1c40f', // Amarelo
        '#e67e22', // Laranja escuro
        '#95a5a6', // Cinza
        '#7f8c8d'  // Cinza escuro
    ];

    /**
     * Cores para diferentes tipos de peso de arestas
     */
    private const CORES_ARESTAS = [
        'positiva' => '#2ecc71',  // Verde
        'negativa' => '#e74c3c',  // Vermelho
        'neutra' => '#95a5a6',    // Cinza
        'padrao' => '#3498db'     // Azul
    ];

    /**
     * Paletas temáticas
     */
    private const PALETAS_TEMATICAS = [
        'oceano' => ['#3498db', '#2980b9', '#1abc9c', '#16a085', '#34495e'],
        'floresta' => ['#2ecc71', '#27ae60', '#f39c12', '#e67e22', '#8e44ad'],
        'sunset' => ['#e74c3c', '#f39c12', '#f1c40f', '#e67e22', '#d35400'],
        'profissional' => ['#34495e', '#2c3e50', '#95a5a6', '#7f8c8d', '#bdc3c7'],
        'vibrante' => ['#e74c3c', '#f39c12', '#2ecc71', '#3498db', '#9b59b6']
    ];

    /**
     * Obtém uma cor aleatória da paleta principal
     */
    public function obterCorAleatoria(): string
    {
        return self::CORES_PRINCIPAIS[array_rand(self::CORES_PRINCIPAIS)];
    }

    /**
     * Obtém uma sequência de cores harmoniosas
     */
    public function obterSequenciaCores(int $quantidade): array
    {
        if ($quantidade <= count(self::CORES_PRINCIPAIS)) {
            return array_slice(self::CORES_PRINCIPAIS, 0, $quantidade);
        }

        // Se precisar de mais cores, gerar cores adicionais
        $cores = self::CORES_PRINCIPAIS;
        $coresAdicionais = $quantidade - count(self::CORES_PRINCIPAIS);

        for ($i = 0; $i < $coresAdicionais; $i++) {
            $cores[] = $this->gerarCorHarmoniosa($cores);
        }

        return array_slice($cores, 0, $quantidade);
    }

    /**
     * Obtém cor específica para tipo de aresta
     */
    public function obterCorAresta(string $tipo): string
    {
        return self::CORES_ARESTAS[$tipo] ?? self::CORES_ARESTAS['padrao'];
    }

    /**
     * Obtém cor baseada no peso da aresta
     */
    public function obterCorPorPeso(int $peso): string
    {
        return match($peso) {
            1 => self::CORES_ARESTAS['positiva'],
            -1 => self::CORES_ARESTAS['negativa'],
            0 => self::CORES_ARESTAS['neutra'],
            default => self::CORES_ARESTAS['padrao']
        };
    }

    /**
     * Obtém paleta temática específica
     */
    public function obterPaletaTematica(string $tema): array
    {
        return self::PALETAS_TEMATICAS[$tema] ?? self::CORES_PRINCIPAIS;
    }

    /**
     * Lista todas as paletas disponíveis
     */
    public function listarPaletasDisponiveis(): array
    {
        return array_keys(self::PALETAS_TEMATICAS);
    }

    /**
     * Gera uma cor harmoniosa baseada em cores existentes
     */
    public function gerarCorHarmoniosa(array $coresExistentes): string
    {
        // Algoritmo simples para gerar cores harmoniosas
        $baseHue = $this->extrairMatizMedia($coresExistentes);
        $novoHue = ($baseHue + 30 + rand(-15, 15)) % 360;
        
        return $this->hslParaHex($novoHue, 70, 50);
    }

    /**
     * Gera gradiente entre duas cores
     */
    public function gerarGradiente(string $corInicial, string $corFinal, int $passos): array
    {
        $hslInicial = $this->hexParaHsl($corInicial);
        $hslFinal = $this->hexParaHsl($corFinal);
        
        $gradiente = [];
        
        for ($i = 0; $i < $passos; $i++) {
            $proporcao = $i / ($passos - 1);
            
            $h = $hslInicial[0] + ($hslFinal[0] - $hslInicial[0]) * $proporcao;
            $s = $hslInicial[1] + ($hslFinal[1] - $hslInicial[1]) * $proporcao;
            $l = $hslInicial[2] + ($hslFinal[2] - $hslInicial[2]) * $proporcao;
            
            $gradiente[] = $this->hslParaHex($h, $s, $l);
        }
        
        return $gradiente;
    }

    /**
     * Clareia uma cor
     */
    public function clarearCor(string $cor, float $fator = 0.2): string
    {
        $hsl = $this->hexParaHsl($cor);
        $hsl[2] = min(100, $hsl[2] + ($fator * 100));
        
        return $this->hslParaHex($hsl[0], $hsl[1], $hsl[2]);
    }

    /**
     * Escurece uma cor
     */
    public function escurecerCor(string $cor, float $fator = 0.2): string
    {
        $hsl = $this->hexParaHsl($cor);
        $hsl[2] = max(0, $hsl[2] - ($fator * 100));
        
        return $this->hslParaHex($hsl[0], $hsl[1], $hsl[2]);
    }

    /**
     * Verifica se uma cor é válida (formato hexadecimal)
     */
    public function ehCorValida(string $cor): bool
    {
        return preg_match('/^#[0-9A-Fa-f]{6}$/', $cor) === 1;
    }

    /**
     * Calcula contraste entre duas cores
     */
    public function calcularContraste(string $cor1, string $cor2): float
    {
        $luminancia1 = $this->calcularLuminancia($cor1);
        $luminancia2 = $this->calcularLuminancia($cor2);
        
        $clara = max($luminancia1, $luminancia2);
        $escura = min($luminancia1, $luminancia2);
        
        return ($clara + 0.05) / ($escura + 0.05);
    }

    /**
     * Encontra cor de texto ideal (preto ou branco) para uma cor de fundo
     */
    public function obterCorTextoIdeal(string $corFundo): string
    {
        $contrasteBranco = $this->calcularContraste($corFundo, '#ffffff');
        $contrastePreto = $this->calcularContraste($corFundo, '#000000');
        
        return $contrasteBranco > $contrastePreto ? '#ffffff' : '#000000';
    }

    /**
     * Converte cor hexadecimal para HSL
     */
    private function hexParaHsl(string $hex): array
    {
        $hex = ltrim($hex, '#');
        
        $r = hexdec(substr($hex, 0, 2)) / 255;
        $g = hexdec(substr($hex, 2, 2)) / 255;
        $b = hexdec(substr($hex, 4, 2)) / 255;
        
        $max = max($r, $g, $b);
        $min = min($r, $g, $b);
        $diff = $max - $min;
        
        // Luminância
        $l = ($max + $min) / 2;
        
        if ($diff === 0) {
            $h = $s = 0; // Cinza
        } else {
            // Saturação
            $s = $l > 0.5 ? $diff / (2 - $max - $min) : $diff / ($max + $min);
            
            // Matiz
            switch ($max) {
                case $r:
                    $h = (($g - $b) / $diff) + ($g < $b ? 6 : 0);
                    break;
                case $g:
                    $h = ($b - $r) / $diff + 2;
                    break;
                case $b:
                    $h = ($r - $g) / $diff + 4;
                    break;
            }
            $h /= 6;
        }
        
        return [$h * 360, $s * 100, $l * 100];
    }

    /**
     * Converte HSL para cor hexadecimal
     */
    private function hslParaHex(float $h, float $s, float $l): string
    {
        $h /= 360;
        $s /= 100;
        $l /= 100;
        
        if ($s === 0) {
            $r = $g = $b = $l; // Cinza
        } else {
            $hue2rgb = function($p, $q, $t) {
                if ($t < 0) $t += 1;
                if ($t > 1) $t -= 1;
                if ($t < 1/6) return $p + ($q - $p) * 6 * $t;
                if ($t < 1/2) return $q;
                if ($t < 2/3) return $p + ($q - $p) * (2/3 - $t) * 6;
                return $p;
            };
            
            $q = $l < 0.5 ? $l * (1 + $s) : $l + $s - $l * $s;
            $p = 2 * $l - $q;
            
            $r = $hue2rgb($p, $q, $h + 1/3);
            $g = $hue2rgb($p, $q, $h);
            $b = $hue2rgb($p, $q, $h - 1/3);
        }
        
        return sprintf('#%02x%02x%02x', 
                      round($r * 255), 
                      round($g * 255), 
                      round($b * 255));
    }

    /**
     * Extrai matiz média de um array de cores
     */
    private function extrairMatizMedia(array $cores): float
    {
        if (empty($cores)) {
            return 0;
        }
        
        $matizesValidos = [];
        
        foreach ($cores as $cor) {
            if ($this->ehCorValida($cor)) {
                $hsl = $this->hexParaHsl($cor);
                $matizesValidos[] = $hsl[0];
            }
        }
        
        return empty($matizesValidos) ? 0 : array_sum($matizesValidos) / count($matizesValidos);
    }

    /**
     * Calcula luminância relativa de uma cor
     */
    private function calcularLuminancia(string $hex): float
    {
        $hex = ltrim($hex, '#');
        
        $r = hexdec(substr($hex, 0, 2)) / 255;
        $g = hexdec(substr($hex, 2, 2)) / 255;
        $b = hexdec(substr($hex, 4, 2)) / 255;
        
        // Aplicar correção gamma
        $r = $r <= 0.03928 ? $r / 12.92 : pow(($r + 0.055) / 1.055, 2.4);
        $g = $g <= 0.03928 ? $g / 12.92 : pow(($g + 0.055) / 1.055, 2.4);
        $b = $b <= 0.03928 ? $b / 12.92 : pow(($b + 0.055) / 1.055, 2.4);
        
        return 0.2126 * $r + 0.7152 * $g + 0.0722 * $b;
    }
}
