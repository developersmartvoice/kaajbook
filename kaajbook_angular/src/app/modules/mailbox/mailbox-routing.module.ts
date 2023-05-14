import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { MailboxComponent } from './pages/mailbox/mailbox.component';

const routes: Routes = [
	{
		path: '',
		children: [
			{ path: '', component: MailboxComponent },
		]
	}
];

@NgModule({
	imports: [RouterModule.forChild(routes)],
	exports: [RouterModule]
})

export class MailboxRoutingModule { }
