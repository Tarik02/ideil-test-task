import {
    ValidationProvider,
    ValidationObserver,
    configure,
    extend,
} from 'vee-validate';
import * as rules from 'vee-validate/dist/rules'
import { alpha_dash } from 'vee-validate/dist/rules';
import Vue from 'vue';
import { i18n } from './i18n';

configure({
    defaultMessage: (field, values) => {
        values._field_ = '"' + i18n.t(`fields.${field}`) + '"';
        return i18n.t(`validation.${values._rule_}`, values);
    },
});

for (const [name, rule] of Object.entries(rules)) {
    extend(name, rule);
}

extend('slug', {
    validate: value => alpha_dash.validate(value, { locale: 'en' }),
});

Vue.component('validation-provider', ValidationProvider);
Vue.component('validation-observer', ValidationObserver);
