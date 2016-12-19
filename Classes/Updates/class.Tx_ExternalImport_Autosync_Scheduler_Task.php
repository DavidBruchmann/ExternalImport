<?php

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

/**
 * This class is just used to be able to unserialize the objects stored in old Scheduler tasks.
 */
class tx_externalimport_autosync_scheduler_Task extends \Cobweb\ExternalImport\Task\AutomatedSyncTask
{


    /**
    * Gets the progress of a task / tableConfiguratio.
     *
     * @param array $tableConfiguration
     *
     * @return float Progress of the task as a two decimal precision float. f.e. 44.87
     */
    public function getProgress() {
            $result = 100;
                   /** @var $importer \Cobweb\ExternalImport\Importer */
                   $importer = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\Cobweb\ExternalImport\Importer::class);

                   if ($this->table == 'all') {
                               $result = $importer->getProgressForAllTables();
                               if ($result === FALSE) {
                                           if ($this->isExecutionRunning() || $this->isDisabled() || empty($task['nextexecution_tstamp']) || $task['nextexecution_tstamp'] < $GLOBALS['EXEC_TIME']) {
                                                       $result = 0;
                                               } else {
                                                       $result = 100;
                                               }
                       }
               } else {
                               $result = $importer->getProgressForTable($this->table, $this->index);
                               if ($result === FALSE) {
                                           /** @var $schedulerRepository \Cobweb\ExternalImport\Domain\Repository\SchedulerRepository */
                                           $schedulerRepository = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\Cobweb\ExternalImport\Domain\Repository\SchedulerRepository::class);
                                           $tasks = $schedulerRepository->fetchAllTasks();
                                           $taskKey = $this->table . '/' . $this->index;
                                           if (isset($tasks[$taskKey])) {
                                                       $task = $tasks[$taskKey];
                                                       // set progressbar to zero if task is running, disabled, has no nextexecution or is late - otherwise set the task to 100%
                                                       if ($this->isExecutionRunning() || $this->isDisabled() || empty($task['nextexecution_tstamp']) || $task['nextexecution_tstamp'] < $GLOBALS['EXEC_TIME']) {
                                                                   $result = 0;
                                                           } else {
                                                                   $result = 100;
                                                           }
                               }
                       }
               }

        return $result;
    }
}