<?php
// $Id: main.php,v 1.7 2007/03/24 20:09:11 marcellobrandao Exp $
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/
//fecharprova.php
define('_MA_ASSESSMENT_CONGRATULATIONS', 'Congratulations, you have finished your exam!');

//fimprova.php
define('_MA_ASSESSMENT_PROIBIDO', "You're not authorized to see this exam");
define('_MA_ASSESSMENT_ACABOU', 'Your time has run out! Your exam has been saved!');
define('_MA_ASSESSMENT_ANDAMENTO', 'You answered %s out of %s question(s)');
define('_MA_ASSESSMENT_TERMINOU', 'You have finished the exam in %s hour(s) and %s minute(s)');
define('_MA_ASSESSMENT_TEMPORESTANTE', 'You stil have');
define('_MA_ASSESSMENT_ALERTA', "Are you sure you want to finish your exam now? You won't be able to change your answers later");
define('_MA_ASSESSMENT_CONFIRMASIM', 'Yes, I am sure');
define('_MA_ASSESSMENT_CONFIRMANAO', 'No, let me take a second look at the questions');
define('_MA_ASSESSMENT_CONFIRMACAO', 'Confirmation:');
define('_MA_ASSESSMENT_STATS', 'Stats:');

//form_resposta.php
define('_MA_ASSESSMENT_SUCESSO', 'Action succeeded');
define('_MA_ASSESSMENT_RESPOSTA_REPETIDA', 'You had answered exactly the same before');

//perguntas.php
define('_MA_ASSESSMENT_JATERMINOU', "You had already finished this exam and now you have to wait for the teacher's validation");
define('_MA_ASSESSMENT_PROVAVAZIA', 'This exam has no questions, yet');
define('_MA_ASSESSMENT_ENCERRARPROVA', 'Finish the exam');
define('_MA_ASSESSMENT_TEXTOSAPOIO', 'Document:');
define('_MA_ASSESSMENT_PERGUNTA', 'Question');
define('_MA_ASSESSMENT_RESPOSTAS', 'Answers:');
define('_MA_ASSESSMENT_RESPOSTA', 'Answer');
define('_MA_ASSESSMENT_LEGENDA', 'Legend:');
define('_MA_ASSESSMENT_JARESP', 'Question already answered');
define('_MA_ASSESSMENT_NAORESP', 'Question still not answered');
define('_MA_ASSESSMENT_ICONJARESP', 'Icon Question already answered');
define('_MA_ASSESSMENT_ICONNAORESP', 'Icon Question still not answered');
define('_MA_ASSESSMENT_TEMPORESTANTECOMPOSTO', 'You still have %s hour(s) and %s minute(s) to finish the  exam');

//class/navegacao.php
define('_MA_ASSESSMENT_PROXIMO', 'Next');
define('_MA_ASSESSMENT_ANTERIOR', 'Previous');

//preperguntas.php
define('_MA_ASSESSMENT_CONTAGEMSTART', "We've started counting the time of your exam NOW");
define('_MA_ASSESSMENT_PROVAEMANDAMENTO', 'You had already started, hurry up your time is running out');

//index.php
define('_MA_ASSESSMENT_NOTAFINAL', 'Final grade:');
define('_MA_ASSESSMENT_LISTAPROVAS', 'Exams:');
define('_MA_ASSESSMENT_PERGUNTAS', 'question(s)');
define('_MA_ASSESSMENT_NIVEL', 'Level:');
define('_MA_ASSESSMENT_EMCORRECAO', 'Your exam is still being marked');
define('_MA_ASSESSMENT_INICIO', 'From');
define('_MA_ASSESSMENT_FIM', 'To');
define('_MA_ASSESSMENT_TEMPOENCERRADO', 'Not available');
define('_MA_ASSESSMENT_DISPONIBILIDADE', 'Availability');

//verprova.php
define('_MA_ASSESSMENT_INSTRUCOES', 'Instructions:');
define('_MA_ASSESSMENT_COMECAR', 'Start exam');
define('_MA_ASSESSMENT_PROVA', 'Exam:');

//form_resposta.php
define('_MA_ASSESSMENT_RESPOSTAEMBRANCO', 'You left your answer in blank');

//gerais
define('_MA_ASSESSMENT_TOKENEXPIRED', 'Your time to send the form has expired, submit the form again');

define('dias_por_mes', (((365 * 3) + 366) / 4) / 12);

//class/Question.php
