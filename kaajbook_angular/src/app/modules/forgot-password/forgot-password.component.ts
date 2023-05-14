import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ToastrService } from 'ngx-toastr';
import { TranslateService } from '@ngx-translate/core';

import { AuthenticationService } from '../../core/services/authentication.service';
import { SettingService } from 'src/app/core/services/setting.service';

import { environment } from 'src/environments/environment';

@Component({
	selector: 'app-forgot-password',
	templateUrl: './forgot-password.component.html',
	styleUrls: ['./forgot-password.component.scss']
})

export class ForgotPasswordComponent implements OnInit {
	private apiUrl = environment.apiUrl;
	forgotPasswordForm: FormGroup;
	settings: any;
	backgroundImage: string;
	isFormSubmitted = false;
	isSettingsLoad = false;

	constructor(
		public translate: TranslateService,
		private router: Router,
		private formBuilder: FormBuilder,
		private toastr: ToastrService,
		private authenticationService: AuthenticationService,
		private settingService: SettingService
	) { }

	ngOnInit() {
		this.getSettings();
		this.loadForm();
	}

	loadForm() {
		this.forgotPasswordForm = this.formBuilder.group({
			email: ['', [Validators.required, Validators.email]]
		});
	}

	get userControl() { return this.forgotPasswordForm.controls; }

	getSettings() {
		this.settingService.getSettings()
			.subscribe(
				data => {
					this.settings = data;
					this.setBGImage();
					this.isSettingsLoad = true;
				});
	}

	setBGImage() {
		if(this.settings.login_background) {
			this.backgroundImage = this.apiUrl + '/uploads/login_bg/' + this.settings.login_background;
		} else {
			this.backgroundImage = 'assets/img/login-bg-2.png';
		}
	}

	onSubmit() {
		this.isFormSubmitted = true;
		if (this.forgotPasswordForm.invalid) {
			return;
		}

		this.authenticationService.forgotPassword(this.forgotPasswordForm.value)
			.subscribe((data) => {
				this.toastr.success(this.translate.instant('forgot_password.messages.success'), this.translate.instant('forgot_password.title'));
				this.router.navigate(['login']);
			});
	}

}
