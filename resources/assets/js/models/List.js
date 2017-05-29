
const List = {

    state: {
        loading: false,
        lists: []
    },

    readCache () {
        return JSON.parse(localStorage.getItem('SOPPLIS_LISTS')) || [];
    },

    writeCache (lists) {
        localStorage.setItem('SOPPLIS_LISTS', JSON.stringify(lists));
    },

    readLists () {

        List.state.loading = true;

        List.state.lists = List.readCache();

        return axios.get('lists').then((response) => {
            List.state.loading = false;
            List.state.lists = response.data.lists;
            List.writeCache(List.state.lists);
        });
    },

    addList (list) {
        return axios.post('lists', list);
    }

}

export default List;
