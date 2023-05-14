import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute } from "@angular/router";

import { ProjectService } from '../../../../../core/services/project.service';
import { AuthenticationService } from '../../../../../core/services/authentication.service';

import { environment } from '../../../../../../environments/environment';

@Component({
	selector: 'app-project-detail',
	templateUrl: './project-detail.component.html',
	styleUrls: ['./project-detail.component.scss']
})

export class ProjectDetailComponent implements OnInit {
	private apiUrl = environment.apiUrl;
	project: any;
	loginUser: any;
	permissions: any = [];
	isProjectTab = 1;
	activeProjectTab = '1';
	isPageLoaded = false;

	constructor(
		private route: ActivatedRoute,
		private router: Router,
		private projectService: ProjectService,
		private authenticationService: AuthenticationService
	) {
		this.authenticationService.loginUser.subscribe(x => this.loginUser = x);
		this.route.paramMap.subscribe(params => {
			this.getCheckPermission(params);
		});
	}

	ngOnInit() {}

	getCheckPermission(params) {
		this.projectService.getProjectPermission(params.get('id')).subscribe(res => {
			this.permissions['edit_delete_permission'] = res;
			if(this.permissions.edit_delete_permission.view) {
				this.getById(params.get('id'));
			} else {
				this.router.navigate(['projects']);
			}
		}, error => {
			this.router.navigate(['projects']);
		});
	}

	setActiveProjectTab($event) {
		this.activeProjectTab = $event.id;
	}

	getActiveProjectTab(tab) {
		return this.activeProjectTab === tab;
	}

	isComponentload(tab) {
		return this.isProjectTab === tab;
	}

	getById(projectId) {
		this.projectService.getById(projectId)
			.subscribe(
				data => {
					this.project = data;
					this.isPageLoaded = true;
				}
			);

	}
}
