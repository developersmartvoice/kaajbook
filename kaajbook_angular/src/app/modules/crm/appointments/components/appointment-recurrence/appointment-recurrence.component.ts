import { Component, OnInit, Input, ViewChild } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';
import { ToastrService } from 'ngx-toastr';
import { DataTableDirective } from 'angular-datatables';
import { Subject } from 'rxjs';

import { AppointmentRecurrenceService } from '../../../../../core/services/appointment-recurrence.service';

import { appointment_status_key_value } from "./../../../../../core/helpers/crm-helper";

import 'datatables.net';
import 'datatables.net-bs4';

@Component({
  selector: 'app-appointment-recurrence',
  templateUrl: './appointment-recurrence.component.html',
  styleUrls: ['./appointment-recurrence.component.scss']
})
export class AppointmentRecurrenceComponent implements OnInit {

    @Input() appointment;
    @Input() loginUser: any;
    @Input() apiUrl;
    @ViewChild(DataTableDirective, { static: true })
    dtElement: DataTableDirective;
    dtTrigger: Subject<any> = new Subject();
    dtOptions: any = {};
    appointmentStatusKeyValue = appointment_status_key_value;

    constructor(
        private appointmentRecurrenceService: AppointmentRecurrenceService,
        public translate: TranslateService,
        private toastr: ToastrService
    ) { }

    ngOnInit() {
        this.loadDatatable();
    }

    loadDatatable() {
        let that = this;
        this.dtOptions = {
            pagingType: 'full_numbers',
            pageLength: that.loginUser.settings.tables_pagination_limit,
            dom: 'lTfgtip',
            paging: true,
            responsive: false,
            autoWidth: false,
            order: [0, 'asc'],
            language: {
                "sEmptyTable": this.translate.instant('common.datatable.sEmptyTable'),
                "sInfo": this.translate.instant('common.datatable.sInfo'),
                "sInfoEmpty": this.translate.instant('common.datatable.sInfoEmpty'),
                "sSearch": "",
                "sInfoPostFix": this.translate.instant('common.datatable.sInfoPostFix'),
                "sInfoThousands": this.translate.instant('common.datatable.sInfoThousands'),
                "sLengthMenu": this.translate.instant('common.datatable.sLengthMenu'),
                "sLoadingRecords": this.translate.instant('common.datatable.sLoadingRecords'),
                "sProcessing": this.translate.instant('common.datatable.sProcessing'),
                "sZeroRecords": this.translate.instant('common.datatable.sZeroRecords'),
                "sSearchPlaceholder": this.translate.instant('common.datatable.sSearchPlaceholder'),
                "oPaginate": {
                    "sFirst": this.translate.instant('common.datatable.oPaginate.sFirst'),
                    "sLast": this.translate.instant('common.datatable.oPaginate.sLast'),
                    "sNext": this.translate.instant('common.datatable.oPaginate.sNext'),
                    "sPrevious": this.translate.instant('common.datatable.oPaginate.sPrevious')
                },
                "oAria": {
                    "sSortAscending": this.translate.instant('common.datatable.oAria.sSortAscending'),
                    "sSortDescending": this.translate.instant('common.datatable.oAria.sSortDescending')
                }
            },
            columnDefs: [
                { width: "5%", targets: [0] },
                { width: "40%", targets: [1] },
                { width: "40%", targets: [2] },
                { width: "15%", targets: [3] }
            ]
        };
    }

    ngAfterViewInit(): void {
        this.dtTrigger.next();
    }

    ngOnDestroy(): void {
        this.dtTrigger.unsubscribe();
    }

    getTranslateStatus(statusKey) {
        return this.appointmentStatusKeyValue[statusKey];
    }

    changeAppointmentRecurrenceStatus(recurrenceId: any, status: any) {
        this.appointmentRecurrenceService.changeStatus({ id: recurrenceId, status: status.id }).subscribe(data => {
            this.toastr.success(this.translate.instant('appointments.messages.status'), this.translate.instant('appointments.title'));
        });
    }

}
