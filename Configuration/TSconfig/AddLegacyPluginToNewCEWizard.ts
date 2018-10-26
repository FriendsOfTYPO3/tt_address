mod.wizards.newContentElement.wizardItems.plugins {
    elements.tx_ttaddress_pi1 {
        iconIdentifier = tt-address-plugin
        title = LLL:EXT:tt_address/Resources/Private/Language/locallang_pi1.xlf:pi1_title
        description = LLL:EXT:tt_address/Resources/Private/Language/locallang_pi1.xlf:pi1_plus_wiz_description
        tt_content_defValues {
            CType = list
            list_type = tt_address_pi1
        }
    }
    show := addToList(tx_ttaddress_pi1)
}