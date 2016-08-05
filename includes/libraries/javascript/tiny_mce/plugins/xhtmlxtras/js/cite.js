 /**
 * $Id: cite.js 6572 2009-02-25 02:46:35Z Garbin $
 *
 * @author Moxiecode - based on work by Andrew Tetlaw
 * @copyright Copyright © 2004-2008, Moxiecode Systems AB, All rights reserved.
 */

function init() {
    SXE.initElementDialog('cite');
    if (SXE.currentAction == "update") {
        SXE.showRemoveButton();
    }
}

function insertCite() {
    SXE.insertElement('cite');
    tinyMCEPopup.close();
}

function removeCite() {
    SXE.removeElement('cite');
    tinyMCEPopup.close();
}

tinyMCEPopup.onInit.add(init);
