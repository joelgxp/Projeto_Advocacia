<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$lang['db_invalid_connection_str'] = 'Não foi possível determinar as configurações do banco de dados com base na string de conexão fornecida.';
$lang['db_unable_to_connect'] = 'Não foi possível conectar ao servidor de banco de dados usando as configurações fornecidas.';
$lang['db_unable_to_select'] = 'Não foi possível selecionar o banco de dados especificado: %s';
$lang['db_unable_to_create'] = 'Não foi possível criar o banco de dados especificado: %s';
$lang['db_invalid_query'] = 'A consulta que você enviou não é válida.';
$lang['db_must_set_table'] = 'Você deve definir a tabela do banco de dados a ser usada em sua consulta.';
$lang['db_must_use_set'] = 'Você deve usar o método "set" para atualizar uma entrada.';
$lang['db_must_use_index'] = 'Você deve especificar um índice para corresponder às atualizações em lote.';
$lang['db_batch_missing_index'] = 'Uma ou mais linhas enviadas para atualização em lote estão faltando o índice especificado.';
$lang['db_must_use_where'] = 'Atualizações não são permitidas a menos que contenham uma cláusula "where".';
$lang['db_del_must_use_where'] = 'Exclusões não são permitidas a menos que contenham uma cláusula "where" ou "like".';
$lang['db_field_param_missing'] = 'Para buscar campos requer o nome da tabela como parâmetro.';
$lang['db_unsupported_function'] = 'Este recurso não está disponível para o banco de dados que você está usando.';
$lang['db_transaction_failure'] = 'Falha na transação: Rollback executado.';
$lang['db_unable_to_drop'] = 'Não foi possível remover o banco de dados especificado.';
$lang['db_unsupported_feature'] = 'Recurso não suportado da plataforma de banco de dados que você está usando.';
$lang['db_unsupported_compression'] = 'O formato de compressão de arquivo que você escolheu não é suportado pelo seu servidor.';
$lang['db_filepath_error'] = 'Não foi possível gravar dados no caminho do arquivo que você enviou.';
$lang['db_invalid_file_path'] = 'O caminho do arquivo que você enviou não é válido.';
$lang['db_file_open_error'] = 'Não foi possível abrir o arquivo: %s';
$lang['db_filename_error'] = 'O nome do arquivo que você enviou não é válido.';
$lang['db_invalid_cache_path'] = 'O caminho de cache que você enviou não é válido ou gravável.';
$lang['db_table_name_required'] = 'Um nome de tabela é necessário para essa operação.';
$lang['db_column_name_required'] = 'Um nome de coluna é necessário para essa operação.';
$lang['db_column_definition_required'] = 'Uma definição de coluna é necessária para essa operação.';
$lang['db_unable_to_set_charset'] = 'Não foi possível definir o conjunto de caracteres da conexão do cliente: %s';
$lang['db_error_heading'] = 'Ocorreu um Erro no Banco de Dados';

