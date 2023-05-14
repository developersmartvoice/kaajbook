import { Component, OnInit } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Router, ActivatedRoute } from '@angular/router';
import { BsModalRef, BsModalService } from 'ngx-bootstrap/modal';
import { NgxRolesService } from 'ngx-permissions';
import { ToastrService } from 'ngx-toastr';
import { TranslateService } from '@ngx-translate/core';
import Swal from 'sweetalert2';

import { User } from './../../../../../shared/models/user.model';
import { ProjectPlanner } from './../../../../../shared/models/project-planner.model';

import { ProjectPlannerSprintService } from './../../../.././../core/services/project-planner-sprint.service';
import { AuthenticationService } from '../../../../../core/services/authentication.service';
import { ProjectService } from './../../../.././../core/services/project.service';
import { UserService } from '../../../../../core/services/user.service';

import { CreateProjectModalComponent } from './../../components/create-project-modal/create-project-modal.component';
import { EditProjectModalComponent } from './../../components/edit-project-modal/edit-project-modal.component';

import { ProjectLogos } from "../../../../../core/helpers/pm-helper";
import { environment } from '../../../../../../environments/environment';

@Component({
	selector: 'app-project-planner-list',
	templateUrl: './project-planner-list.component.html',
	styleUrls: ['./project-planner-list.component.scss']
})

export class ProjectPlannerListComponent implements OnInit {
	private apiUrl = environment.apiUrl;
	public modalRef: BsModalRef;
	projectPlanners: any;
	userLists = [];
	loginUser: any;
	isPageLoaded = false;
	statusfilterId = 0;
	logos = ProjectLogos;
	modalConfigs = {
		animated: true,
		keyboard: true,
		backdrop: true,
		ignoreBackdropClick: false,
		class: "inmodal modal-dialog-centered modal-lg animated fadeIn"
	};

	constructor(
		public translate: TranslateService,
		private toastr: ToastrService,
		public ngxRolesService: NgxRolesService,
		private modalService: BsModalService,
		private http: HttpClient,
		private projectPlannerService: ProjectPlannerSprintService,
		private userService: UserService,
		private projectService: ProjectService,
		private route: ActivatedRoute,
		private router: Router,
		private authenticationService: AuthenticationService
	) {
		this.authenticationService.loginUser.subscribe(x => this.loginUser = x);
	}

	ngOnInit() {
		this.getUserkeyBy();
		this.getAllProjectPlanner();
	}

	getCheckPermission(project, action) {
		let isPermission = false;
		let role = this.ngxRolesService.getRole('admin');
		if ((role && role.name == 'admin') || this.loginUser.is_super_admin) {
			isPermission = true;
		}

		if(project.user_id == this.loginUser.id && project.pivot[action]) {
			isPermission = true;
		}

		if(action == 'view') {
			for(let iRow in project.users) {
				if(project.users[iRow].id == this.loginUser.id) {
					isPermission = true;
				}
			}
		}

		return isPermission;
	}

	getAllProjectPlanner() {
		this.projectPlannerService.getAll({ 'statusId': this.statusfilterId }).subscribe(data => {
			this.projectPlanners = data;
			this.isPageLoaded = true;
		});
	}

	filterByStatus(statusfilterId) {
		this.statusfilterId = statusfilterId;
		this.getAllProjectPlanner();
	}

	getUserkeyBy() {
		this.userService.getUserkeyBy().subscribe(data => {
			this.userLists = data;
		});
	}

	openCreateProjectModal() {
		this.modalRef = this.modalService.show(CreateProjectModalComponent, this.modalConfigs);
		this.modalRef.content.event.subscribe(data => {
			this.getAllProjectPlanner();
		});
	}

	openEditProjectModal(projectID) {
		let modalConfigs = {
			animated: true,
			keyboard: true,
			backdrop: true,
			ignoreBackdropClick: false,
			class: "inmodal modal-dialog-centered modal-lg animated fadeIn",
			initialState: {
				projectId: projectID,
			}
		};
		this.modalRef = this.modalService.show(EditProjectModalComponent, modalConfigs);
		this.modalRef.content.event.subscribe(data => {
			this.getAllProjectPlanner();
		});
	}

	deleteProject(projectId) {
		Swal.fire({
			title: this.translate.instant('common.swal.title'),
			text: this.translate.instant('common.swal.text4'),
			type: 'warning',
			showCancelButton: true,
			confirmButtonText: this.translate.instant('common.swal.confirmButtonText'),
			cancelButtonText: this.translate.instant('common.swal.cancelButtonText')
		}).then((result) => {
			if (result.value) {
				this.projectService.delete(projectId, { 'ProjectLogos' : this.logos }).subscribe(data => {
					this.toastr.success(this.translate.instant('projects.messages.delete'), this.translate.instant('project_planner.title'));
					this.getAllProjectPlanner();
				});
			}
		});
	}

}
