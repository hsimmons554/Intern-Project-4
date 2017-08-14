<?php
if(php_sapi_name()!=='cli')
    die('This script must be run from the command line.');

$queries = array();

$queries[] = "INSERT INTO states (state_name, state_abbreviation)
              VALUES ('Louisiana', 'LA')";
$queries[] = "INSERT INTO states (state_name, state_abbreviation)
              VALUES ('Texas', 'TX')";
$queries[] = "INSERT INTO states (state_name, state_abbreviation)
              VALUES ('California', 'CA')";
$queries[] = "INSERT INTO states (state_name, state_abbreviation)
              VALUES ('Mississippi', 'MS')";
$queries[] = "INSERT INTO states (state_name, state_abbreviation)
              VALUES ('Main', 'ME')";
$queries[] = "INSERT INTO states (state_name, state_abbreviation)
              VALUES ('Missouri', 'MO')";
$queries[] = "INSERT INTO states (state_name, state_abbreviation)
              VALUES ('Arkansas', 'AR')";
$queries[] = "INSERT INTO states (state_name, state_abbreviation)
              VALUES ('New Jersey', 'NJ')";
$queries[] = "INSERT INTO states (state_name, state_abbreviation)
              VALUES ('New York', 'NY')";
$queries[] = "INSERT INTO states (state_name, state_abbreviation)
              VALUES ('Ohio', 'OH')";
$queries[] = "INSERT INTO states (state_name, state_abbreviation)
              VALUES ('Pennsylvania', 'PA')";
$queries[] = "INSERT INTO states (state_name, state_abbreviation)
              VALUES ('Utah', 'UT')";

foreach($queries as $query){
    $container->getEntityManager()->getConnection()->exec($query);
}
