import { AfterViewInit, Component, OnDestroy, OnInit, ViewChild } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Router, ActivatedRoute } from '@angular/router';
import { BsModalRef, BsModalService } from 'ngx-bootstrap/modal';
import { ExportAsService, ExportAsConfig } from 'ngx-export-as';
import { ToastrService } from 'ngx-toastr';
import { NgxRolesService, NgxPermissionsService } from 'ngx-permissions';
import { TranslateService } from '@ngx-translate/core';
import { DataTableDirective } from 'angular-datatables';
import { Subject } from 'rxjs';
import Swal from 'sweetalert2';
import * as JSZip from 'jszip';
import { forkJoin } from 'rxjs';

import { User } from './../../../../../shared/models/user.model';

import { ProjectService } from '../../../../../core/services/project.service';
import { UserService } from '../../../../../core/services/user.service';
import { AuthenticationService } from '../../../../../core/services/authentication.service';

import { ProjectImportComponent } from './../../components/project-import/project-import.component'

import { DatatablesResponse } from '../../../../../core/helpers/datatables-response.helper';
import { project_status_key_value, ProjectLogos } from "./../../../../../core/helpers/pm-helper";
import { environment } from '../../../../../../environments/environment';

import 'datatables.net';
import 'datatables.net-bs4';
import { TaskAttachmentService } from 'src/app/core/services/task-attachment.service';
import { catchError } from 'rxjs/operators';

@Component({
	selector: 'app-project-list',
	templateUrl: './project-list.component.html',
	styleUrls: ['./project-list.component.scss'],
	preserveWhitespaces: true
})

export class ProjectListComponent implements OnInit {
	public apiUrl = environment.apiUrl;
	public modalRef: BsModalRef;
	@ViewChild(DataTableDirective, { static: true })
	dtElement: DataTableDirective;
	dtTrigger: Subject<any> = new Subject();
	dtOptions: any = {};
	projectCount: any;
	countStatus: any;
	statusfilterId: number;
	loginUser: any;
	permissions: any;
	projects = [];
	userLists = [];
	isPageLoaded = false;
	projectstatusKeyValue = project_status_key_value;
	logos = ProjectLogos;
	exportAsConfig: ExportAsConfig = {
		type: 'pdf',
		elementIdOrContent: 'projects_table',
	};
	modalConfigs = {
		animated: true,
		keyboard: true,
		backdrop: true,
		ignoreBackdropClick: false,
		class: "inmodal modal-dialog-centered modal-md animated fadeIn"
	};

	constructor(
		public translate: TranslateService,
		public ngxRolesService: NgxRolesService,
		private modalService: BsModalService,
		private http: HttpClient,
		private router: Router,
		private route: ActivatedRoute,
		private exportAsService: ExportAsService,
		private toastr: ToastrService,
		private projectService: ProjectService,
		private userService: UserService,
		private taskAttachmentService: TaskAttachmentService,
		private authenticationService: AuthenticationService,
		private ngxPermissionsService: NgxPermissionsService
	) {
		this.authenticationService.loginUser.subscribe(x => this.loginUser = x);
		this.ngxPermissionsService.permissions$.subscribe((permissions) => {
            this.permissions = permissions;
        });
	}

	ngOnInit() {
		this.getUserkeyBy();
		this.loadProjectDatatable();
	}

	getUserkeyBy() {
		this.userService.getUserkeyBy().subscribe(data => {
			this.userLists = data;
		});
	}

	loadProjectDatatable() {
		this.statusfilterId = 0;
		if (this.route.snapshot.params['statusId'])
			this.statusfilterId = this.route.snapshot.params['statusId'];

		let that = this;
		this.dtOptions = {
			pagingType: 'full_numbers',
			pageLength: that.loginUser.settings.tables_pagination_limit,
			serverSide: true,
			processing: true,
			dom: '<"html5buttons"B>ltfrtip',
			order: [0],
			columns: [
				{
					'sortable': false,
					'width': "5%",
					'target': [0]
				},
				{
					'sortable': true,
					'width': "10%",
					'target': [1]
				},
				{
					'sortable': true,
					'width': "21%",
					'target': [2]
				},
				{
					'sortable': true,
					'width': "10%",
					'target': [3]
				},
				{
					'sortable': true,
					'width': "22%",
					'target': [4]
				},
				{
					'sortable': true,
					'width': "5%",
					'target': [5]
				},
				{
					'sortable': false,
					'width': "5%",
					'target': [6]
				},
				{
					'sortable': true,
					'width': "17%",
					'target': [7]
				},
				{
					'sortable': false,
					'width': "5%",
					'target': [8]
				}

			],
			buttons: [
				{
					extend: 'csv',
					title: this.translate.instant('projects.title'),
					className: "btn btn-datatable-gredient",
					action: function (e, dt, node, config) {
						that.exportFiles('csv')
					}
				}, {
					extend: 'excel',
					title: this.translate.instant('projects.title'),
					className: "btn btn-datatable-gredient",
					action: function (e, dt, node, config) {
						that.exportFiles('xlsx')
					}
				}, {
					extend: 'pdf',
					title: this.translate.instant('projects.title'),
					className: "btn btn-datatable-gredient",
					action: function (e, dt, node, config) {
						that.exportFiles('pdf')
					}
				}
			],
			language: {
				"sEmptyTable":  this.translate.instant('common.datatable.sEmptyTable'),
				"sInfo":           this.translate.instant('common.datatable.sInfo'),
				"sInfoEmpty":      this.translate.instant('common.datatable.sInfoEmpty'),
				"sSearch": "",
				"sInfoPostFix":    this.translate.instant('common.datatable.sInfoPostFix'),
				"sInfoThousands":  this.translate.instant('common.datatable.sInfoThousands'),
				"sLengthMenu":     this.translate.instant('common.datatable.sLengthMenu'),
				"sLoadingRecords": this.translate.instant('common.datatable.sLoadingRecords'),
				"sProcessing":     this.translate.instant('common.datatable.sProcessing'),
				"sZeroRecords":    this.translate.instant('common.datatable.sZeroRecords'),
				"sSearchPlaceholder": this.translate.instant('common.datatable.sSearchPlaceholder'),
				"oPaginate": {
					"sFirst":    this.translate.instant('common.datatable.oPaginate.sFirst'),
					"sLast":     this.translate.instant('common.datatable.oPaginate.sLast'),
					"sNext":     this.translate.instant('common.datatable.oPaginate.sNext'),
					"sPrevious": this.translate.instant('common.datatable.oPaginate.sPrevious')
				},
				"oAria": {
					"sSortAscending":  this.translate.instant('common.datatable.oAria.sSortAscending'),
					"sSortDescending": this.translate.instant('common.datatable.oAria.sSortDescending')
				}
			},
			ajax: (dataTablesParameters: any, callback) => {
				dataTablesParameters = {
					columns: dataTablesParameters.columns,
					draw: dataTablesParameters.draw,
					length: dataTablesParameters.length,
					order: dataTablesParameters.order,
					search: dataTablesParameters.search,
					start: dataTablesParameters.start,
					statusId: this.statusfilterId,
				}
				this.http
					.post<DatatablesResponse>(this.apiUrl + '/api/all-projects', dataTablesParameters, {})
					.subscribe(resp => {
						this.isPageLoaded = true
						this.projects = resp.data;
						this.countStatus = resp;
						this.countStatus = this.countStatus.statusCount;

						callback({
							recordsTotal: resp.recordsTotal,
							recordsFiltered: resp.recordsFiltered,
							data: [],
						});

					});
			}
		};
	}

	getCheckPermission(project, action) {
		if( ( action == 'edit' && this.permissions.projects_edit ) || ( action == 'delete' && this.permissions.projects_delete) ) {
			let role = this.ngxRolesService.getRole('admin');
			if ((role && role.name == 'admin') || this.loginUser.is_super_admin) {
				return true;
			}

			if(project.pivot[action]) {
				return true;
			}
		}
		return false;
	}

	getTranslateStatus(statusKey) {
		return this.projectstatusKeyValue[statusKey];
	}

	ngAfterViewInit(): void {
		this.dtTrigger.next();
	}

	ngOnDestroy(): void {
		this.dtTrigger.unsubscribe();
	}

	rerender(): void {
		this.dtElement.dtInstance.then((dtInstance: DataTables.Api) => {
			dtInstance.destroy();
			setTimeout(() => {
				this.dtTrigger.next();

				if(this.projects.length > 0) {
					$('.tfoot_dt').addClass('d-none');
				} else {
					$('.tfoot_dt').removeClass('d-none');
				}
			});
		});
	}

	exportFiles(type) {
		this.exportAsConfig.type = type;
		this.exportAsService.save(this.exportAsConfig, this.translate.instant('projects.title')).subscribe(() => {});
	}

	deleteProject(id) {
		Swal.fire({
			title: this.translate.instant('common.swal.title'),
			text: this.translate.instant('common.swal.text4'),
			type: 'warning',
			showCancelButton: true,
			confirmButtonText: this.translate.instant('common.swal.confirmButtonText'),
			cancelButtonText: this.translate.instant('common.swal.cancelButtonText')
		}).then((result) => {
			if (result.value) {
				this.projectService.delete(id, { 'ProjectLogos' : this.logos } ).subscribe(data => {
					this.toastr.success(this.translate.instant('projects.messages.delete'), this.translate.instant('projects.title'));
					this.rerender();
				});
			}
		});
	}

	changeProjectStatus(projectIDs: any, status: any) {
		let changeProject = {
			ids: projectIDs,
			status: status.id
		}
		this.projectService.changeStatus(changeProject)
			.subscribe(
				data => {
					this.toastr.success(this.translate.instant('projects.messages.status'), this.translate.instant('projects.title'));
					this.rerender();
				});
	}

	filterByStatus(statusID) {
		this.router.routeReuseStrategy.shouldReuseRoute = () => false;
		this.router.navigate(['projects', statusID]);
	}

	openProjectImportModal() {
		this.modalRef = this.modalService.show(ProjectImportComponent, this.modalConfigs);
		this.modalRef.content.event.subscribe(data => {
			this.toastr.success(this.translate.instant('projects.messages.import'), this.translate.instant('projects.title'));
			this.rerender();
		});
	}

	saveProjectDetail(index, name, value) {
		this.projects[index][name] = value;
		this.projectService.update(this.projects[index])
			.subscribe(
				data => {
					this.toastr.success(this.translate.instant('projects.messages.update'), this.translate.instant('projects.title'));
					this.rerender();
				});
	}
	
	getAllProjectAndTaskAttachment(project_id) {
		let projectAndTaskAttachments: any[] = [];
		let projectsAllTaskId: any;
	  
		this.projectService.getById(project_id).subscribe(
		  (projectData: any) => {
			projectsAllTaskId = projectData.tasks.map(task => task.id);
			this.isPageLoaded = true;
	  
			// Create an array of observables for each task attachment request
			const taskAttachmentRequests = projectsAllTaskId.map(taskId =>
			  this.taskAttachmentService.getAllAttachmentById(taskId)
			);
	  
			// Use forkJoin to make parallel requests for all task attachments
			forkJoin(taskAttachmentRequests).subscribe(
			  (attachmentsArray: any[]) => {
				// Flatten the array of arrays
				const taskAttachments = attachmentsArray.reduce((acc, curr) => acc.concat(curr), []);
	  
				// Merge project and task attachments using concat
				projectAndTaskAttachments = projectData.attachments.concat(taskAttachments);
	  
				// console.log('Merged Attachments:', projectAndTaskAttachments);
				this.zipAndDownloadAllAttachment(projectAndTaskAttachments, projectData.project_name);
			  },
			  error => {
				console.error('Error fetching task attachments:', error);
			  }
			);
		  },
		  error => {
			console.error('Error fetching project data:', error);
		  }
		);
	  }
	  

	  zipAndDownloadAllAttachment(attachments, project_name) {
		if (attachments.length === 0)
		return this.toastr.error(
			this.translate.instant("common.error_messages.message7"),
			this.translate.instant("common.errors_keys.key5")
		);
		
		// console.log(attachments);
		const zip = new JSZip();
		const downloadObservables = [];
	  
		for (const attachment of attachments) {
		  const isTaskAttachment = attachment.hasOwnProperty('task_id');
		  const attachmentTypeFolder = isTaskAttachment ? 'task_attachment' : 'project_attachment';
	  
		  const downloadURL = this.apiUrl + `/uploads/${attachmentTypeFolder}/${attachment.file_hash}`;
		//   console.log(downloadURL);
		  downloadObservables.push(this.http.get(downloadURL, { responseType: 'blob' }));
		}
	  
		forkJoin(downloadObservables).subscribe((blobs: Blob[]) => {
		  for (let i = 0; i < blobs.length; i++) {
			const fileName = attachments[i].file_name;
			zip.file(fileName, blobs[i], { binary: true });
		  }
	  
		  zip.generateAsync({ type: 'blob' }).then(content => {
			const url = window.URL.createObjectURL(content);
			const link = document.createElement('a');
			link.href = url;
			link.setAttribute('download', `${project_name}_attachments.zip`);
			document.body.appendChild(link);
			link.click();
			link.remove();
		  });
		});
	}

}