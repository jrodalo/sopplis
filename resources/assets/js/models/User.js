
const User = {

    isAuthenticated () {
        return this.data().token && this.data().token.length > 0;
    },

    data () {
        return JSON.parse(localStorage.getItem('SOPPLIS_USER')) || {};
    },

    updateUser (name) {
        return axios.put('users', {name: name}).then(response => {

            if (response.data.success === true) {
                let user = User.data();
                user.name = name;
                localStorage.setItem('SOPPLIS_USER', JSON.stringify(user));
            }
        });
    },

    login (user) {
        return axios.post('users', user).then((response) => {

            if (response.data.success === true) {
                localStorage.setItem('SOPPLIS_USER', JSON.stringify({name: response.data.name, token: response.data.token}));
            }

            return response;
        });
    },

    logout () {
        localStorage.clear();
    }

}

export default User;
