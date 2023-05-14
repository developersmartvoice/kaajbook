import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { first } from 'rxjs/operators';
import { ToastrService } from 'ngx-toastr';
import { TranslateService } from '@ngx-translate/core';

import { AuthenticationService } from '../../core/services/authentication.service';
import { SettingService } from 'src/app/core/services/setting.service';

import { environment } from 'src/environments/environment';

@Component({
	selector: 'app-login',
	templateUrl: './login.component.html',
	styleUrls: ['./login.component.scss']
})

export class LoginComponent implements OnInit {
	private apiUrl = environment.apiUrl;
	loginForm: FormGroup;
	settings: any;
	isSettingsLoad: boolean;
	backgroundImage: string;
	isFormSubmitted = false;

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
		this.loginForm = this.formBuilder.group({
			email: ['', [Validators.required]],
			password: ['', [Validators.required, Validators.minLength(3), Validators.maxLength(30)]]
		});
	}

	getSettings() {
		this.settingService.getSettings()
			.subscribe(
				data => {
					this.settings = data;
					this.setBGImage();
					this.useDefaultLanguage();
					this.isSettingsLoad = true;
				});
	}

	get userControl() { return this.loginForm.controls; }

	setBGImage() {
		if(this.settings && this.settings.login_background) {
			this.backgroundImage = this.apiUrl + '/uploads/login_bg/' + this.settings.login_background;
		} else {
			this.backgroundImage = 'assets/img/login-bg-2.png';
		}
	}

	useDefaultLanguage(){
		if (this.settings.default_language) {
			this.translate.use(this.settings.default_language);
		}
	}

	onSubmit() {
		this.isFormSubmitted = true;
		if (this.loginForm.invalid) {
			return;
		}

		this.authenticationService.login(this.userControl.email.value, this.userControl.password.value)
			.pipe(first())
			.subscribe(
				data => {
					this.router.navigate(['dashboard']);
				});
	}

	instantLogin(email,password){
		this.loginForm.patchValue({ email: email });
		this.loginForm.patchValue({ password: password });
	}

}