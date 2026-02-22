import { App } from "./app/app.js";

document.addEventListener(
    'DOMContentLoaded',
    () => {
        App.listen();
        App.poll();
    }
);
