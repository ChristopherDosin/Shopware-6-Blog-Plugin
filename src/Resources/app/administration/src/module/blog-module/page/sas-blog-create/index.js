const { Component } = Shopware;
const { date } = Shopware.Utils.format;

Component.extend('sas-blog-create', 'sas-blog-detail', {
    methods: {
        getBlog() {
            this.blog = this.repository.create(Shopware.Context.api);
        },

        onClickSave() {
            this.isLoading = true;

            this.repository
                .save(this.blog, Shopware.Context.api)
                .then(() => {
                    this.isLoading = false;
                    this.$router.push({ name: 'blog.module.detail', params: { id: this.blog.id } });
                }).catch((exception) => {
                    this.isLoading = false;
                    if (exception.response.data && exception.response.data.errors) {
                        exception.response.data.errors.forEach((error) => {
                            this.createNotificationWarning({
                                title: this.$t('blog.module.detail.titleError'),
                                message: error.detail,
                                duration: 10000
                            });
                        });
                    }
                });
        }
    }
});
