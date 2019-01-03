<?php
declare(strict_types = 1);

namespace FriendsOfTYPO3\TtAddress\FormEngine\FieldControl;

/**
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Backend\Form\AbstractNode;

class LocationMapWizard extends AbstractNode
{
    public function render()
    {
        $result = [
         'iconIdentifier' => 'location-map-wizard',
         'title' => $GLOBALS['LANG']->sL('LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address.locationMapWizard'),
         'linkAttributes' => [
            'class' => 'locationMapWizard ',
            'data-id' => $this->data['databaseRow']['longitude']
         ],
      ];
        return $result;
    }
}
