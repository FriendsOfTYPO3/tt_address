<?php
namespace TYPO3\TtAddress\Controller;

/**
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
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * Main Controller
 * @author Daniel Pfeil
 * @version 1.0.0
 * @copyright Liebenzeller Mission gemeinnützige GmbH
 */
class MainController extends ActionController{

    /**
     * @var \TYPO3\TtAddress\Domain\Repository\AddressRepository
     * @inject
     */
    protected $addressRepository;

    /**
     * @param integer $id
     */
    public function showAction($id = null){
        if($id)
            $addressId = $this->addressRepository($id);
        elseif($this->settings["addressSelector"])
            $addressId = $this->settings["addressSelector"];
        else
            new \RuntimeException();

        $address = $this->addressRepository->findByUid($addressId);
        $this->view->assign("address",$address);
    }

    /**
     * @param array $pids
     * @param array $uids
     */
    public function listAction($pids = array(), $uids = array()){
        if(count($pids) == 0)
            $pids = $this->settings["pids"];
            $pids = explode(",", $pids);
        if(count($uids) == 0)
            $uids = $this->settings["uids"];
            $uids = explode(",", $uids);

        $addresses = array();

        if(isset($uids) && count($uids) > 1){
            foreach($uids as $uid){
                $address = $this->addressRepository->findByUid($uid);
                $addresses[$address->getUid()] = $address;
            }
        }
        else{
            if($uids[0] > 0){
                $address = $this->addressRepository->findByUid($uids[0]);
                $addresses[$address->getUid()] = $address;
            }
        }

        $querySettings = $this->addressRepository->createQuery()->getQuerySettings();
        $querySettings->setRespectStoragePage(FALSE);
        $this->addressRepository->setDefaultQuerySettings($querySettings);

        if(isset($pids) && count($pids) > 1){
            foreach($pids as $pid){
                $addressesFromPage = $this->addressRepository->findByPid($pid);
                foreach($addressesFromPage as $address){
                    $addresses[$address->getUid()] = $address;
                }
            }
        }
        else{
            if($pids[0] > 0){
                $addressesFromPage = $this->addressRepository->findByPid($pids[0]);
                foreach($addressesFromPage as $address){
                    $addresses[$address->getUid()] = $address;
                }
            }
        }

        $this->view->assign("columns",$this->settings["columns"]);
        $this->view->assign("addresses",$addresses);
    }
}
?>