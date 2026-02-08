import { ApplicationRef } from "@angular/core";
import { createCustomElement } from "@angular/elements";

export class WebComponentService {
    private __app: ApplicationRef;
    
    constructor(app: ApplicationRef) {
        this.__app = app;
    }

    createWebComponent(elementName: string, component: any) {
        const element = createCustomElement(component, { injector: this.__app.injector });
        customElements.define(elementName, element);
    }
}