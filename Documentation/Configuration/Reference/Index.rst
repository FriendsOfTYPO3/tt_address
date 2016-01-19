.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _configuration-reference:

Reference
---------

======================================  ==========  =============================================================  =========================
Property:                               Data type:  Description:                                                   Default:
======================================  ==========  =============================================================  =========================
templatePath                            string      Defines the path where the templates are located. Put          fileadmin/templates/
                                                    templates in that folder and they will be listed
                                                    automatically in the plugin. You can also add a small
                                                    graphic showing how the address will be displayed. That
                                                    graphic need to have the same name as the template but
                                                    must be a gif. This basically works like the template
                                                    selector from MTB2.
--------------------------------------  ----------  -------------------------------------------------------------  -------------------------
defaultTemplateFileName                 string      Defines the default template file.                             addressgroups_default.htm
--------------------------------------  ----------  -------------------------------------------------------------  -------------------------
pidList                                 int         A comma separated list of integers representing page
                                                    ids where to get the address records from
--------------------------------------  ----------  -------------------------------------------------------------  -------------------------
recursive                               int         Defines how many levels to search for tt_address
                                                    records from the given pages in pidList.
--------------------------------------  ----------  -------------------------------------------------------------  -------------------------
wrap                                    string      wraps the whole output
--------------------------------------  ----------  -------------------------------------------------------------  -------------------------
singleSelection                         string /
                                        stdWrap     Comma separated list of tt_address record uids, will be
                                                    overriden with flexform data
--------------------------------------  ----------  -------------------------------------------------------------  -------------------------
groupSelection                          string /
                                        stdWrap     Comma separated list of tt_address group record uids,
                                                    will be overriden with flexform data
--------------------------------------  ----------  -------------------------------------------------------------  -------------------------
combination                             int         0 = AND, 1 = OR
--------------------------------------  ----------  -------------------------------------------------------------  -------------------------
sortByColumn                            string      Defines by which tt_address column you want to sort, if        name
                                                    an invalid column is given it's set to 'name'

                                                    Valid columns for sorting:

                                                    uid, pid, tstamp, name, title, email, phone, mobile,
                                                    www, address, company, city, zip, country, image, fax,
                                                    description

                                                    If set to “singleSelection” and only single records
                                                    are selected by TypoScript or Flexform, the sorting
                                                    order of TypoScript selection of Flexform selection is
                                                    respected.
--------------------------------------  ----------  -------------------------------------------------------------  -------------------------
sortOrder                               string      Defines the sorting order, DESC for descending and ASC         ASC
                                                    for ascending. Any other (invalid) value is set to ASC.
--------------------------------------  ----------  -------------------------------------------------------------  -------------------------
templates.[TEMPLATE_NAME]               wrap        wraps a single address
--------------------------------------  ----------  -------------------------------------------------------------  -------------------------
templates.[TEMPLATE_NAME]               allWrap     wraps the whole output of the specific template
--------------------------------------  ----------  -------------------------------------------------------------  -------------------------
templates.[TEMPLATE_NAME].[FIELD_NAME]  stdWrap     The configurations for the different templates goes
                                                    here. Let's have a look at an example:

                                                    Let's assume you have three different html templates:

                                                    \* template_1.htm

                                                    \* other_template.htm

                                                    \* different_template.htm

                                                    TEMPLATENAME is the file name without the extension.
                                                    Now you can configure each of these templates:

                                                    **Example:**

                                                    plugin.tx_ttaddress_pi1 {

                                                    templates.template_1 {

                                                    email.wrap = E-Mail: |<br />

                                                    email.required = 1

                                                    ...

                                                    }

                                                    templates.other_template {

                                                    ...

                                                    }

                                                    templates.different_template {

                                                    ...

                                                    }

                                                    }

                                                    each standard tt_address field can be configured with
                                                    stdWrap properties. Like wrap and required which will
                                                    propably be the most important and most used ones.

                                                    Here's the list of default fields you can use inside
                                                    each template configuration:

                                                    gender, first_name, middle_name, last_name, title,
                                                    email, phone, mobile, www, address, building, room,
                                                    birthday, organization, city, zip, region, country,
                                                    image, fax, description, mainGroup

                                                    You can control max width and hight of an image with
                                                    image.file.maxW and image.file.maxH

                                                    A placeholder image can be defined:

                                                    ::


                                                    plugin.tx_ttaddress_pi1.templates.myTemplate.placeholderImage
                                                    = path/to/placeholder.png

                                                    (stdWrap enabled, inherits the configuration of the
                                                    image)

                                                    An example template can be found in the res folder in
                                                    the extension. This template also contains a list of
                                                    all available markers.
--------------------------------------  ----------  -------------------------------------------------------------  -------------------------
templates.[TEMPLATE_NAME.][subparts]                Each template configuration can have subparts.

                                                    You can define as many subparts as like, stdWrap
                                                    properties will apply to them. If you have a subpart
                                                    configuration

                                                    templates.other_template {

                                                    ...

                                                    subparts {

                                                    xyz {

                                                    }

                                                    abc {

                                                    }

                                                    }

                                                    }

                                                    you can use these subparts in your HTML template like

                                                    <!-- ###SUBPART_XYZ### begin -->

                                                    ...

                                                    <!-- ###SUBPART_XYZ### end -->

                                                    <!-- ###SUBPART_ABC### begin -->

                                                    ...

                                                    <!-- ###SUBPART_ABC### end -->

                                                    Subparts have a special condition property: hasOneOf,
                                                    with this property you define that an address record
                                                    must have at least one of the defined name fields
                                                    before the whole subpart is shown:

                                                    subparts.abc.hasOneOf = city, zip, country
======================================  ==========  =============================================================  =========================

[tsref:plugin.tx_ttaddress_pi1]
