import template from './sw-cms-el-config-blog-detail.html.twig';
import './sw-cms-el-config-blog-detail.scss';

const { Component, Mixin } = Shopware;

Component.register('sw-cms-el-config-blog-detail', {
    template,

    inject: ['repositoryFactory'],

    mixins: [
        Mixin.getByName('cms-element')
    ],

    created() {
        this.createdComponent();
    },

    methods: {
        createdComponent() {
            this.initElementConfig('blog');
        },
    }
});
