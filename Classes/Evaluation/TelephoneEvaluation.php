<?php
declare(strict_types=1);

namespace FriendsOfTYPO3\TtAddress\Evaluation;

use FriendsOfTYPO3\TtAddress\Domain\Model\Dto\Settings;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Page\JavaScriptModuleInstruction;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

/**
 * Class for telephone number validation/evaluation to be used in 'eval' of TCA
 */
class TelephoneEvaluation
{
    /** @var Settings */
    protected $extensionSettings;

    public function __construct()
    {
        $this->extensionSettings = GeneralUtility::makeInstance(Settings::class);
    }

    /**
     * JavaScript code for client side validation/evaluation
     */
    public function returnFieldJS()
    {
        if ((new Typo3Version())->getMajorVersion() >= 12) {
            GeneralUtility::makeInstance(PageRenderer::class)->addInlineSetting(
                'TtAddress.Evaluation',
                'telephoneValidationPattern',
                $this->extensionSettings->getTelephoneValidationPatternForJs()
            );
            return JavaScriptModuleInstruction::create(
                '@friendsoftypo3/tt-address/telephone-evaluation.js',
                'TelephoneEvaluation'
            );
        }
        return '
         return value.replace(' . $this->extensionSettings->getTelephoneValidationPatternForJs() . ', "");
      ';
    }

    /**
     * Server-side validation/evaluation on saving the record
     *
     * @param string $value The field value to be evaluated
     * @return string Evaluated field value
     */
    public function evaluateFieldValue($value)
    {
        return $this->evaluate($value);
    }

    /**
     * Server-side validation/evaluation on opening the record
     *
     * @param array $parameters Array with key 'value' containing the field value from the database
     * @return string Evaluated field value
     */
    public function deevaluateFieldValue(array $parameters)
    {
        return $this->evaluate($parameters['value']);
    }

    private function evaluate(string $in)
    {
        $data = preg_replace($this->extensionSettings->getTelephoneValidationPatternForPhp(), '', $in);
        return trim($data);
    }
}
