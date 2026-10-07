<?php

require_once 'bootstrap.inc.php';
require_once 'Classes/ShowTable_Model.php';
require_once 'Classes/ShowTable_Contr.php';
require_once 'Classes/ShowTable_View.php';

$showTableView = new ShowTableView("availability");

echo $showTableView->showTable();