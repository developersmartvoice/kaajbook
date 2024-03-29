import { Component, OnInit } from '@angular/core';
import { DatePipe } from '@angular/common';
import {
	FormGroup,
	FormBuilder,
	FormControl,
	Validators
} from '@angular/forms';
import { Router } from '@angular/router';
import { ToastrService } from 'ngx-toastr';
import { TranslateService } from '@ngx-translate/core';

import { AnnouncementService } from '../../../../../core/services/announcement.service';
import { AuthenticationService } from '../../../../../core/services/authentication.service';

import { editorConfig, datepickerConfig } from '../../../../../core/helpers/admin.helper';

@Component({
	selector: 'app-announcement-create',
	templateUrl: './announcement-create.component.html',
	styleUrls: ['./announcement-create.component.scss'],
	providers: [DatePipe]
})

export class AnnouncementCreateComponent implements OnInit {
	loginUser: any;
	createAnnouncementForm: FormGroup;
	isFormSubmitted = false;
	datepickerConfig = datepickerConfig;
	editorConfig = editorConfig;

	constructor(
		public translate: TranslateService,
		private datePipe: DatePipe,
		private router: Router,
		private formBuilder: FormBuilder,
		private toastr: ToastrService,
		private announcementService: AnnouncementService,
		private authenticationService: AuthenticationService
	) { 
		this.authenticationService.loginUser.subscribe(x => this.loginUser = x);
		this.datepickerConfig.dateInputFormat = this.loginUser.settings.date_format;
	}

	ngOnInit() {
		this.loadForms();
	}

	loadForms() {
		this.createAnnouncementForm = this.formBuilder.group({
			title: [null, [Validators.required, Validators.maxLength(100)]],
			start_date: [new Date(), Validators.required],
			end_date: [new Date(), Validators.required],
			status: ['1', Validators.required],
			all_client: [false],
			description: [''],
		});
	}

	get announcementControl() { return this.createAnnouncementForm.controls; }

	startDateChange(event: any) {
		this.createAnnouncementForm.patchValue({ end_date: event });
	}

	onSubmit() {
		this.isFormSubmitted = true;
		if (this.createAnnouncementForm.invalid) {
			return;
		}

		if (this.createAnnouncementForm.value.start_date instanceof Date) {
			this.createAnnouncementForm.value.start_date = this.datePipe.transform(this.createAnnouncementForm.value.start_date, "yyyy-MM-dd h:mm:ss a")
		}
		if (this.createAnnouncementForm.value.end_date instanceof Date) {
			this.createAnnouncementForm.value.end_date = this.datePipe.transform(this.createAnnouncementForm.value.end_date, "yyyy-MM-dd h:mm:ss a")
		}

		this.announcementService.create(this.createAnnouncementForm.value)
			.subscribe(
				data => {
					this.toastr.success(this.translate.instant('announcements.messages.create'), this.translate.instant('announcements.title'));
					this.router.navigate(['announcements']);
				});
	}

}
