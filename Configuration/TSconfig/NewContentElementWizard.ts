mod.wizards.newContentElement.wizardItems {
  plugins {
    elements {    
      tx_ttaddress_listview {
        title = LLL:EXT:tt_address/Resources/Private/Language/db/locallang.xlf:extbase_title
        description = LLL:EXT:tt_address/Resources/Private/Language/db/locallang.xlf:extbase_description
        icon = EXT:tt_address/Resources/Public/Icons/ce_wiz.gif
        tt_content_defValues {
          CType = list
          list_type = ttaddress_listview
        }
      }
    }
    show := addToList(tx_ttaddress_listview)
  }
}
