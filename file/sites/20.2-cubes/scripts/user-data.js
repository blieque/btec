function User(nameFirst, age) {

    // known data
    this.nameFirst = nameFirst;
    this.age = age;

    // empty data
    this.nameLast = '';

    // full name generated from first and last name
    this.nameFull = function() {
        var space = this.nameFirst.length > 0 &&
                    this.nameLast.length > 0 ? ' ' : '';
        return this.nameFirst + space + this.nameLast;
    }

}

// executed once everything has loaded
window.onload = function() {

    // prompt for user info
    var nameFirst = prompt('What\'s your first name?',''),
        age = prompt('How old are you?','');

    // globals
    elemBtnView = document.getElementById('bv');
    elemBtnUpdate = document.getElementById('bu');
    localUser = new User(nameFirst, age);
    
    // "view user data" button event
    elemBtnView.onclick = function() {

        /* This definition must be within the window.onload function, as
         * elemBtnView will not otherwise be defined (and therefore cannot be
         * bound to). The definition of elemBtnView must be in the function as
         * well, as the element will not otherwise be loaded. */

        alert('Full name: ' + localUser.nameFull() + ',\n' +
              'Age: ' + localUser.age + '');

    }

    // "update data" button event
    elemBtnUpdate.onclick = function() {

        localUser.nameFirst = prompt('What\'s your first name?',
                                     localUser.nameFirst);
        localUser.nameLast  = prompt('What\'s your last name?',
                                     localUser.nameLast);
        localUser.age       = prompt('How old are you?',
                                     localUser.age);

    }

}
