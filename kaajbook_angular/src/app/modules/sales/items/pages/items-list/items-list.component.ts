import {
	Component,
	OnInit,
	AfterViewInit,
	OnDestroy,
	ViewChild,
	ElementRef
} from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { DataTableDirective } from 'angular-datatables';
import { BsModalRef, BsModalService } from 'ngx-bootstrap/modal';
import { ExportAsService, ExportAsConfig } from 'ngx-export-as';
import { ToastrService } from 'ngx-toastr';
import { TranslateService } from '@ngx-translate/core';
import { NgxRolesService } from 'ngx-permissions';
import { Subject } from 'rxjs';
import Swal from 'sweetalert2';

import { ItemService } from '../../../../../core/services/item.service';
import { AuthenticationService } from '../../../../../core/services/authentication.service';

import { ItemGroupsComponent } from '../../components/item-groups/item-groups.component';
import { CreateItemComponent } from '../../components/create-item/create-item.component';
import { EditItemComponent } from '../../components/edit-item/edit-item.component';

import { DatatablesResponse } from '../../../../../core/helpers/datatables-response.helper';
import { environment } from '../../../../../../environments/environment';

import 'datatables.net';
import 'datatables.net-bs4';

@Component({
	selector: 'app-items-list',
	templateUrl: './items-list.component.html',
	styleUrls: ['./items-list.component.scss']
})

export class ItemsListComponent implements OnInit {
	private apiUrl = environment.apiUrl;
	public modalRef: BsModalRef;
	@ViewChild(DataTableDirective, {static: true})
	dtElement: DataTableDirective;
	dtTrigger: Subject<any> = new Subject();
	dtOptions: any = {};
	items = [];
	loginUser: any;
	exportAsConfig: ExportAsConfig = {
		type: 'pdf',
		elementIdOrContent: 'items_table',
	};
	modalConfigs = {
		animated: true,
		keyboard: true,
		backdrop: true,
		ignoreBackdropClick: false,
		class: "inmodal modal-dialog-centered modal-lg animated fadeIn"
	};

	constructor(
		public translate: TranslateService,
		public ngxRolesService: NgxRolesService,
		private http: HttpClient,
		private toastr: ToastrService,
		private modalService: BsModalService,
		private exportAsService: ExportAsService,
		private authenticationService: AuthenticationService,
		private itemService: ItemService
	) {
		this.authenticationService.loginUser.subscribe(x => this.loginUser = x);
	}

	ngOnInit() {
		this.loadItemDatatable();
	}

	loadItemDatatable() {
		let that = this;
		this.dtOptions = {
			pagingType: 'full_numbers',
			pageLength: that.loginUser.settings.tables_pagination_limit,
			serverSide: true,
			processing: true,
			dom: '<"html5buttons"B>ltfrtip',
			stateSave: true,
			lengthMenu: [
				[10, 25, 50, 100, 99999999], // Values used for the actual data limit
				[10, 25, 50, 100, "Show all"] // Labels shown in the UI
			],
			order:[0],
			columns: [{
					'sortable': true,
					'width': "25%",
					'target': [0]
				}, {
					'sortable': true,
					'width': "35%",
					'target': [1]
				}, {
					'sortable': false,
					'width': "15%",
					'target': [2]
				}, {
					'sortable': true,
					'width': "10%",
					'target': [3]
				}, {
					'sortable': true,
					'width': "10%",
					'target': [4]
				}, {
					'sortable': false,
					'width': "5%",
					'target': [5]
				}
			],
			buttons: [{
				extend: 'csv',
				className: "btn btn-datatable-gredient",
				action: function (e, dt, node, config) {
					that.exportFiles('csv')
				}
			}, {
				extend: 'excel',
				className: "btn btn-datatable-gredient",
				action: function (e, dt, node, config) {
					that.exportFiles('xlsx')
				}
			}, {
				extend: 'pdf',
				className: "btn btn-datatable-gredient",
				action: function (e, dt, node, config) {
					that.exportFiles('pdf')
				}
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
			ajax: (dataTablesParameters: any, callback) => {
				this.http.post<DatatablesResponse>(this.apiUrl + '/api/all-items', dataTablesParameters, {}).subscribe(resp => {
					if(resp) {
						this.items = resp.data;
					}
					
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
		this.exportAsService.save(this.exportAsConfig, this.translate.instant('items.title')).subscribe(() => { });
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

				if(this.items.length > 0) {
					$('.tfoot_dt').addClass('d-none');
				} else {
					$('.tfoot_dt').removeClass('d-none');
				}
			});
		});
	}

	openItemCreateModal() {
		this.modalRef = this.modalService.show(CreateItemComponent, this.modalConfigs);
		this.modalRef.content.event.subscribe(data => {
			this.rerender();
		});
	}

	openItemEditModal(item) {
		let modalConfig = {
			animated: true,
			keyboard: true,
			backdrop: true,
			ignoreBackdropClick: false,
			class: "inmodal modal-dialog-centered modal-lg animated fadeIn",
			initialState: {
				item: item
			}
		}

		this.modalRef = this.modalService.show(EditItemComponent, modalConfig);
		this.modalRef.content.event.subscribe(data => {
			this.rerender();
		});
	}

	deleteItem(id) {
		Swal.fire({
			title: this.translate.instant('common.swal.title'),
			text: this.translate.instant('common.swal.text') + this.translate.instant('items.title1'),
			type: 'warning',
			showCancelButton: true,
			confirmButtonText: this.translate.instant('common.swal.confirmButtonText'),
			cancelButtonText: this.translate.instant('common.swal.cancelButtonText')
		}).then((result) => {
			if (result.value) {
				this.itemService.delete(id)
				.subscribe(
					data => {
						this.toastr.success(this.translate.instant('items.messages.delete'), this.translate.instant('items.title'));
						this.rerender();
					});
			}
		});
	}

}
