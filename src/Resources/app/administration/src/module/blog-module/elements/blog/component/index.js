import template from './sw-cms-el-blog.html.twig';
import './sw-cms-el-blog.scss';

const { Component, Mixin } = Shopware;
  Shopware.Component.register('sw-cms-el-blog', {
      template,
          mixins: [
            Mixin.getByName('cms-element')
        ],
      created() {
          this.createdComponent();
          },
      methods: {
          createdComponent() {
              this.initElementConfig('blog');
              },
      }
});
