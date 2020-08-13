import template from './sas-blog-create.html.twig';
const { Component, Mixin } = Shopware;
const { mapApiErrors } = Shopware.Component.getComponentHelper();

Component.register('sas-blog-detail', {
    template,

    inject: ['repositoryFactory'],

    mixins: [
        Mixin.getByName('notification')
    ],

    metaInfo() {
        return {
            title: this.$createTitle()
        };
    },

    data() {
        return {
            blog: null,
            isLoading: false,
            processSuccess: false,
            repository: null
        };
    },

    computed: {
        ...mapApiErrors('blog', ['title', 'slug', 'content'])
    },

    created() {
        this.repository = this.repositoryFactory.create('sas_blog_entries');
        this.getBlog();
    },

    methods: {
        slugify(s) {
            s = s.toString().trim().toLowerCase();

            // remove accents, swap ñ for n, etc
            const from = "åàáãâèéëêìíïîòóôùúûñç·/_,:;";
            const to   = "aaaaaeeeeiiiiooouuunc------";

            for (let i = 0, l = from.length; i < l; i++) {
                s = s.replace(new RegExp(from.charAt(i), "g"), to.charAt(i));
            }

            return s
                .replace(/ä/g, "ae")
                .replace(/ö/g, "oe")
                .replace(/ü/g, "ue")
                .replace(/[^a-z0-9 -]/g, "") // remove invalid chars
                .replace(/\s+/g, "-") // collapse whitespace and replace by -
                .replace(/-+/g, "-") // collapse dashes
                .replace(/^-+/, "") // trim - from start of text
                .replace(/-+$/, ""); // trim - from end of text
        },

        updateSlug(title) {
            if( ! this.blog.slug || this.blog.slug === '') {
                this.blog.slug = this.slugify(this.blog.title)
            }
        },

        getBlog() {
            this.repository
                .get(this.$route.params.id, Shopware.Context.api)
                .then((entity) => {
                    this.blog = entity;
                });
        },

        onClickSave() {
            this.updateSlug(this.blog.title)
            this.isLoading = true;

            this.repository
                .save(this.blog, Shopware.Context.api)
                .then(() => {
                    this.getBlog();
                    this.isLoading = false;
                    this.processSuccess = true;
                }).catch((exception) => {
                    this.isLoading = false;
                    this.createNotificationError({
                        title: this.$t('sas-blog.detail.error.title'),
                        message: exception
                });
            });
        },

        saveFinish() {
            this.processSuccess = true;
        }
    }
});
