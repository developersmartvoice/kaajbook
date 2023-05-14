import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { NgxPermissionsGuard } from 'ngx-permissions';

import { LeaveTypesListComponent } from './pages/leave-types-list/leave-types-list.component';

const routes: Routes = [
{
	path: '',
	children: [
		{
			path: '',
			canActivate: [NgxPermissionsGuard],
			component: LeaveTypesListComponent
		}
	]
}];

@NgModule({
	imports: [RouterModule.forChild(routes)],
	exports: [RouterModule]
})

export class LeaveTypesRoutingModule { }
