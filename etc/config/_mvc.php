<?php

$sColCmd = "\033[0;36m";
$sColOff = "\033[0m";

/*
 * add command for emvicy console
 */
$aConfig['EMVICY_CONSOLE'][] = array(

    'register' => 'routes:dbimport',
    'aliases' => ['rtdbi'],
    'description' => $sColCmd . "php emvicy routes:dbimport" . $sColOff . " => imports Route config into database table `RouteDBModelDBTableRoute` for new.",
    'argumentName' => '',
    'argumentMode' => 2, # 1=REQUIRED; 2=OPTIONAL

    'code' => function (\Symfony\Component\Console\Input\InputInterface $oInputInterface, \Symfony\Component\Console\Output\OutputInterface $oOutputInterface): int {
        \RouteDB\Model\Route::init(
            bForceImport: true
        );
        return \Symfony\Component\Console\Command\Command::SUCCESS;
    }
);