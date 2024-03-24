import { Component, OnInit } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';  
import {
	PerfectScrollbarConfigInterface,
	PerfectScrollbarComponent,
	PerfectScrollbarDirective
} from 'ngx-perfect-scrollbar';
import { ToastrService } from 'ngx-toastr';
import { timer } from 'rxjs';
import { take } from 'rxjs/operators';
import { Router } from '@angular/router';

import { AuthenticationService } from '../../../core/services/authentication.service';
import { NotificationService } from '../../../core/services/notification.service';
// import { MailService } from '../../../core/services/mail.service';
import { HelperService } from '../../../core/services/helper.service';
import { TranslationService } from '../../../core/services/translation.service';
import { UserService } from '../../../core/services/user.service';

import { smoothlyMenu } from '../../../core/helpers/app.helper';
import { environment } from './../../../../environments/environment';
import Swal from 'sweetalert2';

declare var jQuery: any;

@Component({
	selector: 'app-header',
	templateUrl: './header.component.html',
	styleUrls: ['./header.component.scss']
})

export class HeaderComponent implements OnInit {
	private apiUrl = environment.apiUrl;
	public scrollConfig: PerfectScrollbarConfigInterface = {};
	loginUser: any;
	notifications: any;
	// mails: any;
	latestVersion: any;
	translations: any;
	selectedLanguage: string;
	interval: any;

	constructor(
		public translate: TranslateService,
		private toastr: ToastrService,
		private router: Router,
		private authenticationService: AuthenticationService,
		private helperService: HelperService,
		private notificationService: NotificationService,
		// private mailService: MailService,
		private translationService: TranslationService,
		private userService: UserService
	) {
		this.authenticationService.loginUser.subscribe(x => this.loginUser = x);

		if(this.loginUser) {
			this.selectedLanguage = this.loginUser.language;
		}
	}

	ngOnInit() {
		this.getNotifications();
		// this.getUnReadMails(8);
		this.getAllActiveTranslations();

		// --
		// Interval
		this.interval = timer(60000, 30000).pipe().subscribe(x => {
			this.getNotifications();
		});
	}

	openThemeSettings() {
		$('.theme-settings').toggleClass('open');
	}

	getNotifications() {
		this.notificationService.getNotifications(10000).subscribe(data => {
			this.notifications = data;
		}, error => {
			this.interval.unsubscribe();
		});
	}

	// getUnReadMails(length) {
	// 	this.mailService.getUnReadMails(length).subscribe(data => {
	// 		this.mails = data;
	// 	}, error => {
	// 		this.interval.unsubscribe();
	// 	});
	// }

	// executeCronJob(){
	// 	this.helperService.executeCronJob().subscribe(data => {})
	// }

	logout() {
		this.interval.unsubscribe();
		this.authenticationService.logout();
	}

	getAllActiveTranslations() {
		this.translationService.getAllActiveTranslations().subscribe(data => {
			this.translations = data;
		});
	}

	changeLanguage(language: string) {
		this.translate.use(language);
		this.changeLocale(language);
	}

	changeLocale(locale: string) {
		this.selectedLanguage = locale;
		this.userService.changeLocale(locale).subscribe(data => {});
	}

	removeNotification(notification) {
		this.notificationService.delete(notification.id).subscribe(data => {
			this.getNotifications();
			if(notification.route == 'mailbox'){
				this.router.navigate(['mailbox']);
			}else if(notification.route_related_id){
				this.router.navigate([notification.route, 'detail', notification.route_related_id]);
			}
		});
	}

	deleteAllNotifications() {

		Swal.fire({
			title: this.translate.instant('common.swal.title'),
			text: this.translate.instant('common.swal.text'),
			type: 'warning',
			showCancelButton: true,
			confirmButtonText: this.translate.instant('common.swal.confirmButtonText'),
			cancelButtonText: this.translate.instant('common.swal.cancelButtonText')
		}).then((result) => {
			if (result.value) {
				this.notificationService.deleteAll().subscribe(data => {
					this.toastr.success(this.translate.instant('header.delete'), this.translate.instant('header.notifications'));
					this.getNotifications();
				});
			}
		});

	}

	clearNotification(event: MouseEvent, notification) {
		event.stopPropagation();
		this.notificationService.delete(notification.id).subscribe(data => {
			this.getNotifications();
		});
	}

}
