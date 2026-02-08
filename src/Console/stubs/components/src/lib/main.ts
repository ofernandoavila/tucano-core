import { createApplication } from '@angular/platform-browser';
import { appConfig } from '../config/app.config';
import { WebComponentService } from '../config/web-components';
import { App } from '../config/app';
import { HelloWord } from '../components/hello-world';

createApplication(appConfig)
	.then((app) => {
		const service = new WebComponentService(app);
		[{ elementName: 'hello-world', component: HelloWord }].map(
			({ component, elementName }) =>
				service.createWebComponent(elementName, component),
		);
	})
	.catch((err) => console.error(err));
