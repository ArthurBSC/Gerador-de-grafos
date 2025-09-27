<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configurações do Sistema Gerador de Grafos
    |--------------------------------------------------------------------------
    |
    | Configurações específicas do sistema de geração e visualização de grafos
    |
    */

    'versao' => env('SISTEMA_VERSAO', '2.0.0'),
    'arquitetura' => env('SISTEMA_ARQUITETURA', 'Clean Code + SOLID + Laravel 9'),
    'autor' => env('SISTEMA_AUTOR', 'Estrutura de Dados II'),

    /*
    |--------------------------------------------------------------------------
    | Limites do Sistema
    |--------------------------------------------------------------------------
    */

    'limites' => [
        'max_nos' => env('GRAFO_MAX_NOS', 50),
        'max_arestas' => env('GRAFO_MAX_ARESTAS', 500),
        'max_peso' => 1000,
        'min_peso' => -1000,
    ],

    /*
    |--------------------------------------------------------------------------
    | Configurações de Visualização
    |--------------------------------------------------------------------------
    */

    'visualizacao' => [
        'cores_padrao' => env('GRAFO_CORES_PADRAO', true),
        'animacoes' => env('GRAFO_ANIMACOES', true),
        'tamanho_no_padrao' => 20,
        'largura_aresta_padrao' => 2,
        'fisica_habilitada' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Configurações de Exportação
    |--------------------------------------------------------------------------
    */

    'exportacao' => [
        'formatos' => explode(',', env('EXPORTACAO_FORMATOS', 'json,csv,txt')),
        'tamanho_maximo' => env('EXPORTACAO_MAX_SIZE', '10MB'),
        'incluir_metadados' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Configurações de Performance
    |--------------------------------------------------------------------------
    */

    'performance' => [
        'paginacao_padrao' => 15,
    ],


    /*
    |--------------------------------------------------------------------------
    | Paletas de Cores
    |--------------------------------------------------------------------------
    */

    'paletas' => [
        'padrao' => [
            '#3498db', '#e74c3c', '#2ecc71', '#f39c12', '#9b59b6',
            '#1abc9c', '#34495e', '#e67e22', '#95a5a6', '#f1c40f'
        ],
        'pastel' => [
            '#ff9ff3', '#54a0ff', '#5f27cd', '#00d2d3', '#ff9f43',
            '#10ac84', '#ee5a24', '#0984e3', '#a29bfe', '#fd79a8'
        ],
        'escuro' => [
            '#2c2c54', '#40407a', '#706fd3', '#f8b500', '#ff5252',
            '#4ecdc4', '#45b7d1', '#96ceb4', '#feca57', '#ff9ff3'
        ]
    ],

];
