<?php

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

// Module Info

// The name of this module
define('_MI_ASSESSMENT_NAME', 'Assessment');

// A brief description of this module
define('_MI_ASSESSMENT_DESC', 'This module enables you to create questions, answers and group them all together to create an exam/assessment');

// Names of admin menu items
define('_MI_ASSESSMENT_ADMENU1', 'Exams');
define('_MI_ASSESSMENT_ADMENU2', 'Results');
define('_MI_ASSESSMENT_ADMENU3', 'Document');
define('_MI_ASSESSMENT_HOME', 'Home');
define('_MI_ASSESSMENT_ABOUT', 'About');

//Configuration options
define('_MI_ASSESSMENT_CONFIG1_DESC', 'Number of items by row in questions.php');
define('_MI_ASSESSMENT_CONFIG1_TITLE', 'Size of Navigation bar');
define('_MI_ASSESSMENT_CONFIG2_DESC', 'Chose your favourite editor');
define('_MI_ASSESSMENT_CONFIG2_TITLE', 'Editor');
define('_MI_ASSESSMENT_CONFIG3_DESC', 'Would like to have your results directly <br>instead of waiting for the teachers correction?');
define('_MI_ASSESSMENT_CONFIG3_TITLE', 'Direct Result');
define('_MI_ASSESSMENT_CONFIG4_DESC', 'Quantity of exams, questions, documents or results<br>in the lists of the admin side of the module');
define('_MI_ASSESSMENT_CONFIG4_TITLE', 'Items per page');
define('_MI_ASSESSMENT_CONFIG5_DESC', 'Quantity of exams in the list of the main index.php page');
define('_MI_ASSESSMENT_CONFIG5_TITLE', 'Exams in index');

//Description of the templates
define('_MI_ASSESSMENT_TPL1_TITLE', "Module's main page");
define('_MI_ASSESSMENT_TPL2_TITLE', 'Starting page of an exam');
define('_MI_ASSESSMENT_TPL3_TITLE', "Question's page");
define('_MI_ASSESSMENT_TPL4_TITLE', 'Confirmation of ending exam page');

//Notifications
define('_MI_ASSESSMENT_PROVA_NOTIFY', 'Result');
define('_MI_ASSESSMENT_PROVA_CORRIGIDA_NOTIFY', 'Exam grading');
define('_MI_ASSESSMENT_PROVA_CORRIGIDA_NOTIFYCAP', 'Warn me when results are in');
define('_MI_ASSESSMENT_PROVA_NOTIFYDSC', 'Category of Results');
define('_MI_ASSESSMENT_PROVA_CORRIGIDA_ASSUNTOMAIL', 'The results are in!');
define('_MI_ASSESSMENT_PROVA_CORRIGIDA_NOTIFYDSC', 'Warn the student when the teacher has finish correcting the exam');

//Config
define('MI_ASSESSMENT_EDITOR_ADMIN', 'Editor: Admin');
define('MI_ASSESSMENT_EDITOR_ADMIN_DESC', 'Select the Editor to use by the Admin');
define('MI_ASSESSMENT_EDITOR_USER', 'Editor: User');
define('MI_ASSESSMENT_EDITOR_USER_DESC', 'Select the Editor to use by the User');

//Help
define('_MI_ASSESSMENT_DIRNAME', basename(dirname(dirname(__DIR__))));
define('_MI_ASSESSMENT_HELP_HEADER', __DIR__ . '/help/helpheader.tpl');
define('_MI_ASSESSMENT_BACK_2_ADMIN', 'Back to Administration of ');
define('_MI_ASSESSMENT_OVERVIEW', 'Overview');
define('_MI_ASSESSMENT_LANGUAGE', $GLOBALS['xoopsConfig']['language']);

//define('_MI_ASSESSMENT_HELP_DIR', __DIR__);

//help multi-page
define('_MI_ASSESSMENT_DISCLAIMER', 'Disclaimer');
define('_MI_ASSESSMENT_LICENSE', 'License');
define('_MI_ASSESSMENT_SUPPORT', 'Support');
define('_MI_ASSESSMENT_TUTORIAL', 'Tutorial');


