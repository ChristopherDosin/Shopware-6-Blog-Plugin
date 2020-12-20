import template from './sw-cms-el-blog-single-select.html.twig';
import './sw-cms-el-blog-single-select.scss';

const { Component, Mixin, Context } = Shopware;
const Criteria = Shopware.Data.Criteria;

Shopware.Component.register('sw-cms-el-blog-single-select', {
    template,

    inject: ['repositoryFactory'],

    mixins: [
        Mixin.getByName('cms-element')
    ],

    created() {
        this.createdComponent();
    },

    data() {
        return {
            article: null,
            title: 'Placeholder Article Title',
            teaser: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque faucibus maximus velit, dictum mollis erat finibus quis. Ut dictum ornare dolor, sed mattis tellus gravida vel.',
            mediaUrl: null,
            categoryName: 'Placeholder Category'
        }
    },

    computed: {
        articleImage() {
            return this.mediaUrl ? this.mediaUrl : `${Shopware.Context.api.assetsPath}/administration/static/img/cms/preview_mountain_small.jpg`;
        },

        repository() {
            return this.repositoryFactory.create('sas_blog_entries');
        },

        selectedBlogEntry() { 
            return this.element.config.blogEntry.value;
        }
    },

    methods: {
        createdComponent() {
            this.initElementConfig('blog-single-select');
            this.initElementData('blog-single-select');

            if(this.element.config.blogEntry.value) {
                this.getEntityProperties();
            }
        },

        getEntityProperties() {
            
            if (this.element.config.blogEntry.value) {
                const criteria = new Criteria();
                criteria.addAssociation('blogCategories');

                this.repository
                .get(this.element.config.blogEntry.value, Context.api, criteria)
                .then((entity) => {
                        this.article = entity;
                        this.title = this.article.translated.title;
                        this.teaser = this.article.translated.teaser;
                        this.mediaUrl = this.article.media.url;
                        this.categoryName = this.article.blogCategories[0] ? this.article.blogCategories[0].translated.name : null;
                });
            } else {
                this.article = null;
                this.title = 'Placeholder Article Title';
                this.teaser = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque faucibus maximus velit, dictum mollis erat finibus quis. Ut dictum ornare dolor, sed mattis tellus gravida vel.';
                this.mediaUrl = null;
                this.categoryName = 'Placeholder Category';
            }
        }

    },

    watch: {
        selectedBlogEntry: function () {
            this.getEntityProperties();
        }
    },
});