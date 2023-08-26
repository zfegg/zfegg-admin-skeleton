import { extname } from 'node:path';
import { createFilter } from '@rollup/pluginutils';
import {PluginOption} from "vite";

const injectNode = (svg) => (`
export default function() {
	return (new DOMParser().parseFromString(${svg}, 'image/svg+xml')).firstChild;
};
`);

const injectString = (svg) => `export default ${svg};`;

/**
 * @param options
 * @param options.include
 * @param options.exclude
 * @param options.stringify - if true returns String, otherwise returns DOM Node
 */
export default function svgImportPlugin(options: any = {}): PluginOption {
    const filter = createFilter(options.include, options.exclude);

    return {
        name: 'svg-import',
        transform: (code, id) => {
            if (!filter(id) || extname(id) !== '.svg') return null;
            const content = JSON.stringify(code);

            return {
                code: code,
                map: { mappings: '' },
            };
        },
    };
}
