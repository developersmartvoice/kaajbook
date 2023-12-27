import { Component, OnInit, Input } from '@angular/core';
import { DatePipe } from '@angular/common';
import { HttpClient } from '@angular/common/http';
import { BsModalRef, BsModalService } from 'ngx-bootstrap/modal';
import { ToastrService } from 'ngx-toastr';
import { NgxRolesService } from 'ngx-permissions';
import { TranslateService } from '@ngx-translate/core';
import {
	CdkDragDrop,
	moveItemInArray,
	transferArrayItem
} from '@angular/cdk/drag-drop';
import { Subject } from 'rxjs';
import Swal from 'sweetalert2';
import { TodoService } from 'src/app/core/services/todo.service';
import { AuthenticationService } from 'src/app/core/services/authentication.service';
import { CreateTodoModalComponent } from 'src/app/modules/admin/todo/components/create-todo-modal/create-todo-modal.component';
import { EditTodoModalComponent } from 'src/app/modules/admin/todo/components/edit-todo-modal/edit-todo-modal.component';


@Component({
  selector: 'app-version',
  templateUrl: './version.component.html',
  styleUrls: ['./version.component.scss']
})
export class VersionComponent implements OnInit {
  ngOnInit(): void {
    throw new Error('Method not implemented.');
  }
}
