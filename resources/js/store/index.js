
export const mutations = {
    HANDLE_LIKE_RESPONSE: (state, newProps) => {
        state.place.likes_count = newProps.likes_count;
        state.place.dislikes_count = newProps.dislikes_count;
        state.like_state = newProps.like_state;
    },
};
