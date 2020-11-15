import { Module } from 'src/core/shopware';

import './component/editorjs';

/**
 * Extensions
 */
import './extension/sw-cms/component/sw-cms-sidebar';

/**
 * Pages
 */
import './page/sas-blog-list';
import './page/sas-blog-create';
import './page/sas-blog-detail';

/**
 * Language Snippets
 */

import deDE from './snippet/de-DE.json';
import enGB from './snippet/en-GB.json';

/**
 * CMS Blocks
 */
import './blocks/listing';
import './blocks/detail';

/**
 * CMS Elements
 */
import './elements/blog-detail';
import './elements/blog';

/**
 * Blog Category
 */
import './component/blog-tree';
import './component/blog-tree-item';
import './component/blog-category-tree';
import './component/blog-category-tree-field';

/**
 * Blog author
 */
import './page/sas-blog-author/sas-blog-author-list';
import './page/sas-blog-author/sas-blog-author-detail';
import './page/sas-blog-author/sas-blog-author-create';

import './component/blog-vertical-tabs';

Module.register('blog-module', {
    type: 'plugin',
    name: 'Blog',
    title: 'sas-blog.general.mainMenuItemGeneral',
    description: 'Description for your custom module',
    color: '#62ff80',
    icon: 'default-object-lab-flask',

    snippets: {
        'de-DE': deDE,
        'en-GB': enGB
    },

    routes: {
        index: {
            components: {
                default: 'sas-blog-list'
            },
            path: 'index',
        },
        create: {
            components: {
                default: 'sas-blog-create'
            },
            path: 'create'
        },
        detail: {
            component: 'sas-blog-detail',
            path: 'detail/:id'
        },
        author: {
            path: 'author',
            component: 'sas-blog-author-list',
            meta: {
                parentPath: 'blog.module.index'
            },
            redirect: {
                name: 'blog.module.author.index'
            },
        },
        'author.index': {
            path: 'author/index',
            component: 'sas-blog-author-list',
        },
        'author.create': {
            path: 'author/new',
            component: 'sas-blog-author-create',
            meta: {
                parentPath: 'blog.module.author.index'
            }
        },
        'author.detail': {
            path: 'author/detail/:id',
            component: 'sas-blog-author-detail',
            meta: {
                parentPath: 'blog.module.author.index'
            }
        }
    },

    navigation: [
        {
            id: 'sas-blog',
            label: 'sas-blog.general.mainMenuItemGeneral',
            path: 'blog.module.index',
            parent: 'sw-content',
        }
    ]
});
