@extends('layouts.aplicacao')

@section('titulo', 'Meus Grafos')

@push('estilos')
<style>
    /* Estilos específicos da página de listagem */
    
    .btn-minimal-secondary:hover {
        background: #545b62;
        border-color: #545b62;
        color: white;
    }
    
    .btn-minimal-dark {
        background: #212529;
        border-color: #212529;
        color: white;
    }
    
    .btn-minimal-dark:hover {
        background: #1a1e21;
        border-color: #1a1e21;
        color: white;
    }
    
    .btn-minimal-outline {
        background: transparent;
        border-color: #dee2e6;
        color: #495057;
    }
    
    .btn-minimal-outline:hover {
        background: #f8f9fa;
        border-color: #adb5bd;
        color: #212529;
    }
    
    /* Cards Minimalistas */
    .card-minimal {
        background: white;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 20px;
        transition: all 0.2s ease;
        height: 100%;
    }
    
    .card-minimal:hover {
        border-color: #007bff;
        box-shadow: 0 4px 12px rgba(0,123,255,0.1);
    }
    
    /* Badges Minimalistas */
    .badge-minimal {
        font-size: 11px;
        font-weight: 500;
        padding: 4px 8px;
        border-radius: 4px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .badge-minimal-blue {
        background: #e3f2fd;
        color: #1976d2;
    }
    
    .badge-minimal-gray {
        background: #f5f5f5;
        color: #616161;
    }
    
    /* Estatísticas Minimalistas */
    .stat-minimal {
        text-align: center;
        padding: 12px 8px;
    }
    
    .stat-minimal .number {
        font-size: 18px;
        font-weight: 600;
        color: #212529;
        margin: 4px 0;
    }
    
    .stat-minimal .label {
        font-size: 11px;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    /* Ações Minimalistas */
    .actions-minimal {
        display: flex;
        gap: 4px;
    }
    
    .btn-action {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        border: 1px solid #dee2e6;
        background: white;
        color: #6c757d;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        font-size: 14px;
    }
    
    .btn-action:hover {
        color: white;
        border-color: transparent;
    }
    
    .btn-action.view:hover {
        background: #007bff;
    }
    
    .btn-action.edit:hover {
        background: #6c757d;
    }
    
    .btn-action.delete:hover {
        background: #dc3545;
    }
    
    /* Header Minimalista */
    .header-minimal {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 1px solid #e9ecef;
    }
    
    .title-minimal {
        font-size: 24px;
        font-weight: 600;
        color: #212529;
        margin: 0;
    }
    
    .subtitle-minimal {
        font-size: 14px;
        color: #6c757d;
        margin: 4px 0 0 0;
    }
    
    /* Grid Minimalista */
    .grid-minimal {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
    }
    
    /* Estado Vazio Minimalista */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #6c757d;
    }
    
    .empty-state .icon {
        font-size: 48px;
        color: #dee2e6;
        margin-bottom: 16px;
    }
    
    .empty-state h3 {
        font-size: 18px;
        font-weight: 500;
        color: #495057;
        margin-bottom: 8px;
    }
    
    .empty-state p {
        font-size: 14px;
        margin-bottom: 24px;
    }
    
    /* Dark Mode para tela de Meus Grafos */
    [data-theme="dark"] body {
        background: var(--bg-primary) !important;
    }
    
    [data-theme="dark"] .container-principal {
        background: var(--bg-secondary) !important;
        border-color: var(--border-color) !important;
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .cabecalho-sistema {
        background: var(--bg-tertiary) !important;
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .card {
        background: var(--bg-secondary) !important;
        border-color: var(--border-color) !important;
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .card-header {
        background: var(--bg-tertiary) !important;
        border-bottom-color: var(--border-color) !important;
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .card-body {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .card-title {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .card-text {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .list-group-item {
        background: var(--bg-secondary) !important;
        border-color: var(--border-color) !important;
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .list-group-item:hover {
        background: var(--bg-tertiary) !important;
    }
    
    [data-theme="dark"] .badge {
        color: white !important;
    }
    
    [data-theme="dark"] .badge.bg-primary {
        background: var(--cor-primaria) !important;
    }
    
    [data-theme="dark"] .badge.bg-secondary {
        background: var(--text-secondary) !important;
    }
    
    [data-theme="dark"] .badge.bg-success {
        background: var(--cor-sucesso) !important;
    }
    
    [data-theme="dark"] .badge.bg-danger {
        background: var(--cor-perigo) !important;
    }
    
    [data-theme="dark"] .badge.bg-warning {
        background: var(--cor-aviso) !important;
    }
    
    [data-theme="dark"] .badge.bg-info {
        background: var(--cor-info) !important;
    }
    
    [data-theme="dark"] .badge.bg-light {
        background: var(--bg-tertiary) !important;
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .badge.bg-dark {
        background: var(--bg-primary) !important;
    }
    
    [data-theme="dark"] .alert {
        background: var(--bg-tertiary) !important;
        border-color: var(--border-color) !important;
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .alert-primary {
        background: rgba(74, 158, 255, 0.1) !important;
        border-color: var(--cor-primaria) !important;
        color: var(--cor-primaria) !important;
    }
    
    [data-theme="dark"] .alert-secondary {
        background: rgba(160, 160, 160, 0.1) !important;
        border-color: var(--text-secondary) !important;
        color: var(--text-secondary) !important;
    }
    
    [data-theme="dark"] .alert-success {
        background: rgba(78, 222, 128, 0.1) !important;
        border-color: var(--cor-sucesso) !important;
        color: var(--cor-sucesso) !important;
    }
    
    [data-theme="dark"] .alert-danger {
        background: rgba(248, 113, 113, 0.1) !important;
        border-color: var(--cor-perigo) !important;
        color: var(--cor-perigo) !important;
    }
    
    [data-theme="dark"] .alert-warning {
        background: rgba(251, 191, 36, 0.1) !important;
        border-color: var(--cor-aviso) !important;
        color: var(--cor-aviso) !important;
    }
    
    [data-theme="dark"] .alert-info {
        background: rgba(34, 211, 238, 0.1) !important;
        border-color: var(--cor-info) !important;
        color: var(--cor-info) !important;
    }
    
    [data-theme="dark"] .alert-light {
        background: var(--bg-tertiary) !important;
        border-color: var(--border-color) !important;
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .alert-dark {
        background: var(--bg-primary) !important;
        border-color: var(--border-color) !important;
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .btn-minimal-primary {
        background: var(--cor-primaria) !important;
        border-color: var(--cor-primaria) !important;
        color: white !important;
    }
    
    [data-theme="dark"] .btn-minimal-primary:hover {
        background: #3a8eef !important;
        border-color: #3a8eef !important;
        color: white !important;
    }
    
    [data-theme="dark"] .btn-minimal-secondary {
        background: var(--text-secondary) !important;
        border-color: var(--text-secondary) !important;
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .btn-minimal-secondary:hover {
        background: var(--bg-tertiary) !important;
        border-color: var(--bg-tertiary) !important;
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .btn-minimal-outline-primary {
        color: var(--cor-primaria) !important;
        border-color: var(--cor-primaria) !important;
    }
    
    [data-theme="dark"] .btn-minimal-outline-primary:hover {
        background: var(--cor-primaria) !important;
        border-color: var(--cor-primaria) !important;
        color: white !important;
    }
    
    [data-theme="dark"] .btn-minimal-outline-secondary {
        color: var(--text-secondary) !important;
        border-color: var(--border-color) !important;
    }
    
    [data-theme="dark"] .btn-minimal-outline-secondary:hover {
        background: var(--bg-tertiary) !important;
        border-color: var(--bg-tertiary) !important;
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .btn-minimal-outline-danger {
        color: var(--cor-perigo) !important;
        border-color: var(--cor-perigo) !important;
    }
    
    [data-theme="dark"] .btn-minimal-outline-danger:hover {
        background: var(--cor-perigo) !important;
        border-color: var(--cor-perigo) !important;
        color: white !important;
    }
    
    [data-theme="dark"] .btn-minimal-outline-warning {
        color: var(--cor-aviso) !important;
        border-color: var(--cor-aviso) !important;
    }
    
    [data-theme="dark"] .btn-minimal-outline-warning:hover {
        background: var(--cor-aviso) !important;
        border-color: var(--cor-aviso) !important;
        color: white !important;
    }
    
    [data-theme="dark"] .btn-minimal-outline-info {
        color: var(--cor-info) !important;
        border-color: var(--cor-info) !important;
    }
    
    [data-theme="dark"] .btn-minimal-outline-info:hover {
        background: var(--cor-info) !important;
        border-color: var(--cor-info) !important;
        color: white !important;
    }
    
    [data-theme="dark"] .btn-minimal-outline-success {
        color: var(--cor-sucesso) !important;
        border-color: var(--cor-sucesso) !important;
    }
    
    [data-theme="dark"] .btn-minimal-outline-success:hover {
        background: var(--cor-sucesso) !important;
        border-color: var(--cor-sucesso) !important;
        color: white !important;
    }
    
    [data-theme="dark"] .btn-minimal-outline-light {
        color: var(--text-primary) !important;
        border-color: var(--border-color) !important;
    }
    
    [data-theme="dark"] .btn-minimal-outline-light:hover {
        background: var(--bg-tertiary) !important;
        border-color: var(--bg-tertiary) !important;
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .btn-minimal-outline-dark {
        color: var(--text-primary) !important;
        border-color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .btn-minimal-outline-dark:hover {
        background: var(--text-primary) !important;
        border-color: var(--text-primary) !important;
        color: var(--bg-primary) !important;
    }
    
    [data-theme="dark"] .text-muted {
        color: var(--text-muted) !important;
    }
    
    [data-theme="dark"] .text-secondary {
        color: var(--text-secondary) !important;
    }
    
    [data-theme="dark"] .text-primary {
        color: var(--cor-primaria) !important;
    }
    
    [data-theme="dark"] .text-success {
        color: var(--cor-sucesso) !important;
    }
    
    [data-theme="dark"] .text-danger {
        color: var(--cor-perigo) !important;
    }
    
    [data-theme="dark"] .text-warning {
        color: var(--cor-aviso) !important;
    }
    
    [data-theme="dark"] .text-info {
        color: var(--cor-info) !important;
    }
    
    [data-theme="dark"] .text-light {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .text-dark {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .text-white {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .text-black {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .text-body {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .text-body-secondary {
        color: var(--text-secondary) !important;
    }
    
    [data-theme="dark"] .text-body-tertiary {
        color: var(--text-muted) !important;
    }
    
    [data-theme="dark"] .small, [data-theme="dark"] small {
        color: var(--text-secondary) !important;
    }
    
    [data-theme="dark"] .lead {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .fw-bold, [data-theme="dark"] .fw-bolder {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .fw-normal, [data-theme="dark"] .fw-light {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .fw-lighter {
        color: var(--text-secondary) !important;
    }
    
    [data-theme="dark"] .fst-italic {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .fst-normal {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .text-decoration-none {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .text-decoration-underline {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .text-decoration-line-through {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .text-nowrap {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .text-break {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .text-truncate {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .text-wrap {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .text-start {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .text-center {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .text-end {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .text-uppercase {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .text-lowercase {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .text-capitalize {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .lh-1 {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .lh-sm {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .lh-base {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .lh-lg {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .font-monospace {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .user-select-all {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .user-select-auto {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .user-select-none {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .pe-none {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .pe-auto {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .visible {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .invisible {
        color: var(--text-primary) !important;
    }
    
    /* Estilos específicos para cards de grafos */
    [data-theme="dark"] .card-minimal {
        background: var(--bg-secondary) !important;
        border: 1px solid var(--border-color) !important;
        color: var(--text-primary) !important;
        transition: all 0.3s ease !important;
    }
    
    [data-theme="dark"] .card-minimal:hover {
        background: var(--bg-tertiary) !important;
        border-color: var(--cor-primaria) !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3) !important;
    }
    
    [data-theme="dark"] .grafo-card {
        background: var(--bg-secondary) !important;
        border: 1px solid var(--border-color) !important;
        color: var(--text-primary) !important;
        transition: all 0.3s ease !important;
    }
    
    [data-theme="dark"] .grafo-card:hover {
        background: var(--bg-tertiary) !important;
        border-color: var(--cor-primaria) !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3) !important;
    }
    
    [data-theme="dark"] .grafo-card .card-header {
        background: var(--bg-tertiary) !important;
        border-bottom: 1px solid var(--border-color) !important;
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .grafo-card .card-body {
        background: var(--bg-secondary) !important;
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .grafo-card .card-title {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .grafo-card .card-text {
        color: var(--text-secondary) !important;
    }
    
    [data-theme="dark"] .grafo-card .badge {
        color: white !important;
    }
    
    [data-theme="dark"] .grafo-card .btn {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .grafo-card .btn:hover {
        color: var(--cor-primaria) !important;
    }
    
    [data-theme="dark"] .grafo-card .text-muted {
        color: var(--text-muted) !important;
    }
    
    [data-theme="dark"] .grafo-card .text-secondary {
        color: var(--text-secondary) !important;
    }
    
    [data-theme="dark"] .grafo-card .text-primary {
        color: var(--cor-primaria) !important;
    }
    
    [data-theme="dark"] .grafo-card .text-success {
        color: var(--cor-sucesso) !important;
    }
    
    [data-theme="dark"] .grafo-card .text-danger {
        color: var(--cor-perigo) !important;
    }
    
    [data-theme="dark"] .grafo-card .text-warning {
        color: var(--cor-aviso) !important;
    }
    
    [data-theme="dark"] .grafo-card .text-info {
        color: var(--cor-info) !important;
    }
    
    [data-theme="dark"] .grafo-card .text-light {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .grafo-card .text-dark {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .grafo-card .text-white {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .grafo-card .text-black {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .grafo-card .text-body {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .grafo-card .text-body-secondary {
        color: var(--text-secondary) !important;
    }
    
    [data-theme="dark"] .grafo-card .text-body-tertiary {
        color: var(--text-muted) !important;
    }
    
    [data-theme="dark"] .grafo-card .small, [data-theme="dark"] .grafo-card small {
        color: var(--text-secondary) !important;
    }
    
    [data-theme="dark"] .grafo-card .lead {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .grafo-card .fw-bold, [data-theme="dark"] .grafo-card .fw-bolder {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .grafo-card .fw-normal, [data-theme="dark"] .grafo-card .fw-light {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .grafo-card .fw-lighter {
        color: var(--text-secondary) !important;
    }
    
    [data-theme="dark"] .grafo-card .fst-italic {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .grafo-card .fst-normal {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .grafo-card .text-decoration-none {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .grafo-card .text-decoration-underline {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .grafo-card .text-decoration-line-through {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .grafo-card .text-nowrap {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .grafo-card .text-break {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .grafo-card .text-truncate {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .grafo-card .text-wrap {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .grafo-card .text-start {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .grafo-card .text-center {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .grafo-card .text-end {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .grafo-card .text-uppercase {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .grafo-card .text-lowercase {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .grafo-card .text-capitalize {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .grafo-card .lh-1 {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .grafo-card .lh-sm {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .grafo-card .lh-base {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .grafo-card .lh-lg {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .grafo-card .font-monospace {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .grafo-card .user-select-all {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .grafo-card .user-select-auto {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .grafo-card .user-select-none {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .grafo-card .pe-none {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .grafo-card .pe-auto {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .grafo-card .visible {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .grafo-card .invisible {
        color: var(--text-primary) !important;
    }
    
    /* Estilos específicos para elementos dos cards minimalistas */
    [data-theme="dark"] .card-minimal .text-dark {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .card-minimal .text-muted {
        color: var(--text-muted) !important;
    }
    
    [data-theme="dark"] .card-minimal .text-primary {
        color: var(--cor-primaria) !important;
    }
    
    [data-theme="dark"] .card-minimal .text-secondary {
        color: var(--text-secondary) !important;
    }
    
    [data-theme="dark"] .card-minimal .text-success {
        color: var(--cor-sucesso) !important;
    }
    
    [data-theme="dark"] .card-minimal .text-danger {
        color: var(--cor-perigo) !important;
    }
    
    [data-theme="dark"] .card-minimal .text-warning {
        color: var(--cor-aviso) !important;
    }
    
    [data-theme="dark"] .card-minimal .text-info {
        color: var(--cor-info) !important;
    }
    
    [data-theme="dark"] .card-minimal .text-light {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .card-minimal .text-white {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .card-minimal .text-black {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .card-minimal .text-body {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .card-minimal .text-body-secondary {
        color: var(--text-secondary) !important;
    }
    
    [data-theme="dark"] .card-minimal .text-body-tertiary {
        color: var(--text-muted) !important;
    }
    
    [data-theme="dark"] .card-minimal .small, [data-theme="dark"] .card-minimal small {
        color: var(--text-secondary) !important;
    }
    
    [data-theme="dark"] .card-minimal .lead {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .card-minimal .fw-bold, [data-theme="dark"] .card-minimal .fw-bolder {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .card-minimal .fw-normal, [data-theme="dark"] .card-minimal .fw-light {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .card-minimal .fw-lighter {
        color: var(--text-secondary) !important;
    }
    
    [data-theme="dark"] .card-minimal .fst-italic {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .card-minimal .fst-normal {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .card-minimal .text-decoration-none {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .card-minimal .text-decoration-underline {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .card-minimal .text-decoration-line-through {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .card-minimal .text-nowrap {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .card-minimal .text-break {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .card-minimal .text-truncate {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .card-minimal .text-wrap {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .card-minimal .text-start {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .card-minimal .text-center {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .card-minimal .text-end {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .card-minimal .text-uppercase {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .card-minimal .text-lowercase {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .card-minimal .text-capitalize {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .card-minimal .lh-1 {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .card-minimal .lh-sm {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .card-minimal .lh-base {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .card-minimal .lh-lg {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .card-minimal .font-monospace {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .card-minimal .user-select-all {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .card-minimal .user-select-auto {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .card-minimal .user-select-none {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .card-minimal .pe-none {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .card-minimal .pe-auto {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .card-minimal .visible {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .card-minimal .invisible {
        color: var(--text-primary) !important;
    }
    
    /* Estilos para badges e botões dentro dos cards */
    [data-theme="dark"] .card-minimal .badge {
        color: white !important;
    }
    
    [data-theme="dark"] .card-minimal .badge-minimal {
        background: var(--bg-tertiary) !important;
        color: var(--text-primary) !important;
        border: 1px solid var(--border-color) !important;
    }
    
    [data-theme="dark"] .card-minimal .badge-minimal-blue {
        background: var(--cor-primaria) !important;
        color: white !important;
    }
    
    [data-theme="dark"] .card-minimal .badge-minimal-gray {
        background: var(--text-secondary) !important;
        color: white !important;
    }
    
    [data-theme="dark"] .card-minimal .btn-action {
        color: var(--text-secondary) !important;
        background: var(--bg-tertiary) !important;
        border: 1px solid var(--border-color) !important;
    }
    
    [data-theme="dark"] .card-minimal .btn-action:hover {
        color: var(--cor-primaria) !important;
        background: var(--bg-primary) !important;
        border-color: var(--cor-primaria) !important;
    }
    
    [data-theme="dark"] .card-minimal .btn-action.view:hover {
        color: var(--cor-info) !important;
        border-color: var(--cor-info) !important;
    }
    
    [data-theme="dark"] .card-minimal .btn-action.edit:hover {
        color: var(--cor-aviso) !important;
        border-color: var(--cor-aviso) !important;
    }
    
    [data-theme="dark"] .card-minimal .btn-action.delete:hover {
        color: var(--cor-perigo) !important;
        border-color: var(--cor-perigo) !important;
    }
    
    /* Estilos para estatísticas dentro dos cards */
    [data-theme="dark"] .card-minimal .stat-minimal .label {
        color: var(--text-secondary) !important;
    }
    
    [data-theme="dark"] .card-minimal .stat-minimal .number {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .card-minimal .stat-minimal .number.text-primary {
        color: var(--cor-primaria) !important;
    }
    
    [data-theme="dark"] .card-minimal .stat-minimal .number.text-success {
        color: var(--cor-sucesso) !important;
    }
    
    [data-theme="dark"] .card-minimal .stat-minimal .number.text-warning {
        color: var(--cor-aviso) !important;
    }
    
    [data-theme="dark"] .card-minimal .stat-minimal .number.text-danger {
        color: var(--cor-perigo) !important;
    }
    
    [data-theme="dark"] .card-minimal .stat-minimal .number.text-info {
        color: var(--cor-info) !important;
    }
    
    /* Estilos para bordas e separadores */
    [data-theme="dark"] .card-minimal .border {
        border-color: var(--border-color) !important;
    }
    
    [data-theme="dark"] .card-minimal .border-top {
        border-top-color: var(--border-color) !important;
    }
    
    [data-theme="dark"] .card-minimal .border-bottom {
        border-bottom-color: var(--border-color) !important;
    }
    
    [data-theme="dark"] .card-minimal .border-start {
        border-left-color: var(--border-color) !important;
    }
    
    [data-theme="dark"] .card-minimal .border-end {
        border-right-color: var(--border-color) !important;
    }
    
    /* Estilos para ícones */
    [data-theme="dark"] .card-minimal .fas {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .card-minimal .fas.text-primary {
        color: var(--cor-primaria) !important;
    }
    
    [data-theme="dark"] .card-minimal .fas.text-success {
        color: var(--cor-sucesso) !important;
    }
    
    [data-theme="dark"] .card-minimal .fas.text-danger {
        color: var(--cor-perigo) !important;
    }
    
    [data-theme="dark"] .card-minimal .fas.text-warning {
        color: var(--cor-aviso) !important;
    }
    
    [data-theme="dark"] .card-minimal .fas.text-info {
        color: var(--cor-info) !important;
    }
    
    [data-theme="dark"] .card-minimal .fas.text-muted {
        color: var(--text-muted) !important;
    }
    
    [data-theme="dark"] .card-minimal .fas.text-secondary {
        color: var(--text-secondary) !important;
    }
</style>
@endpush

@section('conteudo')
<div class="pagina-meus-grafos">

@if($grafos->count() > 0)
    <!-- Lista de Grafos Minimalista -->
    <div class="row">
        <div class="col-12">
    <div class="grid-minimal">
        @foreach($grafos as $grafo)
            <div class="card-minimal" data-grafo-id="{{ $grafo->id }}">
                <!-- Header do Card com Ações no Topo -->
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="flex-grow-1">
                        <h6 class="mb-1 fw-semibold text-dark">
                            <i class="fas {{ $grafo->tipo === 'direcionado' ? 'fa-arrow-right' : 'fa-exchange-alt' }} me-2 text-primary"></i>
                            {{ \Illuminate\Support\Str::limit($grafo->nome, 25) }}
                        </h6>
                        @if(!empty($grafo->descricao))
                            <p class="text-muted small mb-0">
                                {{ \Illuminate\Support\Str::limit($grafo->descricao, 60) }}
                            </p>
                        @endif
                    </div>
                    
                    <!-- Ações Movidas para o Topo Direito -->
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge badge-minimal {{ $grafo->tipo === 'direcionado' ? 'badge-minimal-blue' : 'badge-minimal-gray' }}">
                            {{ $grafo->tipo === 'direcionado' ? 'Dir' : 'N-Dir' }}
                        </span>
                        <div class="actions-minimal">
                            <a href="/grafos/{{ $grafo->id }}" 
                               class="btn-action view" 
                               title="Visualizar Grafo">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="/grafos/{{ $grafo->id }}/editar" 
                               class="btn-action edit" 
                               title="Editar Grafo">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" 
                                    class="btn-action delete" 
                                    title="Excluir Grafo"
                                    onclick="confirmarExclusao({{ $grafo->id }}, '{{ $grafo->nome }}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Estatísticas Minimalistas -->
                <div class="d-flex justify-content-between mb-3" style="border: 1px solid #f1f3f4; border-radius: 6px; padding: 12px;">
                    <div class="stat-minimal">
                        <div class="label">Nós</div>
                        <div class="number">{{ $grafo->quantidade_nos ?? 0 }}</div>
                    </div>
                    <div class="stat-minimal" style="border-left: 1px solid #f1f3f4; border-right: 1px solid #f1f3f4;">
                        <div class="label">Arestas</div>
                        <div class="number">0</div>
                    </div>
                    <div class="stat-minimal">
                        <div class="label">Densidade</div>
                        <div class="number">0%</div>
                    </div>
                </div>
                
                <!-- Informações Temporais e ID -->
                <div class="d-flex justify-content-between align-items-end">
                    <div>
                        <small class="text-muted d-block" style="font-size: 12px;">
                            <i class="fas fa-clock me-1"></i>
                            {{ $grafo->created_at->format('d/m/Y H:i') }}
                        </small>
                        @if($grafo->updated_at != $grafo->created_at)
                            <small class="text-muted d-block" style="font-size: 12px;">
                                <i class="fas fa-edit me-1"></i>
                                Atualizado {{ $grafo->updated_at->diffForHumans() }}
                            </small>
                        @endif
                    </div>
                    
                    <small class="text-muted" style="font-size: 11px; color: #9e9e9e !important;">
                        #{{ $grafo->id }}
                    </small>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Paginação Minimalista -->
    @if($grafos->hasPages())
        <div class="d-flex justify-content-center mt-4">
            <div style="background: white; border: 1px solid #e9ecef; border-radius: 6px; padding: 8px;">
                {{ $grafos->links() }}
                </div>
            </div>
            </div>
        </div>
    @endif
@else
    <!-- Estado Vazio Minimalista -->
    <div class="empty-state">
        <div class="icon">
            <i class="fas fa-project-diagram" style="font-size: 48px; color: #dee2e6;"></i>
        </div>
        <h3>Nenhum grafo criado ainda</h3>
        <p>Comece criando seu primeiro grafo para visualizar e analisar conexões.</p>
        <a href="/grafos/criar" class="btn btn-minimal btn-minimal-primary">
            <i class="fas fa-plus me-2"></i>Criar Primeiro Grafo
        </a>
        
        <!-- Dicas Minimalistas -->
        <div class="mt-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="d-flex justify-content-around text-center">
                        <div style="max-width: 120px;">
                            <i class="fas fa-lightbulb" style="font-size: 24px; color: #6c757d;"></i>
                            <h6 class="mt-2 mb-1" style="font-size: 14px; font-weight: 500;">Grafos Simples</h6>
                            <p style="font-size: 12px; color: #6c757d; margin: 0;">Comece com 3-5 nós</p>
                        </div>
                        <div style="max-width: 120px;">
                            <i class="fas fa-random" style="font-size: 24px; color: #6c757d;"></i>
                            <h6 class="mt-2 mb-1" style="font-size: 14px; font-weight: 500;">Use Geradores</h6>
                            <p style="font-size: 12px; color: #6c757d; margin: 0;">Experimente diferentes tipos</p>
                        </div>
                        <div style="max-width: 120px;">
                            <i class="fas fa-chart-line" style="font-size: 24px; color: #6c757d;"></i>
                            <h6 class="mt-2 mb-1" style="font-size: 14px; font-weight: 500;">Analise Dados</h6>
                            <p style="font-size: 12px; color: #6c757d; margin: 0;">Observe as métricas</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmarExclusao(grafoId, nomeGrafo) {
            // Criar popup personalizado com tema escuro
            const popup = document.createElement('div');
            popup.className = 'custom-popup-overlay';
            popup.innerHTML = `
                <div class="custom-popup">
                    <div class="custom-popup-header">
                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                        <h5 class="mb-0">Tem certeza que deseja excluir?</h5>
            </div>
                    <div class="custom-popup-body">
                        <p class="mb-0">O grafo "<strong>${nomeGrafo}</strong>" será removido permanentemente.</p>
                    </div>
                    <div class="custom-popup-footer">
                        <button type="button" class="btn btn-secondary" onclick="fecharPopup()">
                            <i class="fas fa-times me-1"></i>Cancelar
                        </button>
                        <button type="button" class="btn btn-danger" onclick="confirmarExclusaoFinal(${grafoId}, '${nomeGrafo}')">
                            <i class="fas fa-trash me-1"></i>Sim, excluir
                        </button>
                    </div>
                </div>
            `;
            
            document.body.appendChild(popup);
            
            // Adicionar estilos se não existirem
            if (!document.getElementById('custom-popup-styles')) {
                const styles = document.createElement('style');
                styles.id = 'custom-popup-styles';
                styles.textContent = `
                    .custom-popup-overlay {
                        position: fixed;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        background: rgba(0, 0, 0, 0.7);
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        z-index: 9999;
                        backdrop-filter: blur(5px);
                    }
                    
                    .custom-popup {
                        background: var(--bg-secondary, #2a2a2a);
                        border: 1px solid var(--border-color, #404040);
                        border-radius: 12px;
                        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
                        max-width: 400px;
                        width: 90%;
                        animation: popupSlideIn 0.3s ease-out;
                    }
                    
                    @keyframes popupSlideIn {
                        from {
                            opacity: 0;
                            transform: translateY(-20px) scale(0.95);
                        }
                        to {
                            opacity: 1;
                            transform: translateY(0) scale(1);
                        }
                    }
                    
                    @keyframes popupSlideOut {
                        from {
                            opacity: 1;
                            transform: translateY(0) scale(1);
                        }
                        to {
                            opacity: 0;
                            transform: translateY(-20px) scale(0.95);
                        }
                    }
                    
                    .custom-popup-header {
                        padding: 20px 20px 15px;
                        border-bottom: 1px solid var(--border-color, #404040);
                        display: flex;
                        align-items: center;
                    }
                    
                    .custom-popup-header h5 {
                        color: var(--text-primary, #ffffff);
                        font-weight: 600;
                    }
                    
                    .custom-popup-body {
                        padding: 20px;
                    }
                    
                    .custom-popup-body p {
                        color: var(--text-secondary, #cccccc);
                        margin: 0;
                    }
                    
                    .custom-popup-body strong {
                        color: var(--text-primary, #ffffff);
                    }
                    
                    .custom-popup-footer {
                        padding: 15px 20px 20px;
                        display: flex;
                        gap: 10px;
                        justify-content: flex-end;
                    }
                    
                    .custom-popup-footer .btn {
                        padding: 8px 16px;
                        border-radius: 6px;
                        font-weight: 500;
                        transition: all 0.2s ease;
                    }
                    
                    .custom-popup-footer .btn-secondary {
                        background: var(--bg-tertiary, #3a3a3a);
                        border-color: var(--border-color, #404040);
                        color: var(--text-primary, #ffffff);
                    }
                    
                    .custom-popup-footer .btn-secondary:hover {
                        background: var(--bg-primary, #1a1a1a);
                        border-color: var(--border-color, #404040);
                        color: var(--text-primary, #ffffff);
                    }
                    
                    .custom-popup-footer .btn-danger {
                        background: #dc3545;
                        border-color: #dc3545;
                        color: white;
                    }
                    
                    .custom-popup-footer .btn-danger:hover {
                        background: #c82333;
                        border-color: #bd2130;
                        color: white;
                    }
                `;
                document.head.appendChild(styles);
            }
        }
        
        function fecharPopup() {
            const popup = document.querySelector('.custom-popup-overlay');
            if (popup) {
                popup.style.animation = 'popupSlideOut 0.2s ease-in';
                setTimeout(() => {
                    popup.remove();
                }, 200);
            }
        }
        
        function confirmarExclusaoFinal(grafoId, nomeGrafo) {
            fecharPopup();
            excluirGrafo(grafoId, nomeGrafo);
        }
        
        function excluirGrafo(grafoId, nomeGrafo) {
            // Mostrar loading personalizado com tema escuro
            const loadingPopup = document.createElement('div');
            loadingPopup.className = 'custom-popup-overlay';
            loadingPopup.innerHTML = `
                <div class="custom-popup loading-popup">
                    <div class="custom-popup-header">
                        <div class="spinner-border text-primary me-2" role="status">
                            <span class="visually-hidden">Carregando...</span>
        </div>
                        <h5 class="mb-0">Excluindo...</h5>
                    </div>
                    <div class="custom-popup-body">
                        <p class="mb-0">Removendo grafo e todas as suas conexões</p>
    </div>
</div>
                `;
            
            document.body.appendChild(loadingPopup);
            
            // Obter token CSRF
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            
            // Fazer requisição AJAX
            fetch(`/grafos/${grafoId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken || '',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro na requisição');
                }
                return response.json();
            })
            .then(data => {
                if (data.sucesso) {
                    // Remover loading
                    const loadingPopup = document.querySelector('.custom-popup-overlay');
                    if (loadingPopup) loadingPopup.remove();
                    
                    // Sucesso - mostrar notificação personalizada
                    mostrarNotificacaoSucesso(`Grafo "${nomeGrafo}" foi excluído com sucesso!`);
                    
                    // Recarregar a página após um pequeno delay
                setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    throw new Error(data.erro || 'Erro desconhecido');
                }
            })
            .catch(error => {
                console.error('Erro ao excluir grafo:', error);
                
                // Remover loading
                const loadingPopup = document.querySelector('.custom-popup-overlay');
                if (loadingPopup) loadingPopup.remove();
                
                // Mostrar erro personalizado
                mostrarNotificacaoErro('Não foi possível excluir o grafo. Tente novamente.');
            });
        }
        
        function mostrarNotificacaoSucesso(mensagem) {
            // Usar o sistema de notificações do layout principal
            if (window.NotificationSystem) {
                window.NotificationSystem.show(mensagem, 'success');
            } else {
                // Fallback para alert simples
                alert('✅ ' + mensagem);
            }
        }
        
        function mostrarNotificacaoErro(mensagem) {
            // Usar o sistema de notificações do layout principal
            if (window.NotificationSystem) {
                window.NotificationSystem.show(mensagem, 'error');
            } else {
                // Fallback para alert simples
                alert('❌ ' + mensagem);
            }
        }

        // Log de inicialização
    console.log('📊 Página "Meus Grafos" carregada');
    console.log(`📈 Total de grafos exibidos: {{ $grafos->count() }}`);
    </script>
@endpush