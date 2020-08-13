import { Component } from 'src/core/shopware';
import template from './sas-blog-list.twig';
import Criteria from 'src/core/data-new/criteria.data';

import './sas-blog-list.scss';

Component.register('sas-blog-list', {
    template,

    inject: ['repositoryFactory'],

    data() {
        return {
            repository: null,
            blogEntries: null,
            total: 0,
            isLoading: true
        };
    },

    metaInfo() {
        return {
            title: this.$createTitle()
        };
    },

    computed: {
        columns() {
            return [
                {
                    property: 'title',
                    dataIndex: 'title',
                    label: 'Title',
                    routerLink: 'blog.module.detail',
                    inlineEdit: 'string',
                    allowResize: true,
                    primary: true
                },
                {
                    property: 'active',
                    label: this.$tc('sas-blog.detail.activeLable'),
                    inlineEdit: 'boolean',
                    allowResize: true,
                    align: 'center'
                }
            ];
        }
    },

    created() {
        this.isLoading = true;
        this.repository = this.repositoryFactory.create('sas_blog_entries');

        this.repository.search(new Criteria(), Shopware.Context.api).then(result => {
            this.total = result.total;
            this.blogEntries = result;
            this.isLoading = false;
        });
    }
});
