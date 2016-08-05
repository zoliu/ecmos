 /**
 * $Id: abbr.js 6572 2009-02-25 02:46:35Z Garbin $
 *
 * @author Moxiecode - based on work by Andrew Tetlaw
 * @copyright Copyright © 2004-2008, Moxiecode Systems AB, All rights reserved.
 */

function init() {
    SXE.initElementDialog('abbr');
    if (SXE.currentAction == "update") {
        SXE.showRemoveButton();
    }
}

function insertAbbr() {
    SXE.insertElement(tinymce.isIE ? 'html:abbr' : 'abbr');
    tinyMCEPopup.close();
}

function removeAbbr() {
    SXE.removeElement('abbr');
    tinyMCEPopup.close();
}

tinyMCEPopup.onInit.add(init);
