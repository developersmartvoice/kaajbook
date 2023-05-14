import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from "@angular/router";
import { NgxRolesService, NgxPermissionsService } from 'ngx-permissions';

import { DefectService } from '../../../../../core/services/defect.service';
import { AuthenticationService } from '../../../../../core/services/authentication.service';

import { environment } from '../../../../../../environments/environment';

@Component({
	selector: 'app-defect-detail',
	templateUrl: './defect-detail.component.html',
	styleUrls: ['./defect-detail.component.scss']
})

export class DefectDetailComponent implements OnInit {
	public apiUrl = environment.apiUrl;
	defect: any;
	loginUser: any;
	isPageLoaded = false;
	permission = false;
	permissions: any;

	constructor(
		private route: ActivatedRoute,
		public ngxRolesService: NgxRolesService,
		private defectService: DefectService,
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

	getCheckPermission(defect) {
		if(this.permissions.defects_edit){
			let role = this.ngxRolesService.getRole('admin');
			if ((role && role.name == 'admin') || this.loginUser.is_super_admin) {
				this.permission = true;
			} else if(defect.assign_member == this.loginUser.id || defect.create_user_id == this.loginUser.id) {
				this.permission = true;
			}
		}
	}

	getById(defectId) {
		this.defectService.getById(defectId).subscribe(data => {
			this.defect = data;
			this.getCheckPermission(this.defect);
			this.isPageLoaded = true;
		});
	}
}