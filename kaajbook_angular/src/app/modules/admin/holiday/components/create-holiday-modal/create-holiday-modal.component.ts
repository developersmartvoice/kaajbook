import { Component, OnInit, EventEmitter } from '@angular/core';
import { DatePipe } from '@angular/common';
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

@Component({
	selector: 'app-create-holiday-modal',
	templateUrl: './create-holiday-modal.component.html',
	styleUrls: ['./create-holiday-modal.component.scss']
})

export class CreateHolidayModalComponent implements OnInit {
	public event: EventEmitter<any> = new EventEmitter();
	public onClose: Subject<boolean>;
	createHolidayForm: FormGroup;
	loginUser: any;
	isFormSubmitted = false;
	datepickerConfig = datepickerConfig;
	editorConfig = editorConfig;

	constructor (
		public translate: TranslateService,
		public datepipe: DatePipe,
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
		this.createHolidayForm = this.formBuilder.group({
			event_name: [null, [Validators.required, Validators.maxLength(50)]],
			description: [''],
			date: [null, Validators.required],
			location: [''],
			color: ['#1ab394']
		});
	}

	get holidayControl() { return this.createHolidayForm.controls; }

	onSubmit() {
		this.isFormSubmitted = true;
		if (this.createHolidayForm.invalid) {
			return;
		}
		this.createHolidayForm.value.date = this.datepipe.transform(this.createHolidayForm.value.date, 'yyyy-MM-dd');
		this.holidayService.create(this.createHolidayForm.value).subscribe(data => {
			this.toastr.success(this.translate.instant('holidays.messages.create'), this.translate.instant('holidays.title'));
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
