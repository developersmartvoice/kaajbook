import { Component, OnInit, Input } from '@angular/core';
import {
	FormGroup,
	FormBuilder,
	FormControl,
	Validators
} from '@angular/forms';
import { DatePipe } from '@angular/common';
import { ToastrService } from 'ngx-toastr';
import { NgOption } from '@ng-select/ng-select';
import { TranslateService } from '@ngx-translate/core';

import { SettingService } from '../../../../core/services/setting.service';
import { TranslationService } from '../../../../core/services/translation.service';
import { HelperService } from '../../../../core/services/helper.service';

@Component({
	selector: 'app-system-settings',
	templateUrl: './system-settings.component.html',
	styleUrls: ['./system-settings.component.scss']
})

export class SystemSettingsComponent implements OnInit {
	@Input() settings: any;
	@Input() loginUser: any;
	settingsForm: FormGroup;
	languages: any;
	currencies: any;
	timezones : any[] = [];
	dateFormates: NgOption[] = [];
	timeFormates = [
		{value: 'hh:mm A', label: this.translate.instant('common.12_hours')},
		{value: 'HH:mm', label: this.translate.instant('common.24_hours')}
	];
	isSubmitted = false;
	isFormLoaded = false;

	constructor (
		public translate: TranslateService,
		public datepipe: DatePipe,
		private formBuilder: FormBuilder,
		private toastr: ToastrService,
		private settingService: SettingService,
		private helperService: HelperService,
		private translationService: TranslationService
	) { }

	ngOnInit() {
		this.getTimezones();
		this.getCurrencies();
		this.getLanguages();

		this.dateFormates = [
			{ value: 'DD-MM-YYYY', label: this.datepipe.transform(new Date(), 'dd-MM-yyyy') },
			{ value: 'MM-DD-YYYY', label: this.datepipe.transform(new Date(), 'MM-dd-yyyy') },
			{ value: 'YYYY-MM-DD', label: this.datepipe.transform(new Date(), 'yyyy-MM-dd') },
			{ value: 'D-M-YY', label: this.datepipe.transform(new Date(), 'd-M-yy') },
			{ value: 'DD.MM.YYYY', label: this.datepipe.transform(new Date(), 'dd.MM.yyyy') },
			{ value: 'MM.DD.YYYY', label: this.datepipe.transform(new Date(), 'MM.dd.yyyy') },
			{ value: 'YYYY.MM.DD', label: this.datepipe.transform(new Date(), 'yyyy.MM.dd') },
			{ value: 'D.M.YY', label: this.datepipe.transform(new Date(), 'd.M.yy') }
		];

		this.settingsForm = this.formBuilder.group({
			form_for: ['system_setting'],
			timezone: [this.settings.timezone],
			default_language: [this.settings.default_language, [Validators.required]],
			tables_pagination_limit: [this.settings.tables_pagination_limit, [Validators.required]],
			date_format: [this.settings.date_format, [Validators.required]],
			time_format: [this.settings.time_format, [Validators.required]],
			currency_id: [this.settings.currency_id, [Validators.required]],
			allowed_for_registration: [this.settings.allowed_for_registration]
		});
	}

	get systemSetting() { return this.settingsForm.controls; }

	getTimezones() {
		this.helperService.getTimezones()
			.subscribe(
				data => {
					for(let iRow in data) {
						this.timezones.push({
							key: iRow,
							value: data[iRow]
						});
					}
					this.isFormLoaded = true;
				});
	}

	getCurrencies() {
		this.helperService.getCurrencies().subscribe(data => {
			this.currencies = data;
		});
	}

	getLanguages() {
		this.translationService.getAllActiveTranslations().subscribe(data => {
			this.languages = data;
		});
	}

	onSubmit() {
		this.isSubmitted = true;
		if (this.settingsForm.invalid) {
			return;
		}

		// --
		// this.toastr.error(this.translate.instant('common.not_allowed'));
		// return;

		this.settingsForm.value.date_time_format =  this.settingsForm.value.date_format + ' ' + this.settingsForm.value.time_format;

		this.settingService.create(this.settingsForm.value).subscribe(data => {
			this.toastr.success(this.translate.instant('settings.messages.update'), this.translate.instant('settings.title'));
		});
	}

}
