function User() {

    /* properties */

    this.nameFirst = null;
    this.nameLast = null;
    this.age = null;

    /* methods */

    this.nameFull = function() {

        /* Returns full name generated from first and last name. */

        var out;

        if (this.nameFirst != null ||
            this.nameLast != null) { // if one or more names have been entered

            var nameFirst = this.nameFirst == null ? '' : this.nameFirst,
                nameLast = this.nameLast == null ? '' : this.nameLast,
                space = this.nameFirst != null &&
                        this.nameLast != null ? ' ' : '';
            out = nameFirst + space + nameLast;
        } else {
            out = '(none given)';
        }

        return out;

    };

    this.ageFormatted = function() {

        /* Returns age as a string. */

        var ageStr;

        if (this.age == null) {
            ageStr = '(none given)';
        } else {
            ageStr = this.age + ' years';
        }

        return ageStr;

    };

    this.clear = function() {

        this.nameFirst = null;
        this.nameLast = null;
        this.age = null;

    };

    this.updateData = function(data) {

        /* Updates user object from data object. */

        this.nameFirst = data.nameFirst;
        this.nameLast = data.nameLast;
        this.age = data.age;

    };

    this.exportData = function() {

        /* Exports unique user data for localStorage. */

        return { nameFirst: this.nameFirst,
                 nameLast: this.nameLast,
                 age: this.age };

    };

}
