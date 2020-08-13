import { Module } from 'src/core/shopware';
import './page/sas-blog-list';
import './page/sas-blog-create';
import './page/sas-blog-detail';

import deDE from './snippet/de-DE.json';
import enGB from './snippet/en-GB.json';

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
            path: 'index'
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
        }
    },

    navigation: [
        {
            label: 'sas-blog.general.mainMenuItemGeneral',
            color: '#62ff80',
            path: 'blog.module.index',
            icon: 'default-object-lab-flask'
        }
        ]
});
