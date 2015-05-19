function User(nameFirst, age) {

	// known data
	this.nameFirst = nameFirst;
	this.age = age;

	// empty data
	this.nameLast = '';

	// full name generated from first and last name
	this.nameFull = function() {
		var space = this.nameLast.length > 0 ? ' ' : '';
		return this.nameFirst + space + this.nameLast;
	}

}

// executed once everything has loaded
window.onload = function() {

	// prompt for user info
	var nameFirst = prompt('What\'s your first name?'),
		age = prompt('How old are you?');

	// globals
	elemButton = document.getElementsByTagName('button')[0];
	localUser = new User(nameFirst, age);
	
	// "show data" button event
	elemButton.onclick = function() {

		/* This definition must be within the window.onload function, as
		 * elemButton will not otherwise be defined (and therefore cannot be
		 * bound to). The definition of elemButton must be in the function as
		 * well, as the element will not otherwise be loaded. */

		alert('Full name: ' + localUser.nameFull() + ',\n' +
			  'Age: ' + localUser.age + '');

	}

}
