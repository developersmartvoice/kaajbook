import { Component, EventEmitter, OnInit } from '@angular/core';
import { FormGroup, FormBuilder, Validators, FormArray } from '@angular/forms';
import { Router } from '@angular/router';
import { TranslateService } from '@ngx-translate/core';
import { FileUploader } from 'ng2-file-upload';
import { BsModalRef } from 'ngx-bootstrap';
import { ToastrService } from 'ngx-toastr';
import { Subject } from 'rxjs';
import { AuthenticationService } from 'src/app/core/services/authentication.service';
import { CustomTemplateService } from 'src/app/core/services/custom-template.service';
import { FileBrowserService } from 'src/app/core/services/file-browser.service';
import { HelperService } from 'src/app/core/services/helper.service';
import { ImportProjectService } from 'src/app/core/services/import-project.service';
import { environment } from 'src/environments/environment';

@Component({
  selector: 'app-project-template-create',
  templateUrl: './project-template-create.component.html',
  styleUrls: ['./project-template-create.component.scss']
})
export class ProjectTemplateCreateComponent implements OnInit {
  public event: EventEmitter<any> = new EventEmitter();
  public onClose: Subject<boolean>;
  private apiUrl = environment.apiUrl;
  formTables: any;
  selectboxOptions: any;
  createCustomTemplateForm: FormGroup;
  isPageLoaded = false;
  loginToken: any;
  attachmentsArr = [];
  public tasks: any[] = [];
  hasBaseDropZoneOver: boolean;
  taskName: string;
  current_folder =  "any";
  templateName: any;

  constructor(
    public translate: TranslateService,
    private router: Router,
    public bsModalRef: BsModalRef,
    private formBuilder: FormBuilder,
    private toastr: ToastrService,
    private customTemplateService: CustomTemplateService,
    private helperService: HelperService,
    private authenticationService: AuthenticationService,
    private fbService: FileBrowserService,
    public bsCreateFileModalRef: BsModalRef,
  ) {
    this.loginToken = this.authenticationService.currentTokenValue;
  }

  ngOnInit() {
    this.addTask(); // Initially add one task
    this.hasBaseDropZoneOver = false;
    this.onClose = new Subject();
    // Update additionalParameter when taskName changes
    this.tasks.forEach(task => {
      task.uploader.onAfterAddingFile = (fileItem) => {
        fileItem.withCredentials = false;
        task.uploader.options.additionalParameter['taskName'] = task.taskName;
      };
    });
  }

  addTask() {
    const uploader = new FileUploader({
      url: this.apiUrl + '/api/project-templates',
      authToken: this.loginToken.token_type + ' ' + this.loginToken.token,
      additionalParameter: { folder: this.current_folder, taskName: '' },
      method: 'post',
      removeAfterUpload: false,
      autoUpload: false, // Set autoUpload to false
      isHTML5: true,
    });

    uploader.onBeforeUploadItem = (item) => {
      item.withCredentials = false;
    };

    const newTask = { taskName: '', uploader: uploader };
    this.tasks.push(newTask);
  }

  removeTask(index: number) {
    this.tasks.splice(index, 1);
    if(this.tasks.length === 0){
      this.toastr.error("At least one task is required.", this.translate.instant('projects.create.fields.project_template'));
    }
  }

  public fileOverBase(e: any): void {
    this.hasBaseDropZoneOver = e;
  }

  public uploaderRemove(id) {
    const index = this.attachmentsArr.indexOf(id);
    if (index > -1) {
      const dataObj = {
        id: id,
      };
      this.fbService.removeAttachments(dataObj).subscribe(data => {
        this.toastr.success(this.translate.instant('file_browser.messages.delete_file'), this.translate.instant('file_browser.title'));
      });
      this.attachmentsArr.splice(index, 1);
    }
  }

  onCancel() {
    this.onClose.next(false);
    this.bsCreateFileModalRef.hide();
  }

  // Update task's fileName property when a file is selected
  onFileSelected(event: any, taskIndex: number) {
    const fileName = event.target.files[0].name;
    if(event.target.files[0].type.match('image*')){
      if(event.target.files[0].size > 512000){
        this.toastr.error("File size is too large. Required < 500 kB", this.translate.instant('projects.create.fields.project_template'));
        this.tasks[taskIndex].uploader.clearQueue();
        this.tasks[taskIndex].fileName = ''; // Clear the fileName property
      }
      else{
      this.tasks[taskIndex].fileName = fileName.substring(0, 20); // Truncate filename to 20 characters
    }
  }
  }

  onSubmit() {

    if(this.templateName == null || this.templateName == ""){
       this.toastr.error("Template Name is required.", this.translate.instant('projects.create.fields.project_template'));
       return;
    }
    else
    {
        // Trigger file uploads for all tasks with delay
        const uploadTasks = async () => {
            for (let i = 0; i < this.tasks.length; i++) {
                const task = this.tasks[i];
                // Add an empty file to the queue if no file is selected for the task
                if (task.uploader.queue.length === 0 && task.taskName) {
                    task.uploader.addToQueue([new File([], "-1")]);
                }
                // Set task name as additional parameter for each file uploader
                task.uploader.setOptions({ additionalParameter: { taskName: task.taskName, templateName: this.templateName } });

                let uploadCompleted = false; // Flag to track upload completion

                // Upload the file
                task.uploader.uploadAll();
                
                task.uploader.onCompleteItem = (item: any, response: any, status: any, headers: any) => {        
                  // Handle response from server and set uploadCompleted flag
                  uploadCompleted = item.isSuccess;

                  if (uploadCompleted) {  
                     this.toastr.success(this.translate.instant(`${task.taskName}`),  this.translate.instant(`Custome Template Task ${(i+1)}/${this.tasks.length}`),{
                      timeOut: 1500
                     });
                     this.bsCreateFileModalRef.hide(); // Only hide the modal on success
                 }
                 else{
                   this.toastr.error(JSON.parse(response).error, this.translate.instant('projects.create.fields.project_template'));
                   this.bsCreateFileModalRef.hide(); // hide the modal on error
                 }
                 const isLastTask = i === this.tasks.length - 1;
                 if(isLastTask){
                   this.toastr.success(this.translate.instant('projects.create.fields.create_project_template_success'), this.translate.instant('Done ✅'),{
                      timeOut: 5000
                   });
                  // Emit event to notify the parent component
                  this.event.emit(true);
                 }
                };

              // Wait until upload completes before proceeding
              while (!uploadCompleted) {
                await new Promise(resolve => setTimeout(resolve, 10)); // Short delay to avoid busy waiting
              }
            }
        };

        uploadTasks();
    }
}

}
