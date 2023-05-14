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

import { User } from '../../../../../shared/models/user.model';

import { AuthenticationService } from '../../../../../core/services/authentication.service';
import { UserService } from '../../../../../core/services/user.service';
import { ClientService } from '../../../../../core/services/client.service';
import { MeetingService } from '../../../../../core/services/meeting.service';
import { ProjectService } from '../../../../../core/services/project.service';

import { editorConfig } from '../../../../../core/helpers/admin.helper';

import { environment } from '../../../../../../environments/environment';

import * as moment from 'moment';

@Component({
	selector: 'app-create-meeting-modal',
	templateUrl: './create-meeting-modal.component.html',
	styleUrls: ['./create-meeting-modal.component.scss']
})

export class CreateMeetingModalComponent implements OnInit {
	private apiUrl = environment.apiUrl;
	public event: EventEmitter<any> = new EventEmitter();
	public onClose: Subject<boolean>;
	createMeetingForm: FormGroup;
	loginUser: User;
	isFormSubmitted = false;
	isPageLoaded = false;
	users = [];
	clients = [];
	projects = [];
	editorConfig = editorConfig;

	constructor (
		public translate: TranslateService,
		public bsModalRef: BsModalRef,
		private formBuilder: FormBuilder,
		private toastr: ToastrService,
		private userService: UserService,
		private clientService: ClientService,
		private meetingService: MeetingService,
		private projectService: ProjectService,
		private authenticationService: AuthenticationService
	) {
		this.authenticationService.loginUser.subscribe(x => this.loginUser = x);
	}

	ngOnInit() {
		this.onClose = new Subject();
		this.getUsers();
		this.getClients();
		this.getProjects();
		this.loadForms();
	}

	getUsers() {
		this.userService.getAll().subscribe(data => {
			this.users = data;
			this.isPageLoaded = true;
		});
	}

	getClients() {
		this.clientService.getAll().subscribe(data => {
			this.clients = data;
		});
	}

	getProjects() {
		this.projectService.getMyProjects().subscribe(data => {
			this.projects = data;
		});
	}

	loadForms() {
		this.createMeetingForm = this.formBuilder.group({
			organizer_id: [null],
			title: [null, [Validators.required, Validators.maxLength(255)]],
			description: [''],
			members: [null, Validators.required],
			project_id: [null],
			client_id: [null],
			start_date: [null, Validators.required],
			end_date: [null, Validators.required],
			location: ['']
		});
	}

	get meetingControl() { return this.createMeetingForm.controls; }

	startDateChange(start_date) {
		this.createMeetingForm.patchValue({ end_date: new Date(start_date.value) });
	}

	projectChange(event: any) {
		if(event.client_id){
			this.createMeetingForm.patchValue({ client_id: event.client_id });
		} else{
			this.createMeetingForm.patchValue({ client_id: null });
		}
	}

	onSubmit() {
		this.isFormSubmitted = true;
		if (this.createMeetingForm.invalid) {
			return;
		}

		this.createMeetingForm.value.start_date = moment(this.createMeetingForm.value.start_date).format('YYYY-MM-DD HH:mm:ss');
		this.createMeetingForm.value.end_date = moment(this.createMeetingForm.value.end_date).format('YYYY-MM-DD HH:mm:ss');
		
		// --
		// Check dates
		if((this.createMeetingForm.value.start_date == this.createMeetingForm.value.end_date) || (this.createMeetingForm.value.start_date > this.createMeetingForm.value.end_date)) {
			this.toastr.error(this.translate.instant('meetings.create.error_messages.message6'), this.translate.instant('meetings.title'));
			return false;
		}

		this.meetingService.create(this.createMeetingForm.value)
			.subscribe(
				data => {
					this.toastr.success(this.translate.instant('meetings.messages.create'), this.translate.instant('meetings.title'));
					this.event.emit({ data: true });
					this.onCancel();
				}, error => {
					this.event.emit({ data: true });
					this.onCancel();
				});
	}

	onCancel() {
		this.onClose.next(false);
		this.bsModalRef.hide();
	}

}
