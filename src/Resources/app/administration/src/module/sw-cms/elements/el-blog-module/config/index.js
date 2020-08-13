import { Component, Mixin } from 'src/core/shopware'
import template from './cms-el-config-blog-module.html.twig';

Component.register('cms-el-config-blog-module', {
    template,

    inject: [
        'repositoryFactory'
    ],

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
