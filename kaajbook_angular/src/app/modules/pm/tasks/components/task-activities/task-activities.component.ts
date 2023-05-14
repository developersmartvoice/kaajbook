import {
	Component,
	OnInit,
	Input,
	ViewChild
} from '@angular/core';
import { PerfectScrollbarConfigInterface } from 'ngx-perfect-scrollbar';

@Component({
	selector: 'app-task-activities',
	templateUrl: './task-activities.component.html',
	styleUrls: ['./task-activities.component.scss']
})

export class TaskActivitiesComponent implements OnInit {
	public scrollConfig: PerfectScrollbarConfigInterface = {};
	@Input() task;
	@Input() loginUser: any;
	@Input() apiUrl;

	constructor() {}

	ngOnInit() {}
}
