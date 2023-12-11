import { Component, OnInit, Input } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';
import { NgxChartsModule } from '@swimlane/ngx-charts';

@Component({
  selector: 'app-pm-dashboard-chart3',
  templateUrl: './pm-dashboard-chart3.component.html',
  styleUrls: ['./pm-dashboard-chart3.component.scss']
})
export class PmDashboardChart3Component implements OnInit {

	@Input() monthlyReport: any;
	barChartLabels: string[] = [];
	currentDate : Date = new Date();
	barChartType = 'bar';
	barChartLegend = true;
	tasks = [];
	defects = [];
	incidents = [];
	barChartData: any[] = [];
	barChartOptions: any = {
		scaleShowVerticalLines: false,
		responsive: true,
		maintainAspectRatio: false,

		tooltips: {
			callbacks: {
			  label: function (tooltipItem, data) {
				let label = data.datasets[tooltipItem.datasetIndex].label || '';
		
				if (label === 'Price') {
				  label += ': $' + tooltipItem.yLabel;
				} else {
				  label += ': ' + tooltipItem.yLabel;
				}
		
				return label;
			  }
			}
		  }
	};
	barChartColors: Array<any> = [{
			backgroundColor: 'rgba(255, 141, 96, 0.8)',
			borderColor: 'rgba(148,159,177,1)',
			pointBackgroundColor: 'rgba(148,159,177,1)',
			pointBorderColor: '#fff',
			pointHoverBackgroundColor: '#fff',
			pointHoverBorderColor: 'rgba(148,159,177,0.8)'
		}, {
			backgroundColor: 'rgba(92, 184, 92, 0.7)',
			borderColor: 'rgba(148,159,177,1)',
			pointBackgroundColor: 'rgba(148,159,177,1)',
			pointBorderColor: '#fff',
			pointHoverBackgroundColor: '#fff',
			pointHoverBorderColor: 'rgba(148,159,177,0.8)'
		}
	];

	constructor(public translate: TranslateService) {
		this.barChartLabels = this.translate.instant('months');
	}

	ngOnInit() {
		this.renderChart();
	}

	renderChart() {
		for(let iRow in this.monthlyReport) {
			this.tasks.push(this.monthlyReport[iRow].tasks);
			this.defects.push(this.monthlyReport[iRow].defects);
			this.incidents.push(this.monthlyReport[iRow].incidents);
		}

		this.barChartData = [
			{ data: this.tasks, label: this.translate.instant('Projects') },
			{ data: this.defects, label: this.translate.instant('Total Cost (in million)') },
			{ data: this.incidents, label: this.translate.instant('incidents.title') }
		];
	}


}
