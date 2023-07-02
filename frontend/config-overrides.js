const webpack = require("webpack");
const { execSync } = require("child_process");

const GIT_BRANCH = process.env.CI_COMMIT_REF_NAME || execSync('git name-rev --name-only HEAD').toString().trim()
const GIT_HASH = (process.env.CI_COMMIT_SHA || execSync('git rev-parse HEAD').toString().trim()).substr(0, 8)

console.log("Git version: ", GIT_BRANCH, GIT_HASH);

const {
    override,
    fixBabelImports,
    overrideDevServer,
    addWebpackAlias,
    addLessLoader,
    addWebpackPlugin,
    removeModuleScopePlugin,
    babelInclude,
    addWebpackResolve
} = require('customize-cra');

const path = require('path');

module.exports = (config) => {
    config = override(
        addWebpackAlias({
            '@': path.join(__dirname, '.', 'src'),
        }),
        fixBabelImports('antd', {
            libraryName: 'antd',
            libraryDirectory: 'es',
            style: true,
        }),
        // fixBabelImports('@ant-design/pro-layout', {
        //     libraryName: '@ant-design/pro-layout',
        //     libraryDirectory: 'es',
        // }),
        addLessLoader({
            lessOptions: {
                javascriptEnabled: true,
            },
        }, {
            exportLocalsConvention: 'camelCase',
        }),
        addWebpackPlugin(new webpack.DefinePlugin({
            GIT_BRANCH: JSON.stringify(GIT_BRANCH),
            GIT_HASH: JSON.stringify(GIT_HASH),
        })),
        // multipleEntry.addMultiEntry,
        // removeModuleScopePlugin(),
        babelInclude([
            path.join(__dirname, '.', 'src'),
        ]),
    )(config)


    // @see https://github.com/arackaf/customize-cra/issues/315
    config.module.rules.forEach(item => {
        if (item.oneOf) {
            item.oneOf.forEach(item => {
                item.use?.forEach(item => {
                    if (
                        item.loader?.includes('postcss-loader') &&
                        !item?.options?.postcssOptions
                    ) {
                        const postcssOptions = item.options;
                        item.options = { postcssOptions };
                    }
                });
            });
        }
    })
    return config;
    console.dir(config, {depth: 3})
    process.exit()

}
