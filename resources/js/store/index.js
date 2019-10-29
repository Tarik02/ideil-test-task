import Axios from 'axios';

export const mutations = {
    HANDLE_LIKE_RESPONSE: (state, newProps) => {
        state.place.likes_count = newProps.likes_count;
        state.place.dislikes_count = newProps.dislikes_count;
        state.like_state = newProps.like_state;
    },

    HANDLE_COMMENTS_UPDATE: (state, newComments) => {
        state.place.comments = newComments;
    },
};

export const actions = {
    reloadComments: ({ state, commit }) => {
        Axios.get(`/place/${state.place.slug}/comments`)
            .then(response => commit('HANDLE_COMMENTS_UPDATE', response.data.data))
        ;
    },
};
