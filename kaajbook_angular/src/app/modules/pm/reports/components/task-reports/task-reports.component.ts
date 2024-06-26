import {
	AfterViewInit,
	Component,
	OnDestroy,
	OnInit,
	ViewChild
} from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { DataTableDirective } from 'angular-datatables';
import { ExportAsService, ExportAsConfig } from 'ngx-export-as';
import { TranslateService } from '@ngx-translate/core';
import { Subject } from 'rxjs';

import { AuthenticationService } from '../../../../../core/services/authentication.service';

import { DatatablesResponse } from '../../../../../core/helpers/datatables-response.helper';
import { environment } from '../../../../../../environments/environment';

import 'datatables.net';
import 'datatables.net-bs4';

@Component({
	selector: 'app-task-reports',
	templateUrl: './task-reports.component.html',
	styleUrls: ['./task-reports.component.scss']
})

export class TaskReportsComponent implements OnInit {
	private apiUrl = environment.apiUrl;
	@ViewChild(DataTableDirective, {static: true})
	dtElement: DataTableDirective;
	dtTrigger: Subject<any> = new Subject();
	dtOptions: any = {};
	loginUser: any;
	tasks = [];
	isPageLoaded = false;
	exportAsConfig: ExportAsConfig = {
		type: 'pdf',
		elementIdOrContent: 'tasks_table',
	};

	constructor(
		public translate: TranslateService,
		private http: HttpClient,
		private authenticationService: AuthenticationService,
		private exportAsService: ExportAsService
	) {
		this.authenticationService.loginUser.subscribe(x => this.loginUser = x);
	}

	ngOnInit() {
		this.loadDatatable();
	}

	loadDatatable() {
		let that = this;
		this.dtOptions = {
			pagingType: 'full_numbers',
			pageLength: that.loginUser.settings.tables_pagination_limit,
			serverSide: true,
			searching: true,
			processing: true,
			responsive: true,
			dom: '<"html5buttons"B>ltfrtip',
			stateSave: true,
			lengthMenu: [
				[10, 25, 50, 100, 99999999], // Values used for the actual data limit
				[10, 25, 50, 100, "Show all"] // Labels shown in the UI
			],
			order: [0],
			columns: [
				{
					'sortable': true,
					'target': [0]
				},
				{
					'sortable': true,
					'target': [1]
				},
				// {
				// 	'sortable': true,
				// 	'target': [2]
				// },
				// {
				// 	'sortable': true,
				// 	'target': [3]
				// },
				{
					'sortable': true,
					'target': [2]
				},
				{
					'sortable': true,
					'target': [3]
				},
				// {
				// 	'sortable': true,
				// 	'target': [6]
				// },
				{
					'sortable': true,
					'target': [4]
				},
				// {
				// 	'sortable': true,
				// 	'target': [8]
				// },
				{
					'sortable': true,
					'target': [5]
				},
				// {
				// 	'sortable': true,
				// 	'target': [10]
				// },
				// {
				// 	'sortable': true,
				// 	'target': [11]
				// },
				{
					'sortable': true,
					'target': [6]
				}//,
				// {
				// 	'sortable': true,
				// 	'target': [13]
				// }
			],
			buttons: [
				{
					extend: 'csv',
					title: this.translate.instant('reports.headings.task_report'),
					className: "btn btn-datatable-gredient",
					action: function (e, dt, node, config) {
						that.exportFiles('csv')
					}
				}, {
					extend: 'excel',
					title: this.translate.instant('reports.headings.task_report'),
					className: "btn btn-datatable-gredient",
					action: function (e, dt, node, config) {
						that.exportFiles('xlsx')
					}
				}, {
					extend: 'pdf',
					title: this.translate.instant('reports.headings.task_report'),
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
				this.http
					.post<DatatablesResponse>(this.apiUrl + '/api/tasks/task-report', dataTablesParameters, {})
					.subscribe(resp => {
						this.tasks = resp.data;
						this.isPageLoaded = true

						callback({
							recordsTotal: resp.recordsTotal,
							recordsFiltered: resp.recordsFiltered,
							data: [],
						});

					});
			}

		};
	}

	exportFiles(type) {
		this.exportAsConfig.type = type;
		this.exportAsService.save(this.exportAsConfig, this.translate.instant('reports.headings.task_report')).subscribe(() => {});
	}

	ngOnDestroy(): void {
		this.dtTrigger.unsubscribe();
	}

	rerender(): void {
		this.dtElement.dtInstance.then((dtInstance: DataTables.Api) => {
			dtInstance.destroy();
			setTimeout(() => {
				this.dtTrigger.next();

				if(this.tasks.length > 0) {
					$('.tfoot_dt').addClass('d-none');
				} else {
					$('.tfoot_dt').removeClass('d-none');
				}
			});
		});
	}

	ngAfterViewInit(): void {
		this.dtTrigger.next();
		this.dtElement.dtInstance.then((dtInstance: DataTables.Api) => {
			dtInstance.columns().every(function () {
				const that = this;
				$('input', this.footer()).on('keyup change', function () {
					if (that.search() !== this['value']) {
						that.search(this['value']).draw();
					}
				});
			});
		});
	}
}
