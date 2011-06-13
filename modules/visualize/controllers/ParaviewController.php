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

class Visualize_ParaviewController extends Visualize_AppController
{
  public $_models = array('Item', 'ItemRevision', 'Bitstream');
  /** index */
  public function indexAction()
    {
    $this->_helper->layout->disableLayout();
    $itemid = $this->_getParam('itemId');
    $item = $this->Item->load($itemid);
    
    if($item === false || !$this->Item->policyCheck($item, $this->userSession->Dao, MIDAS_POLICY_READ))
      {
      throw new Zend_Exception("This item doesn't exist  or you don't have the permissions.");
      }   
    
    $modulesConfig=Zend_Registry::get('configsModules');
    $paraviewworkdir = $modulesConfig['visualize']->paraviewworkdir;
    $customtmp = $modulesConfig['visualize']->customtmp;    
    $useparaview = $modulesConfig['visualize']->useparaview;
    $userwebgl = $modulesConfig['visualize']->userwebgl;
    if(!isset($useparaview) || !$useparaview)
      {
      throw new Zend_Exception('Please unable paraviewweb');
      }
      
    if(!isset($paraviewworkdir) || empty($paraviewworkdir))
      {
      throw new Zend_Exception('Please set the paraview work directory');
      }
    
    if(isset($customtmp) && !empty($customtmp))
      {
      $tmp_dir = $customtmp;
      if(!file_exists($tmp_dir) || !is_writable($tmp_dir))
        {
        throw new Zend_Exception('Unable to access temp dir');
        }
      }
    else
      {
      if(!file_exists(BASE_PATH.'/tmp/visualize'))
        {
        mkdir(BASE_PATH.'/tmp/visualize');
        }
      $tmp_dir = BASE_PATH.'/tmp/visualize';
      }
      
    $dir = opendir($tmp_dir);
    while($entry = readdir($dir)) 
      { 
      if(is_dir($tmp_dir.'/'.$entry) && filemtime($tmp_dir.'/'.$entry) < strtotime('-1 hours') && !in_array($entry, array('.','..')))
        {
        if(strpos($entry, 'Paraview') !== false)
          {
          $this->rrmdir($tmp_dir.'/'.$entry);
          }
        }
      }
    do
      {
      $tmpFolderName = 'ParaviewWeb_'.mt_rand(0, 9999999);
      $path = $tmp_dir.'/'.$tmpFolderName;
      }
    while (!mkdir($path));
    
    $revision = $this->Item->getLastRevision($item);
    $bitstreams = $revision->getBitstreams();
    foreach($bitstreams as $bitstream)
      {
      copy($bitstream->getFullPath(), $path.'/'.$bitstream->getName());
      $ext = strtolower(substr(strrchr($bitstream->getName(), '.'), 1));
      if($ext != 'pvsm')
        {
        $filePath = $paraviewworkdir."/".$tmpFolderName.'/'.$bitstream->getName();
        $mainBitstream = $bitstream;
        }
      }   
    
    $this->view->json['visualize']['openState'] = false;
      
    foreach($bitstreams as $bitstream)
      {
      $ext = strtolower(substr(strrchr($bitstream->getName(), '.'), 1));
      if($ext == 'pvsm')
        {        
        $file_contents = file_get_contents($path.'/'.$bitstream->getName());
        $file_contents = preg_replace('/\"([a-zA-Z0-9_.\/\\\:]{1,1000})'.  str_replace('.', '\.', $mainBitstream->getName())."/", '"'.$filePath, $file_contents);
        $filePath = $paraviewworkdir."/".$tmpFolderName.'/'.$bitstream->getName();
        $inF = fopen($path.'/'.$bitstream->getName(),"w");
        fwrite($inF, $file_contents);
        fclose($inF); 
        $this->view->json['visualize']['openState'] = true;
        break;
        }
      }  
      
    
    if(!$userwebgl || $item->getSizebytes()> 1*1024*1024)
      {
      $this->view->renderer = 'js';
      }
    else
      {
      $this->view->renderer = 'webgl';
      }
    $this->view->json['visualize']['url'] = $filePath;    
    $this->view->json['visualize']['width'] = $this->_getParam('width');    
    $this->view->json['visualize']['height'] = $this->_getParam('height');        
    $this->view->usewebgl = $userwebgl;
    }
    
  /** recursively delete a folder*/
  private function rrmdir($dir) 
    { 
    if(is_dir($dir)) 
      {      
      $objects = scandir($dir); 
      }

    foreach($objects as $object) 
      { 
      if($object != "." && $object != "..") 
        { 
        if(filetype($dir."/".$object) == "dir")
          {
          $this->rrmdir($dir."/".$object);
          }
        else 
          {
          unlink($dir."/".$object); 
          }
        } 
      } 
     reset($objects); 
     rmdir($dir); 
   }
} // end class
?>
