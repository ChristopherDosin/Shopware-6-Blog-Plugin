import template from './sas-text-field.html.twig';
import './sas-text-field.scss';

const { Component } = Shopware;

Component.extend('sas-text-field', 'sw-text-field', {
    template,

    props: {
        maxLength: {
            type: Number,
            required: false,
            default: 5000
        },
        textCountBeforeWarning: {
            type: Number,
            required: false,
            default: 20
        }
    },

    watch: {
        value(value) {
            if (!value) {
                return;
            }

            if (value.length > this.maxLength) {
                this.currentValue = value.substr(0, this.maxLength);
                this.$emit('input', this.currentValue);

                return;
            }

            this.currentValue = value;
        },
    },

    computed: {
        currentLength() {
            return this.currentValue ? this.currentValue.length : 0;
        },

        charLeft() {
            return this.maxLength - this.currentLength;
        },

        hasWarning() {
            return this.currentLength > 0 && this.charLeft <= this.textCountBeforeWarning;
        },
    }
})
