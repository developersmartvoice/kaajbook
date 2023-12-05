import { Component, EventEmitter, OnInit } from '@angular/core';
import { FormGroup, FormBuilder, Validators, FormArray } from '@angular/forms';
import { Router } from '@angular/router';
import { TranslateService } from '@ngx-translate/core';
import { BsModalRef } from 'ngx-bootstrap';
import { ToastrService } from 'ngx-toastr';
import { Subject } from 'rxjs';
import { CustomTemplateService } from 'src/app/core/services/custom-template.service';
import { HelperService } from 'src/app/core/services/helper.service';
import { ImportProjectService } from 'src/app/core/services/import-project.service';


@Component({
  selector: 'app-project-template-edit',
  templateUrl: './project-template-edit.component.html',
  styleUrls: ['./project-template-edit.component.scss']
})
export class ProjectTemplateEditComponent implements OnInit {

  public event: EventEmitter<any> = new EventEmitter();
	public onClose: Subject<boolean>;
	formTables: any;
	selectboxOptions: any;
	createCustomTemplateForm: FormGroup;
	isPageLoaded = false;

  constructor(
    public translate: TranslateService,
	private router: Router,
		public bsModalRef: BsModalRef,
		private formBuilder: FormBuilder,
		private toastr: ToastrService,
		private customTemplateService: CustomTemplateService,
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
			template_name: this.createCustomTemplateForm.get('templateName').value,
			tasks: JSON.stringify(
			  this.createCustomTemplateForm.get('taskInTemplate').value.split('.').map(task => task.trim()).filter(task => task !== '')
			)
		  };	  
		
		  console.log('Submitted data:', formData);

      const templateId = 10;
		
    // update template
    this.customTemplateService.updateTemplate(templateId, formData)
    .subscribe(
      data => {					
        this.toastr.success(this.translate.instant('settings.custom_fields.messages.template_update'), this.translate.instant('settings.custom_fields.title3'));
        this.event.emit({ data: true });
        this.onCancel();
        // this.router.navigate(["projects/template"]);
      },
      error => {
        console.log("template update error : "+error);
      });
	}

	onCancel() {
		this.onClose.next(false);
		this.bsModalRef.hide();
	}

  

}
