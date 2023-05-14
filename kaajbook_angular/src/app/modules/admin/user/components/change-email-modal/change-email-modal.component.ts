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

import { UserService } from '../../../../../core/services/user.service';

@Component({
	selector: 'app-change-email-modal',
	templateUrl: './change-email-modal.component.html',
	styleUrls: ['./change-email-modal.component.scss']
})

export class ChangeEmailModalComponent implements OnInit {
	public event: EventEmitter<any> = new EventEmitter();
	public onClose: Subject<boolean>;
	changeUserEmailForm: FormGroup;
	user: any;
	isFormSubmitted = false;

	constructor (
		public translate: TranslateService,
		public bsModalRef: BsModalRef,
		private formBuilder: FormBuilder,
		private toastr: ToastrService,
		private userService: UserService
	) { }

	ngOnInit() {
		this.onClose = new Subject();
		this.loadForms();
	}

	loadForms() {
		this.changeUserEmailForm = this.formBuilder.group({
			id: this.user.id,
			email: [null, [Validators.required, Validators.email]],
			password: [null, Validators.required]
		});
	}

	get userControl() { return this.changeUserEmailForm.controls; }

	onSubmit() {
		this.isFormSubmitted = true;
		if(this.changeUserEmailForm.invalid) {
			return;
		}

		// --
		// this.toastr.error(this.translate.instant('common.not_allowed'));
		// this.onCancel();
		// return;

		this.userService.changeEmail(this.changeUserEmailForm.value)
			.subscribe(
				data => {
					this.toastr.success(this.translate.instant('users.messages.email_change'), this.translate.instant('users.title'));
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
