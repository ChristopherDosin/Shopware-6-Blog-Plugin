import './component';
import './config';
import './preview';

Shopware.Service('cmsService').registerCmsElement({
    name: 'blog-detail',
    label: 'Blog Detail',
    component: 'sas-blog-el-blog-detail',
    configComponent: 'sw-cms-el-config-blog-detail',
    previewComponent: 'sas-blog-el-blog-detail-preview',
    defaultConfig: {
        showCategory: {
            source: 'static',
            value: true
        },
        showAuthor: {
            source: 'static',
            value: true
        }
    }
});
