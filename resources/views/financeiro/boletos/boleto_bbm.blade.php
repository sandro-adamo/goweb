<?php
// +----------------------------------------------------------------------+
// | BoletoPhp - Versão Beta                                              |
// +----------------------------------------------------------------------+
// | Este arquivo está disponível sob a Licença GPL disponível pela Web   |
// | em http://pt.wikipedia.org/wiki/GNU_General_Public_License           |
// | Você deve ter recebido uma cópia da GNU Public License junto com     |
// | esse pacote; se não, escreva para:                                   |
// |                                                                      |
// | Free Software Foundation, Inc.                                       |
// | 59 Temple Place - Suite 330                                          |
// | Boston, MA 02111-1307, USA.                                          |
// +----------------------------------------------------------------------+

// +----------------------------------------------------------------------+
// | Originado do Projeto BBBoletoFree que tiveram colaborações de Daniel |
// | William Schultz e Leandro Maniezo que por sua vez foi derivado do	  |
// | PHPBoleto de João Prado Maia e Pablo Martins F. Costa			       	  |
// | 																	                                    |
// | Se vc quer colaborar, nos ajude a desenvolver p/ os demais bancos :-)|
// | Acesse o site do Projeto BoletoPhp: www.boletophp.com.br             |
// +----------------------------------------------------------------------+

// +----------------------------------------------------------------------+
// | Equipe Coordenação Projeto BoletoPhp: <boletophp@boletophp.com.br>   |
// | Desenvolvimento Boleto Bradesco: Ramon Soares						            |
// +----------------------------------------------------------------------+


// ------------------------- DADOS DINÂMICOS DO SEU CLIENTE PARA A GERAÇÃO DO BOLETO (FIXO OU VIA GET) -------------------- //
// Os valores abaixo podem ser colocados manualmente ou ajustados p/ formulário c/ POST, GET ou de BD (MySql,Postgre,etc)	//



// DADOS DO BOLETO PARA O SEU CLIENTE
$dias_de_prazo_para_pagamento = 5;
$taxa_boleto = 0;
$data_venc = date('d/m/Y', strtotime($boleto->vencimento));  
$valor_cobrado = $boleto->valor; // Valor - REGRA: Sem pontos na milhar e tanto faz com "." ou "," ou com 1 ou 2 ou sem casa decimal
$valor_cobrado = str_replace(",", ".",$valor_cobrado);
$valor_boleto=number_format($valor_cobrado+$taxa_boleto, 2, ',', '');

$dadosboleto["nosso_numero"] = trim($boleto->nosso_numero); // Nosso numero sem o DV - REGRA: Máximo de 11 caracteres!

$dadosboleto["numero_documento"] = trim($boleto->documento);	// Num do pedido ou do documento = Nosso numero
$dadosboleto["data_vencimento"] = $data_venc; // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
$dadosboleto["data_documento"] = date('d/m/Y', strtotime($boleto->dt_documento)); // Data de emissão do Boleto
$dadosboleto["data_processamento"] = date('d/m/Y', strtotime($boleto->dt_documento)); // Data de processamento do boleto (opcional)
$dadosboleto["valor_boleto"] = $valor_boleto; 	// Valor do Boleto - REGRA: Com vírgula e sempre com duas casas depois da virgula

// DADOS DO SEU CLIENTE
$dadosboleto["sacado"] = $boleto->razao .' - CNPJ: '.$boleto->cnpj;
$dadosboleto["endereco1"] = $boleto->endereco . ', '.$boleto->numero . ' ' . $boleto->complemento;
$dadosboleto["endereco2"] = $boleto->cidade . ' - '. $boleto->estado . ' -  CEP:'. $boleto->cep;

// INFORMACOES PARA O CLIENTE
$dadosboleto["demonstrativo1"] = "";
$dadosboleto["demonstrativo2"] = "";
$dadosboleto["demonstrativo3"] = "";
$dadosboleto["instrucoes1"] = "JUROS POR DIA DE ATRASO ....... 0,90";
$dadosboleto["instrucoes2"] = "ESTE BOLETO NAO DEVE SER PAGO ATRAVES DE BOLETO BANCARIO POIS NAO SERA QUITADO";
$dadosboleto["instrucoes3"] = "SUJEITO A PROTESTO APOS 15 DIAS DO VENCIMENTO PARCALA";
$dadosboleto["instrucoes4"] = "TITULO NEGOCIADO PAGAVEL SOMENTE EM BANCO OU REDE DE CORRESPONDENTES";

// DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
$dadosboleto["quantidade"] = "001";
$dadosboleto["valor_unitario"] = $valor_boleto;
$dadosboleto["aceite"] = "NAO";
$dadosboleto["especie"] = "R$";
$dadosboleto["especie_doc"] = "DM";


// ---------------------- DADOS FIXOS DE CONFIGURAÇÃO DO SEU BOLETO --------------- //


// DADOS DA SUA CONTA - Bradesco
$dadosboleto["agencia"] = trim($boleto->agencia); // Num da agencia, sem digito
$dadosboleto["agencia_dv"] = "6"; // Digito do Num da agencia
$dadosboleto["conta"] = trim($boleto->conta); 	// Num da conta, sem digito
$dadosboleto["conta_dv"] = trim($boleto->dv_conta); 	// Digito do Num da conta

// DADOS PERSONALIZADOS - Bradesco
$dadosboleto["conta_cedente"] = trim($boleto->conta); // ContaCedente do Cliente, sem digito (Somente Números)
$dadosboleto["conta_cedente_dv"] = trim($boleto->dv_conta); // Digito da ContaCedente do Cliente
$dadosboleto["carteira"] = trim($boleto->carteira);  // Código da Carteira: pode ser 06 ou 03

// SEUS DADOS
$dadosboleto["identificacao"] = "KENERSON IND E COM DE PROD OPTICOS LTDA";
$dadosboleto["cpf_cnpj"] = "07.019.231/0001-96";
$dadosboleto["endereco"] = "RUA DIOGO MOREIRA, 132 - CONJ 2201";
$dadosboleto["cidade_uf"] = "SAO PAULO - SP";
$dadosboleto["cedente"] = "KENERSON IND E COM PROD OPTICOS LTDA";

// NÃO ALTERAR!
include("../resources/views/financeiro/boletos/include/funcoes_bbm.php");
include("../resources/views/financeiro/boletos/include/layout_bradesco.php");
?>
