import { Component, OnInit, AfterViewInit, Input } from '@angular/core';
import { ActivatedRoute } from "@angular/router";
import { PerfectScrollbarConfigInterface } from 'ngx-perfect-scrollbar';

import { User } from '../../../shared/models/user.model';

import { AuthenticationService } from '../../../core/services/authentication.service';

import { sidebarCollpasedMenu } from '../../../core/helpers/app.helper';
import { environment } from '../../../../environments/environment';

@Component({
	selector: 'app-sidebar',
	templateUrl: './sidebar.component.html',
	styleUrls: ['./sidebar.component.scss']
})

export class SidebarComponent implements OnInit, AfterViewInit {
	public scrollConfig: PerfectScrollbarConfigInterface = {};
	private apiUrl = environment.apiUrl;
	@Input() settings;
	menuItems: Array<any>;
	loginUser: User;
	isSettingsLoad: boolean;
	public versionNumber: string = '1.0.0' // Access version directly from environment file

	constructor(
		private route: ActivatedRoute,
		private authenticationService: AuthenticationService
	) {
		this.menuItems = this.route.snapshot.data.sidebarMenu.menus;
		this.authenticationService.setLoginUser(this.route.snapshot.data.sidebarMenu.loginUser);
		this.loginUser = this.route.snapshot.data.sidebarMenu.loginUser;
	}

	ngOnInit() {}

	ngAfterViewInit() {
		setTimeout(() => {
			sidebarCollpasedMenu();
		});
	}

	logout() {
		this.authenticationService.logout();
	}
}
