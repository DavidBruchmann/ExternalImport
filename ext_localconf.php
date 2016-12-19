<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

// Register handler calls for Scheduler
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][\Cobweb\ExternalImport\Task\AutomatedSyncTask::class] = array(
        'extension' => $_EXTKEY,
        'title' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/ExternalImport.xlf:scheduler.title',
        'description' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/ExternalImport.xlf:scheduler.description',
        'additionalFields' => \Cobweb\ExternalImport\Task\AutomatedSyncAdditionalFieldProvider::class
);

// Register Scheduler tasks update script
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update']['external_import_tasks'] = \Cobweb\ExternalImport\Updates\SchedulerTasksWizard::class;
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$_EXTKEY]['processParameters'][] = \Cobweb\ExternalImport\Hooks\CycleHook::class;
