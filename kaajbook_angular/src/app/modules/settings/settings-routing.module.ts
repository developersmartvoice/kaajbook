import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { SettingsComponent } from './pages/settings/settings.component';
import { NgxPermissionsGuard } from 'ngx-permissions/router/permissions-guard.service';
import { VersionComponent } from './components/version/version.component';

const routes: Routes = [
	{
		path: '',
		component: SettingsComponent
	},
	{
		path: 'version',
		// canActivate: [NgxPermissionsGuard],
		component: VersionComponent,
		data: {
			breadcrumbs: {
				text: "common.version",
				icon: "fa fa-product-hunt",
				show: true,
				isHome: true
			}
		}
	},
];

@NgModule({
	imports: [RouterModule.forChild(routes)],
	exports: [RouterModule]
})

export class SettingsRoutingModule { } 
