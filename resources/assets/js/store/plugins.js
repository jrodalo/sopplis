
const localStoragePlugin = store => {
    store.subscribe(({ type, payload }, state) => {
        console.log({ type, payload });
        //window.localStorage.setItem('STORAGE_KEY', JSON.stringify(all))
    })
}

export default [localStoragePlugin]
