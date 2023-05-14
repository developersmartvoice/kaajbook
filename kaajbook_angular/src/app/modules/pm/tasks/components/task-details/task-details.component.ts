import { Component, OnInit, Input, OnDestroy } from '@angular/core';
import { ToastrService } from 'ngx-toastr';
import { NgxRolesService } from 'ngx-permissions';
import { TranslateService } from '@ngx-translate/core';
import Swal from 'sweetalert2';

import { TaskService } from '../../../../../core/services/task.service';
import { ProjectService } from '../../../../../core/services/project.service';
import { TimesheetService } from '../../../../../core/services/timesheet.service';

import { task_status_key_value, task_priority_key_value } from "./../../../../../core/helpers/pm-helper";
import * as moment from 'moment';

@Component({
	selector: 'app-task-details',
	templateUrl: './task-details.component.html',
	styleUrls: ['./task-details.component.scss']
})

export class TaskDetailsComponent implements OnInit, OnDestroy {
	@Input() task: any;
	@Input() loginUser: any;
	@Input() permission: boolean;
	@Input() apiUrl;
	timeLogs: any;
	timerInterval: any;
	timerInterval1: any;
	startDateTime: any
	totalHours: any;
	totalMinutes: any;
	totalSeconds: any;
	showTimer = false;
	isTimerStarted = false;
	taskstatusKeyValue = task_status_key_value;
	taskPriorityKeyValue = task_priority_key_value;

	constructor(
		public ngxRolesService: NgxRolesService,
		public translate: TranslateService,
		private toastr: ToastrService,
		private taskService: TaskService,
		private timesheetService: TimesheetService
	) {	}

	ngOnInit() {
		this.totalHours = "00";
		this.totalMinutes = "00";
		this.totalSeconds = "00";
		
		this.getTimeLogs();
		this.timerInterval1 = setInterval(() => {
			if(this.loginUser) {
				this.getTimeLogs(false);
			}
		}, 1000);
	}

	getTaskById(taskId) {
		this.taskService.getById(taskId)
			.subscribe(
				data => {
					this.task = data;
				});
	}

	getTimeLogs(isLoad = true) {
		this.timesheetService.getTimeLogs()
			.subscribe(
				data => {
					this.timeLogs = data;

					if(isLoad) {
						this.loadTimer();
					}
				});
	}

	getTaskStatus(status) {
		return 'tasks.status' + status; 
	}

	getTranslateStatus(statusKey) {
		return this.taskstatusKeyValue[statusKey];
	}

	getTranslatePriorities(priorityKey) {
		return this.taskPriorityKeyValue[priorityKey];
	}

	changeTaskPriority(taskId: any, priority: any) {
		this.taskService.changePriority({ id: taskId, priority: priority.id }).subscribe(data => {
			this.toastr.success(this.translate.instant('tasks.messages.priority'), this.translate.instant('tasks.title'));
			this.getTaskById(this.task.id);
		});
	}

	changeTaskStatus(taskID: any, status: any) {
		let changeTask = {
			id: taskID,
			status: status.id
		}
		this.taskService.changeStatus(changeTask).subscribe(data => {
			this.toastr.success(this.translate.instant('tasks.messages.status'), this.translate.instant('tasks.title'));
			this.getTaskById(this.task.id);
		});
	}

	getParseArray(string) {
		return JSON.parse(string);
	}

	saveTaskDetail(name, value) {
		this.task[name] = value;
		this.taskService.update(this.task)
			.subscribe(
				data => {
					this.toastr.success(this.translate.instant('tasks.messages.update'), this.translate.instant('tasks.title'));
					this.getTaskById(this.task.id);
				});
	}

	saveSubTaskDetail(subTask, name, value) {
		subTask[name] = value;
		this.taskService.update(subTask).subscribe(data => {
			this.toastr.success(this.translate.instant('tasks.messages.update'), this.translate.instant('tasks.title'));
			this.getTaskById(this.task.id);
		});
	}

	getCheckPermission(sub_task) {
		let role = this.ngxRolesService.getRole('admin');
		if ((role && role.name == 'admin') || this.loginUser.is_super_admin) {
			return true;
		} else if (sub_task.assign_to == this.loginUser.id || sub_task.created_by == this.loginUser.id) {
			return true;
		} else {
			return false;
		}
	}

	deleteSubTask(id) {
		Swal.fire({
			title: this.translate.instant('common.swal.title'),
			text: this.translate.instant('common.swal.text'),
			type: 'warning',
			showCancelButton: true,
			confirmButtonText: this.translate.instant('common.swal.confirmButtonText'),
			cancelButtonText: this.translate.instant('common.swal.cancelButtonText')
		}).then((result) => {
			if (result.value) {
				this.taskService.delete(id).subscribe(data => {
					this.toastr.success(this.translate.instant('tasks.messages.delete'), this.translate.instant('tasks.title'));
					this.getTaskById(this.task.id);
				});
			}
		});
	}

	loadTimer() {
		this.totalHours = "00";
		this.totalMinutes = "00";
		this.totalSeconds = "00";

		if((this.timeLogs && this.timeLogs.is_task_timer) && (this.timeLogs.reference_id == this.task.id)) {
			this.showTimer = true;
			this.isTimerStarted = this.timeLogs.is_task_timer;
			this.startDateTime = this.timeLogs.start_time;

			this.startTimer(false);
		}

		if(this.timeLogs && (this.timeLogs.is_defect_timer || this.timeLogs.is_incident_timer)) {
			this.showTimer = false;
		}

		if(!this.timeLogs) {
			this.showTimer = true;
		}
	}

	startTimer(isSaved = true) {
		if(isSaved) {
			if(this.timeLogs && (this.timeLogs.is_task_timer || this.timeLogs.is_defect_timer || this.timeLogs.is_incident_timer)) {
				this.toastr.error(this.translate.instant('timer_logs.error_messages.message1'), this.translate.instant('tasks.title'));
				return false;
			}
		}

		this.timerInterval = setInterval(() => {
			this.startTimerWatch();
		}, 1000);

		if(isSaved) {
			this.isTimerStarted = true;
			this.saveTimeLogs();
		}
	}

	startTimerWatch() {
		let time = this.startDateTime ? this.startDateTime : this.getDateTime();

		let endDate = new Date(),
			startDate = new Date(time),
			dateDiff = endDate.getTime() - startDate.getTime();

		let hours = Math.floor((dateDiff / 36e5) % 24),
			minutes = Math.floor((dateDiff / 6e4) % 60),
			seconds = Math.floor((dateDiff / 1e3) % 60);

		this.totalHours = hours < 10 ? "0" + hours : hours ,
		this.totalMinutes = minutes < 10 ? "0" + minutes: minutes,
		this.totalSeconds = seconds < 10 ? "0" + seconds : seconds;
	}

	getDateTime() {
		let date = new Date(),
			fullDate = date.getDate() < 10 ? "0" + date.getDate() :date.getDate(),
			months = date.getMonth() + 1 < 10 ? "0" + (date.getMonth() + 1) : date.getMonth() + 1,
			hours = date.getHours() < 10 ? "0" + date.getHours() : date.getHours(),
			minutes = date.getMinutes() < 10 ? "0" + date.getMinutes() : date.getMinutes(),
			seconds = date.getSeconds() < 10 ? "0" + date.getSeconds() : date.getSeconds();

		return date.getFullYear() + "-" + months + "-" + fullDate + " " + hours + ":" + minutes + ":" + seconds;
	}

	saveTimeLogs() {
		this.timesheetService.createTimeLog({
			"project_id": this.task.project_id,
			"reference_id": this.task.id,
			"is_task_timer": true,
			"is_defect_timer": false,
			"is_incident_timer": false,
			"start_time": moment(new Date()).format("YYYY-MM-DD HH:mm:ss")
		}).subscribe(data => {
			this.startDateTime = new Date();
		});
	}

	stopTimer(isSaved) {
		let endDate = new Date(),
			startDate = new Date(this.startDateTime),
			dateDiff = endDate.getTime() - startDate.getTime();

			let diffHrs = Math.floor((dateDiff % 86400000) / 3600000); // hours
			let diffMins = Math.round(((dateDiff % 86400000) % 3600000) / 60000);

			// --
			// Check 
			if(diffMins < 1) {
				this.toastr.error(this.translate.instant('timer_logs.error_messages.message2'), this.translate.instant('timesheet.title'));
				isSaved = false;
				// this.setClearInterval();
				// return false;
			}
			
			if(this.getStartFutureDate(this.startDateTime, this.getDateTime()) > 720) {
				this.toastr.error(this.translate.instant('timer_logs.error_messages.message3'), this.translate.instant('timesheet.title'));
				isSaved = false;
				// this.setClearInterval();
				// return false;
			}

		this.deleteTimesheet(isSaved);
	}

	getStartFutureDate(startDate, endDate) {
        (startDate = new Date(startDate)), (endDate = new Date(endDate));

        let diff = (startDate.getTime() - endDate.getTime()) / 1e3;
        return (diff /= 60), Math.abs(Math.round(diff));
    }

	setClearInterval() {
		clearInterval(this.timerInterval);
		this.getTimeLogs();
		this.isTimerStarted = false;
		this.startDateTime = null;
	}

	saveTimesheet() {
		this.timesheetService.create({
			"project_id": this.task.project_id,
			"module_id": 2,
			"module_related_id": this.task.id,
			"start_time": moment(new Date(this.startDateTime)).format("YYYY-MM-DD HH:mm:ss"),
			"end_time": moment(new Date()).format("YYYY-MM-DD HH:mm:ss"),
		}).subscribe(data => {
			this.toastr.success(this.translate.instant('timesheet.messages.create'), this.translate.instant('timesheet.title'));
			this.setClearInterval();
		});
	}

	deleteTimesheet(isSaved) {
		this.timesheetService.deleteTimeLog(this.loginUser.id)
			.subscribe(data => {
				if(isSaved) {
					this.saveTimesheet();
				} else {
					this.setClearInterval();
				}
			});
	}

	ngOnDestroy() {
		clearInterval(this.timerInterval);
		clearInterval(this.timerInterval1);
	}
}
