import { Component, EventEmitter, OnInit, Input, SimpleChanges } from '@angular/core';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
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

  @Input() customTemplate: any; // Input property to receive data from parent component

  public event: EventEmitter<any> = new EventEmitter();
  public onClose: Subject<boolean>;
  updateCustomTemplateForm: FormGroup;
  receivedTemplateData = false;

  constructor(
    public translate: TranslateService,
    private router: Router,
    public bsModalRef: BsModalRef,
    private formBuilder: FormBuilder,
    private toastr: ToastrService,
    private customTemplateService: CustomTemplateService,
    private helperService: HelperService
  ) {}

  ngOnInit() {
    this.onClose = new Subject();

    // Initialize the form
    this.updateCustomTemplateForm = this.formBuilder.group({
      templateName: ['', Validators.required],
      taskInTemplate: ['', Validators.required]
    });
  }
 
  ngAfterViewChecked() {
    // Update the form values based on the received data
    if (this.customTemplate && !this.receivedTemplateData) {

      this.receivedTemplateData = true;
      const regex = /\[|\]|"/g;
      const tasksArray1 = this.customTemplate.tasks.replace(regex, '');
      const tasksArray = tasksArray1.split(',');

      this.updateCustomTemplateForm.patchValue({
        templateName: this.customTemplate.template_name,
        taskInTemplate: tasksArray.join('.\n')
      });
      
    }
  }
  
  

  onSubmit() {
   // Mark all controls as touched
		  this.updateCustomTemplateForm.markAllAsTouched();

		if (this.updateCustomTemplateForm.invalid) {
			console.log('Form is invalid');
			return;
		  }

		  const formData = {
			template_name: this.updateCustomTemplateForm.get('templateName').value,
			tasks: JSON.stringify(
			  this.updateCustomTemplateForm.get('taskInTemplate').value.split('.').map(task => task.trim()).filter(task => task !== '')
			)
		  };	  
		
		  // console.log('Submitted data:', formData);
		


		// update template
    this.customTemplateService.updateTemplate(this.customTemplate.id, formData)
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
