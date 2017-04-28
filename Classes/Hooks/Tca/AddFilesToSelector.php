<?php
namespace TYPO3\TtAddress\Hooks\Tca;

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
use TYPO3\CMS\Core\TypoScript\TemplateService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Page\PageRepository;

/**
 * Class AddFilesToSelector
 */
class AddFilesToSelector
{
    /**
     * Manipulating the input array, $params, adding new selectorbox items.
     *
     * @param	array	$params array of select field options (reference)
     * @param	object	$pObj parent object (reference)
     * @return	void
     */
    public function main(&$params, &$pObj)
    {

        // get the current page ID
	if (version_compare(TYPO3_version, '7.6', '<')) {
		// legacy code for 6.2
		$thePageId = $params['row']['pid'];
	} else {
		$thePageId = $params['flexParentDatabaseRow']['pid'];
	}

        /** @var TemplateService $template */
        $template = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\TypoScript\\TemplateService');
        // do not log time-performance information
        $template->tt_track = 0;
        $template->init();
        /** @var PageRepository $sys_page */
        $sys_page = GeneralUtility::makeInstance('TYPO3\\CMS\\Frontend\\Page\\PageRepository');
        $rootLine = $sys_page->getRootLine($thePageId);
        // generate the constants/config + hierarchy info for the template.
        $template->runThroughTemplates($rootLine);
        $template->generateConfig();

        // get value for the path containing the template files
        $readPath = GeneralUtility::getFileAbsFileName(
            $template->setup['plugin.']['tx_ttaddress_pi1.']['templatePath']
        );

        // if that direcotry is valid and is a directory then select files in it
        if (@is_dir($readPath)) {
            $template_files = GeneralUtility::getFilesInDir($readPath, 'tmpl,html,htm', 1, 1);
            /** @var HtmlParser $parseHTML */
            $parseHTML = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Html\\HtmlParser');

            foreach ($template_files as $htmlFilePath) {
                // reset vars
                $selectorBoxItem_title = '';
                $selectorBoxItem_icon  = '';

                // read template content
                $content = GeneralUtility::getUrl($htmlFilePath);
                // ... and extract content of the title-tags
                $parts = $parseHTML->splitIntoBlock('title', $content);
                $titleTagContent = $parseHTML->removeFirstAndLastTag($parts[1]);

                // set the item label
                $selectorBoxItem_title = trim($titleTagContent . ' (' . basename($htmlFilePath) . ')');

                // try to look up an image icon for the template
                $fI = GeneralUtility::split_fileref($htmlFilePath);
                $testImageFilename=$readPath . $fI['filebody'] . '.gif';
                if (@is_file($testImageFilename)) {
                    $selectorBoxItem_icon = '../' . substr($testImageFilename, strlen(PATH_site));
                }

                // finally add the new item
                $params['items'][] = array(
                    $selectorBoxItem_title,
                    basename($htmlFilePath),
                    $selectorBoxItem_icon
                );
            }
        }
    }
}
