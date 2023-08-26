import {defineConfig, PluginOption} from 'vite'
import react from '@vitejs/plugin-react'
import {resolve} from "path";
import {theme} from "antd";
// import {convertLegacyToken} from "@ant-design/compatible";
import svgr from "@svgr/rollup";
import {execSync} from "child_process";
import svgImportPlugin from './scripts/svg-plugin';

const GIT_BRANCH = process.env.CI_COMMIT_REF_NAME || execSync('git name-rev --name-only HEAD').toString().trim()
const GIT_HASH = (process.env.CI_COMMIT_SHA || execSync('git rev-parse HEAD').toString().trim()).substr(0, 8)

const {defaultAlgorithm, defaultSeed} = theme;

const mapToken = defaultAlgorithm(defaultSeed);
// const v4Token = convertLegacyToken(mapToken);
const svgrPlugin: PluginOption = {
    name: "svgr",
    ...svgr({
        ref: true,
        memo: true,
    }) as any,
    apply: () => true
}
const svgPlugin: PluginOption = {
    ...svgImportPlugin({
        stringify: true,
        include: [
            /ckeditor5-[^/\\]+[/\\]theme[/\\]icons[/\\][^/\\]+\.svg$/,
        ]
    }) as any,
}

// https://vitejs.dev/config/
export default defineConfig({
    // base: '/coupon/',
    resolve: {
        alias: {
            '@': resolve(__dirname, './src')
        },
    },
    plugins: [
        react(),
        svgPlugin,
        svgrPlugin,
    ],
    css: {
        preprocessorOptions: {
            less: {
                math: "always",
                relativeUrls: true,
                javascriptEnabled: true,
                modifyVars: {
                    // ...v4Token,
                    // 'primary-color': "rgb(255,96,57)",
                    // 'item-hover-bg': "rgba(255,96,57, 0.1)",
                    // "item-active-bg": "rgba(255,96,57, 0.1)",
                },
            },
        },
        modules: {
            localsConvention: "camelCase"
        }
    },
    server: {
        proxy: {
            '^/(api|zfegg|uploads)/.*': {
                target: 'http://localhost/moln/bee-bi/backend/public',
                changeOrigin: true,
            },
        }
    },
    define: {
        GIT_BRANCH: JSON.stringify(GIT_BRANCH),
        GIT_HASH: JSON.stringify(GIT_HASH),
        'process.env.NODE_ENV': 'null',
        'process.env': '{}',
    }
})
