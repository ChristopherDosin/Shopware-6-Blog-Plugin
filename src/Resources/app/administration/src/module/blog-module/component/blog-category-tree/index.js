import template from './sas-blog-category-tree.html.twig';
const { Component } = Shopware;
import Criteria from 'src/core/data-new/criteria.data';

Component.extend('sas-blog-category-tree', 'sw-category-tree', {
    template,

    props: {
        categoryId: {
            type: String,
            required: false,
            default: null
        },

        currentLanguageId: {
            type: String,
            required: true
        },
    },

    data() {
        return {
            blogCategory: null,
            translationContext: 'sas-blog-category'
        };
    },

    computed: {
        category() {
            return this.blogCategory;
        },
        categoryRepository() {
            return this.repositoryFactory.create('sas_blog_category');
        },
    },

    methods: {
        createdComponent() {
            this.getCategory();
            this.$super('createdComponent');
        },

        changeCategory(category) {
            this.$emit('change-category-id', category.id);
        },

        onFinishEditCategory(editCategory) {
            const category = editCategory.data

            this.categoryRepository.save(category, Shopware.Context.api).then(() => {
                const criteria = new Criteria();
                criteria.setIds([category.id, category.parentId].filter((id) => id !== null));
                this.categoryRepository.search(criteria, Shopware.Context.api).then((categories) => {
                    this.addCategories(categories);
                });
            });
        },

        getCategory() {
            const criteria = new Criteria();

            if (this.categoryId) {
                criteria.addFilter(Criteria.equals('blogCategories.id', this.categoryId));
            }

            this.categoryRepository.search(criteria, Shopware.Context.api).then((result) => {
                this.blogCategory = result[0];
                this.blogCategory.childCount = 9;
            });
        }
    }
});
