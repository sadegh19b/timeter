import { writable } from 'svelte/store';
import { Inertia } from '@inertiajs/inertia';

const store = () => {
    const defaultData = {
        showDialog: false,
        id: null,
        type: null, // create, update, confirm, delete or anything you want (use show method)
        name: null,
        title: null,
        description: null,
        model: null,
        showIcon: null,
        url: null,
        urlType: null, // get, post, put, patch, delete
        urlData: []
    };

    const {subscribe, set, update} = writable(defaultData);

    return {
        subscribe,
        show: (name, type, title, description = '', showIcon = false, url = '', urlType = '', urlData = []) => {
            update(() => ({
                ...defaultData,
                showDialog: true,
                id: `${type}-${name}`,
                name: name,
                type: type,
                title: title,
                description: description,
                showIcon: showIcon,
                url: url,
                urlType: urlType,
                urlData: urlData
            }));
        },
        create: (name, title, url) => {
            update(() => ({
                ...defaultData,
                showDialog: true,
                type: 'create',
                id: `form-create-${name}`,
                name: name,
                title: title,
                url: url,
                urlType: 'post'
            }));
        },
        update: (name, title, url, model) => {
            update(() => ({
                ...defaultData,
                showDialog: true,
                type: 'update',
                id: `form-update-${name}`,
                name: name,
                title: title,
                model: model,
                url: url,
                urlType: 'put'
            }));
        },
        confirm: (name, title, description, url = '', urlType = '', urlData = []) => {
            update(() => ({
                ...defaultData,
                showDialog: true,
                type: 'confirm',
                id: `confirm-${name}`,
                name: name,
                title: title,
                description: description,
                showIcon: true,
                url: url,
                urlType: urlType,
                urlData: urlData
            }));
        },
        delete: (name, title, url, urlData = []) => {
            update(() => ({
                ...defaultData,
                showDialog: true,
                type: 'delete',
                id: `delete-${name}`,
                name: name,
                title: title,
                showIcon: true,
                url: url,
                urlType: 'delete',
                urlData: urlData
            }));
        },

        confirmAction: () => {
            update((modal) => {
                if (modal.url !== '') {
                    switch (modal.urlType) {
                        case 'get':
                            Inertia.get(modal.url, {data: modal.urlData});
                            break;
                        case 'post':
                            Inertia.post(modal.url, {data: modal.urlData});
                            break;
                        case 'put':
                            Inertia.put(modal.url, {data: modal.urlData});
                            break;
                        case 'patch':
                            Inertia.patch(modal.url, {data: modal.urlData});
                            break;
                        case 'delete':
                            Inertia.delete(modal.url, {data: modal.urlData});
                            break;
                        default:
                            console.error('modal urlType is wrong!');
                    }
                }

                return set(defaultData);
            });
        },
        closeAction: () => set(defaultData)
    };
}

export const modalStore = store();
