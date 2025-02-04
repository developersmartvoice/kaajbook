import { Component, OnInit } from "@angular/core";
import { BsModalRef, BsModalService } from "ngx-bootstrap";
import { ProjectTemplateCreateComponent } from "../project-template-create/project-template-create.component";
import { CustomTemplateService } from "src/app/core/services/custom-template.service";
import { TranslateService } from "@ngx-translate/core";
import { ToastrService } from "ngx-toastr";
import { ProjectTemplateEditComponent } from "../project-template-edit/project-template-edit.component";
import Swal from "sweetalert2";
import { NgxPermissionsService } from 'ngx-permissions';
import { environment } from "src/environments/environment";
@Component({
    selector: "app-project-template-list",
    templateUrl: "./project-template-list.component.html",
    styleUrls: ["./project-template-list.component.scss"],
})
export class ProjectTemplateListComponent implements OnInit {
    private apiUrl = environment.apiUrl;

    public modalRef: BsModalRef;
    modalConfigs = {
        animated: true,
        keyboard: true,
        backdrop: true,
        ignoreBackdropClick: true,
        class: "inmodal modal-dialog-centered animated fadeIn modal-lg "
    };
    ProjectTemplateCreateComponent
    customTemplateList = [];
    permissions: any;
    constructor(
        public translate: TranslateService,
        private modalService: BsModalService,
        private customTemplateService: CustomTemplateService,
        private toastr: ToastrService,
        private ngxPermissionsService: NgxPermissionsService // Inject NgxPermissionsService

    ) { }

    ngOnInit() {
        this.ngxPermissionsService.permissions$.subscribe((permissions) => {
            this.permissions = permissions;
        });

        if (this.permissions && (this.permissions.admin || this.permissions.super_admin)) {
            this.getCustomTemplateList();
        }
    }


    getCustomTemplateList() {
        this.customTemplateService.getAllTemplates().subscribe(
            (data) => {
                // console.log("This is to see all templates from create template: ", data);
                this.customTemplateList = data;
                this.customTemplateList.reverse();
            },
            (error) => {
                // console.log(error);
            }
        );
    }


    removeCustomTemplate(id) {
        Swal.fire({
            title: this.translate.instant('common.swal.title'),
            text: this.translate.instant('common.swal.text5'),
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: this.translate.instant('common.swal.confirmButtonText'),
            cancelButtonText: this.translate.instant('common.swal.cancelButtonText')
        }).then((result) => {
            if (result.value) {
                this.customTemplateService.deleteTemplate(id).subscribe(
                    (data) => {
                        this.toastr.success(this.translate.instant('settings.custom_fields.messages.template_delete'), this.translate.instant('settings.custom_fields.title3'));
                        this.getCustomTemplateList();
                    },
                    (error) => {
                        // console.log(error);
                    }
                );
            }
        });
    }

    // Method to parse the string and return the length
    getTasksLength(tasksString: string): number {
        try {
            const tasksObject = JSON.parse(tasksString) || {};
            return Object.keys(tasksObject).length;
        } catch (error) {
            console.error('Error parsing tasks:', error);
            return 0;
        }
    }
    getTaskImages(tasksString: string): string[] {
        try {
            const tasksObject = JSON.parse(tasksString) || {};
            return Object.values(tasksObject);
        } catch (error) {
            console.error('Error parsing tasks:', error);
            return [];
        }
    }

    openCustomFieldCreateModal() {
        this.modalRef = this.modalService.show(ProjectTemplateCreateComponent, this.modalConfigs);
        // this.modalRef.content.event.subscribe(data => {
        // 	this.rerender();
        // });

        this.modalRef.content.event.subscribe(data => {
            // Handle the event if needed
            // console.log(data);
            this.getCustomTemplateList(); // Refresh the list after an update if needed
        });
    }

    openCustomTemplateEditModal(customTemplate) {
        this.modalRef = this.modalService.show(ProjectTemplateEditComponent, this.modalConfigs);

        // Pass data to the modal component using componentInstance
        this.modalRef.content.customTemplate = customTemplate;

        this.modalRef.content.event.subscribe(data => {
            // Handle the event if needed
            //   console.log(data);
            this.getCustomTemplateList(); // Refresh the list after an update if needed
        });
    }




}
