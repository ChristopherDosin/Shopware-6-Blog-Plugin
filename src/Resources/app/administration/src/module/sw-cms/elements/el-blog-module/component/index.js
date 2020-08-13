import { Component, Mixin } from 'src/core/shopware';
import template from './cms-el-blog-module.html.twig';
import './cms-el-blog-module.scss';

Component.register('cms-el-component-blog-module', {
    template,

    mixins: [
        Mixin.getByName('cms-element')
    ],


    created() {
        this.createdComponent();
    },

    methods: {
        createdComponent() {
            this.initElementConfig('el-blog-module');
        }
    }
});
