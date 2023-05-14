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

import { RoleService } from '../../../../../core/services/role.service';

@Component({
	selector: 'app-create-role-modal',
	templateUrl: './create-role-modal.component.html',
	styleUrls: ['./create-role-modal.component.scss']
})

export class CreateRoleModalComponent implements OnInit {
	public event: EventEmitter<any> = new EventEmitter();
	public onClose: Subject<boolean>;
	createRoleForm: FormGroup;
	isSubmitted = false;

	constructor (
		public translate: TranslateService,
		public bsCreateRoleModalRef: BsModalRef,
		private formBuilder: FormBuilder,
		private roleService: RoleService,
		private toastr: ToastrService
	) { }

	ngOnInit() {
		this.onClose = new Subject();
		this.loadForm();
	}

	loadForm() {
		this.createRoleForm = this.formBuilder.group({
			name: [null, [Validators.required, Validators.maxLength(50)]],
			description: ['']
		});
	}

	get roleControl() { return this.createRoleForm.controls; }

	onSubmit() {
		this.isSubmitted = true;
		if (this.createRoleForm.invalid) {
			return;
		}

		this.roleService.create(this.createRoleForm.value)
			.subscribe(
				data => {
					this.toastr.success(this.translate.instant('roles.messages.create'), this.translate.instant('roles.title'));
					this.event.emit({ data: true });
					this.onCancel();
				}, error => {
					this.onCancel();
				});
	}

	onCancel() {
		this.onClose.next(false);
		this.bsCreateRoleModalRef.hide();
	}
}
