import { Component, OnInit, Input } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';
import { SingleDataSet, Label } from 'ng2-charts';
import { ChartType } from 'chart.js';

@Component({
	selector: 'app-pm-dashboard-chart1',
	templateUrl: './pm-dashboard-chart1.component.html',
	styleUrls: ['./pm-dashboard-chart1.component.scss']
})

export class PmDashboardChart1Component implements OnInit {
	@Input() projectChartReport: any;
	polarAreaChartLabels: string[] = [];
	polarAreaChartData: number[] = [0, 0, 0];
	polarAreaLegend = true;
	ploarChartColors: any[] = [{
		backgroundColor: ["rgba(28, 188, 216, 0.6)", "rgba(255, 177, 54, 0.6)", "rgba(46, 204, 113, 0.7)"]
	}];
	polarAreaChartType = 'polarArea';
	polarChartOptions: any = {
		animation: false,
		responsive: true,
		maintainAspectRatio: false
	};

	constructor(public translate: TranslateService) {
		this.polarAreaChartLabels = [
			this.translate.instant('common.status.open'),
			this.translate.instant('common.status.in_progress'),
			this.translate.instant('common.status.completed')
		];
	}

	ngOnInit() {
		this.polarAreaChartData = [
			this.projectChartReport.open,
			this.projectChartReport.in_progress + this.projectChartReport.on_hold,
			this.projectChartReport.cancel + this.projectChartReport.completed,
		];
	}

}
