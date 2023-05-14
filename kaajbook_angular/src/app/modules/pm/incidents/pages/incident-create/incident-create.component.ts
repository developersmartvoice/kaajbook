import { Component, ElementRef, OnInit, ViewChild } from '@angular/core';
import {
	FormGroup,
	FormBuilder,
	FormControl,
	FormArray,
	Validators
} from '@angular/forms';
import { DatePipe } from '@angular/common';
import { Router } from '@angular/router';
import { NgOption } from '@ng-select/ng-select';
import { TranslateService } from '@ngx-translate/core';
import { ToastrService } from 'ngx-toastr';

import { CustomField } from '../../../../../shared/models/custom-field.model';

import { TeamService } from '../../../../../core/services/team.service';
import { ProjectService } from '../../../../../core/services/project.service';
import { IncidentService } from '../../../../../core/services/incident.service';
import { CustomFieldsService } from '../../../../../core/services/custom-fields.service';
import { AuthenticationService } from '../../../../../core/services/authentication.service';

import { editorConfig, datepickerConfig } from '../../../../../core/helpers/admin.helper';

@Component({
	selector: 'app-incident-create',
	templateUrl: './incident-create.component.html',
	styleUrls: ['./incident-create.component.scss'],
	preserveWhitespaces: true
})

export class IncidentCreateComponent implements OnInit {
	createIncidentForm: FormGroup;
	loginUser: any;
	customFields: any = { length: 0 }
	projects: any;
	projectVersions: any;
	projectStartDate: any;
	isFormSubmitted = false;
	isPageLoaded = false;
	teams = [];
	teamsMembers = [];
	datepickerConfig = datepickerConfig;
	editorConfig = editorConfig;

	constructor(
		public translate: TranslateService,
		public datepipe: DatePipe,
		private router: Router,
		private formBuilder: FormBuilder,
		private toastr: ToastrService,
		private teamService: TeamService,
		private projectService: ProjectService,
		private incidentService: IncidentService,
		private customFieldsService: CustomFieldsService,
		private authenticationService: AuthenticationService
	) { 
		this.authenticationService.loginUser.subscribe(x => this.loginUser = x);
		this.datepickerConfig.dateInputFormat = this.loginUser.settings.date_format;
	}

	ngOnInit() {
		this.getTeams();
		this.getProjects();
		this.getCustomFieldByForm();
		this.loadForms();
	}

	loadForms() {
		this.createIncidentForm = this.formBuilder.group({
			incident_name: ['', [Validators.required, Validators.maxLength(255)]],
			project_id: [null],
			project_version: [null],
			start_date: [null],
			end_date: [null],
			priority: [2, Validators.required],
			estimated_hours: ['', Validators.pattern(/^[0-9]+\:[0-5][0-9]$/)],
			incident_type: [1, Validators.required],
			assigned_group_id: [null],
			assign_to: [null],
			status: [1, Validators.required],
			description: [''],
			custom_field: this.formBuilder.array([]),
			custom_fields: [null]
		});

		this.isPageLoaded = true;
	}

	get incidentControl() { return this.createIncidentForm.controls; }

	getCustomFieldByForm() {
		this.customFieldsService.getCustomFieldByForm(4)
			.subscribe(data => {
				this.customFields = data;
				if (this.customFields.length > 0) {
					this.addDynamicField(this.customFields);
				}
			})
	}

	getProjects() {
		this.projectService.getMyProjects().subscribe(data => {
			this.projects = data;
		});
	}

	getTeams() {
		this.teamService.getAll().subscribe(data => {
			this.teams = data;
		});
	}

	projectChange(event: any) {
		this.createIncidentForm.patchValue({ project_version: null });
		this.createIncidentForm.patchValue({ start_date: null });
		this.createIncidentForm.patchValue({ end_date: null });
		if (this.createIncidentForm.value.project_id == undefined || this.createIncidentForm.value.project_id == null || this.createIncidentForm.value.project_id == '') {
			this.projectVersions = null;
			return;
		}
		this.projectVersions = event.project_version.split(",").reverse();
		// --
		// Dates
		this.projectStartDate = new Date(event.start_date);
	}

	startDateChange(event: any) {
		this.createIncidentForm.patchValue({ end_date: event });
	}

	addDynamicField(fieldList) {
		fieldList.forEach(element => {
			let control = <FormArray>this.createIncidentForm.controls.custom_field;
			let validators = null
			if (element.is_required == 1) {
				validators = [Validators.required];
			}

			control.push(
				this.formBuilder.group({
					[element.field_column]: [null, validators]
				})
			)
		});
	}

	assignGroupChange(event: any) {
		if (this.createIncidentForm.value.assigned_group_id == undefined || this.createIncidentForm.value.assigned_group_id == null || this.createIncidentForm.value.assigned_group_id == '') {
			this.createIncidentForm.patchValue({ assign_to: null });
			this.teamsMembers = null;
			return;
		}
		this.teamsMembers = event.members;
	}

	setCustomFields() {
		let arr = this.createIncidentForm.value.custom_field,
			obj = {},
			iRow = 0,
			that = this;
			arr.forEach(function (item) {
				let key = Object.keys(item)[0];
				obj[key] = item[key];
				if (that.customFields[iRow++].field_type == "date") {
					obj[key] = that.datepipe.transform(item[key], 'yyyy-MM-dd')
				}
			})
			this.createIncidentForm.patchValue({ custom_fields: obj });
	}

	onSubmit() {
		this.isFormSubmitted = true;
		if (this.createIncidentForm.invalid) {
			return;
		}

		if (this.customFields.length > 0) {
			this.setCustomFields();
		}

		this.incidentService.create(this.createIncidentForm.value).subscribe(data => {
			this.toastr.success(this.translate.instant('incidents.messages.create'), this.translate.instant('incidents.title'));
			this.router.navigate(['incidents']);
		});
	}

}
