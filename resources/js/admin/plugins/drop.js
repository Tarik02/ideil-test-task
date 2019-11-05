import Vue from 'vue';

Vue.directive('drop', {
    bind: (el, binding, vnode) => {
        const preventDefaults = event => event.preventDefault();
        const highlight = () => el.classList.add('highlight');
        const unhighlight = () => el.classList.remove('highlight');

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(
            eventName => el.addEventListener(eventName, preventDefaults, false),
        );

        ['dragenter', 'dragover'].forEach(
            eventName => el.addEventListener(eventName, highlight, false),
        );

        ['dragleave', 'drop'].forEach(
            eventName => el.addEventListener(eventName, unhighlight, false),
        );

        el.addEventListener('drop', event => {
            for (const file of [...event.dataTransfer.files]) {
                binding.value(file);
            }
        });
    },
});
