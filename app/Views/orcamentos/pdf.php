<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Orçamento #<?= $orcamento->id ?> - Auto Portas</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 14px;
            line-height: 1.5;
            color: #333;
            padding: 40px;
            max-width: 800px;
            margin: 0 auto;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 3px solid #2563eb;
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #2563eb;
        }
        .logo small {
            display: block;
            font-size: 12px;
            color: #666;
            font-weight: normal;
        }
        .orcamento-info {
            text-align: right;
        }
        .orcamento-info h1 {
            font-size: 24px;
            color: #333;
        }
        .orcamento-info p {
            color: #666;
        }
        .status {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 8px;
        }
        .status-pendente { background: #fef3c7; color: #92400e; }
        .status-aprovado { background: #d1fae5; color: #065f46; }
        .status-rejeitado { background: #fee2e2; color: #991b1b; }
        .status-em_execucao { background: #dbeafe; color: #1e40af; }
        .status-concluido { background: #d1fae5; color: #065f46; }
        
        .section {
            margin-bottom: 30px;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #e5e7eb;
        }
        .cliente-info {
            background: #f9fafb;
            padding: 15px;
            border-radius: 8px;
        }
        .cliente-info h3 {
            font-size: 18px;
            margin-bottom: 8px;
        }
        .cliente-info p {
            color: #666;
            margin: 4px 0;
        }
        
        .descricao {
            background: #f9fafb;
            padding: 15px;
            border-radius: 8px;
            white-space: pre-line;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }
        th {
            background: #f9fafb;
            font-weight: 600;
            color: #374151;
        }
        td.text-right, th.text-right {
            text-align: right;
        }
        td.text-center, th.text-center {
            text-align: center;
        }
        
        .totais {
            margin-top: 20px;
            padding: 20px;
            background: #f9fafb;
            border-radius: 8px;
        }
        .totais-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
        }
        .totais-row.total {
            border-top: 2px solid #e5e7eb;
            margin-top: 10px;
            padding-top: 15px;
            font-size: 18px;
            font-weight: bold;
            color: #2563eb;
        }
        
        .observacoes {
            background: #fffbeb;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #f59e0b;
        }
        
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
        .footer p {
            margin: 4px 0;
        }
        
        .assinatura {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
        }
        .assinatura-box {
            width: 45%;
            text-align: center;
        }
        .assinatura-linha {
            border-top: 1px solid #333;
            padding-top: 10px;
            margin-top: 50px;
        }
        
        @media print {
            body {
                padding: 20px;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px; text-align: right;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #2563eb; color: white; border: none; border-radius: 5px; cursor: pointer;">
            Imprimir / Salvar PDF
        </button>
    </div>

    <div class="header">
        <div class="logo">
            Auto Portas
            <small>Portas Automáticas e Serviços</small>
        </div>
        <div class="orcamento-info">
            <h1>ORÇAMENTO #<?= $orcamento->id ?></h1>
            <p>Data: <?= format_date($orcamento->created_at) ?></p>
            <?php if (!empty($orcamento->data_validade)): ?>
                <p>Válido até: <?= format_date($orcamento->data_validade) ?></p>
            <?php endif; ?>
            <span class="status status-<?= $orcamento->status ?>"><?= status_text($orcamento->status) ?></span>
        </div>
    </div>
    
    <div class="section">
        <div class="section-title">Dados do Cliente</div>
        <div class="cliente-info">
            <h3><?= e($orcamento->cliente_nome ?? 'N/A') ?></h3>
            <?php if (!empty($orcamento->cliente_telefone)): ?>
                <p><strong>Telefone:</strong> <?= format_phone($orcamento->cliente_telefone) ?></p>
            <?php endif; ?>
            <?php if (!empty($orcamento->cliente_email)): ?>
                <p><strong>Email:</strong> <?= e($orcamento->cliente_email) ?></p>
            <?php endif; ?>
            <?php if (!empty($orcamento->cliente_endereco)): ?>
                <p><strong>Endereço:</strong> <?= e($orcamento->cliente_endereco) ?><?= !empty($orcamento->cliente_cidade) ? ', ' . e($orcamento->cliente_cidade) : '' ?><?= !empty($orcamento->cliente_estado) ? ' - ' . e($orcamento->cliente_estado) : '' ?></p>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="section">
        <div class="section-title">Descrição do Serviço</div>
        <div class="descricao"><?= e($orcamento->descricao) ?></div>
    </div>
    
    <?php if (!empty($orcamento->servicos)): ?>
    <div class="section">
        <div class="section-title">Serviços</div>
        <table>
            <thead>
                <tr>
                    <th>Serviço</th>
                    <th class="text-center">Qtd</th>
                    <th class="text-right">Valor Unit.</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orcamento->servicos as $servico): ?>
                    <tr>
                        <td><?= e($servico->servico_nome) ?></td>
                        <td class="text-center"><?= $servico->quantidade ?></td>
                        <td class="text-right"><?= money($servico->valor_unitario) ?></td>
                        <td class="text-right"><?= money($servico->valor_total) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
    
    <div class="section">
        <div class="totais">
            <div class="totais-row">
                <span>Subtotal:</span>
                <span><?= money($orcamento->valor_total ?? 0) ?></span>
            </div>
            <?php if (($orcamento->desconto ?? 0) > 0): ?>
            <div class="totais-row">
                <span>Desconto:</span>
                <span style="color: #dc2626;">- <?= money($orcamento->desconto) ?></span>
            </div>
            <?php endif; ?>
            <div class="totais-row total">
                <span>VALOR TOTAL:</span>
                <span><?= money($orcamento->valor_final ?? 0) ?></span>
            </div>
            <?php if (!empty($orcamento->forma_pagamento)): ?>
            <div class="totais-row" style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #e5e7eb;">
                <span>Forma de Pagamento:</span>
                <span><?= ucfirst(str_replace('_', ' ', $orcamento->forma_pagamento)) ?></span>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <?php if (!empty($orcamento->observacoes)): ?>
    <div class="section">
        <div class="section-title">Observações</div>
        <div class="observacoes"><?= nl2br(e($orcamento->observacoes)) ?></div>
    </div>
    <?php endif; ?>
    
    <div class="assinatura">
        <div class="assinatura-box">
            <div class="assinatura-linha">Auto Portas</div>
        </div>
        <div class="assinatura-box">
            <div class="assinatura-linha">Cliente</div>
        </div>
    </div>
    
    <div class="footer">
        <p><strong>Auto Portas</strong> - Portas Automáticas e Serviços</p>
        <p>Este orçamento tem validade de 15 dias a partir da data de emissão.</p>
    </div>
</body>
</html>

