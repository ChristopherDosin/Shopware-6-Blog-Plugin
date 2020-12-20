import template from './sas-cms-preview-blog-single-entry.html.twig';
import './sas-cms-preview-blog-single-entry.scss';

Shopware.Component.register('sas-cms-preview-blog-single-entry', {
    template,

    computed: {
        today() {
            return new Date().toLocaleDateString();
        }
    }
});
