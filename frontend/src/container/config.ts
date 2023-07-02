import mergeWith from 'lodash/mergeWith';
import {ConfigProvider as AdminApp, CONFIG_KEY as applicationConfigKey, configMerge, IConfigProvider } from '@zfegg/admin-application'
import {ConfigProvider as AdminAdmin } from '@zfegg/admin-admin'
import BookConfigProvider from '@/modules/book/config-provider'
import '@zfegg/admin-application/dist/style.css'
import '@zfegg/admin-admin/dist/style.css'

const moduleConfigs: object[] = [
    AdminApp,
    AdminAdmin,
    BookConfigProvider,
    {
        [applicationConfigKey]: {
            avatarDropdownProps: {
                git: {
                    tag: GIT_BRANCH,
                    hash: GIT_HASH,
                }
            },
        } as IConfigProvider
    }
];
const configs: Record<any, any> = {
};
// console.log(window.m = mergeWith);

moduleConfigs.forEach((config) => {
    mergeWith(configs, config, configMerge);
});

export default configs;
