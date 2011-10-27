<?php
/*=========================================================================
MIDAS Server
Copyright (c) Kitware SAS. 20 rue de la Villette. All rights reserved.
69328 Lyon, FRANCE.

See Copyright.txt for details.
This software is distributed WITHOUT ANY WARRANTY; without even
the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR
PURPOSE.  See the above copyright notices for more information.
=========================================================================*/

require_once BASE_PATH.'/modules/remoteprocessing/models/base/JobModelBase.php';

/** job model */
class Remoteprocessing_JobModel extends Remoteprocessing_JobModelBase
{
  /** get jobs */
  function getBy($os, $condition, $expiration_date = false, $status = MIDAS_REMOTEPROCESSING_STATUS_WAIT)
    {
    if($expiration_date == false)
      {
      $expiration_date = date('c');
      }
    $sql = $this->database->select()
          ->setIntegrityCheck(false)
          ->where('os = ?', $os)
          ->where('status = ?', $status)
          ->where('expiration_date > ?', $expiration_date)
          ->order('job_id DESC');

    $rowset = $this->database->fetchAll($sql);
    $return = array();
    foreach($rowset as $row)
      {
      $tmpDao = $this->initDao('Job', $row, 'remoteprocessing');
      $return[] = $tmpDao;
      unset($tmpDao);
      }
    return $return;
    }

  /** add item relation*/
  function addItemRelation($job, $item)
    {
    if(!$job instanceof Remoteprocessing_JobDao)
      {
      throw new Zend_Exception("Should be a job.");
      }
    if(!$item instanceof ItemDao)
      {
      throw new Zend_Exception("Should be an item.");
      }
    $this->database->link('items', $job, $item);
    }

  /** get related job */
  function getRelatedJob($item)
    {
    if(!$item instanceof ItemDao)
      {
      throw new Zend_Exception("Should be an item.");
      }

    $sql = $this->database->select()
          ->from('remoteprocessing_job2item')
          ->setIntegrityCheck(false)
          ->where('item_id = ?', $item->getKey())
          ->order('job_id DESC');

    $rowset = $this->database->fetchAll($sql);
    $return = array();
    foreach($rowset as $row)
      {
      $tmpDao = $this->load($row['job_id']);
      if($tmpDao != false)
        {
        $return[] = $tmpDao;
        unset($tmpDao);
        }
      }
    return $return;
    }
}  // end class
?>