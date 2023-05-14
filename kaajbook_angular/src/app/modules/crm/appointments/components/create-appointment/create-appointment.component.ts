import { Component, OnInit, EventEmitter } from '@angular/core';
import { FormGroup, FormBuilder, FormControl, Validators } from '@angular/forms';
import { BsModalRef } from 'ngx-bootstrap/modal';
import { ToastrService } from 'ngx-toastr';
import { TranslateService } from '@ngx-translate/core';
import { Subject } from 'rxjs';

import { ClientService } from '../../../../../core/services/client.service';
import { ProviderService } from '../../../../../core/services/provider.service';
import { AppointmentsService } from '../../../../../core/services/appointments.service';

import { editorConfig } from '../../../../../core/helpers/admin.helper';

import * as moment from 'moment';

@Component({
	selector: 'app-create-appointment',
	templateUrl: './create-appointment.component.html',
	styleUrls: ['./create-appointment.component.scss']
})

export class CreateAppointmentComponent implements OnInit {
	public event: EventEmitter<any> = new EventEmitter();
	public onClose: Subject<boolean>;
	createAppointmentForm: FormGroup;
	availabileSlots: any;
	providers: any;
	minDate = new Date;
	clients = [];
	isFormSubmitted = false;
	isPageLoad = false;
	editorConfig = editorConfig;

	constructor(
		public translate: TranslateService,
		public bsCreateModalRef: BsModalRef,
		private formBuilder: FormBuilder,
		private toastr: ToastrService,
		private clientService: ClientService,
		private providerService: ProviderService,
		private appointmentsService: AppointmentsService
	) {}

	ngOnInit() {
		this.onClose = new Subject();
		this.getClients();
	}

	loadForm() {
		this.createAppointmentForm = this.formBuilder.group({
			title: [null, [Validators.required, Validators.maxLength(255)]],
			client_id: [null],
			provider_id: [null, Validators.required],
			attendees: [null, Validators.required],
			start_date_time: [null, Validators.required],
			end_date_time: [null, Validators.required],
			location: [''],
			note: [''],
			recurrence: [false],
			recurrence_pattern: [null],
			recurrence_occurrences: [null],
		});

		this.isPageLoad = true;
		this.checkAvailabilities(false);
	}

	get appointmentControl() { return this.createAppointmentForm.controls; }

	startDateChange(startDate) {
		this.createAppointmentForm.patchValue({ end_date_time: new Date(startDate.value) });
		this.checkAvailabilities(true);
	}

	changeRecurrence($event = []) {
		if (this.createAppointmentForm.value.recurrence) {
			this.createAppointmentForm.get('recurrence_occurrences').setValidators([Validators.required]);
			this.createAppointmentForm.get('recurrence_pattern').setValidators([Validators.required]);
			this.createAppointmentForm.get('recurrence_occurrences').updateValueAndValidity();
			this.createAppointmentForm.get('recurrence_pattern').updateValueAndValidity();
		} else {
			this.createAppointmentForm.patchValue({ 
				recurrence_occurrences: null, 
				recurrence_pattern: null
			});
			this.createAppointmentForm.get('recurrence_occurrences').clearValidators();
			this.createAppointmentForm.get('recurrence_pattern').clearValidators();
			this.createAppointmentForm.get('recurrence_occurrences').updateValueAndValidity();
			this.createAppointmentForm.get('recurrence_pattern').updateValueAndValidity();
		}
	}

	getClients() {
		this.clientService.getAll()
			.subscribe(
				data => {
					this.clients = data;
					this.getProviders();
				});
	}

	getProviders() {
		this.providerService.getAll()
			.subscribe(
				data => {
					this.providers = data;
					this.loadForm();
				});
	}

	checkAvailabilities(isValid) {
		if(!this.createAppointmentForm.value.provider_id && isValid) {
			this.toastr.error(this.translate.instant('appointments.create.error_messages.message9'), this.translate.instant('appointments.title'));
			return false;
		}

		this.createAppointmentForm.value.start_date_time = moment(this.createAppointmentForm.value.start_date_time).format('YYYY-MM-DD HH:mm:ss');
		this.appointmentsService.getAppointmentAvailabilities(this.createAppointmentForm.value)
			.subscribe(
				data => {
					this.availabileSlots = data;
				});
	}

	onSubmit() {
		this.isFormSubmitted = true;
		if (this.createAppointmentForm.invalid) {
			return;
		}

		this.createAppointmentForm.value.start_date_time = moment(this.createAppointmentForm.value.start_date_time).format('YYYY-MM-DD HH:mm:ss');
		this.createAppointmentForm.value.end_date_time = moment(this.createAppointmentForm.value.end_date_time).format('YYYY-MM-DD HH:mm:ss');

		this.appointmentsService.create(this.createAppointmentForm.value)
			.subscribe(
				data => {
					this.toastr.success(this.translate.instant('appointments.messages.create'), this.translate.instant('appointments.title'));
					this.event.emit({ data: true });
					this.onCancel();
				});
	}

	onCancel() {
		this.onClose.next(false);
		this.bsCreateModalRef.hide();
	}

}
