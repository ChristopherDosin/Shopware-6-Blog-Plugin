import './component';
import './preview';

Shopware.Service('cmsService').registerCmsBlock({
    name: 'cms-blog-module',
    label: 'sw-cms.blocks.blog-module.label',
    category: 'text-image',
    component: 'cms-block-blog-module',
    previewComponent: 'cms-preview-blog-module',

    defaultConfig: {
        marginBottom: '20px',
        marginTop:    '20px',
        marginLeft:   '20px',
        marginRight:  '20px'
    },

    slots: {
        content: {
            type: 'el-blog-module'
        }
    }
});
