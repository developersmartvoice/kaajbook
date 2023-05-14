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

import { Meeting } from '../../../../../shared/models/meeting.model';

import { UserService } from '../../../../../core/services/user.service';
import { ClientService } from '../../../../../core/services/client.service';
import { MeetingService } from '../../../../../core/services/meeting.service';
import { ProjectService } from '../../../../../core/services/project.service';

import { editorConfig } from '../../../../../core/helpers/admin.helper';

import { environment } from '../../../../../../environments/environment';

import * as moment from 'moment';

@Component({
	selector: 'app-edit-meeting-modal',
	templateUrl: './edit-meeting-modal.component.html',
	styleUrls: ['./edit-meeting-modal.component.scss']
})

export class EditMeetingModalComponent implements OnInit {
	private apiUrl = environment.apiUrl;
	public event: EventEmitter<any> = new EventEmitter();
	public onClose: Subject<boolean>;
	editMeetingForm: FormGroup;
	meeting: Meeting;
	isFormSubmitted = false;
	isPageLoaded = false;
	users = [];
	clients = [];
	projects = [];
	teamMembers = [];
	editorConfig = editorConfig;

	constructor (
		public translate: TranslateService,
		public bsModalRef: BsModalRef,
		private formBuilder: FormBuilder,
		private toastr: ToastrService,
		private userService: UserService,
		private clientService: ClientService,
		private meetingService: MeetingService,
		private projectService: ProjectService
	) { }

	ngOnInit() {
		this.onClose = new Subject();
		this.getUsers();
		this.getClients();
		this.getProjects();
	}

	getUsers() {
		this.userService.getAll().subscribe(data => {
			this.users = data;
			this.loadForms();
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
		for(let iRow in this.meeting.members) {
			if (!this.meeting.members[iRow].is_client && this.meeting.members[iRow].member_id != this.meeting.organizer_id) {
				this.teamMembers.push(this.meeting.members[iRow].member_id);
			}
		}

		this.editMeetingForm = this.formBuilder.group({
			id: [this.meeting.id],
			title: [this.meeting.title, [Validators.required, Validators.maxLength(255)]],
			organizer_id: [this.meeting.organizer_id],
			description: [this.meeting.description],
			members: [this.teamMembers, Validators.required],
			client_id: [this.meeting.client_id],
			project_id: [this.meeting.project_id],
			start_date: [new Date(this.meeting.start_date), Validators.required],
			end_date: [new Date(this.meeting.end_date), Validators.required],
			location: [this.meeting.location],
			status: [this.meeting.status, Validators.required],
		});
		this.isPageLoaded = true;
	}

	get meetingControl() { return this.editMeetingForm.controls; }

	startDateChange(start_date) {
		this.editMeetingForm.patchValue({ end_date: new Date(start_date.value) });
	}

	projectChange(event: any) {
		if(event.client_id){
			this.editMeetingForm.patchValue({ client_id: event.client_id });
		} else{
			this.editMeetingForm.patchValue({ client_id: null });
		}
	}

	onSubmit() {
		this.isFormSubmitted = true;
		if (this.editMeetingForm.invalid) {
			return;
		}

		this.editMeetingForm.value.start_date = moment(this.editMeetingForm.value.start_date).format('YYYY-MM-DD HH:mm:ss');
		this.editMeetingForm.value.end_date = moment(this.editMeetingForm.value.end_date).format('YYYY-MM-DD HH:mm:ss');
		
		// --
		// Check dates
		if((this.editMeetingForm.value.start_date == this.editMeetingForm.value.end_date) || (this.editMeetingForm.value.start_date > this.editMeetingForm.value.end_date)) {
			this.toastr.error(this.translate.instant('meetings.create.error_messages.message6'), this.translate.instant('meetings.title'));
			return false;
		}

		this.meetingService.update(this.editMeetingForm.value)
			.subscribe(
				data => {
					this.toastr.success(this.translate.instant('meetings.messages.update'), this.translate.instant('meetings.title'));
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
