<?php

require_once 'Classes/Dbh.php';
require_once 'Classes/ShowTable_Model.php';
require_once 'Classes/ShowTable_Contr.php';
require_once 'Classes/ShowTable_View.php';

$showTableView = new ShowTableView("availability");

$data = $showTableView->showTable();

echo $data;