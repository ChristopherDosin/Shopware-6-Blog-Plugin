import './component';
import './config';
import './preview';

Shopware.Service('cmsService').registerCmsElement({
    name: 'el-blog-module',
    label: 'sw-cms.elements.blog-module.label',
    component: 'cms-el-component-blog-module',
    configComponent: 'cms-el-config-blog-module',
    previewComponent: 'cms-el-preview-blog-module',

    defaultConfig: {
        sortOrder: {
            source: 'static',
            value: 'desc'
        },
        layout: {
            source: 'static',
            value: 'list'
        }
    }
});
