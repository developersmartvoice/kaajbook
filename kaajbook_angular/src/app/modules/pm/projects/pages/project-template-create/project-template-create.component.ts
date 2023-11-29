import { Component, EventEmitter, OnInit } from '@angular/core';
import { FormGroup, FormBuilder, Validators, FormArray } from '@angular/forms';
import { TranslateService } from '@ngx-translate/core';
import { BsModalRef } from 'ngx-bootstrap';
import { ToastrService } from 'ngx-toastr';
import { Subject } from 'rxjs';
import { CustomFieldsService } from 'src/app/core/services/custom-fields.service';
import { HelperService } from 'src/app/core/services/helper.service';
import { ImportProjectService } from 'src/app/core/services/import-project.service';

@Component({
  selector: 'app-project-template-create',
  templateUrl: './project-template-create.component.html',
  styleUrls: ['./project-template-create.component.scss']
})
export class ProjectTemplateCreateComponent implements OnInit {
  public event: EventEmitter<any> = new EventEmitter();
	public onClose: Subject<boolean>;
	formTables: any;
	selectboxOptions: any;
	createCustomTemplateForm: FormGroup;
	isPageLoaded = false;

  constructor(
    public translate: TranslateService,
		public bsModalRef: BsModalRef,
		private formBuilder: FormBuilder,
		private toastr: ToastrService,
		private customFieldsService: CustomFieldsService,
		private helperService: HelperService
    ) { }

  ngOnInit() {
    this.onClose = new Subject();

		this.loadForms();
  }

  loadForms() {
		this.createCustomTemplateForm = this.formBuilder.group({
			templateName: ['', Validators.required],
   	 		taskInTemplate: ['', Validators.required]
		});
	}


	onSubmit() {

		  // Mark all controls as touched
		  this.createCustomTemplateForm.markAllAsTouched();

		if (this.createCustomTemplateForm.invalid) {
			console.log('Form is invalid');
			return;
		  }

		  const formData = {
			templateName: this.createCustomTemplateForm.get('templateName').value,
			tasks: this.createCustomTemplateForm.get('taskInTemplate').value.split('.').map(task => task.trim()).filter(task => task !== '')
		  };
		
		//   console.log('Submitted data:', formData);
		


		// this.customFieldsService.create(this.createCustomTemplateForm.value)
		// 	.subscribe(
		// 		data => {					
		// 			this.toastr.success(this.translate.instant('settings.custom_fields.messages.create'), this.translate.instant('settings.custom_fields.title'));
		// 			this.event.emit({ data: true });
		// 			this.onCancel();
		// 		});
	}

	onCancel() {
		this.onClose.next(false);
		this.bsModalRef.hide();
	}

  

} 
