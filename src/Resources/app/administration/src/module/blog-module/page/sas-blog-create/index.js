const { Component } = Shopware;

Component.extend('sas-blog-create', 'sas-blog-detail', {

    methods: {
        createdComponent() {
            if (Shopware.Context.api.languageId !== Shopware.Context.api.systemLanguageId) {
                Shopware.State.commit('context/setApiLanguageId', Shopware.Context.api.languageId)
            }

            this.$super('createdComponent');
        },

        getBlog() {
            this.blog = this.repository.create(Shopware.Context.api);
            this.isLoading = false;
        }
    }
});
