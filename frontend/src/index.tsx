import 'reflect-metadata';
import container from './container';
import {App} from '@zfegg/admin-application';
import reportWebVitals from './reportWebVitals';
import {render} from "react-dom";
import React, {StrictMode} from 'react';
import type {History} from 'history';

const history = container.get<History>("history")

render(
    <StrictMode>
        <App container={container} history={history} />
    </StrictMode>,
    document.getElementById("root")
);

// If you want to start measuring performance in your app, pass a function
// to log results (for example: reportWebVitals(console.log))
// or send to an analytics endpoint. Learn more: https://bit.ly/CRA-vitals
reportWebVitals();
