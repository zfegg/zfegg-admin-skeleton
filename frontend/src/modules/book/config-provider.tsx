import {DependencyConfigInterface, FactoryFunction, InjectionToken} from "@moln/dependency-container";
import {lazy} from "react";
import Ajv from "ajv";
import { RouteConfigMap } from "@zfegg/admin-layout";
import { book } from "./models/book";

const ConfigProvider = {
    dependencies: {
        singletonFactories: new Map<InjectionToken, FactoryFunction<any>>([
        ]),
        activationMiddlewares: new Map([
            [Ajv, [
                (container, token, next) => {
                    const instance: Ajv = next();
                    instance.addSchema(book, 'book/books')
                    instance.addSchema(book, 'book/v2/books')
                    // instance.addSchema(batch, 'game-card/batches')

                    return instance;
                }
            ]]
        ])
    } as DependencyConfigInterface,
    routes: {
        'application': {
            children: {
                'book': {
                    name: "书本管理",
                    path: "/book",
                    children: {
                        'home': {
                            name: "首页",
                            path: "/book/home",
                            exact: true,
                            component : lazy(() => import('./pages/Home')),
                        },
                        'books': {
                            name: "书本列表",
                            path: "/book/books",
                            exact: true,
                            authorization: true,
                            component : lazy(() => import('./pages/Books'))
                        },
                        'books-v2': {
                            name: "书本列表v2",
                            path: "/book/books-v2",
                            exact: true,
                            authorization: true,
                            component : lazy(() => import('./pages/BooksV2'))
                        },
                    }
                },
            },
        },
    } as RouteConfigMap,
}

export default ConfigProvider;