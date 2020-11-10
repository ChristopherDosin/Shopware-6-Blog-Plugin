const { Component } = Shopware;

Component.extend('sas-blog-tree', 'sw-tree', {
    methods: {
        async onFinishEditNameingElement(draft, event, editItem) {
            if (editItem) {
                await this.$emit('finish-edit-item', editItem);
                this.saveItems();
                if (this.currentEditMode !== null && this.contextItem) {
                    this.currentEditMode(this.contextItem, this.addElementPosition);
                }
            }
            this._eventFromEdit = event;
            this.newElementId = null;
        },
    }
});
