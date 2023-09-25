import { Component, OnInit, Input, ViewChild } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';
import { DataTableDirective } from 'angular-datatables';
import { Subject } from 'rxjs';

import 'datatables.net';
import 'datatables.net-bs4';
import { TaskService } from 'src/app/core/services/task.service';

@Component({
	selector: 'app-project-tasks',
	templateUrl: './project-tasks.component.html',
	styleUrls: ['./project-tasks.component.scss']
})

export class ProjectTasksComponent implements OnInit {
	@Input() project;
	@Input() loginUser: any;
	@Input() apiUrl;
	@ViewChild(DataTableDirective, { static: true })
	dtElement: DataTableDirective;
	dtTrigger: Subject<any> = new Subject();
	dtOptions: any = {};

	taskDetails: any ;
	commentCounts: any[]=[{id:null }];
	attachmentCounts: any[]=[{id:null }];
	activityCounts: any[]=[{id:null }];
	taskProjectName: any[]=[{project_id:null }];
	
	constructor(
		public translate: TranslateService,
		private taskService: TaskService) {}

	ngOnInit() {
		this.loadDatatable();
		
		// to get total comment/activity/attachment length
		this.project.tasks.forEach((task) => {
			this.getTaskCommentLengthById(task.id, task.project_id);
		});
	}

	loadDatatable() {
		let that = this;
		this.dtOptions = {
			pagingType: 'full_numbers',
			pageLength: that.loginUser.settings.tables_pagination_limit,
			dom: '<"html5buttons"B>lTfgtip',
			paging: true,
			responsive: false,
			autoWidth: false,
			order: [[0, 'asc']],
			buttons: [{
				extend: 'csv',
				title: this.translate.instant('tasks.title'),
				className: "btn btn-datatable-gredient",
			}, {
				extend: 'excel',
				title: this.translate.instant('tasks.title'),
				className: "btn btn-datatable-gredient",
			}, {
				extend: 'pdf',
				title: this.translate.instant('tasks.title'),
				className: "btn btn-datatable-gredient",
			}],
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
			columnDefs: [
				{ width: "8%", targets: [0] },
				{ width: "42%", targets: [1] },
				{ width: "20%", targets: [2] },
				{ width: "20%", targets: [3] },
				{ width: "10%", targets: [4] },
				// { width: "10%", targets: [5] },
				// { width: "10%", targets: [6] }
			]
		};
	}

	ngAfterViewInit(): void {
		this.dtTrigger.next();
	}

	ngOnDestroy(): void {
		this.dtTrigger.unsubscribe();
	}

	// to get total comment/activity/attachment length
	getTaskCommentLengthById(id, project_id) {	 
		this.taskService.getById(id)
			.subscribe(
				data => {
					this.taskDetails = data;
					this.commentCounts[id] = this.taskDetails.comments.length;
					this.activityCounts[id] = this.taskDetails.activities.length;
					this.attachmentCounts[id] = this.taskDetails.attachments.length;
					this.taskProjectName[project_id] = this.taskDetails.project1.project_name;
 				});
	}

}