<?php

namespace Addons\InsertLocalVideo\Controller;
use Home\Controller\AddonsController;

class VideoController extends AddonsController{
   
   public function getVideoRoot()
    {
        $root=__ROOT__;
        $this->ajaxReturn( $root);
        
    }

    public function getVideoDriver()
    {
        $driver = modC('DOWNLOAD_UPLOAD_DRIVER','local','config');
        $this->ajaxReturn( $driver);

    }

}