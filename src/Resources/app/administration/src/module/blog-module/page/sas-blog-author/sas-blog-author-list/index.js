import template from './sas-blog-author-list.html.twig';
import { Component, Mixin } from 'src/core/shopware';
import Criteria from 'src/core/data-new/criteria.data';
import './sas-blog-author-list.scss';

Component.register('sas-blog-author-list', {
    template,

    inject: ['repositoryFactory'],

    mixins: [
        Mixin.getByName('notification'),
        Mixin.getByName('salutation'),
        Mixin.getByName('listing')
    ],

    data() {
        return {
            blogAuthors: null,
            total: 0,
            isLoading: true,
            currentLanguageId: Shopware.Context.api.languageId
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
        blogAuthorRepository() {
            return this.repositoryFactory.create('sas_blog_author');
        },

        columns() {
            return [
                {
                    property: 'salutation.displayName',
                    label: 'sas-blog-author.list.table.salutation',
                    width: '100px',
                    allowResize: true,
                }, {
                    property: 'fullName',
                    dataIndex: 'firstName,lastName',
                    inlineEdit: 'string',
                    label: 'sas-blog-author.list.table.fullName',
                    routerLink: 'sw.blog.author.detail',
                    allowResize: true,
                    primary: true
                }, {
                    property: 'displayName',
                    label: 'sas-blog-author.list.table.displayName',
                    allowResize: true,
                    inlineEdit: 'string'
                }, {
                    property: 'email',
                    label: 'sas-blog-author.list.table.email',
                    align: 'right',
                    inlineEdit: 'string',
                    allowResize: true
                }
            ];
        }
    },

    methods: {
        changeLanguage(newLanguageId) {
            this.currentLanguageId = newLanguageId;
            this.getList();
        },

        getList() {
            this.isLoading = true;
            const criteria = new Criteria();
            criteria.addAssociation('media');
            criteria.addAssociation('salutation');

            return this.blogAuthorRepository.search(criteria, Shopware.Context.api).then((result) => {
                this.total = result.total;
                this.blogAuthors = result;
                this.isLoading = false;
            })
        }
    }
});
