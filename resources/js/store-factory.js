import Vuex from 'vuex';

const files = require.context('./store/', true, /\.js$/i);

let indexModule = {};
const modules = {};
for (const file of files.keys()) {
    const match = file.match(/^\.\/(.*)\.js$/);
    if (!match) {
        continue;
    }

    const key = match[1];

    if (key === 'index') {
        indexModule = files(file);
    } else {
        modules[key] = {
            namespaced: true,
            ...files(file),
        }
    }
}

export const createStore = (initialState = {}) => {
    const store = new Vuex.Store({
        ...indexModule,
        modules,
    });

    const newState = JSON.parse(JSON.stringify(store.state));
    for (const [key, value] of Object.entries(initialState)) {
        if (key in modules) {
            // This is a module
            newState[key] = {
                ...newState[key],
                ...value,
            };
        } else {
            // This is just a property of root (index) state
            newState[key] = value;
        }
    }
    store.replaceState(newState);

    return store;
};
