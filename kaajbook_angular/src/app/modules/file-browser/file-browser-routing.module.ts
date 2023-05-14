import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { FileBrowserComponent } from './pages/file-browser/file-browser.component';

const routes: Routes = [
	{
		path: '',
		children: [
			{
				path: '',
				component: FileBrowserComponent
			},
		]
	}
];

@NgModule({
	imports: [RouterModule.forChild(routes)],
	exports: [RouterModule]
})

export class FileBrowserRoutingModule { }
