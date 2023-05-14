import { Component, OnInit, Input } from '@angular/core';
import { FormGroup, FormBuilder, FormControl, Validators } from '@angular/forms';
import { ToastrService } from 'ngx-toastr';
import { TranslateService } from '@ngx-translate/core';

import { SettingService } from '../../../../core/services/setting.service';

import { environment } from '../../../../../environments/environment';

@Component({
	selector: 'app-slack-settings',
	templateUrl: './slack-settings.component.html',
	styleUrls: ['./slack-settings.component.scss']
})

export class SlackSettingsComponent implements OnInit {
	public apiUrl = environment.apiUrl;
	@Input() loginUser: any;
	slackSettings: any;
	slackSettingsForm: FormGroup;
	isFormSubmitted = false;
	isFormLoad = false;

	constructor (
		public translate: TranslateService,
		private formBuilder: FormBuilder,
		private toastr: ToastrService,
		private settingService: SettingService
	) { }

	ngOnInit() {
		this.getSlackSetting();
	}

	getSlackSetting() {
		this.settingService.getSlackSettings()
			.subscribe(data => {
				this.slackSettings = data;
				this.loadForm();
			});
	}

	loadForm() {
		this.slackSettingsForm = this.formBuilder.group({
			slack_client_id: [this.slackSettings.slack_client_id, Validators.required],
			slack_client_secret: [this.slackSettings.slack_client_secret, Validators.required],
			slack_redirect_URL: [this.slackSettings.slack_redirect_URL, Validators.required],
			slack_status: [this.slackSettings.slack_status]
		});

		this.isFormLoad = true;
	}

	get slackSetting() { return this.slackSettingsForm.controls; }

	onSubmit() {
		this.isFormSubmitted = true;
		if (this.slackSettingsForm.invalid) {
			return;
		}

		this.settingService.createSlackSettings(this.slackSettingsForm.value).subscribe(data => {
			this.toastr.success(this.translate.instant('settings.messages.update'), this.translate.instant('settings.title'));
		});
	}

}
