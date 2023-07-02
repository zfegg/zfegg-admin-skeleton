/// <reference types="react-scripts" />

declare var GIT_BRANCH: string;
declare var GIT_HASH: string;

declare module '*.module.less' {
    const classes: { readonly [key: string]: string };
    export default classes;
}
