/* globals */
var toTrim = ' ,.;:_~=+<>!?|$%^&*@#`\'"{}()[\\\]\\/-',
    regexpTrailing = new RegExp("(^[" + toTrim + "]+)|" +
                                 "([" + toTrim + "]+$)", 'g');

/* functions */

function getUserData(isInit) {

    // initialise temporary objects for raw and formatted data
    var data = { nameFirst: null,
                 nameLast: null,
                 age: null },
        echo = { nameFirst: '',
                 nameLast: '',
                 age: '' };

    if (!isInit) {
        echo.nameFirst = localUser.nameFirst == null ? '' : localUser.nameFirst;
        echo.nameLast = localUser.nameLast == null ? '' : localUser.nameLast;
        echo.age = localUser.age == null ? '' : localUser.age;
    }

    data.nameFirst = prompt('What\'s your first name?', echo.nameFirst);
    data.nameFirst = trimTrailing(data.nameFirst);
    
    if (isInit) {
        data.nameLast = null;
    } else {
        // don't ask for a last name when the page loads
        data.nameLast = prompt('What\'s your last name?', echo.nameLast);
        data.nameLast = trimTrailing(data.nameLast);
    }

    var ageInput = prompt('How old are you, in years?', echo.age),
        ageInt = parseInt(ageInput);

    while (ageInput != '' && // user doesn't leave field blank
           ageInput != null && // user doesn't cancel prompt
           (ageInt < 0 || isNaN(ageInt))) { // user submits invalid age

        ageInput = prompt('Please enter a valid age or nothing.\n' +
                     'How old are you, in years?', ageInput);
        ageInt = parseInt(ageInput);

    }

    if (ageInput == '') { // user left field blank
        data.age = null;
    } else if (ageInput != null) { // ...but didn't cancel prompt
        data.age = ageInt;
    }

    // prevent cancelled prompts from removing data
    data.nameFirst = data.nameFirst == null ?
                     localUser.nameFirst : data.nameFirst;
    data.nameLast = data.nameLast == null ?
                    localUser.nameLast : data.nameLast;
    data.age = data.age == null ?
               localUser.age : data.age;

    return data;

}

function trimTrailing(name) { // remove guff from start and end of string

    if (name !== null) {
        return name.replace(regexpTrailing, '');
    } else {
        return name;
    }

}

/* event handlers */

window.onload = function() { // fired once everything has loaded

    // globals
    localUser = new User();

    var data;
    if (typeof localStorage.localUserData === 'undefined') {
        // prompt for user info
        data = getUserData(true);
    } else {
        // read in data from localStorage
        data = JSON.parse(localStorage.localUserData);
    }

    localUser.updateData(data);

    elemBtnView = document.getElementById('bv');
    elemBtnUpdate = document.getElementById('bu');
    elemBtnClear = document.getElementById('bc');
    
    // "view user data" button event
    elemBtnView.onmousedown = function() {
        alert('Full name: ' + localUser.nameFull() + ',\n' +
              'Age: ' + localUser.ageFormatted() + '.');
    };

    // "update user data" button event
    elemBtnUpdate.onmousedown = function() {
        var data = getUserData(false);
        localUser.updateData(data);
    };

    // "clear user data" button event
    elemBtnClear.onmousedown = function() {
        localUser.clear();
        localStorage.removeItem('localUserData');
    }

}

window.onunload = function() {
    // save user data when the page is navigated away from, if it should be
    if (!(localUser.nameFirst === null &&
          localUser.nameLast === null &&
          localUser.age === null &&
          typeof localStorage.localUserData === 'undefined')) {
        localStorage.localUserData = JSON.stringify(localUser.exportData());
    }
};
