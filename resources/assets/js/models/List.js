import Vue from 'vue';

let List = {

    state: {
        lists: []
    },

    readCache: function() {
        return JSON.parse(localStorage.getItem('SOPPLIS_LISTS')) || [];
    },

    writeCache: function(lists) {
        localStorage.setItem('SOPPLIS_LISTS', JSON.stringify(lists));
    },

    readLists: function() {

        List.state.lists = List.readCache();

        return axios.get('lists').then((response) => {
            List.state.lists = response.data.lists;
            List.writeCache(List.state.lists);
        });
    },

    addList: function(list) {
        return axios.post('lists', list);
    },

    splitEmails: function(emails) {
        return (emails || '')
                    .split(/\r*\n/)
                    .filter(function(line) {
                        return line && /^.*@.*\.[A-z]{2,3}$/.test(line);
                    });
    }

}

export default List;
