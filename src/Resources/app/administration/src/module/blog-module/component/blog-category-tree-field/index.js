const { Component } = Shopware;
const { Criteria } = Shopware.Data;

Component.extend('sas-blog-category-tree-field', 'sw-category-tree-field', {
    computed: {
        globalCategoryRepository() {
            return this.repositoryFactory.create('sas_blog_category');
        },
    },
    methods: {
        getTreeItems(parentId = null) {
            this.isFetching = true;

            // create criteria
            const categoryCriteria = new Criteria(1, 500);
            categoryCriteria.addFilter(Criteria.equals('parentId', parentId));

            // search for categories
            return this.globalCategoryRepository.search(categoryCriteria, Shopware.Context.api).then((searchResult) => {
                // when requesting root categories, replace the data
                if (parentId === null) {
                    this.categories = searchResult;
                    this.isFetching = false;
                    return Promise.resolve();
                }

                // add new categories
                searchResult.forEach((category) => {
                    this.categories.add(category);
                });

                return Promise.resolve();
            });
        },

        searchCategories(term) {
            // create criteria
            const categorySearchCriteria = new Criteria(1, 500);
            categorySearchCriteria.setTerm(term);

            // search for categories
            return this.globalCategoryRepository.search(categorySearchCriteria, Shopware.Context.api);
        }
    }
});
