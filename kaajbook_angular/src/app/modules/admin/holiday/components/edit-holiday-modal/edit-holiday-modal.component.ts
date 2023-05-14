import { Component, OnInit, EventEmitter } from '@angular/core';
import {
	FormGroup,
	FormBuilder,
	FormControl,
	Validators
} from '@angular/forms';
import { BsModalRef } from 'ngx-bootstrap/modal';
import { ToastrService } from 'ngx-toastr';
import { TranslateService } from '@ngx-translate/core';
import { Subject } from 'rxjs';

import { HolidayService } from '../../../../../core/services/holiday.service';
import { AuthenticationService } from '../../../../../core/services/authentication.service';

import { editorConfig, datepickerConfig } from '../../../../../core/helpers/admin.helper';

import { Holiday } from '../../../../../shared/models/holiday.model';

@Component({
	selector: 'app-edit-holiday-modal',
	templateUrl: './edit-holiday-modal.component.html',
	styleUrls: ['./edit-holiday-modal.component.scss']
})

export class EditHolidayModalComponent implements OnInit {
	public event: EventEmitter<any> = new EventEmitter();
	public onClose: Subject<boolean>;
	editHolidayForm: FormGroup;
	loginUser: any;
	holiday: Holiday;
	isFormSubmitted = false;
	datepickerConfig = datepickerConfig;
	editorConfig = editorConfig;

	constructor (
		public translate: TranslateService,
		public bsModalRef: BsModalRef,
		private formBuilder: FormBuilder,
		private toastr: ToastrService,
		private holidayService: HolidayService,
		private authenticationService: AuthenticationService
	) { 
		this.authenticationService.loginUser.subscribe(x => this.loginUser = x);
		this.datepickerConfig.dateInputFormat = this.loginUser.settings.date_format;
	}

	ngOnInit() {
		this.onClose = new Subject();
		this.loadForms();
	}

	loadForms() {
		this.editHolidayForm = this.formBuilder.group({
			id: [this.holiday.id],
			event_name: [this.holiday.event_name, [Validators.required, Validators.maxLength(50)]],
			description: [this.holiday.description],
			date: [new Date(this.holiday.date), Validators.required],
			location: [this.holiday.location],
			color: [this.holiday.color]
		});
	}

	get holidayControl() { return this.editHolidayForm.controls; }

	onSubmit() {
		this.isFormSubmitted = true;
		if (this.editHolidayForm.invalid) {
			return;
		}

		this.holidayService.update(this.editHolidayForm.value).subscribe(data => {
			this.toastr.success(this.translate.instant('holidays.messages.update'), this.translate.instant('holidays.title'));
			this.event.emit({ data: true });
			this.onCancel();
		}, error => {
			this.onCancel();
		});
	}

	onCancel() {
		this.onClose.next(false);
		this.bsModalRef.hide();
	}

}
