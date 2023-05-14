import { Component, OnInit, Input, ViewChild } from '@angular/core';
import { PerfectScrollbarConfigInterface } from 'ngx-perfect-scrollbar';

@Component({
	selector: 'app-defect-activities',
	templateUrl: './defect-activities.component.html',
	styleUrls: ['./defect-activities.component.scss']
})

export class DefectActivitiesComponent implements OnInit {
	public scrollConfig: PerfectScrollbarConfigInterface = {};
	@Input() defect;
	@Input() loginUser: any;
	@Input() apiUrl;

	constructor() { }

	ngOnInit() { }
}

