import './component';
import './config';
import './preview';

Shopware.Service('cmsService').registerCmsElement({
    name: 'blog',
    label: 'Blog',
    component: 'sw-cms-el-blog',
    configComponent: 'sw-cms-el-config-blog',
    previewComponent: 'sw-cms-el-preview-blog',
    defaultConfig: {
        paginationCount: {
            source: 'static',
            value: 5
        },
        showType: {
            source: 'static',
            value: 'all'
        },
        blogCategories: {
            source: 'static',
            value: null,
            entity: {
                name: 'sas_blog_categories',
            }
        }
    }
});
