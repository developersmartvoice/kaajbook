import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute } from "@angular/router";
import { NgxRolesService, NgxPermissionsService } from 'ngx-permissions';

import { IncidentService } from '../../../../../core/services/incident.service';
import { AuthenticationService } from '../../../../../core/services/authentication.service';

import { environment } from '../../../../../../environments/environment';

@Component({
	selector: 'app-incident-detail',
	templateUrl: './incident-detail.component.html',
	styleUrls: ['./incident-detail.component.scss']
})

export class IncidentDetailComponent implements OnInit {
	public apiUrl = environment.apiUrl;
	incident: any;
	loginUser: any;
	activities: any;
	IncidentTab = 1;
	isPageLoaded = false;
	activeIncidentTab = '1';
	permission = false;
	permissions: any;

	constructor(
		public ngxRolesService: NgxRolesService,
		private route: ActivatedRoute,
		private router: Router,
		private incidentService: IncidentService,
		private authenticationService: AuthenticationService,
		private ngxPermissionsService: NgxPermissionsService
	) {
		this.authenticationService.loginUser.subscribe(x => this.loginUser = x);
		this.ngxPermissionsService.permissions$.subscribe((permissions) => {
            this.permissions = permissions;
        });
		this.route.paramMap.subscribe(params => {
			this.getById(params.get('id'));
		});
	}

	ngOnInit() {}

	getCheckPermission(incident) {
		if(this.permissions.incidents_edit){
			let role = this.ngxRolesService.getRole('admin');
			if ((role && role.name == 'admin') || this.loginUser.is_super_admin) {
				this.permission = true;
			} else if(incident.assign_to == this.loginUser.id || incident.create_user_id == this.loginUser.id) {
				this.permission = true;
			}
		}
	}

	getActiveIncidentTab(tab) {
		return this.activeIncidentTab = tab;
	}

	setActiveIncidentTab(tab) {
		return this.activeIncidentTab === tab;
	}

	getById(incidentId) {
		this.incidentService.getById(incidentId)
			.subscribe(
				data => {
					this.incident = data;
					this.getCheckPermission(this.incident);
					this.isPageLoaded = true;
				}
			);

	}
}