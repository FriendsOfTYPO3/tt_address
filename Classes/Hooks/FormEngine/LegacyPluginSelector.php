<?php

namespace FriendsOfTYPO3\TtAddress\Hooks\FormEngine;

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

use TYPO3\CMS\Core\Html\HtmlParser;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\TypoScript\TemplateService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Page\PageRepository;

/**
 * Hook class with two entry points:
 * - addFieldsToSelector (add additional fields to a dropdown)
 * - addFilesToSelector (check for template files for a dropdown)
 */
class LegacyPluginSelector
{


    /** @var LanguageService */
    protected $languageService;

    public function __construct()
    {
        $this->languageService = $GLOBALS['LANG'];
    }

    /**
     * Manipulating the input array, $params, adding new selectorbox items.
     *
     * @param    array $params array of select field options (reference)
     */
    public function addFieldsToSelector(&$params)
    {
        // TODO consolidate with list in pi1
        $coreSortFields = 'gender, first_name, middle_name, last_name, title, company, '
            . 'address, building, room, birthday, zip, city, region, country, email, www, phone, mobile, '
            . 'fax';

        $sortFields = GeneralUtility::trimExplode(',', $coreSortFields);

        $selectOptions = [];
        foreach ($sortFields as $field) {
            $label = $this->languageService->sL($GLOBALS['TCA']['tt_address']['columns'][$field]['label']);
            $label = rtrim($label, ':');

            $selectOptions[] = [
                'field' => $field,
                'label' => $label
            ];
        }

        // add sorting by order of single selection
        $selectOptions[] = [
            'field' => 'singleSelection',
            'label' => $this->languageService->sL('LLL:EXT:tt_address/Resources/Private/Language/locallang_pi1.xlf:pi1_flexform.sortBy.singleSelection')
        ];

        // sort by labels
        $labels = [];
        foreach ($selectOptions as $key => $v) {
            $labels[$key] = $v['label'];
        }
        $labels = array_map('strtolower', $labels);
        array_multisort($labels, SORT_ASC, $selectOptions);

        // add fields to <select>
        foreach ($selectOptions as $option) {
            $params['items'][] = [
                $option['label'],
                $option['field']
            ];
        }
    }

    /**
     * Manipulating the input array, $params, adding new selectorbox items.
     *
     * Checks for the TypoScript path of the templates and looks up if there is a ".gif" file at the same location
     *
     * @param    array $params array of select field options (reference)
     * @param    object $pObj parent object (reference)
     */
    public function addFilesToSelector(&$params, &$pObj)
    {
        $pageId = (int)$params['flexParentDatabaseRow']['pid'];
        $readPath = $this->getTemplatePathFromTypoScriptOfPage($pageId);

        // If that directory is valid and is a directory then select files in it
        if (!empty($readPath) && @is_dir($readPath)) {
            $template_files = GeneralUtility::getFilesInDir($readPath, 'tmpl,html,htm', true);
            /** @var HtmlParser $parseHTML */
            $parseHTML = GeneralUtility::makeInstance(HtmlParser::class);

            foreach ($template_files as $htmlFilePath) {
                // Read template content
                $content = file_get_contents($htmlFilePath);
                // ... and extract content of the title-tags
                $parts = $parseHTML->splitIntoBlock('title', $content);
                $titleTagContent = $parseHTML->removeFirstAndLastTag($parts[1]);

                // set the item label
                $selectorBoxItem_title = trim($titleTagContent . ' (' . basename($htmlFilePath) . ')');

                // try to look up an image icon for the template
                $fI = GeneralUtility::split_fileref($htmlFilePath);

                $fileExtensionsToCheck = ['.gif', '.png', '.jpeg', '.jpg'];
                $selectorBoxItem_icon = '';
                foreach ($fileExtensionsToCheck as $fileExtension) {
                    $testImageFilename = $readPath . $fI['filebody'] . $fileExtension;
                    if (@is_file($testImageFilename)) {
                        $selectorBoxItem_icon = '../' . substr($testImageFilename, strlen(PATH_site));
                        break;
                    }
                }

                // finally add the new item
                $params['items'][] = [
                    $selectorBoxItem_title,
                    basename($htmlFilePath),
                    $selectorBoxItem_icon
                ];
            }
        }
    }

    /**
     * Parses the TypoScript of a given page
     * We know this is not bullet proof at all (TS conditions, different locations of TS inclusions)
     * but it is kept for legacy reasons.
     *
     * @param int $pageId
     * @return string the path to the templates according to TypoScript
     */
    protected function getTemplatePathFromTypoScriptOfPage($pageId)
    {
        /** @var TemplateService $template */
        $template = GeneralUtility::makeInstance(TemplateService::class);
        // do not log time-performance information
        $template->tt_track = false;
        $template->init();

        /** @var PageRepository $sys_page */
        $sys_page = GeneralUtility::makeInstance(PageRepository::class);
        $rootLine = $sys_page->getRootLine((int)$pageId);
        // generate the constants/config + hierarchy info for the template.
        $template->runThroughTemplates($rootLine);
        $template->generateConfig();

        // get value for the path containing the template files
        return GeneralUtility::getFileAbsFileName($template->setup['plugin.']['tx_ttaddress_pi1.']['templatePath']);
    }
}
