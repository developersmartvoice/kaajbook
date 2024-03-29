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

import { TodoService } from '../../../../../core/services/todo.service';
import { AuthenticationService } from '../../../../../core/services/authentication.service';

import { CreateTodoModalComponent } from '../../components/create-todo-modal/create-todo-modal.component';
import { EditTodoModalComponent } from '../../components/edit-todo-modal/edit-todo-modal.component';

@Component({
	selector: 'app-todo-detail',
	templateUrl: './todo-detail.component.html',
	styleUrls: ['./todo-detail.component.scss']
})

export class TodoDetailComponent implements OnInit {
	public modalRef: BsModalRef;
	@Input() module_id: number;
	@Input() module_related_id: number;
	loginUser: any;
	todos: any;
	isPageloaded = false;
	todoParams: any;
	datepickerConfigs = { dateInputFormat: 'YYYY-MM-DD' }
	modalConfigs = {
		animated: true,
		keyboard: true,
		backdrop: true,
		ignoreBackdropClick: false,
		class: "inmodal modal-dialog-centered modal-md animated fadeIn"
	};

	constructor(
		public translate: TranslateService,
		public ngxRolesService: NgxRolesService,
		private modalService: BsModalService,
		private http: HttpClient,
		private toastr: ToastrService,
		private todoService: TodoService,
		private authenticationService: AuthenticationService
	) {
		this.authenticationService.loginUser.subscribe(x => this.loginUser = x);
	}

	ngOnInit() {
		this.todoParams = {
			'length': 10,
			'module_id': this.module_id,
			'module_related_id': this.module_related_id
		}

		this.getTodos();
	}

	drop(event: CdkDragDrop<string[]>) {
		if (event.previousContainer === event.container) {
			moveItemInArray(event.container.data, event.previousIndex, event.currentIndex);
		} else {
			transferArrayItem(
				event.previousContainer.data,
				event.container.data,
				event.previousIndex,
				event.currentIndex
			);
		}

		this.changeTodosStatus();
	}

	getTodos() {
		this.todoService.getAllTodos(this.todoParams)
			.subscribe(
				data => {
					this.todos = data;
					this.isPageloaded = true;
				}, error => {});
	}

	changeTodosStatus() {
		this.todoService.changeTodosStatus(this.todos)
			.subscribe(
				data => {
					this.toastr.success(this.translate.instant('todos.messages.status'), this.translate.instant('todos.title'));
				}, error => {});
	}

	changeStatus(todo, status) {
		todo.status = status;
		this.todoService.update(todo)
			.subscribe(
				data => {
					this.toastr.success(this.translate.instant('todos.messages.update'), this.translate.instant('todos.title'));
					this.getTodos();
				});
	}

	openTodoCreateModal() {
		let modalConfigs = {
			animated: true,
			keyboard: true,
			backdrop: true,
			ignoreBackdropClick: false,
			class: "inmodal modal-dialog-centered modal-md animated fadeIn",
			initialState: {
				todoParams: this.todoParams
			}
		};

		this.modalRef = this.modalService.show(CreateTodoModalComponent, modalConfigs);
		this.modalRef.content.event.subscribe(data => {
			this.getTodos();
		});
	}

	openTodoEditModal(todo) {
		let modalConfigs = {
			animated: true,
			keyboard: true,
			backdrop: true,
			ignoreBackdropClick: false,
			class: "inmodal modal-dialog-centered modal-md animated fadeIn",
			initialState: {
				todo: todo
			}
		};
		this.modalRef = this.modalService.show(EditTodoModalComponent, modalConfigs);
		this.modalRef.content.event.subscribe(data => {
			this.getTodos();
		});
	}

	deleteTodo(id) {
		Swal.fire({
			title: this.translate.instant('common.swal.title'),
			text: this.translate.instant('common.swal.text'),
			type: 'warning',
			showCancelButton: true,
			confirmButtonText: this.translate.instant('common.swal.confirmButtonText'),
			cancelButtonText: this.translate.instant('common.swal.cancelButtonText')
		}).then((result) => {
			if (result.value) {
				this.todoService.delete(id)
				.subscribe(
					data => {
						this.toastr.success(this.translate.instant('todos.messages.delete'), this.translate.instant('todos.title'));
						this.getTodos();
					});
			}
		});
	}

	saveTodosDetail(todo, index, value) {
		todo[index] = value;
		this.todoService.update(todo)
			.subscribe(
				data => {
					this.toastr.success(this.translate.instant('todos.messages.update'), this.translate.instant('todos.title'));
					this.getTodos();
				});
	}

}
