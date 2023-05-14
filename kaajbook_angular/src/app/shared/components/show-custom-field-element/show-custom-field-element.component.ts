import { Component, OnInit, Input } from '@angular/core';
import {
	FormGroup,
	FormBuilder,
	FormArray,
	Validators,
	FormControl
} from '@angular/forms';

import { AuthenticationService } from '../../../core/services/authentication.service';

import { datepickerConfig } from '../../../core/helpers/admin.helper';

@Component({
	selector: 'app-show-custom-field-element',
	templateUrl: './show-custom-field-element.component.html',
	styleUrls: ['./show-custom-field-element.component.scss']
})
export class ShowCustomFieldElementComponent implements OnInit {
	@Input() customFields;
	@Input() formArray;
	@Input() controls: FormArray;
	@Input() formName : FormGroup;
	@Input() isFormSubmitted: boolean;
	loginUser: any;
	datepickerConfig = datepickerConfig;

	constructor(private authenticationService: AuthenticationService) { 
		this.authenticationService.loginUser.subscribe(x => this.loginUser = x);
		this.datepickerConfig.dateInputFormat = this.loginUser.settings.date_format;
	}

	ngOnInit() {}
}
