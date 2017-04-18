### TypoScript Setup for extbase plugin for tt_address

plugin.tx_ttaddress {
  view {
    templateRootPath = {$plugin.tx_ttaddress.view.templateRootPath}
    partialRootPath = {$plugin.tx_ttaddress.view.partialRootPath}
    layoutRootPath = {$plugin.tx_ttaddress.view.layoutRootPath}
  }
  settings {
    ## Override settings if empty in flexform
    overrideFlexformSettingsIfEmpty = paginate.itemsPerPage, singlePid, recursive
    ## Default configuration for pagebrowser
    paginate {
      itemsPerPage = 15
      insertAbove = 1
      insertBelow = 1
      templatePath =
      prevNextHeaderTags = 1
      maximumNumberOfLinks = 6
    }
    detail {
      ## Set your lightbox here. The address records UID is appended, see fluid template
      imageClass = lightbox lightbox_
    }
  }
}


plugin.tx_ttaddress._CSS_DEFAULT_STYLE (
  /** Some very basic styles for tt_address **/
  .addressImage {
    float:left;
    margin-right:10px;
    margin-bottom:15px;
    width: 140px;
    height: 140px;
  }
  /** add paddings to images **/
  .address-image-rows>a {
    text-decoration:none !important;
    padding-right:5px;
  }
  .address-image-rows>a>img {
    padding-bottom:5px;
  }
  /** remove right-padding for every third image **/
  .address-image-rows>a:nth-child(3n) {
    padding-right:0px;
  }
  
  .vcard {
    clear:both;
  }
  .addressImagePlaceholder {
    background-color:#CCC;
    width: 140px;
    height: 140px;
  }

  /** pagination viewhelper **/
  ul.f3-widget-paginator {
    list-style-type:none;
    float:right;
  }
  ul.f3-widget-paginator li {
    list-style-type:none;
    float:left;
    padding-right:7px;
  }
  ul.f3-widget-paginator li.next {
    padding-right:0px;
  }
  
  /** Helper classes **/
  .tt_address_line {
    border-bottom: 1px solid #CCC;
    clear:both;
    height:5px;
    line-height:5px;
  }
  .div15 {
    display:block;
    height:15px;
    width:1px;
    overflow:hidden;
    line-height:14px;
    margin:0;
    padding:0;
    clear:both;
    font-size:15px;
    font-family:Arial, sans-serif;
  }
)

### EOF