import { Component } from 'src/core/shopware';
import template from './sas-blog-list.twig';
import Criteria from 'src/core/data-new/criteria.data';

import './sas-blog-list.scss';

Component.register('sas-blog-list', {
    template,

    inject: ['repositoryFactory'],

    data() {
        return {
            categoryId: null,
            blogEntries: null,
            total: 0,
            isLoading: true,
            currentLanguageId: Shopware.Context.api.languageId,
        };
    },

    metaInfo() {
        return {
            title: this.$createTitle()
        };
    },

    created() {
        this.getList();
    },

    computed: {
        blogEntriesRepository() {
            return this.repositoryFactory.create('sas_blog_entries');
        },

        blogCategoryRepository() {
            return this.repositoryFactory.create('sas_blog_category');
        },

        columns() {
            return [
                {
                    property: 'title',
                    dataIndex: 'title',
                    label: this.$tc('sas-blog.list.table.title'),
                    routerLink: 'blog.module.detail',
                    primary: true
                },
                {
                    property: 'active',
                    label: this.$tc('sas-blog.list.table.active')
                }
            ];
        }
    },

    methods: {
        changeLanguage(newLanguageId) {
            this.currentLanguageId = newLanguageId;
            this.getList();
        },

        changeCategoryId(categoryId) {
            if (categoryId && categoryId !== this.categoryId) {
                this.categoryId = categoryId;
                this.getList();
            }
        },

        getList() {
            this.isLoading = true;
            const criteria = new Criteria();
            criteria.addAssociation('blogCategories');

            if (this.categoryId) {
                criteria.addFilter(Criteria.equals('blogCategories.id', this.categoryId));
            }
            return this.blogEntriesRepository.search(criteria, Shopware.Context.api).then((result) => {
                this.total = result.total;
                this.blogEntries = result;
                this.isLoading = false;
            })
        }
    }
});
