<?php
// $Id: admin.php,v 1.2 2007/03/24 14:41:55 marcellobrandao Exp $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <https://xoops.org>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
//index.php
define('_AM_ASSESSMENT_CONGRATULATIONS', 'Parab�ns a prova foi salva com sucesso!');

//excluirprova.php
define('_AM_ASSESSMENT_CONFIRMAEXCLUSAOPROVAS', 'Ao excluir esta prova voc� tamb�m estar�
       excluindo:<br><br>Suas Perguntas<br>As Respostas das perguntas<br>
       Os documentos que aparecem antes das perguntas<br>Os resultados dos
       alunos que j� fizeram a prova<br><br>Voc� tem certeza que � isto que
       voc� quer fazer?<br>');

define('_AM_ASSESSMENT_SIMCONFIRMAEXCLUSAOPROVAS', 'Sim entendi que vou excluir mais do que
       somente a prova e concordo');
define('_AM_ASSESSMENT_PERGUNTASAPAGADAS', 'pergunta(s) apagadas');
define('_AM_ASSESSMENT_RESPDAPERG', 'Respostas da pergunta %s apagada');
define('_AM_ASSESSMENT_RESULTAPAGADOS', 'resultado(s) apagados');
define('_AM_ASSESSMENT_DOCUMENTOSPAGADOS', 'documento(s) apagados');

//index.php
define('_AM_ASSESSMENT_REQUERIMENTOS', "Para o correto funcionamento deste m�dulo �
                            necess�ria a instala��o dos pacotes:<br><br>
   <a href='http://dev.xoops.org/modules/xfmod/project/showfiles.php?group_id=1357'>
   Frameworks do phppp de janeiro 1.10 ou superior</a><br>
   <br>
   <a href='http://dev.xoops.org/modules/xfmod/project/showfiles.php?group_id=1155'>
   Xoops editor 1.10 ou superior   </a><br>");
define('_AM_ASSESSMENT_SEMPROVAS', 'Nenhuma prova cadastrada ainda');
define('_AM_ASSESSMENT_EDITARPROVAS', 'Editar provas');
define('_AM_ASSESSMENT_EXCLUIRPROVAS', 'Excluir provas');
define('_AM_ASSESSMENT_VERRESULT', 'Ver Resultados');
define('_AM_ASSESSMENT_LISTAPROVAS', 'Lista de Provas:');
define('_AM_ASSESSMENT_PERGUNTA', 'Pergunta:');
define('_AM_ASSESSMENT_RESPCERTA', 'Resposta certa');
define('_AM_ASSESSMENT_RESPUSR', 'Resposta do Usu�rio');
define('_AM_ASSESSMENT_SEMRESULT', 'Nenhum resultado cadastrado ainda ');
define('_AM_ASSESSMENT_QTDRESULT', 'Quantidade de resultados');
define('_AM_ASSESSMENT_NOTAMAX', ' Nota M�xima:');
define('_AM_ASSESSMENT_NOTAMIN', ' Nota M�nima:');
define('_AM_ASSESSMENT_MEDIA', ' M�dia:');
define('_AM_ASSESSMENT_LISTARESULTADOS', 'Lista de Resultados:');
define('_AM_ASSESSMENT_PROVAANDAMENTO', 'Prova em andamento ainda');
define('_AM_ASSESSMENT_TERMINADA', 'Prova terminada');
define('_AM_ASSESSMENT_NOMEALUNO', 'Nome do Aluno:');
define('_AM_ASSESSMENT_DATA', 'Data:');
define('_AM_ASSESSMENT_CODPROVA', 'C�digo da Prova:');
define('_AM_ASSESSMENT_STATS', 'Estat�sticas:');
define('_AM_ASSESSMENT_LISTAPERGASSOC', 'Lista de Perguntas associadas a esta prova:');
define('_AM_ASSESSMENT_SEMPERGUNTA', 'Nenhuma pergunta cadastrada para esta prova por enquanto');
define('_AM_ASSESSMENT_EDITARPERGUNTAS', 'Editar perguntas');
define('_AM_ASSESSMENT_EXCLUIRPERGUNTAS', 'Excluir perguntas');
define('_AM_ASSESSMENT_SEMDOCUMENTO', 'Nenhum documento cadastrado ainda ');
define('_AM_ASSESSMENT_LISTADOC', 'Lista Documentos:');
define('_AM_ASSESSMENT_EDITARDOC', 'Editar Documento');
define('_AM_ASSESSMENT_EXCLUIRDOC', 'Excluir documento');
define('_AM_ASSESSMENT_INSTRUCOESNOVODOC', 'Para cadastrar um documento novo v� em manter prova selecione editar prova e ent�o cadastre o documento');
define('_AM_ASSESSMENT_PROVA', 'Prova');
define('_AM_ASSESSMENT_DOCUMENTO', 'Documento');
define('_AM_ASSESSMENT_RESULTADO', 'Resultado');
define('_AM_ASSESSMENT_RESULTPROVA', 'Resultado por prova');
define('_AM_ASSESSMENT_RESPALUNO', 'Resposta do aluno');
define('_AM_ASSESSMENT_EDITAR', 'Editar');
define('_AM_ASSESSMENT_CADASTRAR', 'Cadastrar');
define('_AM_ASSESSMENT_ACERTOU', 'Acertou: ');
define('_AM_ASSESSMENT_ERROU', 'errou');
define('_AM_ASSESSMENT_SEMREPONDER', 'sem responder');
define('_AM_ASSESSMENT_DEUMTOTALDE', 'de um total de');
define('_AM_ASSESSMENT_PERGUNTAS', 'perguntas');

//assessment_documentos.php
define('_AM_ASSESSMENT_PERGASSOC', 'Perguntas associadas');
define('_AM_ASSESSMENT_TITULO', 'T�tulo');
define('_AM_ASSESSMENT_FONTE', 'Fonte');

//Question.php
define('_AM_ASSESSMENT_RESPOSTA', 'Resposta');
define('_AM_ASSESSMENT_RESPCORRETA', 'Resposta correta');
define('_AM_ASSESSMENT_SALVARALTERACOES', 'Salvar Altera��es');

//Test.php
define('_AM_ASSESSMENT_DESCRICAO', 'Descri��o');
define('_AM_ASSESSMENT_INSTRUCOES', 'Instru��es');
define('_AM_ASSESSMENT_TEMPO', 'Tempo em segundos');
define('_AM_ASSESSMENT_GRUPOSACESSO', 'Grupos com acesso');
define('_AM_ASSESSMENT_DATA_INICIO', 'Data In�cio');
define('_AM_ASSESSMENT_DATA_FIM', 'Data Fim');

//Result
define('_AM_ASSESSMENT_PERGDETALHES', 'Clique no c�digo da pergunta para ver detalhes');
define('_AM_ASSESSMENT_RESPCERTAS', 'Respostas Certas');
define('_AM_ASSESSMENT_RESPERR', 'Respostas Erradas');
define('_AM_ASSESSMENT_SUGESTNOTA', 'Sugest�o de nota');
define('_AM_ASSESSMENT_NOTAFINAL', 'Nota Final');
define('_AM_ASSESSMENT_NIVEL', 'Nivel');
define('_AM_ASSESSMENT_OBS', 'Observa��es');

//cadastro_prova.php
define('_AM_ASSESSMENT_DATAINICIOMAIORQFIM', 'A data/hora de in�cio da prova � maior que a de fim');
//geral
define('_AM_ASSESSMENT_SUCESSO', 'Opera��o realizada com sucesso');
define('_AM_ASSESSMENT_TOKENEXPIRED', 'Tempo para envio do formul�rio esgotado, envie novamente');
define('_AM_ASSESSMENT_ORDEM', 'Ordem');

//clone.php
define('_AM_ASSESSMENT_CLONE', '[Clone] ');
