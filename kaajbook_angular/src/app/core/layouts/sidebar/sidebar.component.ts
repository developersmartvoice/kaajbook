import { Component, OnInit, AfterViewInit, Input } from '@angular/core';
import { ActivatedRoute } from "@angular/router";
import { PerfectScrollbarConfigInterface } from 'ngx-perfect-scrollbar';

import { User } from '../../../shared/models/user.model';

import { AuthenticationService } from '../../../core/services/authentication.service';

import { sidebarCollpasedMenu } from '../../../core/helpers/app.helper';
import { environment } from '../../../../environments/environment';
import versionLog from 'src/assets/version';


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
	public currentVersion: string = versionLog.currentVersion // Access version directly from environment file

	constructor(
		private route: ActivatedRoute,
		private authenticationService: AuthenticationService
	) {
		this.menuItems = this.route.snapshot.data.sidebarMenu.menus;
		this.authenticationService.setLoginUser(this.route.snapshot.data.sidebarMenu.loginUser);
		this.loginUser = this.route.snapshot.data.sidebarMenu.loginUser;
	}

	ngOnInit() {

		// Add a new menu item
		// badge-pill float-right  mr-1 mt-1
	
		const versionMenuItem = {
			"id": 17,  // Use a unique ID for the new item
			"parent_menu_id": 0,
			"module": "setting",  // Specify the module
			"label": "/settings/version",
			"text": "Version "+ this.currentVersion,
			"link": "/settings/version",
			"icon": "fa fa-code-fork",  // Specify the icon class
			"order": 14,  // Adjust the order as needed
			"subscription": 1,
			"status": 1,
			"class": "",  // Additional CSS classes if needed
			// "badge": "new",
			// "badgeClass": "badge badge-pill badge-warning mt-1",
			"isExternalLink": false,
			"submenu": []  // You can add submenus if needed
		};
		
		// Check if menuItems already has an item with id 17
		const existingMenuItem = this.menuItems.find(item => item.id === 17);

		// Push the new menu item only if it doesn't exist
		if (!existingMenuItem) {
			this.menuItems.push(versionMenuItem);
		}
	}

	ngAfterViewInit() {
		setTimeout(() => {
			sidebarCollpasedMenu();
		});
	}

	logout() {
		this.authenticationService.logout();
	}
}
