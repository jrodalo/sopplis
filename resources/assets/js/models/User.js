
var User = {

    isAuthenticated: function() {
        return this.data().token && this.data().token.length > 0;
    },

    data: function() {
        return JSON.parse(localStorage.getItem('SOPPLIS_USER')) || {};
    },

    updateUser: function(name) {
        return axios.put('users', {name: name}).then(response => {
            if (response.ok) {
                var user = User.data();
                user.name = name;
                localStorage.setItem('SOPPLIS_USER', JSON.stringify(user));
            }
        });
    },

    login: function(user) {
        return axios.post('users', user).then((response) => {

            if (response.data.success === true) {
                localStorage.setItem('SOPPLIS_USER', JSON.stringify({name: response.data.name, token: response.data.token}));
            }

            return response;
        });
    },

    logout: function() {
        localStorage.clear();
    }

}

export default User;
