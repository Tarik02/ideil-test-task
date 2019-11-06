import base from 'vee-validate/dist/locale/uk.json';

export default {
    common: {
        create: 'Створити',
        save: 'Зберегти',
        destroy: 'Видалити',
        cancel: 'Скасувати',
        back: 'Назад',
        page: 'сторінка',
    },

    fields: {
        name: 'Назва',
        slug: 'URL',
        description: 'Опис',
        mark10: 'Оцінка (від 1 до 10)',
        key: 'Ключ',
        value: 'Значення',
        likes: 'Лайки',
        dislikes: 'Дизлайки',
        created_at: 'Створено',
        updated_at: 'Оновлено',
        visible: 'Видимий',
    },

    validation: {
        ...base.messages,
        slug: 'Поле {_field_} має складатися тільки з латинських символів, цифр та тире',
    },

    resources: {
        common: {
            create: 'Створити',
            actions: 'Дії',
        },

        places: {
            singular: 'Місце',
            plural: 'Місця',
        },

        comments: {
            singular: 'Коментар',
            plural: 'Коментарі',
        },
    },

    vee: {
        messages: {
            slug: 'Поле {_field_} має складатися тільки з латинських символів, цифр та тире',
        },
    },
};
