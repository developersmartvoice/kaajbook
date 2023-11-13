import { Component, OnInit } from "@angular/core";
import { BsModalRef, BsModalService } from "ngx-bootstrap";
import { ProjectTemplateCreateComponent } from "../project-template-create/project-template-create.component";

@Component({
    selector: "app-project-template-list",
    templateUrl: "./project-template-list.component.html",
    styleUrls: ["./project-template-list.component.scss"],
})
export class ProjectTemplateListComponent implements OnInit {
    public modalRef: BsModalRef;

    modalConfigs = {
		animated: true,
		keyboard: true,
		backdrop: true,
		ignoreBackdropClick: false,
		class: "inmodal modal-dialog-centered animated fadeIn"
	};
    customFields = [
        {
            field_label: "Create Template",
            task_in_template: 6,
        },
        {
            field_label: "Create Template",
            task_in_template: 2,
        },
        {
            field_label: "Create Template",
            task_in_template: 8,
        },
        {
            field_label: "Create Template",
            task_in_template: 5,
        },
        {
            field_label: "Create Template",
            task_in_template: 13,
        },
        {
            field_label: "Create Template",
            task_in_template: 8,
        },
        {
            field_label: "Create Template",
            task_in_template: 6,
        },
        {
          field_label: "Create Template",
          task_in_template: 6,
      },
      {
          field_label: "Create Template",
          task_in_template: 6,
      },
      {
          field_label: "Create Template",
          task_in_template: 6,
      },
      {
          field_label: "Create Template",
          task_in_template: 6,
      },
      {
          field_label: "Create Template",
          task_in_template: 6,
      },
      {
          field_label: "Create Template",
          task_in_template: 6,
      },
      {
          field_label: "Create Template",
          task_in_template: 6,
      },
    ];
    constructor(
        private modalService: BsModalService
    ) {}

    ngOnInit() {}

    openCustomFieldCreateModal() {
		this.modalRef = this.modalService.show(ProjectTemplateCreateComponent, this.modalConfigs);
		// this.modalRef.content.event.subscribe(data => {
		// 	this.rerender();
		// });
	}
}
