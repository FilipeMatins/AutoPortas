<?php

use Core\Router;

/**
 * Rotas da aplicação
 * Auto Portas - Sistema de Gestão
 */

// ============================================
// AUTENTICAÇÃO
// ============================================
Router::get('/login', 'AuthController@login', 'login');
Router::post('/login', 'AuthController@authenticate', 'login.auth');
Router::get('/logout', 'AuthController@logout', 'logout');

// ============================================
// PÁGINA INICIAL / DASHBOARD
// ============================================
Router::get('/', 'HomeController@index', 'home');

// ============================================
// CLIENTES
// ============================================
Router::get('/clientes', 'ClienteController@index', 'clientes.index');
Router::get('/clientes/novo', 'ClienteController@create', 'clientes.create');
Router::post('/clientes', 'ClienteController@store', 'clientes.store');
Router::get('/clientes/{id}', 'ClienteController@show', 'clientes.show');
Router::get('/clientes/{id}/editar', 'ClienteController@edit', 'clientes.edit');
Router::post('/clientes/{id}', 'ClienteController@update', 'clientes.update');
Router::post('/clientes/{id}/excluir', 'ClienteController@destroy', 'clientes.destroy');

// ============================================
// SERVIÇOS
// ============================================
Router::get('/servicos', 'ServicoController@index', 'servicos.index');
Router::get('/servicos/novo', 'ServicoController@create', 'servicos.create');
Router::post('/servicos', 'ServicoController@store', 'servicos.store');
Router::get('/servicos/{id}', 'ServicoController@show', 'servicos.show');
Router::get('/servicos/{id}/editar', 'ServicoController@edit', 'servicos.edit');
Router::post('/servicos/{id}', 'ServicoController@update', 'servicos.update');
Router::post('/servicos/{id}/excluir', 'ServicoController@destroy', 'servicos.destroy');

// ============================================
// ORÇAMENTOS
// ============================================
Router::get('/orcamentos', 'OrcamentoController@index', 'orcamentos.index');
Router::get('/orcamentos/novo', 'OrcamentoController@create', 'orcamentos.create');
Router::post('/orcamentos', 'OrcamentoController@store', 'orcamentos.store');
Router::get('/orcamentos/{id}', 'OrcamentoController@show', 'orcamentos.show');
Router::get('/orcamentos/{id}/editar', 'OrcamentoController@edit', 'orcamentos.edit');
Router::post('/orcamentos/{id}', 'OrcamentoController@update', 'orcamentos.update');
Router::post('/orcamentos/{id}/status', 'OrcamentoController@updateStatus', 'orcamentos.status');
Router::post('/orcamentos/{id}/excluir', 'OrcamentoController@destroy', 'orcamentos.destroy');
Router::get('/orcamentos/{id}/pdf', 'OrcamentoController@pdf', 'orcamentos.pdf');
Router::post('/orcamentos/{id}/cobranca', 'OrcamentoController@gerarCobranca', 'orcamentos.cobranca');

// ============================================
// PEÇAS E MARCAS
// ============================================
Router::get('/marcas', 'MarcaController@index', 'marcas.index');
Router::get('/marcas/novo', 'MarcaController@create', 'marcas.create');
Router::post('/marcas', 'MarcaController@store', 'marcas.store');
Router::get('/marcas/{id}/editar', 'MarcaController@edit', 'marcas.edit');
Router::post('/marcas/{id}', 'MarcaController@update', 'marcas.update');
Router::post('/marcas/{id}/excluir', 'MarcaController@destroy', 'marcas.destroy');

Router::get('/pecas', 'PecaController@index', 'pecas.index');
Router::get('/pecas/novo', 'PecaController@create', 'pecas.create');
Router::post('/pecas', 'PecaController@store', 'pecas.store');
Router::get('/pecas/{id}', 'PecaController@show', 'pecas.show');
Router::get('/pecas/{id}/editar', 'PecaController@edit', 'pecas.edit');
Router::post('/pecas/{id}', 'PecaController@update', 'pecas.update');
Router::post('/pecas/{id}/excluir', 'PecaController@destroy', 'pecas.destroy');

// ============================================
// CONTAS A RECEBER
// ============================================
Router::get('/contas', 'ContaReceberController@index', 'contas.index');
Router::get('/contas/novo', 'ContaReceberController@create', 'contas.create');
Router::post('/contas', 'ContaReceberController@store', 'contas.store');
Router::get('/contas/{id}', 'ContaReceberController@show', 'contas.show');
Router::get('/contas/{id}/editar', 'ContaReceberController@edit', 'contas.edit');
Router::post('/contas/{id}', 'ContaReceberController@update', 'contas.update');
Router::post('/contas/{id}/pagamento', 'ContaReceberController@registrarPagamento', 'contas.pagamento');
Router::post('/contas/{id}/excluir', 'ContaReceberController@destroy', 'contas.destroy');

// ============================================
// ESTOQUE (Movimentações)
// ============================================
Router::get('/estoque', 'EstoqueController@index', 'estoque.index');
Router::get('/estoque/saida', 'EstoqueController@saida', 'estoque.saida');
Router::post('/estoque/saida', 'EstoqueController@registrarSaida', 'estoque.saida.store');
Router::get('/estoque/entrada', 'EstoqueController@entrada', 'estoque.entrada');
Router::post('/estoque/entrada', 'EstoqueController@registrarEntrada', 'estoque.entrada.store');

// ============================================
// NOTIFICAÇÕES
// ============================================
Router::get('/notificacoes', 'NotificacaoController@page', 'notificacoes.page');
Router::get('/api/notificacoes', 'NotificacaoController@index', 'notificacoes.api');
Router::post('/notificacoes/lida', 'NotificacaoController@marcarLida', 'notificacoes.lida');
Router::post('/notificacoes/todas-lidas', 'NotificacaoController@marcarTodasLidas', 'notificacoes.todas-lidas');
Router::post('/notificacoes/excluir', 'NotificacaoController@excluir', 'notificacoes.excluir');

