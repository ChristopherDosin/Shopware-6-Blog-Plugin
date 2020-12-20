import template from './blog-tree-item.html.twig';
const { Component } = Shopware;

Component.extend('sas-blog-tree-item', 'sw-tree-item', {
    template,

    computed: {
        parentScope() {
            let parentNode = this.$parent;
            // eslint-disable-next-line
            while (parentNode.$options._componentTag !== 'sas-blog-tree') {
                parentNode = parentNode.$parent;
            }
            return parentNode;
        },
    },

    data() {
        return {
            editingCategory: null,
        }
    },
    methods: {
        onEditCategory(category) {
            this.editingCategory = category;
            this.currentEditElement = category.id;
            this.editElementName();
        },

        onFinishNameingElement(draft, event) {
            if (this.editingCategory) {
                this.parentScope.onFinishEditNameingElement(draft, event, this.editingCategory);

                this.currentEditElement = null;
                this.editingCategory = null;
            } else {
                this.parentScope.onFinishNameingElement(draft, event);
            }
        },

        onBlurTreeItemInput(item) {
            this.abortCreateElement(item);
        },

        onCancelSubmit(item) {
            this.abortCreateElement(item);
        },

        abortCreateElement(item) {
            this.currentEditElement = null;
            this.editingCategory = null;
            this.$super('abortCreateElement', item);
        }
    }
});
